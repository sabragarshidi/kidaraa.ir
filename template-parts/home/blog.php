<?php
/**
 * Manifest #6: "Our Blog" banner + 3 recent posts. Static copy of the demo.
 */
$up = 'https://buny.wpbingosite.com/wp-content/uploads/';
$posts = [
    ['url' => '/little-fashionistas-wardrobe/', 'img' => $up . '2023/01/little-fashionistas-wardrobe-8-720x484.jpg', 'date' => 'January 12, 2023', 'title' => 'Little Fashionistas’ Wardrobe', 'excerpt' => 'From everyday outfits to unique styling tips, Little Fashionistas’ Wardrobe is not just a place to shop, but also...'],
    ['url' => '/tiny-trendsetters-collection/', 'img' => $up . '2023/01/little-fashionistas-wardrobe-7-720x484.jpg', 'date' => 'January 11, 2023', 'title' => 'Tiny Trendsetters Collection', 'excerpt' => 'Dive into a world of miniature style statements curated for the trendsetting tots. Sed a libero. Mauris sollicitudin fermentum libero....'],
    ['url' => '/fashionable-kiddos-attire/', 'img' => $up . '2023/01/little-fashionistas-wardrobe-6-720x484.jpg', 'date' => 'January 10, 2023', 'title' => 'Fashionable Kiddos Attire', 'excerpt' => 'Our collection is tailored to suit the dynamic tastes of the trendiest little ones. Sed a libero. Mauris sollicitudin fermentum...'],
];
?>
<section class="container mt-25 mb-24">
    <div class="-mx-3.75 flex flex-col min-[1025px]:flex-row">
        <!-- banner -->
        <div class="relative w-full px-3.75 min-[1025px]:w-1/2 min-[1025px]:px-0">
            <img src="<?= esc_url($up . '2023/11/heart.png') ?>" alt="" width="53" height="59" class="pointer-events-none absolute -top-7.5 start-5 z-0 max-w-none" loading="lazy">
            <a href="#" class="relative z-[1] block overflow-hidden min-[1025px]:mx-10 min-[1025px]:h-[620px]">
                <?= kidara_img($up . '2023/11/banner-3.jpg', 'Banner Image', 'block aspect-square h-full w-full object-cover opacity-100 min-[1025px]:aspect-auto') ?>
                <span class="absolute inset-0 flex items-center justify-center px-7.5">
                    <span class="block max-w-[418px] text-center text-white">
                        <span class="mb-6.25 block font-display text-[25px] leading-[1.2] min-[768px]:text-[30px] min-[1025px]:text-[40px]">Our Blog</span>
                        <span class="block whitespace-nowrap text-base leading-6.5 max-[480px]:whitespace-normal">Discover a wealth of inspiration and knowledge on Our Blog,<br> where we curate engaging content to fuel your passions and<br> pursuits.From in-depth articles to inspiring stories…</span>
                    </span>
                </span>
            </a>
        </div>
        <!-- posts -->
        <div class="relative w-full px-3.75 max-[1024px]:mt-10 min-[1025px]:w-1/2 min-[1025px]:ps-10 min-[1025px]:pe-7.5">
            <img src="<?= esc_url($up . '2023/11/rainbow.png') ?>" alt="" width="147" height="122" class="pointer-events-none absolute -start-10 top-27 z-[2] max-w-none max-[1199px]:hidden" loading="lazy">
            <div class="flex flex-col gap-7.5">
                <?php foreach ($posts as $p) : ?>
                    <article class="flex gap-0 max-[767px]:flex-col">
                        <a href="<?= esc_url($p['url']) ?>" class="block shrink-0 min-[768px]:w-60" aria-hidden="true" tabindex="-1">
                            <?= kidara_img($p['img'], $p['title'], 'block h-auto w-full object-cover min-[768px]:h-40 min-[768px]:w-60') ?>
                        </a>
                        <div class="min-w-0 max-w-[385px] pt-2.25 max-[767px]:pt-3.75 min-[768px]:ps-7.5">
                            <div class="mb-3.5 flex items-center gap-1 text-sm text-accent"><?= kidara_icon('calendar-clock', 'text-[18px]') ?><time><?= esc_html($p['date']) ?></time></div>
                            <h3 class="mb-3 font-body text-base font-bold leading-[18px]"><a href="<?= esc_url($p['url']) ?>" class="text-heading hover:text-accent-dark"><?= esc_html($p['title']) ?></a></h3>
                            <p class="m-0 text-sm leading-[1.7]"><?= esc_html($p['excerpt']) ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
