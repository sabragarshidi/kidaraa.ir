<?php
/**
 * Reusable product card (Buny .products-entry.content-product1).
 *
 * get_template_part('template-parts/product-card', null, [
 *   'url','title','category','category_url','image','image_hover',
 *   'price','regular_price','price_html',
 *   'badges' => ['hot'=>bool, 'sale'=>'-33%', 'stock'=>'out of stock'|'pre order'],
 *   'swatches' => [['name'=>'Green','color'=>'#5e6762'], ...],
 *   'product_id' => 0, 'slide' => false,
 * ]);
 */
$a = wp_parse_args($args ?? [], [
    'url' => '#', 'title' => '', 'category' => '', 'category_url' => '#',
    'image' => '', 'image_hover' => '', 'price' => '', 'regular_price' => '', 'price_html' => '',
    'badges' => [], 'swatches' => [], 'product_id' => 0, 'slide' => false,
    'cart_label' => 'Add to cart',
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
<div class="group/card relative mb-7.5 products-entry"<?= $pid ? ' data-product-id="' . $pid . '"' : '' ?>>
    <div class="relative overflow-hidden mb-3.75 bg-bg-light">
        <a href="<?= esc_url($a['url']) ?>" class="relative block aspect-[600/836]" aria-label="<?= esc_attr($a['title']) ?>">
            <?php if ($a['image']) : ?>
                <?= kidara_img($a['image'], $a['title'], 'absolute inset-0 size-full object-cover transition-all duration-300 group-hover/card:opacity-0' . ($hover ? '' : ' group-hover/card:opacity-100')) ?>
            <?php endif; ?>
            <?php if ($hover) : ?>
                <?= kidara_img($hover, '', 'absolute inset-0 size-full object-cover opacity-0 invisible transition-all duration-300 group-hover/card:opacity-100 group-hover/card:visible group-hover/card:scale-103') ?>
            <?php endif; ?>
        </a>

        <?php
        $badge = 'absolute top-5 z-[5] h-11.25 rounded-full text-center text-white text-sm font-bold leading-[45px] lowercase';
        if (!empty($badges['hot']) && empty($badges['stock'])) : ?>
            <div class="<?= $badge ?> start-5 w-11.25 bg-hot">hot</div>
        <?php endif; ?>
        <?php if (!empty($badges['stock'])) : ?>
            <div class="absolute top-5 start-5 z-10 h-11.25 rounded-[45px] bg-stock px-3.75 text-sm font-bold leading-[45px] whitespace-nowrap lowercase text-white"><?= esc_html($badges['stock']) ?></div>
        <?php endif; ?>
        <?php if (!empty($badges['sale'])) : ?>
            <div class="<?= $badge ?> end-5 w-11.25 bg-accent"><?= esc_html($badges['sale']) ?></div>
        <?php endif; ?>

        <!-- hover buttons (desktop >=1200) -->
        <div class="absolute inset-x-0 top-1/2 z-[9] -translate-y-1/2 flex flex-wrap items-center justify-center max-[1199px]:hidden">
            <div class="<?= $tip ?> [transition-delay:0.05s]" data-title="<?= esc_attr($a['cart_label']) ?>">
                <a href="<?= esc_url($a['url']) ?>" class="js-add-to-cart size-11.25 <?= $circle ?>" aria-label="<?= esc_attr($a['cart_label']) ?>" data-product-id="<?= $pid ?>"><?= kidara_icon('shopping-bag', 'text-[28px]') ?></a>
            </div>
            <div class="<?= $tip ?> [transition-delay:0.1s]" data-title="Quick View">
                <button type="button" class="js-quickview size-11.25 <?= $circle ?>" aria-label="Quick View" data-product-id="<?= $pid ?>"><?= kidara_icon('eye', 'text-[27px]') ?></button>
            </div>
            <div class="<?= $tip ?> [transition-delay:0.15s]" data-title="Wishlist">
                <button type="button" class="js-wishlist size-11.25 <?= $circle ?>" aria-label="Wishlist" data-product-id="<?= $pid ?>"><?= kidara_icon('heart', 'text-[17px]') ?></button>
            </div>
        </div>

        <!-- compact buttons (<1200) -->
        <div class="absolute bottom-2.5 end-2.5 z-[9] flex flex-col min-[1200px]:hidden">
            <button type="button" class="js-wishlist my-[3.5px] size-8.75 <?= $circle ?>" aria-label="Wishlist" data-product-id="<?= $pid ?>"><?= kidara_icon('heart', 'text-base') ?></button>
            <button type="button" class="js-quickview my-[3.5px] size-8.75 <?= $circle ?>" aria-label="Quick View" data-product-id="<?= $pid ?>"><?= kidara_icon('eye', 'text-2xl') ?></button>
        </div>
    </div>

    <div class="text-center">
        <?php if ($a['category']) : ?>
            <div class="mb-1.25 text-base leading-[27.2px]"><a href="<?= esc_url($a['category_url']) ?>" class="text-body hover:text-accent"><?= esc_html($a['category']) ?></a></div>
        <?php endif; ?>
        <h3 class="mb-[11px] font-body text-base font-bold leading-[19.2px] text-heading"><a href="<?= esc_url($a['url']) ?>" class="line-clamp-2 hover:text-accent"><?= esc_html($a['title']) ?></a></h3>
        <span class="mb-2.5 block text-base font-bold leading-none text-accent [&_del]:text-sm [&_del]:font-normal [&_del]:text-muted [&_ins]:no-underline">
            <?php if ($a['price_html']) : ?>
                <?= wp_kses_post($a['price_html']) ?>
            <?php else : ?>
                <?php if ($a['regular_price']) : ?><del><?= esc_html($a['regular_price']) ?></del> <?php endif; ?><ins><?= esc_html($a['price']) ?></ins>
            <?php endif; ?>
        </span>
        <?php if (!empty($a['swatches'])) : ?>
            <div class="-mx-1.25 mt-0.75 flex flex-wrap items-center justify-center">
                <?php foreach ($a['swatches'] as $sw) : ?>
                    <div class="group/sw relative px-1.25 py-0.5 before:content-[attr(data-title)] before:absolute before:-top-8.25 before:left-1/2 before:-translate-x-1/2 before:bg-heading before:text-white before:text-xs before:leading-[23px] before:px-2.5 before:rounded-sm before:whitespace-nowrap before:z-10 before:opacity-0 before:invisible before:pointer-events-none before:transition-all before:duration-200 hover:before:opacity-100 hover:before:visible" data-title="<?= esc_attr($sw['name'] ?? '') ?>">
                        <span class="inline-block size-5.5 cursor-pointer rounded-full border-2 border-white align-middle shadow-[0_0_1px_1px_#ddd] transition-all duration-300 group-hover/sw:border-heading group-hover/sw:shadow-none" style="background-color:<?= esc_attr($sw['color'] ?? '#ccc') ?>"></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php if ($a['slide']) : ?></div><?php endif; ?>
