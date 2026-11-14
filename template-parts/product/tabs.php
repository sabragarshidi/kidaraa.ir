<?php
/**
 * Below-the-fold cards: specifications, description, reviews, shipping. Sticky anchor bar on top.
 * Args: product
 */
$product = $args['product'];
$pid     = $product->get_id();
$desc    = trim((string) get_post($pid)->post_content);

// Specifications: SKU + visible attributes + weight/dimensions.
$specs = [];
if ($product->get_sku()) $specs['کد محصول'] = '<bdi dir="ltr">' . esc_html($product->get_sku()) . '</bdi>';
foreach ($product->get_attributes() as $attr) {
    if (!is_a($attr, 'WC_Product_Attribute') || !$attr->get_visible()) continue;
    $vals = $attr->is_taxonomy() ? wc_get_product_terms($pid, $attr->get_name(), ['fields' => 'names']) : $attr->get_options();
    $vals = array_filter(array_map('strval', (array) $vals));
    if ($vals) $specs[wc_attribute_label($attr->get_name(), $product)] = esc_html(implode('، ', $vals));
}
if ($product->has_weight()) $specs['وزن'] = esc_html(kidara_fa(wc_format_weight($product->get_weight())));
if ($product->has_dimensions()) $specs['ابعاد'] = esc_html(kidara_fa(wc_format_dimensions($product->get_dimensions(false))));

$nav = [];
if ($specs) $nav['specs'] = 'مشخصات';
if ($desc !== '') $nav['desc'] = 'توضیحات';
if (comments_open($pid)) $nav['reviews'] = 'دیدگاه‌ها';
$nav['shipping'] = 'ارسال';

$card = 'scroll-mt-32 rounded-2xl border border-border bg-white p-5 min-[768px]:p-7';
$h2   = 'mb-5 mt-0 flex items-center gap-2.5 text-xl leading-normal text-heading after:h-5 after:w-1 after:rounded-full after:bg-accent after:content-[\'\']';
?>
<div class="kd-tabs min-w-0" data-tabs>
    <nav class="kd-tabbar sticky top-[64px] z-[20] mb-4 flex gap-1 overflow-x-auto rounded-2xl border border-border bg-white p-1.5 [scrollbar-width:none] min-[1200px]:top-[84px] [&::-webkit-scrollbar]:hidden" aria-label="بخش‌های محصول">
        <?php foreach ($nav as $k => $label) : ?>
            <a href="#tab-<?= esc_attr($k) ?>" data-tab-link="<?= esc_attr($k) ?>" class="shrink-0 rounded-xl px-5 py-2 text-base text-body transition-colors hover:text-accent-dark [&.is-active]:bg-[#e4ede6] [&.is-active]:text-accent-dark"><?= esc_html($label) ?></a>
        <?php endforeach; ?>
    </nav>

    <div class="grid gap-4">
        <?php if ($specs) : ?>
        <section id="tab-specs" class="<?= $card ?>" data-tab="specs">
            <h2 class="<?= $h2 ?>">مشخصات</h2>
            <dl class="m-0 grid gap-x-10 min-[768px]:grid-cols-2">
                <?php foreach ($specs as $k => $v) : ?>
                    <div class="flex items-start justify-between gap-4 border-b border-border py-3 text-sm">
                        <dt class="text-muted"><?= esc_html($k) ?></dt>
                        <dd class="m-0 text-end text-heading"><?= $v // phpcs:ignore ?></dd>
                    </div>
                <?php endforeach; ?>
            </dl>
        </section>
        <?php endif; ?>

        <?php if ($desc !== '') : ?>
        <section id="tab-desc" class="<?= $card ?>" data-tab="desc">
            <h2 class="<?= $h2 ?>">توضیحات</h2>
            <div class="kd-desc relative max-h-[260px] overflow-hidden transition-[max-height] duration-300" data-desc>
                <div class="kd-prose text-body leading-[2]"><?= apply_filters('the_content', $desc) // phpcs:ignore ?></div>
                <div class="kd-desc-fade pointer-events-none absolute inset-x-0 bottom-0 hidden h-16 bg-gradient-to-t from-white to-transparent"></div>
            </div>
            <button type="button" class="kd-desc-toggle mt-3 hidden cursor-pointer border-0 bg-transparent p-0 text-sm font-bold text-accent-dark" data-desc-toggle>نمایش کامل توضیحات</button>
        </section>
        <?php endif; ?>

        <?php if (comments_open($pid)) : ?>
        <section id="tab-reviews" class="<?= $card ?>" data-tab="reviews">
            <h2 class="<?= $h2 ?>">دیدگاه‌ها <span class="text-sm font-normal text-muted">(<?= esc_html(kidara_fa((string) $product->get_review_count())) ?>)</span></h2>
            <?php comments_template(); ?>
        </section>
        <?php endif; ?>

        <section id="tab-shipping" class="<?= $card ?>" data-tab="shipping">
            <h2 class="<?= $h2 ?>">ارسال</h2>
            <p class="m-0 text-sm leading-8 text-body">سفارش شما پس از تأیید، در سریع‌ترین زمان ممکن ارسال می‌شود و تحویل آن ۳ تا ۷ روز کاری طول می‌کشد. ارسال با پست پیشتاز انجام می‌شود و هزینهٔ آن در مرحلهٔ تکمیل سفارش مشخص می‌شود.</p>
        </section>
    </div>
</div>
