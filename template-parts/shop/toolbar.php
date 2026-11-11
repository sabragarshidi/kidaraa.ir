<?php
/** Top bar: filter toggle (mobile), sorting dropdown (GET orderby), grid switcher. */
$opts = apply_filters('woocommerce_catalog_orderby', [
    'menu_order' => 'پیش‌فرض', 'popularity' => 'محبوب‌ترین', 'rating' => 'بیشترین امتیاز',
    'date' => 'جدیدترین', 'price' => 'ارزان‌ترین', 'price-desc' => 'گران‌ترین',
]);
$cur = isset($_GET['orderby']) ? wc_clean(wp_unslash($_GET['orderby'])) : 'menu_order'; // phpcs:ignore
if (!isset($opts[$cur])) $cur = 'menu_order';
$mk = fn($n) => '<svg viewBox="0 0 20 16" class="h-3.5 w-4" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">' . implode('', array_map(fn($i) => '<path d="M' . (2 + $i * (16 / max(1, $n - 1))) . ' 1v14"/>', range(0, $n - 1))) . '</svg>';
$btn = 'kd-view inline-flex size-10 cursor-pointer items-center justify-center rounded-full border border-border bg-white p-0 text-body transition-colors duration-300 hover:border-accent hover:text-accent';
?>
<div class="mb-12.5 flex items-center justify-between max-[991px]:mb-10">
    <div class="flex items-center gap-3.75">
        <button type="button" class="size-10 cursor-pointer items-center justify-center rounded-full border border-border bg-white p-0 text-heading min-[992px]:hidden inline-flex" data-shop-open aria-label="نمایش فیلترها"><?= kidara_icon('filter', 'text-[18px]') ?></button>
        <div class="relative" data-sort>
            <button type="button" class="inline-flex h-10 cursor-pointer items-center gap-2 rounded-btn border border-border bg-white px-5 text-sm text-heading" data-sort-toggle aria-expanded="false">
                <?= esc_html($opts[$cur]) ?> <?= kidara_icon('chevron-down', 'text-[14px]') ?>
            </button>
            <ul class="invisible absolute start-0 top-full z-20 m-0 mt-2 min-w-55 list-none rounded-[5px] bg-white p-2.5 opacity-0 shadow-[0_0_30px_rgba(0,0,0,0.12)] transition-all duration-200 [.is-open_&]:visible [.is-open_&]:opacity-100">
                <?php foreach ($opts as $k => $label) : ?>
                    <li><a href="<?= esc_url(add_query_arg('orderby', $k, remove_query_arg('paged'))) ?>" class="block px-2.5 py-1.5 text-sm <?= $k === $cur ? 'text-accent' : 'text-heading' ?> hover:text-accent"><?= esc_html($label) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="flex items-center gap-2.5 max-[767px]:hidden" data-views>
        <button type="button" class="<?= $btn ?>" data-view="2" aria-label="دو ستونه"><?= $mk(2) ?></button>
        <button type="button" class="<?= $btn ?> is-active" data-view="3" aria-label="سه ستونه"><?= $mk(3) ?></button>
        <button type="button" class="<?= $btn ?>" data-view="4" aria-label="چهار ستونه"><?= $mk(4) ?></button>
        <button type="button" class="<?= $btn ?>" data-view="list" aria-label="نمایش لیستی"><?= kidara_icon('list', 'text-[18px]') ?></button>
    </div>
</div>
