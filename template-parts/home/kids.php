<?php
/**
 * Manifest #4: Kids — intro text, video thumbnail with lightbox, lookbook with 3 hotspots.
 */
$up = 'https://buny.wpbingosite.com/wp-content/uploads/';
$video = 'https://www.youtube.com/embed/XHOmBV4js_E';
// Popup position classes are literal so Tailwind picks them up (from the demo's .content-lookbook rules).
$spots = [
    ['left' => '32.04%', 'top' => '29.18%', 'url' => '/shop/braced-bloomer-fawn/', 'img' => $up . '2018/05/braced-bloomer-fawn-7.jpg', 'slug' => 'braced-bloomer-fawn',
     'pos' => 'translate-x-[calc(-100%_+_145px)] translate-y-7.5 group-data-[open]/spot:translate-y-13.75',
     'arrow' => 'before:-top-5.5 before:border-b-white before:left-[calc(50%_-_20px)]'],
    ['left' => '65.38%', 'top' => '32.52%', 'url' => '/shop/corduroy-baby-overalls/', 'img' => $up . '2020/12/corduroy-baby-overalls-4.jpg', 'slug' => 'corduroy-baby-overalls',
     'pos' => 'translate-x-[calc(-100%_+_40px)] translate-y-7.5 group-data-[open]/spot:translate-y-13.75',
     'arrow' => 'before:-top-5.5 before:border-b-white before:right-3.75'],
    ['left' => '87.42%', 'top' => '52.7%', 'url' => '/shop/organic-flutter-dress/', 'img' => $up . '2020/12/organic-flutter-dress-5.jpg', 'slug' => 'organic-flutter-dress',
     'pos' => 'translate-x-[calc(-100%_+_40px)] -translate-y-[calc(100%_+_20px)]',
     'arrow' => 'before:-bottom-5 before:border-t-white before:right-3.75'],
];
// Hotspots show the newest real products (positions stay fixed on the lookbook photo).
$kd_live = kidara_home_products(count($spots));
foreach ($spots as $kd_i => &$kd_sp) {
    $kd_pr = $kd_live[$kd_i] ?? null;
    $kd_sp['url'] = $kd_pr ? get_permalink($kd_pr->get_id()) : home_url('/shop/');
    $kd_sp['title'] = $kd_pr ? $kd_pr->get_name() : '';
    if ($kd_pr && ($kd_im = wp_get_attachment_image_url($kd_pr->get_image_id(), 'woocommerce_thumbnail'))) $kd_sp['img'] = $kd_im;
    if ($kd_pr && $kd_pr->is_on_sale() && !$kd_pr->is_type('variable')) {
        $kd_sp['price'] = '<del class="opacity-70">' . esc_html(kidara_money($kd_pr->get_regular_price())) . '</del> <ins class="no-underline">' . esc_html(kidara_money($kd_pr->get_sale_price())) . '</ins>';
    } else {
        $kd_sp['price'] = '<span>' . esc_html($kd_pr ? kidara_money($kd_pr->get_price() ?: $kd_pr->get_variation_price('min')) : '') . '</span>';
    }
}
unset($kd_sp);
?>
<section class="container mt-36.25 mb-24">
    <div class="-mx-3.75 flex flex-col min-[768px]:flex-row">
        <!-- left column -->
        <div class="w-full px-3.75 min-[768px]:w-1/3">
            <div class="pt-8 min-[1025px]:pt-31.25">
                <h2 class="m-0 text-[30px] leading-normal text-[#000022] min-[1025px]:text-[50px]">کودکان</h2>
                <h2 class="mb-0 mt-5.75 text-lg leading-normal">لباس، کفش و اکسسوری</h2>
                <p class="mb-0 mt-6.25">کمد کودکتان را با مجموعهٔ متنوع لباس، کفش و اکسسوری کودکانه ما کامل کنید؛ طراحی‌شده مطابق مد روز، با اولویت راحتی و دوام.</p>
            </div>
            <div class="relative mt-30 max-[1024px]:mt-12.5">
                <img src="<?= esc_url($up . '2023/11/leaves.png') ?>" alt="" width="39" height="87" class="pointer-events-none absolute -top-13.75 start-12.5 z-[2] max-w-none" loading="lazy">
                <div class="relative">
                    <?= kidara_img($up . '2023/11/banner-video.jpg', 'ویدیوی معرفی کیدارا', 'block h-auto w-full') ?>
                    <button type="button" data-video-open="#kd-video-modal" data-video-src="<?= esc_url($video) ?>" aria-label="پخش ویدیو"
                            class="absolute left-1/2 top-1/2 flex size-22.25 -translate-x-1/2 -translate-y-1/2 cursor-pointer items-center justify-center border-0 bg-transparent bg-contain bg-center bg-no-repeat text-white transition-all duration-300 hover:scale-110"
                            style="background-image:url('<?= esc_url($up . '2024/03/ellipse-2.png') ?>')">
                        <?= kidara_icon('play', 'text-[26px]') ?>
                    </button>
                </div>
                <img src="<?= esc_url($up . '2023/11/leaves2.png') ?>" alt="" width="122" height="104" class="pointer-events-none absolute start-[398px] top-[392px] z-[2] max-w-none max-[1024px]:hidden" loading="lazy">
            </div>
        </div>
        <!-- lookbook -->
        <div class="w-full px-3.75 max-[767px]:mt-10 min-[768px]:w-2/3">
            <div class="relative max-w-[910px]" data-lookbook>
                <?= kidara_img('https://buny.wpbingosite.com/wp-content/plugins/wpbingo/lib/lookbook/images/bwp_lookbook/lookbook-1.jpg', 'استایل کودکانه کیدارا', 'block h-auto w-full', ['width' => 910, 'height' => 762, 'loading' => 'eager']) ?>
                <?php foreach ($spots as $i => $sp) : ?>
                    <div data-spot class="group/spot absolute z-[1] size-7.5 cursor-pointer min-[768px]:size-10" style="left:<?= $sp['left'] ?>;top:<?= $sp['top'] ?>">
                        <span class="absolute -inset-1 -z-[1] rounded-full bg-white/80"></span>
                        <span class="absolute inset-0 flex items-center justify-center rounded-full bg-white text-heading transition-colors duration-[400ms] group-hover/spot:bg-accent group-hover/spot:text-white group-data-[open]/spot:bg-accent group-data-[open]/spot:text-white"><?= kidara_icon('plus', 'text-[16px]') ?></span>
                        <div class="invisible absolute left-0 top-0 z-[999] w-56.25 text-center opacity-0 transition-all duration-200 group-data-[open]/spot:visible group-data-[open]/spot:opacity-100 max-[767px]:fixed! max-[767px]:inset-x-0! max-[767px]:bottom-5! max-[767px]:top-auto! max-[767px]:mx-auto! max-[767px]:translate-x-0! max-[767px]:translate-y-0! <?= $sp['pos'] ?>">
                            <div class="relative bg-white p-3.75 shadow-[-1px_5px_5px_0_rgba(144,144,144,0.2)] before:absolute before:content-[''] before:border-solid before:border-transparent before:border-x-8 before:border-y-12 <?= $sp['arrow'] ?>">
                                <div class="mb-3 hidden items-center justify-between text-start max-[767px]:flex"><span class="font-bold text-heading">محصول</span><button type="button" data-spot-close class="cursor-pointer border-0 bg-transparent text-sm text-body">بستن</button></div>
                                <a href="<?= esc_url($sp['url']) ?>" class="block"><?= kidara_img($sp['img'], $sp['title'], 'mx-auto block h-auto w-full') ?></a>
                                <div class="mt-4.5 pb-2.5">
                                    <div class="mb-0.75 font-bold"><a href="<?= esc_url($sp['url']) ?>" class="text-heading hover:text-accent-dark"><?= esc_html($sp['title']) ?></a></div>
                                    <div class="text-sm text-body"><?= $sp['price'] ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- video lightbox -->
<div id="kd-video-modal" class="fixed inset-0 z-[10000] hidden items-center justify-center bg-black/80 p-4" role="dialog" aria-modal="true" aria-label="ویدیو">
    <div class="absolute inset-0 cursor-pointer" data-video-close></div>
    <div class="relative aspect-video w-full max-w-[900px] bg-black" data-video-frame></div>
    <button type="button" data-video-close aria-label="بستن" class="absolute end-5 top-5 cursor-pointer border-0 bg-transparent text-white"><?= kidara_icon('x', 'text-[32px]') ?></button>
</div>
