<?php
/** Product gallery: vertical thumbs + main swiper (thumbs go below on mobile). Args: product */
$product = $args['product'];
$ids = array_filter(array_merge([$product->get_image_id()], $product->get_gallery_image_ids()));
$items = [];
foreach ($ids as $id) {
    $src = wp_get_attachment_image_url($id, 'woocommerce_single');
    if (!$src) continue;
    $items[] = [
        'src'   => $src,
        'full'  => wp_get_attachment_image_url($id, 'full') ?: $src,
        'thumb' => wp_get_attachment_image_url($id, 'woocommerce_thumbnail') ?: $src,
        'alt'   => trim(get_post_meta($id, '_wp_attachment_image_alt', true)) ?: $product->get_name(),
    ];
}
if (!$items) {
    $ph = wc_placeholder_img_src('woocommerce_single');
    $items[] = ['src' => $ph, 'full' => $ph, 'thumb' => $ph, 'alt' => $product->get_name()];
}
$multi = count($items) > 1;
?>
<div class="kd-gallery grid min-w-0 items-start gap-3.75 min-[992px]:grid-cols-[88px_minmax(0,1fr)] min-[992px]:gap-x-3.5" data-gallery>
    <?php if ($multi) : ?>
    <div class="kd-thumbs order-2 grid grid-cols-4 gap-2.5 min-[992px]:order-1 min-[992px]:flex min-[992px]:flex-col min-[992px]:gap-3 min-[992px]:max-h-[760px] min-[992px]:overflow-y-auto">
        <?php foreach ($items as $i => $it) : ?>
            <button type="button" data-thumb="<?= $i ?>" aria-label="<?= esc_attr(sprintf('تصویر %s', kidara_fa((string) ($i + 1)))) ?>"
                    class="kd-thumb block cursor-pointer overflow-hidden rounded-xl border-2 border-transparent bg-bg-light p-0 transition-colors duration-200 hover:border-accent <?= $i === 0 ? "is-active" : "" ?>">
                <?= kidara_img($it['thumb'], $it['alt'], 'block aspect-[1000/1394] w-full object-cover') ?>
            </button>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="relative order-1 min-w-0 overflow-hidden rounded-2xl bg-bg-light min-[992px]:order-2 <?= $multi ? '' : 'min-[992px]:col-span-2' ?>">
        <div class="swiper kd-main" dir="rtl">
            <div class="swiper-wrapper">
                <?php foreach ($items as $i => $it) : ?>
                    <div class="swiper-slide">
                        <a href="<?= esc_url($it['full']) ?>" class="kd-zoom relative block aspect-[1000/1394] cursor-zoom-in overflow-hidden" data-full="<?= esc_url($it['full']) ?>">
                            <?= kidara_img($it['src'], $it['alt'], 'kd-main-img absolute inset-0 size-full object-cover transition-transform duration-200', ['data-orig' => $it['src'], 'data-orig-full' => $it['full']]) ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php if ($multi) : ?>
            <span class="kd-count pointer-events-none absolute start-3 top-3 z-[2] rounded-full bg-white/90 px-3 py-1 text-xs text-heading"><?= esc_html(kidara_fa('1')) ?> / <?= esc_html(kidara_fa((string) count($items))) ?></span>
        <?php endif; ?>
        <span class="pointer-events-none absolute inset-x-0 bottom-3 z-[2] mx-auto hidden w-fit items-center gap-1.5 rounded-full bg-white/90 px-4 py-1.5 text-xs text-heading min-[992px]:inline-flex"><?= kidara_icon('zoom-in', 'text-[14px]') ?> برای بزرگ‌نمایی کلیک کنید</span>
        <?php if ($multi) : ?>
            <button type="button" class="kd-prev absolute start-5 top-1/2 z-[2] -translate-y-1/2 cursor-pointer border-0 bg-transparent p-0 text-[30px] text-heading transition-colors duration-300 hover:text-accent-dark" aria-label="تصویر قبلی"><?= kidara_icon('chevron-left', 'kd-dir') ?></button>
            <button type="button" class="kd-next absolute end-5 top-1/2 z-[2] -translate-y-1/2 cursor-pointer border-0 bg-transparent p-0 text-[30px] text-heading transition-colors duration-300 hover:text-accent-dark" aria-label="تصویر بعدی"><?= kidara_icon('chevron-right', 'kd-dir') ?></button>
        <?php endif; ?>
    </div>
</div>
