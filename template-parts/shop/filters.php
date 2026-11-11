<?php
/** Filter sidebar: categories, price range (GET min_price/max_price), featured products. Off-canvas drawer < 992px. */
global $wpdb;
$title = 'mb-6.25 mt-0 text-xl leading-[1.2] text-heading';
$wrap  = 'mb-12.5';

$cats = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => true, 'exclude' => [get_option('default_product_cat')], 'orderby' => 'term_id']);
$cur  = is_product_category() ? get_queried_object_id() : 0;

$row = $wpdb->get_row("SELECT FLOOR(MIN(min_price)) a, CEIL(MAX(max_price)) b FROM {$wpdb->wc_product_meta_lookup}");
$min = $row && $row->b > 0 ? (int) $row->a : 0;
$max = $row && $row->b > 0 ? (int) $row->b : 1000;
$vmin = isset($_GET['min_price']) ? max($min, (int) $_GET['min_price']) : $min; // phpcs:ignore
$vmax = isset($_GET['max_price']) ? min($max, (int) $_GET['max_price']) : $max; // phpcs:ignore
$action = is_product_category() ? get_term_link(get_queried_object()) : get_permalink(wc_get_page_id('shop'));

$featured = wc_get_products(['limit' => 3, 'status' => 'publish', 'featured' => true]);
?>
<div class="kd-shop-sidebar-inner">
    <button type="button" class="kd-filter-close mb-5 hidden size-10 items-center justify-center rounded-full border-0 bg-bg-light text-heading" data-shop-close aria-label="Close filters"><?= kidara_icon('x', 'text-[20px]') ?></button>

    <?php if ($cats && !is_wp_error($cats)) : ?>
    <section class="<?= $wrap ?>">
        <h3 class="<?= $title ?>">Categories</h3>
        <ul class="m-0 list-none p-0">
            <?php foreach ($cats as $c) : ?>
                <li class="py-2 text-[13px] leading-[18px]">
                    <a href="<?= esc_url(get_term_link($c)) ?>" class="<?= $cur === $c->term_id ? 'text-accent' : 'text-heading' ?> hover:text-accent"><?= esc_html($c->name) ?> <span class="text-xs text-muted">(<?= (int) $c->count ?>)</span></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <?php endif; ?>

    <section class="<?= $wrap ?>">
        <h3 class="<?= $title ?>">Price</h3>
        <form method="get" action="<?= esc_url($action) ?>" class="kd-price" data-price data-min="<?= $min ?>" data-max="<?= $max ?>">
            <?php if (!empty($_GET['orderby'])) : // phpcs:ignore ?><input type="hidden" name="orderby" value="<?= esc_attr(wc_clean(wp_unslash($_GET['orderby']))) ?>"><?php endif; ?>
            <div class="kd-range relative mb-5 h-5">
                <span class="kd-range-track absolute inset-x-0 top-2 h-1 rounded bg-border"></span>
                <span class="kd-range-fill absolute top-2 h-1 rounded bg-accent" data-fill></span>
                <input type="range" name="min_price" min="<?= $min ?>" max="<?= $max ?>" value="<?= $vmin ?>" data-lo aria-label="Minimum price">
                <input type="range" name="max_price" min="<?= $min ?>" max="<?= $max ?>" value="<?= $vmax ?>" data-hi aria-label="Maximum price">
            </div>
            <div class="flex items-center justify-between text-[13px] text-heading">
                <span>Range : <span class="text-[#e00]"><span data-lo-out><?= $vmin ?>.00</span>$ - <span data-hi-out><?= $vmax ?>.00</span>$</span></span>
                <button type="submit" class="cursor-pointer rounded-btn border border-border bg-white px-3.75 py-1 text-xs font-bold text-heading transition-colors hover:border-accent hover:bg-accent hover:text-white">Filter</button>
            </div>
        </form>
    </section>

    <?php if ($featured) : ?>
    <section>
        <h3 class="<?= $title ?>">Feature Product</h3>
        <ul class="m-0 list-none p-0">
            <?php foreach ($featured as $f) : ?>
                <li class="mb-3.75 flex gap-3.75">
                    <a href="<?= esc_url(get_permalink($f->get_id())) ?>" class="block w-18.75 shrink-0 bg-bg-light"><?= kidara_img(wp_get_attachment_image_url($f->get_image_id(), 'woocommerce_thumbnail') ?: '', $f->get_name(), 'aspect-[600/836] w-full object-cover') ?></a>
                    <div class="min-w-0">
                        <a href="<?= esc_url(get_permalink($f->get_id())) ?>" class="block text-sm leading-[1.4] text-heading hover:text-accent"><?= esc_html($f->get_name()) ?></a>
                        <span class="mt-1 block text-[13px] font-bold text-accent [&_del]:font-normal [&_del]:text-muted [&_ins]:no-underline"><?= wp_kses_post($f->get_price_html()) ?></span>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <?php endif; ?>
</div>
