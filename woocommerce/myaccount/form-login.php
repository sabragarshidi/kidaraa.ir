<?php
/** Phone + SMS code login/registration (replaces the WooCommerce email/password forms). */
defined('ABSPATH') || exit;
$redir = isset($_GET['redirect_to']) ? esc_url(wp_unslash($_GET['redirect_to'])) : '';
?>
<div class="mx-auto max-w-[420px] rounded-3xl border border-border bg-white p-6 text-center min-[768px]:p-8" data-otp data-ajax="<?= esc_url(admin_url('admin-ajax.php')) ?>" data-redirect="<?= esc_attr($redir) ?>">
    <h2 class="mb-2 mt-0 text-xl text-heading">ورود یا ثبت‌نام</h2>
    <p class="mb-6 mt-0 text-sm text-muted" data-otp-hint>شمارهٔ موبایل را وارد کنید تا کد تأیید برایتان پیامک شود.</p>

    <form data-otp-step="phone" class="m-0">
        <input type="tel" inputmode="numeric" autocomplete="tel" dir="ltr" maxlength="14" placeholder="09123456789" data-otp-phone
               class="h-12 w-full rounded-xl border border-border bg-white px-4 text-center text-base tracking-wider outline-none focus:border-accent">
        <button type="submit" class="mt-3 h-12 w-full cursor-pointer rounded-xl border-0 bg-accent text-sm font-bold text-white hover:bg-accent-dark">دریافت کد</button>
    </form>

    <form data-otp-step="code" class="m-0 hidden">
        <input type="text" inputmode="numeric" autocomplete="one-time-code" dir="ltr" maxlength="5" placeholder="• • • • •" data-otp-code
               class="h-12 w-full rounded-xl border border-border bg-white px-4 text-center text-xl tracking-[.5em] outline-none focus:border-accent">
        <button type="submit" class="mt-3 h-12 w-full cursor-pointer rounded-xl border-0 bg-accent text-sm font-bold text-white hover:bg-accent-dark">ورود</button>
        <div class="mt-4 flex items-center justify-between text-xs text-muted">
            <button type="button" class="cursor-pointer border-0 bg-transparent p-0 text-xs text-accent-dark" data-otp-edit>ویرایش شماره</button>
            <button type="button" class="cursor-pointer border-0 bg-transparent p-0 text-xs text-accent-dark disabled:text-muted" data-otp-resend disabled>ارسال دوباره</button>
        </div>
    </form>

    <p class="mb-0 mt-4 hidden text-sm text-hot" data-otp-err role="alert"></p>
</div>
