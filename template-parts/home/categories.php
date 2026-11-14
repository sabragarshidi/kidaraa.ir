<?php
/**
 * Categories as arched "doorways" (echoing the hero arch), staggered on wide screens.
 * Image = category thumbnail, else the newest product photo of that category.
 */
$kd_tints = ['bg-[#f3dfe3]', 'bg-[#dfe8e1]', 'bg-[#f6ecd2]', 'bg-[#dde9ee]'];
$kd_cats = [];
if (taxonomy_exists('product_cat')) {
    foreach (get_terms(['taxonomy' => 'product_cat', 'hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC']) as $t) {
        if (in_array($t->slug, ['all', 'uncategorized'], true)) continue;
        $img = '';
        $tid = (int) get_term_meta($t->term_id, 'thumbnail_id', true);
        if ($tid) $img = wp_get_attachment_image_url($tid, 'woocommerce_single');
        if (!$img) {
            foreach (wc_get_products(['category' => [$t->slug], 'status' => 'publish', 'limit' => 5, 'orderby' => 'date', 'order' => 'DESC']) as $pr) {
                $img = wp_get_attachment_image_url($pr->get_image_id(), 'woocommerce_single');
                if ($img) break;
            }
        }
        if (!$img) continue;
        $kd_cats[] = ['name' => $t->name, 'count' => $t->count, 'url' => get_term_link($t), 'img' => $img];
        if (count($kd_cats) === 4) break;
    }
}
if (count($kd_cats) < 2) { return; }
?>
<section class="container mt-25">
    <?php get_template_part('template-parts/section-title', null, ['title' => 'دسته‌بندی‌ها', 'subtitle' => 'از میان دسته‌ها، لباس دلخواه کوچولوی‌تان را پیدا کنید']); ?>
    <div class="mt-12 grid grid-cols-2 gap-x-4 gap-y-10 min-[768px]:grid-cols-4 min-[768px]:gap-x-7">
        <?php foreach ($kd_cats as $i => $c) : ?>
            <a href="<?= esc_url($c['url']) ?>" class="group block min-[768px]:[&:nth-child(even)]:mt-14">
                <span class="block aspect-[3/4] overflow-hidden rounded-t-[999px] <?= $kd_tints[$i % 4] ?>">
                    <?= kidara_img($c['img'], $c['name'], 'block h-full w-full object-cover mix-blend-multiply transition-transform duration-500 group-hover:scale-105') ?>
                </span>
                <span class="mt-4 flex items-center justify-between gap-3">
                    <span class="min-w-0">
                        <span class="block truncate text-xl leading-snug text-heading"><?= esc_html($c['name']) ?></span>
                        <span class="block text-sm text-muted"><?= esc_html(kidara_fa((string) $c['count'])) ?> محصول</span>
                    </span>
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-full border border-border text-[18px] text-heading transition-colors duration-200 group-hover:border-accent group-hover:bg-accent group-hover:text-white"><?php kidara_icon_e('arrow-left', 'kd-dir'); ?></span>
                </span>
            </a>
        <?php endforeach; ?>
    </div>
</section>
