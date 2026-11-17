<?php
/** About Us (slug about-us): intro + perks. */
get_header();
$bg = get_theme_file_uri('assets/img/demo/uploads/2024/01/bg-about.jpg');
$perks = [
    ['truck', 'ارسال سریع', 'سفارش‌های شما با بسته‌بندی ایمن و در سریع‌ترین زمان ممکن به دستتان می‌رسد.'],
    ['shield-check', 'پرداخت امن', 'خرید را با خیال راحت و از طریق درگاه پرداخت معتبر انجام دهید.'],
    ['rotate-ccw', 'بازگشت کالا تا ۷ روز', 'اگر اندازه یا مدل مناسب نبود، تا ۷ روز می‌توانید کالا را تعویض یا مرجوع کنید.'],
];
?>
<main class="about-us">
    <!-- intro: photo as background on tablet+, stacked photo then text on mobile -->
    <section class="mb-16 min-[768px]:mb-23.75 min-[768px]:bg-[image:var(--kd-bg)] min-[768px]:bg-cover min-[768px]:bg-center min-[768px]:bg-no-repeat min-[768px]:px-3.75 min-[768px]:pt-70 min-[768px]:pb-80 min-[1200px]:pt-105 min-[1200px]:pb-100" style="--kd-bg:url('<?= esc_url($bg) ?>')">
        <img src="<?= esc_url($bg) ?>" alt="کودک با لباس نرم کیدارا" width="1920" height="880" class="block aspect-[4/3] w-full object-cover object-[52%_30%] min-[768px]:hidden" fetchpriority="high">
        <div class="mx-auto flex w-full max-w-[1410px] flex-col items-start gap-4 px-5 pt-8 min-[768px]:flex-row min-[768px]:items-center min-[768px]:gap-6 min-[768px]:px-0 min-[768px]:pt-0">
            <div class="w-full min-[768px]:w-1/2 min-[768px]:ps-[105px]"><h1 class="m-0 font-display text-[32px] leading-[1.5] text-heading min-[1025px]:text-[60px]">دربارهٔ کیدارا</h1></div>
            <div class="w-full min-[768px]:w-1/2 min-[768px]:ps-[150px] min-[1200px]:ps-[260px]"><p class="m-0 text-[15px] leading-[2] min-[768px]:text-sm">کیدارا با یک باور ساده شروع شد: بچه‌ها بهترین‌ها را می‌خواهند.<br class="max-[767px]:hidden"> لباس‌هایی نرم و امن، با طرح‌هایی شاد و قیمتی منصفانه،<br class="max-[767px]:hidden"> تا کودک شما راحت بازی کند و شما با خیال آسوده خرید کنید.</p></div>
        </div>
    </section>

    <!-- perks -->
    <section class="container mb-23.75">
        <div class="-mx-3.75 grid grid-cols-1 gap-y-8 min-[768px]:grid-cols-3">
            <?php foreach ($perks as $p) : ?>
                <div class="px-3.75">
                    <div class="flex justify-center text-accent text-[48px] leading-none"><?php kidara_icon_e($p[0], 'text-[48px]'); ?></div>
                    <h3 class="mb-2.5 mt-6 text-center font-body text-lg font-bold leading-[1.5] text-heading"><?= esc_html($p[1]) ?></h3>
                    <p class="m-0 text-center text-sm leading-[1.9]"><?= esc_html($p[2]) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

</main>
<?php get_footer();
