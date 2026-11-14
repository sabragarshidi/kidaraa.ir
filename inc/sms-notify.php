<?php
/**
 * Order SMS notifications (pattern-based). Each pattern code lives in a WP option; an empty option = that SMS is off.
 *   kidara_sms_p_order    customer, order placed/paid   vars: name, order
 *   kidara_sms_p_shipped  customer, order completed     vars: name, order
 *   kidara_sms_p_admin    shop owner, new order         vars: order, total
 *   kidara_sms_admin_phone  owner mobile (09…)
 */
defined('ABSPATH') || exit;

function kidara_sms_pattern(string $phone, string $pattern, array $vars) {
    $key = get_option('kidara_sms_key');
    if (!$key || !$pattern || !$phone || kidara_otp_is_local()) return false;
    $r = wp_remote_post('https://api.iranpayamak.com/ws/v1/sms/pattern', [
        'timeout' => 15,
        'headers' => ['Api-Key' => $key, 'Content-Type' => 'application/json', 'Accept' => 'application/json'],
        'body'    => wp_json_encode([
            'code' => $pattern, 'attributes' => $vars, 'recipient' => $phone,
            'line_number' => get_option('kidara_sms_line', '90008361'), 'number_format' => 'english',
        ]),
    ]);
    $j = is_wp_error($r) ? null : json_decode(wp_remote_retrieve_body($r), true);
    if (!is_array($j) || ($j['status'] ?? '') === 'error') {
        error_log('[kidara-sms] failed: ' . (is_wp_error($r) ? $r->get_error_message() : wp_remote_retrieve_body($r)));
        return false;
    }
    return true;
}

function kidara_sms_once(WC_Order $o, string $tag, string $phone, string $option, array $vars): void {
    $pattern = (string) get_option($option);
    $phone   = kidara_phone_norm($phone);
    if (!$pattern || !$phone || $o->get_meta('_kd_sms_' . $tag)) return;
    if (kidara_sms_pattern($phone, $pattern, $vars)) {
        $o->update_meta_data('_kd_sms_' . $tag, time());
        $o->save();
    }
}

add_action('woocommerce_order_status_changed', function ($id, $from, $to) {
    $o = wc_get_order($id);
    if (!$o) return;
    $name  = trim($o->get_billing_first_name() . ' ' . $o->get_billing_last_name()) ?: 'مشتری';
    $num   = kidara_fa((string) $o->get_order_number());
    $total = kidara_money($o->get_total());
    if (in_array($to, ['processing', 'on-hold'], true)) {
        kidara_sms_once($o, 'order', $o->get_billing_phone(), 'kidara_sms_p_order', ['name' => $name, 'order' => $num]);
        kidara_sms_once($o, 'admin', (string) get_option('kidara_sms_admin_phone'), 'kidara_sms_p_admin', ['order' => $num, 'total' => $total]);
    } elseif ($to === 'completed') {
        kidara_sms_once($o, 'shipped', $o->get_billing_phone(), 'kidara_sms_p_shipped', ['name' => $name, 'order' => $num]);
    }
}, 10, 3);
