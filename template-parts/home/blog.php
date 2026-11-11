<?php
/**
 * Manifest #6: "Our Blog" banner + 3 recent posts. 3 latest real posts.
 */
$up = 'https://buny.wpbingosite.com/wp-content/uploads/';
$demo_imgs = [$up . '2023/01/little-fashionistas-wardrobe-8-720x484.jpg', $up . '2023/01/little-fashionistas-wardrobe-7-720x484.jpg', $up . '2023/01/little-fashionistas-wardrobe-6-720x484.jpg'];
$posts = [];
foreach (get_posts(['numberposts' => 3, 'post_status' => 'publish', 'ignore_sticky_posts' => true]) as $n => $kd_post) {
    $posts[] = [
        'url'     => get_permalink($kd_post),
        'img'     => get_the_post_thumbnail_url($kd_post, 'medium_large') ?: $demo_imgs[$n % 3],
        'date'    => kidara_post_date($kd_post),
        'title'   => get_the_title($kd_post),
        'excerpt' => wp_trim_words(get_the_excerpt($kd_post) ?: wp_strip_all_tags($kd_post->post_content), 20, '…'),
    ];
}
?>
<section class="container mt-25 mb-24">
    <div class="-mx-3.75 flex flex-col min-[1025px]:flex-row">
        <!-- banner -->
        <div class="relative w-full px-3.75 min-[1025px]:w-1/2 min-[1025px]:px-0">
            <img src="<?= esc_url($up . '2023/11/heart.png') ?>" alt="" width="53" height="59" class="pointer-events-none absolute -top-7.5 start-5 z-0 max-w-none" loading="lazy">
            <a href="<?= esc_url(home_url('/blog/')) ?>" class="relative z-[1] block overflow-hidden min-[1025px]:mx-10 min-[1025px]:h-[620px]">
                <?= kidara_img($up . '2023/11/banner-3.jpg', 'بنر وبلاگ کیدارا', 'block aspect-square h-full w-full object-cover opacity-100 min-[1025px]:aspect-auto') ?>
                <span class="absolute inset-0 flex items-center justify-center px-7.5">
                    <span class="block max-w-[418px] text-center text-white">
                        <span class="mb-6.25 block font-display text-[25px] leading-normal min-[768px]:text-[30px] min-[1025px]:text-[40px]">وبلاگ ما</span>
                        <span class="block whitespace-nowrap text-base leading-8 max-[480px]:whitespace-normal">در وبلاگ ما انبوهی از الهام و دانستنی‌ها را پیدا می‌کنید؛<br> مطالبی جذاب برای والدین و کودکان،<br> از مقاله‌های کاربردی تا داستان‌های الهام‌بخش…</span>
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
                            <h3 class="mb-3 font-body text-base font-bold leading-normal"><a href="<?= esc_url($p['url']) ?>" class="text-heading hover:text-accent-dark"><?= esc_html($p['title']) ?></a></h3>
                            <p class="m-0 text-sm leading-[1.9]"><?= esc_html($p['excerpt']) ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
