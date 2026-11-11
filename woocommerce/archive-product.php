<?php
/**
 * Shop + product category archive (demo: /shop/). Uses the main WC query — no second query.
 */
defined('ABSPATH') || exit;
get_header();

$is_cat = is_product_category();
$title  = $is_cat ? single_term_title('', false) : 'فروشگاه';
$crumbs = [['label' => 'خانه', 'url' => home_url('/')]];
if ($is_cat) {
    $crumbs[] = ['label' => 'فروشگاه', 'url' => get_permalink(wc_get_page_id('shop'))];
}
$crumbs[] = ['label' => $title];

ob_start();
get_template_part('template-parts/shop/banner-cats');
$after = ob_get_clean();

get_template_part('template-parts/page-title', null, ['title' => $title, 'crumbs' => $crumbs, 'after' => $after]);
?>
<main class="woocommerce kd-shop container pb-17.5" data-kd-shop>
    <div class="kd-shop-overlay fixed inset-0 z-[998] hidden bg-black/50" data-shop-overlay></div>
    <div class="flex flex-wrap">
        <aside class="kd-shop-sidebar w-full min-[992px]:w-1/4 min-[992px]:pe-7.5" data-shop-sidebar aria-label="فیلترها">
            <?php get_template_part('template-parts/shop/filters'); ?>
        </aside>

        <div class="w-full min-w-0 min-[992px]:w-3/4">
            <?php get_template_part('template-parts/shop/toolbar'); ?>

            <?php if (have_posts()) : ?>
                <div class="kd-shop-grid" data-shop-grid data-cols="3">
                    <?php while (have_posts()) : the_post();
                        $p = wc_get_product(get_the_ID());
                        if (!$p) continue;
                        get_template_part('template-parts/product-card', null, kidara_card_args($p, ['slide' => false]));
                    endwhile; ?>
                </div>
                <?php get_template_part('template-parts/shop/pagination'); ?>
            <?php else : ?>
                <div class="py-20 text-center">
                    <h2 class="mb-3 text-3xl text-heading">محصولی پیدا نشد</h2>
                    <p class="mb-6 text-base">محصولی با این فیلترها پیدا نشد. بازهٔ قیمت را بازتر کنید یا دستهٔ دیگری را ببینید.</p>
                    <a href="<?= esc_url(get_permalink(wc_get_page_id('shop'))) ?>" class="inline-block rounded-btn bg-accent px-7.5 py-3 font-bold text-white hover:bg-accent-dark">بازگشت به فروشگاه</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>
<?php get_footer();
