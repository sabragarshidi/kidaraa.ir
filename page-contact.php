<?php
/** Contact: map, info, working form (nonce + honeypot + wp_mail, PRG). */
$status = '';
if ('POST' === $_SERVER['REQUEST_METHOD'] && isset($_POST['kd_contact_nonce'])) {
    $status = 'error';
    if (wp_verify_nonce($_POST['kd_contact_nonce'], 'kd_contact')) {
        if (!empty($_POST['kd_website'])) { // honeypot: pretend success
            $status = 'sent';
        } else {
            $name  = sanitize_text_field(wp_unslash($_POST['kd_name'] ?? ''));
            $email = sanitize_email(wp_unslash($_POST['kd_email'] ?? ''));
            $phone = sanitize_text_field(wp_unslash($_POST['kd_phone'] ?? ''));
            $msg   = sanitize_textarea_field(wp_unslash($_POST['kd_message'] ?? ''));
            if ($name && is_email($email) && $msg) {
                $headers = ['Reply-To: ' . $name . ' <' . $email . '>'];
                $body = "نام: $name\nایمیل: $email\nشماره تماس: $phone\n\n$msg";
                $status = wp_mail(get_option('admin_email'), '[' . get_bloginfo('name') . '] پیام جدید از فرم تماس: ' . $name, $body, $headers) ? 'sent' : 'error';
            } else {
                $status = 'invalid';
            }
        }
    }
    if ($status !== 'invalid') {
        wp_safe_redirect(add_query_arg('kd_msg', $status, get_permalink()) . '#get-in-touch');
        exit;
    }
} elseif (isset($_GET['kd_msg'])) {
    $status = in_array($_GET['kd_msg'], ['sent', 'error'], true) ? $_GET['kd_msg'] : '';
}
get_header();
get_template_part('template-parts/page-title', null, ['title' => 'تماس با ما', 'crumbs' => [['label' => 'خانه', 'url' => home_url('/')], ['label' => 'تماس با ما']]]);
$f = 'mb-5 h-12.5 w-full rounded-full border border-border bg-white px-5 py-2.5 text-base font-normal leading-[50px] text-body placeholder:text-body/75 focus:border-heading focus:outline-none';
$req = 'relative';
$star = '<span class="pointer-events-none absolute end-3.75 top-3 text-xl leading-none text-[red]" aria-hidden="true">*</span>';
?>
<main>
    <section class="mb-22.5 -mt-9 h-[507px] w-full max-[767px]:h-[350px]">
        <iframe title="نقشه" src="https://maps.google.com/maps?q=Tehran%2C%20Iran&amp;t=m&amp;z=10&amp;output=embed&amp;iwloc=near" class="h-full w-full border-0" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </section>
    <section id="get-in-touch" class="container mb-25">
        <div class="-mx-3.75 flex flex-col min-[1025px]:flex-row">
            <div class="w-full px-3.75 min-[1025px]:w-[68%]">
                <h2 class="mb-8.75 mt-0 font-body text-[30px] leading-[1.5] text-heading">با ما در تماس باشید</h2>
                <?php if ($status === 'sent') : ?>
                    <p class="mb-5 rounded-[10px] border border-accent bg-accent/10 px-5 py-3 text-sm text-accent-dark" role="status">از پیام شما سپاسگزاریم. پیام شما با موفقیت ارسال شد و در اسرع وقت پاسخ می‌دهیم.</p>
                <?php elseif ($status === 'error') : ?>
                    <p class="mb-5 rounded-[10px] border border-[#d33] bg-[#d33]/10 px-5 py-3 text-sm text-[#d33]" role="alert">متأسفانه ارسال پیام با مشکل روبه‌رو شد. لطفاً کمی بعد دوباره تلاش کنید.</p>
                <?php elseif ($status === 'invalid') : ?>
                    <p class="mb-5 rounded-[10px] border border-[#d33] bg-[#d33]/10 px-5 py-3 text-sm text-[#d33]" role="alert">لطفاً نام، یک ایمیل معتبر و متن پیام را وارد کنید.</p>
                <?php endif; ?>
                <form method="post" action="<?= esc_url(get_permalink()) ?>#get-in-touch" novalidate>
                    <?php wp_nonce_field('kd_contact', 'kd_contact_nonce'); ?>
                    <div class="absolute -start-[9999px]" aria-hidden="true"><label>وب‌سایت <input type="text" name="kd_website" tabindex="-1" autocomplete="off"></label></div>
                    <div class="-mx-1.75 flex flex-col min-[768px]:flex-row">
                        <div class="w-full px-1.75 min-[768px]:w-1/3"><label for="kd_name" class="relative mb-2.5 block text-base font-semibold text-heading">نام</label><div class="<?= $req ?>"><input id="kd_name" class="<?= $f ?>" type="text" name="kd_name" placeholder="نام شما" required value="<?= esc_attr($status === 'invalid' ? wp_unslash($_POST['kd_name'] ?? '') : '') ?>"><?= $star ?></div></div>
                        <div class="w-full px-1.75 min-[768px]:w-1/3"><label for="kd_email" class="relative mb-2.5 block text-base font-semibold text-heading">ایمیل</label><div class="<?= $req ?>"><input id="kd_email" class="<?= $f ?>" type="email" dir="ltr" name="kd_email" placeholder="ایمیل شما" required value="<?= esc_attr($status === 'invalid' ? wp_unslash($_POST['kd_email'] ?? '') : '') ?>"><?= $star ?></div></div>
                        <div class="w-full px-1.75 min-[768px]:w-1/3"><label for="kd_phone" class="relative mb-2.5 block text-base font-semibold text-heading">شماره تماس</label><input id="kd_phone" class="<?= $f ?>" type="tel" dir="ltr" name="kd_phone" placeholder="شماره تماس" value="<?= esc_attr($status === 'invalid' ? wp_unslash($_POST['kd_phone'] ?? '') : '') ?>"></div>
                    </div>
                    <label for="kd_message" class="mb-2.5 block text-base font-semibold text-heading">پیام</label>
                    <textarea id="kd_message" name="kd_message" rows="10" maxlength="2000" required placeholder="پیام خود را بنویسید *" class="mb-5.75 max-h-92.5 w-full rounded-[25px] border border-border bg-white px-5 py-2.5 text-sm font-light text-body placeholder:text-body/75 focus:border-heading focus:outline-none"><?= esc_textarea($status === 'invalid' ? wp_unslash($_POST['kd_message'] ?? '') : '') ?></textarea>
                    <button type="submit" class="h-13.75 cursor-pointer rounded-[25px] border-0 bg-[#d33] px-13.75 text-sm font-bold leading-[55px] text-white transition-all duration-300 hover:bg-accent-dark">ارسال پیام</button>
                </form>
            </div>
            <div class="w-full px-3.75 max-[1024px]:mt-12.5 min-[1025px]:w-[32%] min-[1025px]:ps-7.5">
                <?php
                $info = [
                    ['تلفن', kidara_phone_link()],
                    ['ایمیل', '<a href="mailto:info@kidaraa.ir"><bdi dir="ltr">info@kidaraa.ir</bdi></a>'],
                    ['ساعت کاری', 'شنبه تا پنجشنبه، ۹ صبح تا ۹ شب؛ جمعه‌ها تعطیل'],
                ];
                foreach ($info as $row) : ?>
                    <div class="mb-6 border-b border-border pb-6">
                        <h2 class="mb-2.5 mt-0 font-body text-xl leading-[1.5] text-heading"><?= esc_html($row[0]) ?></h2>
                        <div class="text-sm text-heading [&_a]:text-heading [&_a:hover]:text-accent"><?= $row[1] // phpcs:ignore ?></div>
                    </div>
                <?php endforeach; ?>
                <h2 class="mb-3.75 mt-0 font-body text-xl leading-[1.5] text-heading">ما را دنبال کنید</h2>
                <ul class="m-0 flex list-none gap-5 p-0 text-[16px] text-accent">
                    <?php foreach (['tiktok' => 'تیک‌تاک', 'x-twitter' => 'ایکس', 'facebook' => 'فیس‌بوک', 'behance' => 'بیهنس'] as $s => $sl) : ?>
                        <li><a href="#" class="text-accent hover:text-accent-dark" aria-label="<?= esc_attr($sl) ?>"><?php kidara_icon_e($s); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </section>
</main>
<?php get_footer();
