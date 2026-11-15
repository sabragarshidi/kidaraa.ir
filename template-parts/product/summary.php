<?php
/** Product summary column. Args: product */
$product = $args['product'];
$pid     = $product->get_id();
$circle  = 'inline-flex size-13 shrink-0 cursor-pointer items-center justify-center rounded-[14px] border border-border bg-white p-0 text-heading transition-colors duration-300 hover:border-accent hover:text-accent';

// swatch colours for the JS layer: {"Green":"#5e6762",...}
$sw = [];
foreach ((array) get_post_meta($pid, '_kd_swatches', true) as $s) {
    if (is_array($s) && count($s) >= 2) $sw[$s[0]] = $s[1];
}

// Extra buttons live inside WC's add-to-cart form (after the add to cart button).
$extra_buttons = function () use ($product, $circle, $pid) {
    ?>
    <button type="button" class="kd-wishlist <?= $circle ?>" aria-label="افزودن به علاقه‌مندی‌ها" title="علاقه‌مندی‌ها" data-product-id="<?= (int) $pid ?>"><?= kidara_icon('heart', 'text-[17px]') ?></button>
    <?php
};
add_action('woocommerce_after_add_to_cart_button', $extra_buttons, 20);

$cats = wc_get_product_category_list($pid, ', ', '<span class="posted_in">' . 'دسته‌بندی:' . ' ', '</span>');
$tags = wc_get_product_tag_list($pid, ', ', '<span class="tagged_as">' . 'برچسب:' . ' ', '</span>');
$brands = taxonomy_exists('product_brand') ? get_the_term_list($pid, 'product_brand', '<span class="posted_in">برند: ', ', ', '</span>') : '';
$url   = rawurlencode(get_permalink($pid));
$img   = rawurlencode((string) wp_get_attachment_image_url($product->get_image_id(), 'full'));
$title = rawurlencode($product->get_name());
$shares = [
    ['facebook', 'فیس‌بوک', 'https://www.facebook.com/sharer.php?u=' . $url],
    ['x-twitter', 'ایکس', 'https://twitter.com/intent/tweet?url=' . $url . '&text=' . $title],
    ['linkedin', 'لینکدین', 'https://www.linkedin.com/shareArticle?mini=true&url=' . $url],
    ['pinterest', 'پینترست', 'https://pinterest.com/pin/create/button/?url=' . $url . '&media=' . $img],
];
$stock_qty = $product->managing_stock() ? $product->get_stock_quantity() : null;
$rating_count = $product->get_rating_count();
?>
<?php $kd_cat = $cats_terms = wc_get_product_terms($pid, 'product_cat', ['orderby' => 'parent', 'order' => 'DESC']); $kd_cat = $cats_terms && !is_wp_error($cats_terms) ? $cats_terms[0] : null; ?>
<div class="kd-summary min-w-0" data-swatches="<?= esc_attr(wp_json_encode($sw)) ?>" data-product-type="<?= esc_attr($product->get_type()) ?>">
    <?php if ($kd_cat) : ?><a href="<?= esc_url(get_term_link($kd_cat)) ?>" class="mb-1.5 inline-block text-sm text-accent-dark hover:text-accent"><?= esc_html($kd_cat->name) ?></a><?php endif; ?>
    <h1 class="product_title entry-title mb-2 mt-0 text-[19px] leading-[1.5] text-heading min-[768px]:text-[28px] min-[992px]:text-[34px]"><?php the_title(); ?></h1>

    <div class="mb-4 flex flex-wrap items-center gap-x-5 gap-y-1 text-sm text-muted">
        <?php if (wc_product_sku_enabled() && $product->get_sku()) : ?><span>کد محصول: <bdi dir="ltr"><?= esc_html($product->get_sku()) ?></bdi></span><?php endif; ?>
        <?php if (wc_review_ratings_enabled() && $rating_count > 0) : ?>
            <span class="woocommerce-product-rating flex items-center">
                <?= wc_get_rating_html($product->get_average_rating(), $rating_count) ?>
                <a href="#tab-reviews" class="woocommerce-review-link ms-2 text-body hover:text-accent-dark" rel="nofollow">(<?= esc_html(kidara_fa((string) $rating_count)) ?> دیدگاه)</a>
            </span>
        <?php endif; ?>
    </div>

    <div class="price-single mb-5 border-b border-border pb-5">
        <p class="kd-price m-0 text-[20px] min-[768px]:text-[30px] font-bold leading-tight text-heading [&_del]:me-2 [&_del]:text-base [&_del]:font-normal [&_del]:text-muted [&_ins]:text-accent-dark [&_ins]:no-underline [&_.woocommerce-Price-currencySymbol]:ms-1 [&_.woocommerce-Price-currencySymbol]:text-sm [&_.woocommerce-Price-currencySymbol]:font-normal [&_.woocommerce-Price-currencySymbol]:text-body"><?= $product->get_price_html() ?></p>
    </div>

    <?php if ($product->get_short_description()) : ?>
        <div class="description mb-5 text-sm leading-7 text-body [&_p]:mb-0"><?= apply_filters('woocommerce_short_description', $product->get_short_description()) ?></div>
    <?php endif; ?>

    <?php if (!$product->is_type('variable')) : ?>
        <div class="mb-4 flex items-center gap-2 text-sm <?= $product->is_in_stock() ? 'text-[#2f8a4d]' : 'text-hot' ?>">
            <span class="size-2 rounded-full <?= $product->is_in_stock() ? 'bg-[#2f8a4d]' : 'bg-hot' ?>"></span>
            <?= $product->is_in_stock() ? 'موجود در انبار' . ($stock_qty !== null && $stock_qty > 0 ? ' — ' . esc_html(kidara_fa((string) (int) $stock_qty)) . ' عدد' : '') : 'ناموجود' ?>
        </div>
    <?php endif; ?>

    <?php kidara_size_guide_render($product); ?>
    <div class="kd-cart"><?php woocommerce_template_single_add_to_cart(); ?></div>
    <?php remove_action('woocommerce_after_add_to_cart_button', $extra_buttons, 20); ?>

    <ul class="m-0 mt-6 grid list-none gap-3 rounded-2xl bg-[#f7f4f0] p-4 text-sm text-body">
        <?php foreach ([
            ['truck', 'ارسال به سراسر کشور، تحویل ۳ تا ۷ روز کاری پس از ثبت سفارش'],
            ['shield-check', 'پرداخت امن از طریق درگاه بانکی'],
        ] as [$ic, $tx]) : ?>
            <li class="flex items-center gap-3"><span class="inline-flex size-9 shrink-0 items-center justify-center rounded-full bg-white text-accent-dark"><?= kidara_icon($ic, 'text-[18px]') ?></span><span><?= esc_html($tx) ?></span></li>
        <?php endforeach; ?>
    </ul>
</div>
