<?php
/**
 * Categories: a quiet row of round photos with one-line names.
 * Image = category thumbnail, else the newest product photo of that category.
 */
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
    <div class="mt-12 flex justify-center gap-4 min-[768px]:gap-12">
        <?php foreach ($kd_cats as $c) : ?>
            <a href="<?= esc_url($c['url']) ?>" class="group block w-1/4 max-w-[190px] text-center">
                <span class="mx-auto block aspect-square w-full overflow-hidden rounded-full bg-bg-light ring-1 ring-transparent ring-offset-4 transition-shadow duration-300 group-hover:ring-accent">
                    <?= kidara_img($c['img'], $c['name'], 'block h-full w-full scale-110 object-cover transition-transform duration-500 group-hover:scale-125') ?>
                </span>
                <span class="mt-4 block text-base leading-snug text-heading transition-colors group-hover:text-accent min-[768px]:text-lg"><?= esc_html($c['name']) ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</section>
