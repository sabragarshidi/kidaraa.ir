<?php
/** Sticky mini buy card beside the cards (desktop >= 1100px). Args: product */
$product = $args['product'];
$img = wp_get_attachment_image_url($product->get_image_id(), 'woocommerce_thumbnail');
?>
<aside class="sticky top-[110px] hidden rounded-2xl border border-border bg-white p-4 min-[1100px]:block" aria-label="خرید سریع">
    <div class="flex items-center gap-3">
        <?php if ($img) : ?><span class="block w-16 shrink-0 overflow-hidden rounded-xl bg-bg-light"><?= kidara_img($img, $product->get_name(), 'block aspect-[600/836] w-full object-cover') ?></span><?php endif; ?>
        <div class="min-w-0">
            <div class="line-clamp-2 text-sm leading-6 text-heading"><?= esc_html($product->get_name()) ?></div>
        </div>
    </div>
    <div class="my-4 text-lg font-bold text-heading [&_del]:me-2 [&_del]:text-sm [&_del]:font-normal [&_del]:text-muted [&_ins]:text-accent-dark [&_ins]:no-underline [&_.woocommerce-Price-currencySymbol]:ms-1 [&_.woocommerce-Price-currencySymbol]:text-xs [&_.woocommerce-Price-currencySymbol]:font-normal"><?= $product->get_price_html() ?></div>
    <button type="button" class="h-12 w-full cursor-pointer rounded-[14px] border-0 bg-accent text-sm font-bold text-white transition-colors hover:bg-accent-dark" data-mini-buy><?= $product->is_type('variable') ? 'انتخاب و افزودن به سبد' : 'افزودن به سبد خرید' ?></button>
    <a href="#main" class="mt-3 block text-center text-xs text-muted hover:text-accent-dark" data-to-top>بازگشت به بالای صفحه</a>
</aside>
<div class="fixed inset-x-0 bottom-[50px] z-[99] flex items-center gap-3 border-t border-border bg-white px-3 py-2 shadow-[0_-4px_14px_rgba(0,0,0,.06)] min-[1100px]:hidden" data-buybar>
    <div class="min-w-0 shrink-0 text-[13px] font-bold leading-tight text-heading [&_del]:block [&_del]:text-[11px] [&_del]:font-normal [&_del]:text-muted [&_ins]:no-underline [&_.woocommerce-Price-currencySymbol]:ms-1 [&_.woocommerce-Price-currencySymbol]:text-[10px] [&_.woocommerce-Price-currencySymbol]:font-normal"><?= $product->get_price_html() ?></div>
    <button type="button" class="h-10 min-w-0 flex-1 cursor-pointer rounded-xl border-0 bg-accent text-[13px] font-bold text-white" data-mini-buy><?= $product->is_type('variable') ? 'انتخاب و افزودن' : 'افزودن به سبد' ?></button>
</div>
