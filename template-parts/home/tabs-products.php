<?php
// Manifest #5: tabs + 4x2 product grid + View All.
// Tabs = «همه» + top-level categories (baby/boys/girls); each tab has its own 8 products,
// mixed across that category's sub-categories. Grid = union; data-cat lists the tabs an item belongs to.
$kd_lists = ['all' => kidara_home_products(8)];
$tabs = ['all' => 'همه'];
foreach (kidara_shop_cats(true) as $kd_t) {
    $kd_l = kidara_home_products(8, ['category' => [$kd_t->slug]]);
    if (!$kd_l) continue;
    $kd_lists[$kd_t->slug] = $kd_l;
    $tabs[$kd_t->slug] = $kd_t->name;
}
if (count($tabs) < 3) { $tabs = ['all' => 'همه']; $kd_lists = ['all' => $kd_lists['all']]; }
$kd_items = [];
foreach ($kd_lists as $kd_tab => $kd_l) {
    foreach ($kd_l as $kd_prod) {
        $kd_id = $kd_prod->get_id();
        $kd_items[$kd_id] ??= ['prod' => $kd_prod, 'tabs' => []];
        $kd_items[$kd_id]['tabs'][] = $kd_tab;
    }
}
$products = [];
foreach ($kd_items as $kd_it) {
    $products[] = kidara_card_args($kd_it['prod']) + ['cat_slugs' => implode(' ', $kd_it['tabs']), 'in_all' => in_array('all', $kd_it['tabs'], true)];
}
if (!$products) return;
?>
<section class="container mb-16.25" data-product-tabs>
    <ul class="flex flex-nowrap items-center justify-center py-3.75 max-[767px]:justify-start" role="tablist">
        <?php $i = 0; foreach ($tabs as $slug => $label) : ?>
            <li class="group/tab <?= $i ? 'ps-[58px] max-[767px]:ps-5' : '' ?>" role="presentation">
                <button type="button" role="tab" aria-selected="<?= $i ? 'false' : 'true' ?>" data-tab="<?= esc_attr($slug) ?>" class="relative cursor-pointer border-0 bg-transparent p-0 font-display text-xl max-[767px]:text-lg leading-[34px] whitespace-nowrap text-heading">
                    <img src="/wp-content/themes/kidara/assets/img/demo/uploads/2023/12/ellipse.png" alt="" width="64" height="61" class="pointer-events-none absolute left-1/2 top-1/2 -z-10 h-[61px] w-16 max-w-none -translate-x-1/2 -translate-y-1/2 opacity-0 transition-opacity duration-300 group-has-[[aria-selected=true]]/tab:opacity-100" loading="lazy">
                    <?= esc_html($label) ?>
                </button>
            </li>
        <?php $i++; endforeach; ?>
    </ul>
    <div class="relative isolate -mx-3.75 max-[767px]:-mx-[7.5px] mt-5 grid grid-cols-2 min-[768px]:grid-cols-3 min-[1200px]:grid-cols-4" data-grid>
        <?php foreach ($products as $p) : ?>
            <div class="px-3.75 max-[767px]:px-[7.5px]" data-cat="<?= esc_attr($p['cat_slugs']) ?>"<?= $p['in_all'] ? '' : ' hidden' ?>>
                <?php get_template_part('template-parts/product-card', null, $p); ?>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="mt-5 text-center">
        <a href="<?= esc_url(home_url('/shop/')) ?>" class="inline-flex h-12.5 items-center rounded-btn bg-accent px-13.75 text-sm font-bold leading-[50px] text-white transition-colors duration-300 hover:bg-accent-dark">مشاهدهٔ همه</a>
    </div>
</section>
