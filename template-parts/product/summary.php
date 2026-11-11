<?php
/** Product summary column. Args: product */
$product = $args['product'];
$pid     = $product->get_id();
$circle  = 'inline-flex size-12.5 shrink-0 cursor-pointer items-center justify-center rounded-full border border-border bg-white p-0 text-heading transition-colors duration-300 hover:border-accent hover:text-accent';

// swatch colours for the JS layer: {"Green":"#5e6762",...}
$sw = [];
foreach ((array) get_post_meta($pid, '_kd_swatches', true) as $s) {
    if (is_array($s) && count($s) >= 2) $sw[$s[0]] = $s[1];
}

// Extra buttons live inside WC's add-to-cart form (after the add to cart button).
$extra_buttons = function () use ($product, $circle, $pid) {
    ?>
    <button type="button" class="kd-wishlist <?= $circle ?>" aria-label="افزودن به علاقه‌مندی‌ها" title="علاقه‌مندی‌ها" data-product-id="<?= (int) $pid ?>"><?= kidara_icon('heart', 'text-[17px]') ?></button>
    <button type="button" class="kd-compare <?= $circle ?>" aria-label="مقایسه" title="مقایسه" data-product-id="<?= (int) $pid ?>"><?= kidara_icon('shuffle', 'text-[17px]') ?></button>
    <?php if ($product->is_purchasable() && $product->is_in_stock()) : ?>
        <button type="submit" name="add-to-cart" value="<?= (int) $pid ?>" formaction="<?= esc_url(wc_get_checkout_url()) ?>"
                class="kd-buy-now order-last mt-0 block h-13 w-full basis-full cursor-pointer rounded-btn border border-border bg-white text-sm font-bold leading-[50px] text-body transition-colors duration-300 hover:border-heading hover:bg-heading hover:text-white">خرید فوری</button>
    <?php endif;
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
<div class="kd-summary min-w-0 min-[992px]:pt-0" data-swatches="<?= esc_attr(wp_json_encode($sw)) ?>" data-product-type="<?= esc_attr($product->get_type()) ?>">
    <h1 class="product_title entry-title mb-2.5 mt-0 text-[30px] leading-[35px] text-heading"><?php the_title(); ?></h1>

    <?php if (wc_review_ratings_enabled() && $rating_count > 0) : ?>
        <div class="woocommerce-product-rating mb-1.5 flex items-center text-sm leading-[23.8px]">
            <?= wc_get_rating_html($product->get_average_rating(), $rating_count) ?>
            <a href="#reviews" class="woocommerce-review-link ms-5 text-body hover:text-accent-dark" rel="nofollow">(<?= '<span class="count">' . esc_html(kidara_fa((string) $rating_count)) . '</span> دیدگاه کاربر' ?>)</a>
        </div>
    <?php endif; ?>

    <div class="price-single mb-5 border-b border-border pb-[21px]">
        <p class="kd-price m-0 text-[23px] leading-[14px] font-bold text-accent [&_del]:me-2 [&_del]:text-base [&_del]:font-normal [&_del]:text-muted [&_ins]:no-underline"><?= $product->get_price_html() ?></p>
    </div>

    <div class="product-count-view mb-2.5 flex items-center gap-2 text-sm text-heading" data-min="30" data-max="40" data-timeout="10000">
        <?= kidara_icon('eye', 'text-[15px]') ?><span class="kd-viewers font-bold">۳۲</span> نفر همین حالا این محصول را می‌بینند
    </div>

    <?php if ($product->get_short_description()) : ?>
        <div class="description mb-5 text-body [&_p]:mb-0"><?= apply_filters('woocommerce_short_description', $product->get_short_description()) ?></div>
    <?php endif; ?>

    <?php if ($stock_qty !== null && $stock_qty > 0 && $product->is_in_stock()) : $pc = min(100, max(5, round($stock_qty / max(1, $stock_qty + 5) * 100))); ?>
        <div class="percent_quantity_stock mb-3.75">
            <div class="quantity_stock mb-2 text-sm text-heading">فقط <span class="text-[#e00]"><?= kidara_fa((string) (int) $stock_qty) ?> عدد</span> در انبار باقی مانده است!</div>
            <div class="h-[3px] bg-[#eee]"><div class="h-full bg-[#e00]" style="width:<?= (int) $pc ?>%"></div></div>
        </div>
    <?php endif; ?>

    <div class="kd-cart"><?php woocommerce_template_single_add_to_cart(); ?></div>
    <?php remove_action('woocommerce_after_add_to_cart_button', $extra_buttons, 20); ?>

    <div class="safe-checkout mt-7.5 rounded-[25px] bg-[#f3f3f3] px-5 pt-9.5 pb-8 text-center">
        <img src="https://buny.wpbingosite.com/wp-content/themes/buny/images/payment-product.png" alt="روش‌های پرداخت امن" class="mx-auto block h-auto max-w-full" loading="lazy" width="400" height="30">
        <div class="mt-4 text-smtext-heading">پرداخت امن و تضمین‌شده</div>
    </div>

    <ul class="product-shipping-delivers m-0 mt-5 list-none border-b border-border p-0 pb-5 text-sm text-body">
        <li class="mb-3 flex items-center gap-3.5"><?= kidara_icon('package', 'text-[26px] text-heading') ?><span>ارسال رایگان برای سفارش‌های بالای <?= kidara_money(1000000) ?></span></li>
        <li class="flex items-center gap-3.5"><?= kidara_icon('clock', 'text-[26px] text-heading') ?><span>زمان تحویل: ۳ تا ۷ روز کاری <a href="<?= esc_url(home_url('/faq/')) ?>" class="text-heading underline">ارسال و بازگشت کالا</a></span></li>
    </ul>

    <div class="product_meta pt-5 text-sm leading-[29px] text-body [&_a]:text-heading [&_a:hover]:text-accent-dark [&>span]:block">
        <?php if (wc_product_sku_enabled() && ($product->get_sku() || $product->is_type('variable'))) : ?>
            <span class="sku_wrapper">شناسهٔ محصول: <span class="sku text-heading"><?= $product->get_sku() ? '<bdi dir="ltr">' . esc_html($product->get_sku()) . '</bdi>' : 'نامشخص' ?></span></span>
        <?php endif; ?>
        <?= $cats ?>
        <?= $tags ?>
        <?= $brands ?>
        <span class="social-icon !flex items-center gap-3">اشتراک‌گذاری:
            <?php foreach ($shares as [$ic, $label, $href]) : ?>
                <a href="<?= esc_url($href) ?>" target="_blank" rel="noopener" title="<?= esc_attr($label) ?>" aria-label="<?= esc_attr($label) ?>" class="inline-flex text-base !text-accent hover:!text-accent-dark"><?= kidara_icon($ic) ?></a>
            <?php endforeach; ?>
        </span>
    </div>
</div>
