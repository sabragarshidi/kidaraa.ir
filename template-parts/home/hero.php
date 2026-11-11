<?php
/**
 * Manifest #0: hero slider (Revolution Slider in the demo, rebuilt with Swiper fade).
 * Rev "levels" (gw 1920/1024/778/480): layer numbers below are the demo's data-xy/dim values
 * per level [desktop>=1240, notebook>=1200, tablet 480-1199, mobile<480]; --hu scales them like Rev does.
 * Right-anchored/centered layers were converted to plain left/right offsets from the stage edge.
 */
$up = 'https://buny.wpbingosite.com/wp-content/uploads/';
$yr = kidara_jdate(null, 'Y');
$slides = [
    [
        'eyebrow' => "کالکشن بهار {$yr}",
        'line'    => 'لباس بهاره',
        'mark'    => 'نوزاد و کودک',
        'text'    => 'کیدارا را ساختیم تا همهٔ خریدهای آگاهانه و ارگانیکِ پدر و مادرها، برای نوزادشان، یک‌جا باشد.',
        'photo'   => $up . '2024/01/slider-1.jpg',
    ],
    [
        'eyebrow' => 'تازه‌های نوزادی',
        'line'    => 'لباس نوزاد',
        'mark'    => 'تازه‌متولد',
        'text'    => 'بامزه‌ترین استایل‌ها و ستهای هماهنگ، طراحی‌شده برای پوشیدن راحت و آسایش هر روزه.',
        'photo'   => $up . '2024/01/slider-2.jpg',
    ],
    [
        'eyebrow' => 'مد پسرانهٔ بهار',
        'line'    => 'لباس پسرانه',
        'mark'    => 'شاد و راحت',
        'text'    => 'جدیدترین مدل‌های بهار و تابستان با طرح‌های روز؛ جزئیات ظریف، برای کوچک‌ترین عضوهای خانواده.',
        'photo'   => $up . '2024/01/slider-3.jpg',
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
    /* text block: sizes are design px scaled by --hu, per Rev level */
    .kd-hero { --ts: 34; --ps: 15; --bs: 15; --es: 13; --bh: 46; --gap: 14; }
    .kd-hero .kd-txt { display:flex; flex-direction:column; align-items:center; text-align:center; gap:calc(var(--gap)*var(--hu)); }
    .kd-hero .kd-eyebrow { display:inline-flex; align-items:center; gap:calc(8*var(--hu)); background:#fff; border:1px solid var(--color-border); border-radius:999px; padding:calc(6*var(--hu)) calc(18*var(--hu)); font-size:calc(var(--es)*var(--hu)); line-height:1.6; color:var(--color-accent-dark); box-shadow:0 2px 12px rgba(0,0,0,.04); }
    .kd-hero .kd-eyebrow::before { content:""; width:calc(8*var(--hu)); height:calc(8*var(--hu)); border-radius:50%; background:var(--color-hot); flex:none; }
    .kd-hero .kd-title { margin:0; font-size:calc(var(--ts)*var(--hu)); line-height:1.5; font-weight:700; color:var(--color-heading); }
    .kd-hero .kd-title .kd-mark { position:relative; z-index:0; display:inline-block; white-space:nowrap; color:var(--color-hot); }
    .kd-hero .kd-title .kd-mark::after { content:""; position:absolute; z-index:-1; inset-inline:-.12em; bottom:.12em; height:.34em; background:var(--color-blob); border-radius:999px; }
    .kd-hero .kd-lead { margin:0; max-width:calc(470*var(--hu)); font-size:calc(var(--ps)*var(--hu)); line-height:2; color:var(--color-body); }
    .kd-hero .kd-btns { display:flex; flex-wrap:wrap; gap:calc(14*var(--hu)); justify-content:center; }
    .kd-hero .kd-btn { display:inline-flex; align-items:center; justify-content:center; gap:calc(10*var(--hu)); height:calc(var(--bh)*var(--hu)); padding-inline:calc(var(--bh)*.7*var(--hu)); border-radius:999px; font-size:calc(var(--bs)*var(--hu)); font-weight:700; line-height:1; white-space:nowrap; transition:background-color .2s,color .2s,border-color .2s,transform .2s; }
    .kd-hero .kd-btn-primary { background:var(--color-accent); color:#fff; border:2px solid var(--color-accent); }
    .kd-hero .kd-btn-primary:hover { background:var(--color-accent-dark); border-color:var(--color-accent-dark); }
    .kd-hero .kd-btn-ghost { background:transparent; color:var(--color-accent-dark); border:2px solid var(--color-accent); }
    .kd-hero .kd-btn-ghost:hover { background:var(--color-accent); color:#fff; }
    .kd-hero .kd-btn .kd-icon { font-size:1.15em; }
    @media (max-width: 479px) { .kd-hero .kd-eyebrow, .kd-hero .kd-lead, .kd-hero .kd-btn-ghost { display:none; } }
    @media (max-width: 1199px) { .kd-hero .kd-lead { display:none; } }
    @media (max-width: 1199px) { .kd-hero .kd-btn-ghost { display:none; } }
    @media (min-width: 480px) { .kd-hero { --ts: 38; --es: 13; --bs: 15; --bh: 46; --gap: 12; } }
    @media (min-width: 1200px) {
        .kd-hero { --ts: 50; --ps: 16; --bs: 16; --es: 14; --bh: 50; --gap: 16; }
        .kd-hero .kd-txt { align-items:flex-start; text-align:start; }
        .kd-hero .kd-btns { justify-content:flex-start; }
    }
    @media (min-width: 1240px) { .kd-hero { --ts: 68; --ps: 20; --bs: 18; --es: 16; --bh: 58; --gap: 22; } }
</style>
<section class="kd-hero relative overflow-hidden bg-[#f6f6f6]" data-hero-slider>
    <div class="swiper" dir="rtl">
        <div class="swiper-wrapper">
            <?php foreach ($slides as $s) : ?>
                <div class="swiper-slide !h-auto">
                    <div class="relative mx-auto h-[calc(var(--gh)*var(--hu))] w-[calc(var(--gw)*var(--hu))]">
                        <!-- cloud along the bottom edge, centered on the stage -->
                        <img src="<?= esc_url($up . '2024/01/cloud.png') ?>" alt="" width="1920" height="280"
                             class="kd-l pointer-events-none absolute -bottom-px left-1/2 z-[7] max-w-none -translate-x-1/2 h-[calc(var(--h)*var(--hu))] w-[calc(var(--w)*var(--hu))]"
                             style="<?= kd_hero_lv(['w' => [1920, 1371, 1371, 1029], 'h' => [280, 200, 200, 150]]) ?>">
                        <!-- arch photo, right anchored (x = right offset, y = bottom offset) -->
                        <img src="<?= esc_url($s['photo']) ?>" alt="کودک با لباس کیدارا" width="498" height="732"
                             class="kd-l absolute z-[6] max-w-none rounded-t-[249px] border-5 border-white object-cover end-[calc(var(--x)*var(--hu))] bottom-[calc(var(--y)*var(--hu))] h-[calc(var(--h)*var(--hu))] w-[calc(var(--w)*var(--hu))]"
                             style="<?= kd_hero_lv(['x' => [280, 30, 30, 90], 'y' => [0, 0, 0, -90], 'w' => [498, 350, 330, 300], 'h' => [732, 514, 485, 441]]) ?>">
                        <!-- text group -->
                        <div class="kd-l kd-txt absolute z-[8] start-[calc(var(--x)*var(--hu))] top-[calc(var(--y)*var(--hu))] h-[calc(var(--h)*var(--hu))] w-[calc(var(--w)*var(--hu))]"
                             style="<?= kd_hero_lv(['x' => [300, 50, 30, 90], 'y' => [170, 142, 137, 144], 'w' => [640, 500, 330, 300], 'h' => [460, 400, 300, 220]]) ?>">
                            <span class="kd-eyebrow"><?= esc_html($s['eyebrow']) ?></span>
                            <h2 class="kd-title"><?= esc_html($s['line']) ?><br><span class="kd-mark"><?= esc_html($s['mark']) ?></span></h2>
                            <p class="kd-lead"><?= esc_html($s['text']) ?></p>
                            <div class="kd-btns">
                                <a href="<?= esc_url(home_url('/shop/')) ?>" class="kd-btn kd-btn-primary">همین حالا بخرید <?php kidara_icon_e('arrow-right', 'kd-dir'); ?></a>
                                <a href="<?= esc_url(home_url('/about-us/')) ?>" class="kd-btn kd-btn-ghost">دربارهٔ کیدارا</a>
                            </div>
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
