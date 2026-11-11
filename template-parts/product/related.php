<?php
/** Related + recently viewed product sliders (product-card via kidara_card_args). Args: product */
$product = $args['product'];

$related = array_filter(array_map('wc_get_product', wc_get_related_products($product->get_id(), 8)), function ($p) {
    return $p && $p->is_visible();
});
$recent_ids = isset($_COOKIE['woocommerce_recently_viewed']) ? array_reverse(array_filter(array_map('absint', explode('|', wp_unslash($_COOKIE['woocommerce_recently_viewed']))))) : [];
if (!in_array($product->get_id(), $recent_ids, true)) array_unshift($recent_ids, $product->get_id());
$recent = array_filter(array_map('wc_get_product', array_slice($recent_ids, 0, 8)), function ($p) {
    return $p && $p->is_visible();
});

$sections = ['محصولات مرتبط' => $related, 'بازدیدهای اخیر شما' => $recent];
$i = 0;
foreach ($sections as $title => $list) :
    if (!$list) continue; $i++; ?>
    <section class="container <?= $i === 1 ? 'mt-25' : 'mt-15' ?> mb-3.75">
        <h2 class="mb-10 text-center text-3xl leading-none max-[767px]:text-[25px]"><?= esc_html($title) ?></h2>
        <div class="group/slider relative -mx-3.75" data-product-slider>
            <div class="swiper" dir="rtl">
                <div class="swiper-wrapper">
                    <?php foreach ($list as $p) get_template_part('template-parts/product-card', null, kidara_card_args($p, ['slide' => true])); ?>
                </div>
            </div>
            <button type="button" class="js-prev absolute start-3.75 top-[35%] z-[1] cursor-pointer text-[30px] text-heading opacity-0 invisible transition-all duration-300 hover:text-accent-dark group-hover/slider:opacity-100 group-hover/slider:visible max-[1199px]:opacity-100 max-[1199px]:visible max-[480px]:hidden" aria-label="قبلی"><?= kidara_icon('chevron-left', 'kd-dir') ?></button>
            <button type="button" class="js-next absolute end-3.75 top-[35%] z-[1] cursor-pointer text-[30px] text-heading opacity-0 invisible transition-all duration-300 hover:text-accent-dark group-hover/slider:opacity-100 group-hover/slider:visible max-[1199px]:opacity-100 max-[1199px]:visible max-[480px]:hidden" aria-label="بعدی"><?= kidara_icon('chevron-right', 'kd-dir') ?></button>
        </div>
    </section>
<?php endforeach;
