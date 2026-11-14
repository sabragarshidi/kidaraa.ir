<?php
/**
 * Single product (demo: buny-product / buny-product-simple).
 * Parts live in template-parts/product/*.php. WC data + hooks are used for everything dynamic.
 */
defined('ABSPATH') || exit;

get_header();
// Colour attribute is a local attribute named 'Color': show the Persian label (variation form + additional info table).
add_filter('woocommerce_attribute_label', function ($label) {
    return strtolower(trim((string) $label)) === 'color' ? 'رنگ' : $label;
});
// Variation form script (swatches sit on top of WC's own selects).
wp_enqueue_script('wc-add-to-cart-variation');

while (have_posts()) :
    the_post();
    global $product;
    $product = wc_get_product(get_the_ID());
    if (!$product) { continue; }

    $cats = wc_get_product_terms($product->get_id(), 'product_cat', ['orderby' => 'parent', 'order' => 'DESC']);
    $crumbs = [['label' => 'خانه', 'url' => home_url('/')], ['label' => 'فروشگاه', 'url' => wc_get_page_permalink('shop')]];
    if ($cats && !is_wp_error($cats)) {
        $crumbs[] = ['label' => $cats[0]->name, 'url' => get_term_link($cats[0])];
    }
    $crumbs[] = ['label' => get_the_title()];
    ?>
    <div id="product-<?php the_ID(); ?>" <?php wc_product_class('kd-product woocommerce', $product); ?>>
        <?php // Breadcrumb (demo: 16px/24 heading colour, 20px vertical padding under the header) ?>
        <nav class="container mt-18 min-[1200px]:mt-29.5 flex flex-wrap items-center py-4 text-sm leading-6 text-body" aria-label="مسیر صفحه">
            <?php $last = count($crumbs) - 1; foreach ($crumbs as $i => $c) : ?>
                <?php if ($i < $last) : ?>
                    <a href="<?= esc_url($c['url']) ?>" class="text-body hover:text-accent-dark"><?= esc_html($c['label']) ?></a>
                    <span class="mx-1.5 text-muted"><?php kidara_icon_e('chevron-left', 'kd-dir text-[12px]'); ?></span>
                <?php else : ?>
                    <span class="text-heading"><?= esc_html($c['label']) ?></span>
                <?php endif; ?>
            <?php endforeach; ?>
        </nav>

        <?php woocommerce_output_all_notices(); ?>

        <div class="container grid gap-y-6 pb-10 min-[992px]:grid-cols-[minmax(0,1fr)_minmax(0,1fr)] min-[992px]:gap-x-14 min-[992px]:pb-14">
            <?php get_template_part('template-parts/product/gallery', null, ['product' => $product]); ?>
            <?php get_template_part('template-parts/product/summary', null, ['product' => $product]); ?>
        </div>

        <section class="kd-below bg-[#f7f4f0] py-8 min-[992px]:py-10">
            <div class="container grid items-start gap-6 min-[1100px]:grid-cols-[minmax(0,1fr)_320px]">
                <?php get_template_part('template-parts/product/tabs', null, ['product' => $product]); ?>
                <?php get_template_part('template-parts/product/mini', null, ['product' => $product]); ?>
            </div>
        </section>
        <?php get_template_part('template-parts/product/related', null, ['product' => $product]); ?>
    </div>
<?php endwhile;

get_footer();
