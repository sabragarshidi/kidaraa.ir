<?php
/**
 * Manifest #7 (title) + #8 (brand logo strip).
 */
$up = 'https://buny.wpbingosite.com/wp-content/uploads/2023/11/';
$brands = [
    ['name' => 'Dolphin', 'url' => '/shop/?brand=dolphin', 'img' => $up . 'brand-2.png'],
    ['name' => 'Gummy', 'url' => '/shop/?brand=gummy', 'img' => $up . 'brand-5.png'],
    ['name' => 'Harmony', 'url' => '/shop/?brand=harmony', 'img' => $up . 'brand-3.png'],
    ['name' => 'Lucya', 'url' => '/shop/?brand=lucya', 'img' => $up . 'brand-4.png'],
    ['name' => 'Rainboow', 'url' => '/shop/?brand=rainboow', 'img' => $up . 'brand-1.png'],
];
?>
<section class="container mb-24">
    <?php get_template_part('template-parts/section-title', null, ['title' => 'Find us in']); ?>
    <div class="-mx-3.75 mt-11.25" data-brands-slider>
        <div class="swiper">
            <div class="swiper-wrapper items-center">
                <?php foreach ($brands as $b) : ?>
                    <div class="swiper-slide flex justify-center px-3.75">
                        <a href="<?= esc_url($b['url']) ?>" class="block"><?= kidara_img($b['img'], $b['name'], 'block h-auto max-w-full transition-transform duration-300 hover:translate-x-1') ?></a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
