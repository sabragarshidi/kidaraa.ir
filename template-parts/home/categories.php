<?php
/**
 * Categories: a quiet row of round photos with one-line names.
 * 
 */
$kd_cats = [];
$kd_art = ['baby' => 'cat-baby.webp', 'boys' => 'cat-boys.webp', 'girls' => 'cat-girls.webp']; // watercolor illustrations; other categories are not shown
if (taxonomy_exists('product_cat')) {
    foreach ($kd_art as $slug => $file) {
        $t = get_term_by('slug', $slug, 'product_cat');
        if (!$t || is_wp_error($t) || !$t->count) continue;
        $kd_cats[] = ['name' => $t->name, 'url' => get_term_link($t), 'img' => get_theme_file_uri('assets/img/' . $file)];
    }
}
if (count($kd_cats) < 1) { return; }
?>
<section class="container mt-25">
    <?php get_template_part('template-parts/section-title', null, ['title' => 'دسته‌بندی‌ها', 'subtitle' => 'از میان دسته‌ها، لباس دلخواه کوچولوی‌تان را پیدا کنید']); ?>
    <div class="mt-12 flex justify-center gap-4 min-[768px]:gap-12">
        <?php foreach ($kd_cats as $c) : ?>
            <a href="<?= esc_url($c['url']) ?>" class="group block w-1/4 max-w-[190px] text-center">
                <span class="mx-auto block aspect-square w-full overflow-hidden rounded-full bg-bg-light ring-1 ring-transparent ring-offset-4 transition-shadow duration-300 group-hover:ring-accent">
                    <?= kidara_img($c['img'], $c['name'], 'block h-full w-full object-cover transition-transform duration-500 group-hover:scale-105') ?>
                </span>
                <span class="mt-4 block text-base leading-snug text-heading transition-colors group-hover:text-accent min-[768px]:text-lg"><?= esc_html($c['name']) ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</section>
