<?php
/**
 * Manifest #3: "Baby Outfits & Sets" — two-up photo slider + text block.
 */
$up = 'https://buny.wpbingosite.com/wp-content/uploads/';
// Slides = real products of the "baby" category (image, name, price).
$kd_items = [];
if (function_exists('wc_get_products')) {
    foreach (wc_get_products(['category' => ['baby'], 'status' => 'publish', 'limit' => 8, 'orderby' => 'date', 'order' => 'DESC']) as $kd_prod) {
        $kd_img = wp_get_attachment_image_url($kd_prod->get_image_id(), 'woocommerce_single');
        if (!$kd_img) continue;
        $kd_items[] = ['url' => get_permalink($kd_prod->get_id()), 'img' => $kd_img, 'name' => $kd_prod->get_name(), 'price' => kidara_card_args($kd_prod)['price'] ?? '', 'regular' => kidara_card_args($kd_prod)['regular_price'] ?? ''];
    }
}
if (!$kd_items) { return; }
?>
<section class="container relative">
<div class="-mx-3.75 flex flex-col items-stretch min-[768px]:flex-row">
    <img src="<?= esc_url($up . '2023/11/sun.png') ?>" alt="" width="112" height="103" class="pointer-events-none absolute -start-8.75 -top-8.75 z-0 max-w-none" loading="lazy">
    <div class="relative z-[1] w-full min-w-0 px-3.75 min-[768px]:w-[66%]" data-outfits-slider>
        <div class="swiper !overflow-hidden" dir="rtl">
            <div class="swiper-wrapper">
                <?php foreach ($kd_items as $it) : ?>
                    <div class="swiper-slide">
                        <a href="<?= esc_url($it['url']) ?>" class="group block">
                            <span class="block overflow-hidden bg-bg-light"><?= kidara_img($it['img'], $it['name'], 'block aspect-[436/610] w-full object-cover transition-transform duration-500 group-hover:scale-[1.03]') ?></span>
                            <span class="mt-3 block truncate text-base text-heading"><?= esc_html($it['name']) ?></span>
                            <span class="mt-1 flex items-center gap-2 text-sm font-bold text-accent">
                                <?php if ($it['regular']) : ?><del class="font-normal text-muted"><?= esc_html($it['regular']) ?></del><?php endif; ?>
                                <?= esc_html($it['price']) ?>
                            </span>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="mt-3.75 flex justify-end gap-5 pe-15 max-[767px]:hidden">
            <button type="button" class="js-outfits-prev text-[22px] text-heading transition-colors hover:text-accent" aria-label="قبلی"><?php kidara_icon_e('arrow-left', 'kd-dir'); ?></button>
            <button type="button" class="js-outfits-next text-[22px] text-heading transition-colors hover:text-accent" aria-label="بعدی"><?php kidara_icon_e('arrow-right', 'kd-dir'); ?></button>
        </div>
    </div>
    <div class="flex w-full flex-col justify-center px-3.75 max-[767px]:mt-6 min-[768px]:order-first min-[768px]:w-[34%]">
        <div class="@container relative isolate pe-0 min-[1200px]:pe-10">
            <h2 class="relative m-0 whitespace-nowrap text-start text-[min(40px,calc(100cqw/11.8))] leading-normal text-[#000022] max-[767px]:text-[25px]">لباس و ست‌های نوزادی</h2>
            <img src="<?= esc_url($up . '2023/12/ellipse.png') ?>" alt="" width="64" height="61" class="pointer-events-none absolute -top-3.75 start-[133px] -z-10 h-[61px] w-16 max-w-none max-[767px]:start-[40px]" loading="lazy">
            <p class="mb-8.75 mt-5 max-w-[394px] text-sm leading-[1.9]">نوزادتان را با لباس‌ها و ست‌های دوست‌داشتنی ما بپوشانید؛ دوخته‌شده از پارچه‌های نرم برای بیشترین راحتی و زیبایی. از سرهمی‌های دل‌چسب تا ست‌های دوتکهٔ ناز، هر لباس طوری طراحی شده که کوچولوی شما گرم و دوست‌داشتنی بماند.</p>
            <a href="<?= esc_url(home_url('/shop/')) ?>" class="inline-block rounded-btn bg-accent px-13.75 text-sm font-bold leading-[50px] text-white transition-colors duration-200 hover:bg-accent-dark">همین حالا بخرید</a>
        </div>
    </div>
</div>
</section>
