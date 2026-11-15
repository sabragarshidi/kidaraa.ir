<?php
// Manifest #1 (title) + #2 (product slider): best sellers (total_sales), ties broken by newest.
$kd_ids = (new WP_Query([
    'post_type'      => 'product',
    'post_status'    => 'publish',
    'posts_per_page' => 10,
    'fields'         => 'ids',
    'no_found_rows'  => true,
    'tax_query'      => [['taxonomy' => 'product_visibility', 'field' => 'name', 'terms' => ['exclude-from-catalog'], 'operator' => 'NOT IN']],
    'meta_query'     => ['relation' => 'OR', 'kd_sales' => ['key' => 'total_sales', 'type' => 'NUMERIC'], 'kd_nosales' => ['key' => 'total_sales', 'compare' => 'NOT EXISTS']],
    'orderby'        => ['kd_sales' => 'DESC', 'date' => 'DESC'],
]))->posts;
$products = array_filter(array_map('kidara_card_args', $kd_ids));
?>
<section class="container mt-25">
    <?php get_template_part('template-parts/section-title', null, ['title' => 'پرفروش‌ترین‌ها', 'subtitle' => 'شیک، راحت و مطابق مد روز']); ?>
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
