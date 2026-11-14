<?php
/**
 * Reusable product card (Buny .products-entry.content-product1).
 *
 * get_template_part('template-parts/product-card', null, [
 *   'url','title','category','category_url','image','image_hover',
 *   'price','regular_price','price_html',
 *   'badges' => ['hot'=>bool, 'sale'=>'۳۳٪', 'stock'=>'ناموجود'|'پیش‌سفارش'],
 *   'swatches' => [['name'=>'سبز','color'=>'#5e6762'], ...],
 *   'product_id' => 0, 'slide' => false,
 * ]);
 */
$a = wp_parse_args($args ?? [], [
    'url' => '#', 'title' => '', 'category' => '', 'category_url' => '#',
    'image' => '', 'image_hover' => '', 'price' => '', 'regular_price' => '', 'price_html' => '',
    'badges' => [], 'swatches' => [], 'product_id' => 0, 'slide' => false,
    'cart_label' => 'افزودن به سبد',
]);
$badges = (array) $a['badges'];
$hover  = $a['image_hover'] ?: '';
$pid    = (int) $a['product_id'];

$circle = 'inline-flex items-center justify-center rounded-full bg-white text-heading shadow-[0_0_30px_0_rgba(0,0,0,0.06)] transition-colors duration-300 hover:text-accent cursor-pointer border-0 p-0';
// desktop hover buttons: tooltip via data-title
$tip = 'relative m-1.25 opacity-0 invisible transition-all duration-500 group-hover/card:opacity-100 group-hover/card:visible '
    . 'before:content-[attr(data-title)] before:absolute before:-top-9.75 before:left-1/2 before:-translate-x-1/2 before:bg-heading before:text-white before:text-xs before:leading-6 before:px-2.5 before:rounded-[3px] before:whitespace-nowrap before:opacity-0 before:invisible before:pointer-events-none before:transition-all before:duration-200 '
    . 'hover:before:opacity-100 hover:before:visible hover:before:-top-8.25';
?>
<?php if ($a['slide']) : ?><div class="swiper-slide px-3.75 !h-auto"><?php endif; ?>
<div class="group/card relative mb-7.5 products-entry overflow-hidden rounded-2xl border border-border bg-white"<?= $pid ? ' data-product-id="' . $pid . '"' : '' ?>>
    <div class="kd-card-img relative overflow-hidden bg-bg-light">
        <a href="<?= esc_url($a['url']) ?>" class="relative block aspect-[600/836]" aria-label="<?= esc_attr($a['title']) ?>">
            <?php if ($a['image']) : ?>
                <?= kidara_img($a['image'], $a['title'], 'absolute inset-0 size-full object-cover transition-all duration-300 group-hover/card:opacity-0' . ($hover ? '' : ' group-hover/card:opacity-100')) ?>
            <?php endif; ?>
            <?php if ($hover) : ?>
                <?= kidara_img($hover, '', 'absolute inset-0 size-full object-cover opacity-0 invisible transition-all duration-300 group-hover/card:opacity-100 group-hover/card:visible group-hover/card:scale-103') ?>
            <?php endif; ?>
        </a>

        <?php
        $badge = 'absolute top-3 z-[5] h-8 rounded-full text-center text-white text-xs font-bold leading-8';
        if (!empty($badges['hot']) && empty($badges['stock'])) : ?>
            <div class="<?= $badge ?> start-3 min-w-8 px-2.5 bg-hot">ویژه</div>
        <?php endif; ?>
        <?php if (!empty($badges['stock'])) : ?>
            <div class="absolute top-3 start-3 z-10 h-8 rounded-full bg-stock px-3 text-xs font-bold leading-8 whitespace-nowrap text-white max-[767px]:top-3.75 max-[767px]:start-2.5 max-[767px]:h-8 max-[767px]:px-2.5 max-[767px]:text-xs max-[767px]:leading-8"><?= esc_html($badges['stock']) ?></div>
        <?php endif; ?>
        <?php if (!empty($badges['sale'])) : ?>
            <div class="<?= $badge ?> end-3 min-w-8 px-1.5 bg-accent"><?= esc_html($badges['sale']) ?></div>
        <?php endif; ?>

        <!-- hover buttons (desktop >=1200) -->
        <div class="kd-card-actions absolute inset-x-0 top-1/2 z-[9] -translate-y-1/2 flex flex-wrap items-center justify-center max-[1199px]:hidden">
            <div class="<?= $tip ?> [transition-delay:0.05s]" data-title="<?= esc_attr($a['cart_label']) ?>">
                <a href="<?= esc_url($a['url']) ?>" class="js-add-to-cart size-11.25 <?= $circle ?>" aria-label="<?= esc_attr($a['cart_label']) ?>" data-product-id="<?= $pid ?>"><?= kidara_icon('shopping-bag', 'text-[28px]') ?></a>
            </div>
            <div class="<?= $tip ?> [transition-delay:0.1s]" data-title="نمایش سریع">
                <button type="button" class="js-quickview size-11.25 <?= $circle ?>" aria-label="نمایش سریع" data-product-id="<?= $pid ?>"><?= kidara_icon('eye', 'text-[27px]') ?></button>
            </div>
            <div class="<?= $tip ?> [transition-delay:0.15s]" data-title="علاقه‌مندی">
                <button type="button" class="js-wishlist size-11.25 <?= $circle ?>" aria-label="علاقه‌مندی" data-product-id="<?= $pid ?>"><?= kidara_icon('heart', 'text-[17px]') ?></button>
            </div>
        </div>

        <!-- compact buttons (<1200) -->
        <div class="kd-card-actions absolute bottom-20 end-2.5 z-[9] flex flex-col min-[1200px]:hidden">
            <button type="button" class="js-wishlist my-[3.5px] size-8.75 <?= $circle ?>" aria-label="علاقه‌مندی" data-product-id="<?= $pid ?>"><?= kidara_icon('heart', 'text-base') ?></button>
            <button type="button" class="js-quickview my-[3.5px] size-8.75 <?= $circle ?>" aria-label="نمایش سریع" data-product-id="<?= $pid ?>"><?= kidara_icon('eye', 'text-2xl') ?></button>
        </div>
    </div>

    <div class="kd-card-info absolute inset-x-2.5 bottom-2.5 z-[6] rounded-xl bg-white/90 px-3 py-2.5 text-center backdrop-blur-sm">
        <h3 class="mb-1 font-body text-sm font-bold leading-normal text-heading"><a href="<?= esc_url($a['url']) ?>" class="line-clamp-1 hover:text-accent"><?= esc_html($a['title']) ?></a></h3>
        <span class="block text-sm font-bold leading-none text-accent [&_del]:text-xs [&_del]:font-normal [&_del]:text-muted max-[767px]:[&_del]:hidden [&_ins]:no-underline">
            <?php if ($a['price_html']) : ?>
                <?= wp_kses_post($a['price_html']) ?>
            <?php else : ?>
                <?php if ($a['regular_price']) : ?><del><?= esc_html($a['regular_price']) ?></del> <?php endif; ?><ins><?= esc_html($a['price']) ?></ins>
            <?php endif; ?>
        </span>
    </div>
</div>
<?php if ($a['slide']) : ?></div><?php endif; ?>
