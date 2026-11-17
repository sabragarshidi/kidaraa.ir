<?php
/** About Us (slug about-us). Partials reused from the homepage. */
get_header();
$up = '/wp-content/themes/kidara/assets/img/demo/uploads/';
$testimonials = [
    ['«از وقتی لباس بچه‌ها را از کیدارا می‌خرم خیالم راحت است. پارچه‌ها نرم و بی‌حساسیت‌اند و بعد از چند بار شستن هم رنگشان تغییر نمی‌کند.»', 'لیلا محمدی'],
    ['«بسته‌بندی تمیز، ارسال سریع و جنس عالی. اندازه‌ها دقیقاً همان چیزی بود که در سایت نوشته بودند. حتماً دوباره خرید می‌کنم.»', 'سارا احمدی'],
    ['«پشتیبانی کیدارا واقعاً پاسخگوست. برای تعویض سایز تماس گرفتم و همان روز مشکل حل شد. ممنون از همراهی‌تان.»', 'مینا رضایی'],
    ['«لباس‌های نوزادی‌شان را برای هدیهٔ تولد خریدم؛ هم قشنگ بود هم باکیفیت. همه را به دوستانم هم معرفی کردم.»', 'نیلوفر کریمی'],
];
$perks = [
    ['truck', 'ارسال سریع', 'سفارش‌های شما با بسته‌بندی ایمن و در سریع‌ترین زمان ممکن به دستتان می‌رسد.'],
    ['shield-check', 'پرداخت امن', 'خرید را با خیال راحت و از طریق درگاه پرداخت معتبر انجام دهید.'],
    ['rotate-ccw', 'بازگشت کالا تا ۷ روز', 'اگر اندازه یا مدل مناسب نبود، تا ۷ روز می‌توانید کالا را تعویض یا مرجوع کنید.'],
];
?>
<main class="about-us">
    <!-- intro -->
    <section class="mb-23.75 bg-cover bg-center bg-no-repeat px-3.75 pt-40 pb-28 min-[768px]:pt-70 min-[768px]:pb-80 min-[1200px]:pt-105 min-[1200px]:pb-100" style="background-image:url('<?= esc_url($up . '2024/01/bg-about.jpg') ?>')">
        <div class="mx-auto flex w-full max-w-[1410px] flex-col items-start gap-6 min-[768px]:flex-row min-[768px]:items-center">
            <div class="w-full min-[768px]:w-1/2 min-[768px]:ps-[105px]"><h1 class="m-0 font-display text-[35px] leading-[1.5] text-heading min-[1025px]:text-[60px]">دربارهٔ کیدارا</h1></div>
            <div class="w-full min-[768px]:w-1/2 min-[768px]:ps-[150px] min-[1200px]:ps-[260px]"><p class="m-0 text-sm leading-[2]">کیدارا با یک باور ساده شروع شد: بچه‌ها بهترین‌ها را می‌خواهند.<br>لباس‌هایی نرم و امن، با طرح‌هایی شاد و قیمتی منصفانه،<br>تا کودک شما راحت بازی کند و شما با خیال آسوده خرید کنید.</p></div>
        </div>
    </section>

    <?php get_template_part('template-parts/home/outfits'); ?>

    <!-- Anana testimonial strip -->
    <section class="container relative my-18 text-center" data-about-testimonials>
        <img src="<?= esc_url($up . '2020/06/leaves-2.png') ?>" alt="" class="pointer-events-none absolute -top-12 end-[6%] max-w-none max-[767px]:hidden" loading="lazy">
        <div class="swiper !overflow-hidden mx-auto max-w-[1000px]" dir="rtl">
            <div class="swiper-wrapper">
                <?php foreach ($testimonials as $t) : ?>
                    <div class="swiper-slide">
                        <p class="m-0 text-base leading-[2]"><?= esc_html($t[0]) ?></p>
                        <h3 class="mt-6.5 mb-0 font-display text-sm leading-[1.5] text-heading"><?= esc_html($t[1]) ?></h3>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="js-testimonials-dots mt-7.5 flex justify-center gap-2"></div>
    </section>

    <!-- lookbook -->
    <section class="container mb-23.75">
        <div class="relative flex items-center bg-cover bg-center bg-no-repeat px-7.5 py-20 min-[768px]:bg-fixed min-[768px]:py-50 min-[1200px]:ps-25 min-[1200px]:pt-61.25 min-[1200px]:pb-50" style="background-image:url('<?= esc_url($up . '2024/01/bg-paralax.jpg') ?>')">
            <div class="relative isolate">
                <img src="<?= esc_url($up . '2024/01/ellipse-3.png') ?>" alt="" width="64" height="61" class="pointer-events-none absolute -top-3.75 start-[140px] -z-10 h-[61px] w-16 max-w-none max-[767px]:start-[100px]" loading="lazy">
                <h2 class="mb-8.75 mt-0 text-[35px] leading-[1.5] text-white min-[1025px]:text-[40px]">لوک‌بوک لباس کودک</h2>
                <p class="m-0 text-sm font-bold leading-[2] text-white">از لباس خواب نرم نوزادی تا ست‌های رنگارنگ بازی؛<br>هر قطعه را با دقت از میان پارچه‌های ایمن و بادوام<br>انتخاب کرده‌ایم تا کودکتان هر روز با شادی بزرگ شود.</p>
            </div>
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

    <?php get_template_part('template-parts/home/blog'); ?>
</main>
<?php get_footer();
