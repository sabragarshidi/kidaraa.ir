<?php
/**
 * Manifest #0: hero slider (Revolution Slider in the demo, rebuilt with Swiper fade).
 * Rev "levels" (gw 1920/1024/778/480): layer numbers below are the demo's data-xy/dim values
 * per level [desktop>=1240, notebook>=1200, tablet 480-1199, mobile<480]; --hu scales them like Rev does.
 * Right-anchored/centered layers were converted to plain left/right offsets from the stage edge.
 */
$up = 'https://buny.wpbingosite.com/wp-content/uploads/';
$slides = [
    [
        'title' => "Spring<br>Baby<br>Clothes",
        'text'  => "We created Buny with an aspiration to<br>become a one stop shop for all conscious, organic and ethical<br>purchases relating to parenthood and your baby.",
        'photo' => $up . '2024/01/slider-1.jpg',
        'gw'    => [420, 350, 300, 250],
    ],
    [
        'title' => "Newborn<br>Baby<br>Clothes",
        'text'  => "Our cutest looks &amp; matching sets,<br>designed for easy dressing &amp; everyday comfort.<br>Welcome to Buny!",
        'photo' => $up . '2024/01/slider-2.jpg',
        'gw'    => [420, 350, 300, 250],
    ],
    [
        'title' => "Boys'<br>Clothes<br>Online",
        'text'  => "The latest styles for spring &amp; summer with<br>on-trend prints, positive slogans &amp; more. Delicate prints &amp; special<br>features, designed with the smallest arrivals in mind",
        'photo' => $up . '2024/01/slider-3.jpg',
        'gw'    => [450, 350, 300, 250],
    ],
];

/** [d, n, t, m] -> "--{$k}0:d;--{$k}1:n;--{$k}2:t;--{$k}3:m;" */
if (!function_exists('kd_hero_lv')) {
function kd_hero_lv(array $vars): string
{
    $s = '';
    foreach ($vars as $k => $v) {
        foreach ($v as $i => $n) {
            $s .= '--' . $k . $i . ':' . $n . ';';
        }
    }
    return $s;
} }
?>
<style>
    .kd-hero { --hu: min(1px, calc(100vw / 480)); --gw3: 480; --gh3: 750; --gw: var(--gw3); --gh: var(--gh3) }
    .kd-hero .kd-l { --x: var(--x3); --y: var(--y3); --w: var(--w3); --h: var(--h3); --fs: var(--fs3); --lh: var(--lh3); --px: var(--px3) }
    @media (min-width: 480px) {
        .kd-hero { --hu: min(1px, calc(100vw / 778)); --gw: 778; --gh: 550 }
        .kd-hero .kd-l { --x: var(--x2); --y: var(--y2); --w: var(--w2); --h: var(--h2); --fs: var(--fs2); --lh: var(--lh2); --px: var(--px2) }
    }
    @media (min-width: 1200px) {
        .kd-hero { --hu: min(1px, calc(100vw / 1024)); --gw: 1024; --gh: 650 }
        .kd-hero .kd-l { --x: var(--x1); --y: var(--y1); --w: var(--w1); --h: var(--h1); --fs: var(--fs1); --lh: var(--lh1); --px: var(--px1) }
    }
    @media (min-width: 1240px) {
        .kd-hero { --hu: min(1px, calc(100vw / 1920)); --gw: 1920; --gh: 880 }
        .kd-hero .kd-l { --x: var(--x0); --y: var(--y0); --w: var(--w0); --h: var(--h0); --fs: var(--fs0); --lh: var(--lh0); --px: var(--px0) }
    }
</style>
<section class="kd-hero relative overflow-hidden bg-[#f6f6f6]" data-hero-slider>
    <div class="swiper">
        <div class="swiper-wrapper">
            <?php foreach ($slides as $s) : ?>
                <div class="swiper-slide !h-auto">
                    <div class="relative mx-auto h-[calc(var(--gh)*var(--hu))] w-[calc(var(--gw)*var(--hu))]">
                        <!-- cloud along the bottom edge, centered on the stage -->
                        <img src="<?= esc_url($up . '2024/01/cloud.png') ?>" alt="" width="1920" height="280"
                             class="kd-l pointer-events-none absolute -bottom-px left-1/2 z-[7] max-w-none -translate-x-1/2 h-[calc(var(--h)*var(--hu))] w-[calc(var(--w)*var(--hu))]"
                             style="<?= kd_hero_lv(['w' => [1920, 1371, 1371, 1029], 'h' => [280, 200, 200, 150]]) ?>">
                        <!-- arch photo, right anchored (x = right offset, y = bottom offset) -->
                        <img src="<?= esc_url($s['photo']) ?>" alt="" width="498" height="732"
                             class="kd-l absolute z-[6] max-w-none rounded-t-[249px] border-5 border-white object-cover end-[calc(var(--x)*var(--hu))] bottom-[calc(var(--y)*var(--hu))] h-[calc(var(--h)*var(--hu))] w-[calc(var(--w)*var(--hu))]"
                             style="<?= kd_hero_lv(['x' => [280, 30, 30, 90], 'y' => [0, 0, 0, -90], 'w' => [498, 350, 330, 300], 'h' => [732, 514, 485, 441]]) ?>">
                        <!-- text group -->
                        <div class="kd-l absolute z-[8] start-[calc(var(--x)*var(--hu))] top-[calc(var(--y)*var(--hu))] h-[calc(var(--h)*var(--hu))] w-[calc(var(--w)*var(--hu))]"
                             style="<?= kd_hero_lv(['x' => [315, 50, 30, 115], 'y' => [211, 142, 137, 144], 'w' => $s['gw'], 'h' => [500, 400, 300, 220]]) ?>">
                            <h2 class="kd-l absolute start-0 top-0 m-0 w-full text-center font-display font-normal text-heading text-[length:calc(var(--fs)*var(--hu))] leading-[calc(var(--lh)*var(--hu))]"
                                style="<?= kd_hero_lv(['fs' => [60, 50, 40, 35], 'lh' => [75, 65, 55, 50]]) ?>"><?= $s['title'] ?></h2>
                            <p class="kd-l absolute start-1/2 m-0 -translate-x-1/2 whitespace-nowrap text-center text-body max-[1199px]:hidden top-[calc(var(--y)*var(--hu))] text-[length:calc(var(--fs)*var(--hu))] leading-[calc(var(--lh)*var(--hu))]"
                               style="<?= kd_hero_lv(['y' => [239, 217, 174, 107], 'fs' => [16, 16, 16, 9], 'lh' => [26, 25, 25, 15]]) ?>"><?= $s['text'] ?></p>
                            <a href="<?= esc_url(home_url('/shop/')) ?>"
                               class="kd-l absolute start-1/2 -translate-x-1/2 whitespace-nowrap rounded-btn bg-accent text-center text-[length:calc(14*var(--hu))] font-bold text-white max-[479px]:text-sm transition-colors duration-200 hover:bg-accent-dark top-[calc(var(--y)*var(--hu))] leading-[calc(var(--lh)*var(--hu))] px-[calc(var(--px)*var(--hu))] max-[479px]:px-12.5 max-[479px]:leading-[50px]"
                               style="<?= kd_hero_lv(['y' => [356, 339, 187, 170], 'lh' => [50, 50, 50, 50], 'px' => [80, 70, 55, 50]]) ?>">Shop Now</a>
                        </div>
                        <!-- decor: dragonfly, feather(leaves), balloon (hidden on mobile) -->
                        <img src="<?= esc_url($up . '2020/06/dragonfly.png') ?>" alt="" class="kd-l absolute z-[9] max-w-none max-[479px]:hidden start-[calc(var(--x)*var(--hu))] top-[calc(var(--y)*var(--hu))] h-[calc(var(--h)*var(--hu))] w-[calc(var(--w)*var(--hu))]"
                             style="<?= kd_hero_lv(['x' => [753, 481, 365, 225], 'y' => [674, 453, 344, 212], 'w' => [29, 29, 25, 15], 'h' => [34, 34, 29, 17]]) ?>">
                        <img src="<?= esc_url($up . '2023/11/leaves.png') ?>" alt="" class="kd-l absolute z-10 max-w-none max-[479px]:hidden start-[calc(var(--x)*var(--hu))] top-[calc(var(--y)*var(--hu))] h-[calc(var(--h)*var(--hu))] w-[calc(var(--w)*var(--hu))]"
                             style="<?= kd_hero_lv(['x' => [946, 534, 371, 228], 'y' => [463, 236, 175, 107], 'w' => [39, 35, 35, 21], 'h' => [87, 78, 78, 48]]) ?>">
                        <img src="<?= esc_url($up . '2020/06/balloon.png') ?>" alt="" class="kd-l absolute z-[11] max-w-none max-[479px]:hidden start-[calc(var(--x)*var(--hu))] top-[calc(var(--y)*var(--hu))] h-[calc(var(--h)*var(--hu))] w-[calc(var(--w)*var(--hu))]"
                             style="<?= kd_hero_lv(['x' => [1043, 575, 443, 273], 'y' => [215, 107, 55, 33], 'w' => [75, 70, 55, 33], 'h' => [71, 66, 52, 32]]) ?>">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
