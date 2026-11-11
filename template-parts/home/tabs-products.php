<?php
// Manifest #5: tabs + 4x2 product grid + View All. Only the "All" list exists in the capture; tabs filter it by category.
$kd_slugs = ['the-rest-shortie-snowberry', '100-pure-cotton-jumpsuit', 'dress-pansy-floral-mist', 'quincy-mae-luca-pants-pecan', 'organic-flutter-dress', 'nature-baby-drawstring-pants-nettle', 'dress-petite-fleur-soft-peony', 'peplum-set-cherries-blush'];
$products = [];
foreach ($kd_slugs as $kd_slug) {
    $kd_p = function_exists('wc_get_product') ? get_page_by_path($kd_slug, OBJECT, 'product') : null;
    if (!$kd_p) continue;
    $kd_terms = wp_get_post_terms($kd_p->ID, 'product_cat', ['fields' => 'slugs']);
    $products[] = kidara_card_args($kd_p->ID) + ['cat_slugs' => is_wp_error($kd_terms) ? '' : implode(' ', $kd_terms)];
}
$tabs = ['all' => 'همه', 'baby' => 'نوزاد', 'boys' => 'پسرانه', 'girls' => 'دخترانه', 'toys' => 'اسباب‌بازی'];
?>
<section class="container mb-16.25" data-product-tabs>
    <ul class="flex flex-nowrap items-center justify-center py-3.75 max-[767px]:justify-start" role="tablist">
        <?php $i = 0; foreach ($tabs as $slug => $label) : ?>
            <li class="group/tab <?= $i ? 'ps-[58px] max-[767px]:ps-5' : '' ?>" role="presentation">
                <button type="button" role="tab" aria-selected="<?= $i ? 'false' : 'true' ?>" data-tab="<?= esc_attr($slug) ?>" class="relative cursor-pointer border-0 bg-transparent p-0 font-display text-xl max-[767px]:text-lg leading-[34px] whitespace-nowrap text-heading">
                    <img src="https://buny.wpbingosite.com/wp-content/uploads/2023/12/ellipse.png" alt="" width="64" height="61" class="pointer-events-none absolute left-1/2 top-1/2 -z-10 h-[61px] w-16 max-w-none -translate-x-1/2 -translate-y-1/2 opacity-0 transition-opacity duration-300 group-has-[[aria-selected=true]]/tab:opacity-100" loading="lazy">
                    <?= esc_html($label) ?>
                </button>
            </li>
        <?php $i++; endforeach; ?>
    </ul>
    <div class="relative isolate -mx-3.75 max-[767px]:-mx-[7.5px] mt-5 grid grid-cols-2 min-[768px]:grid-cols-3 min-[1200px]:grid-cols-4" data-grid>
        <?php foreach ($products as $p) : ?>
            <div class="px-3.75 max-[767px]:px-[7.5px]" data-cat="<?= esc_attr($p['cat_slugs']) ?>">
                <?php get_template_part('template-parts/product-card', null, $p); ?>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="mt-5 text-center">
        <a href="<?= esc_url(home_url('/shop/')) ?>" class="inline-flex h-12.5 items-center rounded-btn bg-accent px-13.75 text-sm font-bold leading-[50px] text-white transition-colors duration-300 hover:bg-accent-dark">مشاهدهٔ همه</a>
    </div>
</section>
