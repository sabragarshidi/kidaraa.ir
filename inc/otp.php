<?php
/**
 * Phone + SMS-code login/registration (Iran Payamak / Faraz SMS pattern API).
 * Settings are WP options (never in git): kidara_sms_key, kidara_sms_pattern, kidara_sms_var (default "code"),
 * kidara_sms_line (default 90008361). On *.loc hosts nothing is sent; the code is returned for testing.
 */
defined('ABSPATH') || exit;

function kidara_phone_norm(string $raw): string {
    $raw = strtr($raw, ['۰'=>'0','۱'=>'1','۲'=>'2','۳'=>'3','۴'=>'4','۵'=>'5','۶'=>'6','۷'=>'7','۸'=>'8','۹'=>'9',
                        '٠'=>'0','١'=>'1','٢'=>'2','٣'=>'3','٤'=>'4','٥'=>'5','٦'=>'6','٧'=>'7','٨'=>'8','٩'=>'9']);
    $d = preg_replace('/\D+/', '', $raw);
    if (strpos($d, '0098') === 0) $d = substr($d, 4);
    elseif (strpos($d, '98') === 0 && strlen($d) === 12) $d = substr($d, 2);
    if (strlen($d) === 10 && $d[0] === '9') $d = '0' . $d;
    return preg_match('/^09\d{9}$/', $d) ? $d : '';
}

function kidara_otp_is_local(): bool {
    return (bool) preg_match('/\.(loc|test|local)$/', wp_parse_url(home_url(), PHP_URL_HOST) ?: '');
}

function kidara_otp_find_user(string $phone): ?WP_User {
    $u = get_user_by('login', $phone);
    if ($u) return $u;
    $q = get_users(['meta_key' => 'billing_phone', 'meta_value' => $phone, 'number' => 1]);
    return $q ? $q[0] : null;
}

function kidara_otp_user_allowed(WP_User $u): bool {
    // Code login is for shoppers only; staff accounts keep password login.
    return (bool) array_intersect($u->roles, ['customer', 'subscriber']) && !$u->has_cap('edit_posts');
}

function kidara_otp_create_user(string $phone) {
    $id = wp_insert_user([
        'user_login'   => $phone,
        'user_pass'    => wp_generate_password(32),
        'role'         => 'customer',
        'display_name' => $phone,
    ]);
    if (is_wp_error($id)) return $id;
    update_user_meta($id, 'billing_phone', $phone);
    return $id;
}

function kidara_otp_send_sms(string $phone, string $code) {
    $key = get_option('kidara_sms_key');
    $pat = get_option('kidara_sms_pattern');
    if (!$key || !$pat) return new WP_Error('cfg', 'سرویس پیامک هنوز تنظیم نشده است.');
    $r = wp_remote_post('https://api.iranpayamak.com/ws/v1/sms/pattern', [
        'timeout' => 15,
        'headers' => ['Api-Key' => $key, 'Content-Type' => 'application/json', 'Accept' => 'application/json'],
        'body'    => wp_json_encode([
            'code'          => $pat,
            'attributes'    => [get_option('kidara_sms_var', 'code') => $code],
            'recipient'     => $phone,
            'line_number'   => get_option('kidara_sms_line', '90008361'),
            'number_format' => 'english',
        ]),
    ]);
    if (is_wp_error($r)) return new WP_Error('net', 'ارسال پیامک ممکن نشد. دوباره تلاش کنید.');
    $j = json_decode(wp_remote_retrieve_body($r), true);
    if (!is_array($j) || ($j['status'] ?? '') === 'error') {
        error_log('[kidara-otp] sms failed: ' . wp_remote_retrieve_body($r));
        return new WP_Error('sms', 'ارسال پیامک ممکن نشد. کمی بعد دوباره تلاش کنید.');
    }
    return true;
}

function kidara_otp_ip(): string {
    return preg_replace('/[^0-9a-f:.]/i', '', $_SERVER['REMOTE_ADDR'] ?? '0');
}

add_action('wp_ajax_nopriv_kidara_otp_send', 'kidara_otp_ajax_send');
add_action('wp_ajax_kidara_otp_send', 'kidara_otp_ajax_send');
function kidara_otp_ajax_send() {
    $phone = kidara_phone_norm((string) ($_POST['phone'] ?? ''));
    if (!$phone) wp_send_json_error(['msg' => 'شمارهٔ موبایل درست نیست. مثال: ۰۹۱۲۱۲۳۴۵۶۷']);

    $ex = kidara_otp_find_user($phone);
    if ($ex && !kidara_otp_user_allowed($ex)) wp_send_json_error(['msg' => 'این شماره با کد قابل ورود نیست.']);

    $ipk = 'kd_otp_ip_' . md5(kidara_otp_ip());
    $phk = 'kd_otp_ph_' . $phone;
    if (get_transient('kd_otp_cd_' . $phone)) wp_send_json_error(['msg' => 'لطفاً کمی صبر کنید و دوباره درخواست دهید.']);
    if ((int) get_transient($phk) >= 5 || (int) get_transient($ipk) >= 20) {
        wp_send_json_error(['msg' => 'تعداد درخواست‌ها زیاد است. یک ساعت دیگر تلاش کنید.']);
    }

    $code = (string) random_int(10000, 99999);
    if (!kidara_otp_is_local()) {
        $s = kidara_otp_send_sms($phone, $code);
        if (is_wp_error($s)) wp_send_json_error(['msg' => $s->get_error_message()]);
    }
    set_transient('kd_otp_cd_' . $phone, 1, 60);
    set_transient($phk, (int) get_transient($phk) + 1, HOUR_IN_SECONDS);
    set_transient($ipk, (int) get_transient($ipk) + 1, HOUR_IN_SECONDS);
    set_transient('kd_otp_c_' . $phone, ['h' => wp_hash($code . '|' . $phone), 'n' => 0], 120);

    $out = ['phone' => $phone, 'wait' => 60];
    if (kidara_otp_is_local()) $out['debug_code'] = $code;
    wp_send_json_success($out);
}

add_action('wp_ajax_nopriv_kidara_otp_verify', 'kidara_otp_ajax_verify');
add_action('wp_ajax_kidara_otp_verify', 'kidara_otp_ajax_verify');
function kidara_otp_ajax_verify() {
    $phone = kidara_phone_norm((string) ($_POST['phone'] ?? ''));
    $code  = preg_replace('/\D+/', '', strtr((string) ($_POST['code'] ?? ''), ['۰'=>'0','۱'=>'1','۲'=>'2','۳'=>'3','۴'=>'4','۵'=>'5','۶'=>'6','۷'=>'7','۸'=>'8','۹'=>'9']));
    $st = $phone ? get_transient('kd_otp_c_' . $phone) : false;
    if (!$st) wp_send_json_error(['msg' => 'کد منقضی شده است. کد جدید بگیرید.', 'expired' => true]);
    if ($st['n'] >= 5) { delete_transient('kd_otp_c_' . $phone); wp_send_json_error(['msg' => 'تعداد تلاش‌ها زیاد بود. کد جدید بگیرید.', 'expired' => true]); }
    if (!hash_equals($st['h'], wp_hash($code . '|' . $phone))) {
        $st['n']++;
        set_transient('kd_otp_c_' . $phone, $st, 120);
        wp_send_json_error(['msg' => 'کد درست نیست.']);
    }
    delete_transient('kd_otp_c_' . $phone);

    $u = kidara_otp_find_user($phone);
    if ($u) {
        if (!kidara_otp_user_allowed($u)) wp_send_json_error(['msg' => 'این شماره با کد قابل ورود نیست.']);
        $uid = $u->ID;
    } else {
        $uid = kidara_otp_create_user($phone);
        if (is_wp_error($uid)) wp_send_json_error(['msg' => 'ساخت حساب ممکن نشد.']);
    }
    wp_set_current_user($uid);
    wp_set_auth_cookie($uid, true);
    $to = !empty($_POST['redirect']) ? wp_validate_redirect(esc_url_raw(wp_unslash($_POST['redirect'])), '') : '';
    wp_send_json_success(['redirect' => $to ?: home_url('/my-account/')]);
}

// Guest checkout: attach the order to the account of that phone (created if missing) so it can be seen after a code login.
add_action('woocommerce_checkout_order_processed', function ($order_id) {
    $order = wc_get_order($order_id);
    if (!$order || $order->get_customer_id()) return;
    $phone = kidara_phone_norm((string) $order->get_billing_phone());
    if (!$phone) return;
    $u = kidara_otp_find_user($phone);
    if ($u && !kidara_otp_user_allowed($u)) return;
    $uid = $u ? $u->ID : kidara_otp_create_user($phone);
    if (is_wp_error($uid)) return;
    $order->set_customer_id($uid);
    $order->save();
}, 20);
