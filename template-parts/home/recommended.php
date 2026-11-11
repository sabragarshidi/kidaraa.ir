<?php
// Manifest #1 (title) + #2 (product slider). Data copied from captured demo.
$kd_slugs = ['braced-bloomer-fawn', 'short-sleeve-belle-dress', 'gathered-bloomers-basil', 'organic-belle-dress-ditsy-clay', 'zanti-humpback-belt-for-babies'];
$products = [];
foreach ($kd_slugs as $kd_slug) {
    $kd_p = function_exists('wc_get_product') ? get_page_by_path($kd_slug, OBJECT, 'product') : null;
    if ($kd_p) $products[] = kidara_card_args($kd_p->ID);
}
?>
<section class="container mt-25">
    <?php get_template_part('template-parts/section-title', null, ['title' => 'پیشنهاد ویژه برای شما', 'subtitle' => 'شیک، راحت و مطابق مد روز']); ?>
</section>
<section class="container mt-10 mb-16.25">
    <div class="group/slider relative -mx-3.75" data-product-slider>
        <div class="swiper" dir="rtl">
            <div class="swiper-wrapper">
                <?php foreach ($products as $p) {
                    get_template_part('template-parts/product-card', null, $p + ['slide' => true]);
                } ?>
            </div>
        </div>
        <button type="button" class="js-prev absolute start-3.75 top-[35%] z-[1] text-[30px] text-heading opacity-0 invisible transition-all duration-300 hover:text-accent-dark group-hover/slider:opacity-100 group-hover/slider:visible max-[1199px]:opacity-100 max-[1199px]:visible max-[480px]:hidden cursor-pointer" aria-label="قبلی"><?= kidara_icon('chevron-left', 'kd-dir') ?></button>
        <button type="button" class="js-next absolute end-3.75 top-[35%] z-[1] text-[30px] text-heading opacity-0 invisible transition-all duration-300 hover:text-accent-dark group-hover/slider:opacity-100 group-hover/slider:visible max-[1199px]:opacity-100 max-[1199px]:visible max-[480px]:hidden cursor-pointer" aria-label="بعدی"><?= kidara_icon('chevron-right', 'kd-dir') ?></button>
    </div>
</section>
