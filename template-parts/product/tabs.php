<?php
/**
 * Description / Additional information / Reviews. Pills on desktop, accordion < 992px.
 * (own classes: WC's wc-single-product script would hijack .wc-tabs / .woocommerce-tabs)
 * Args: product
 */
$product = $args['product'];
$post    = get_post($product->get_id());
$tabs    = [];

$desc = trim((string) $post->post_content);
if ($desc !== '') {
    $tabs['description'] = ['title' => 'توضیحات', 'cb' => function () use ($desc) {
        echo '<div class="kd-prose">' . apply_filters('the_content', $desc) . '</div>'; // phpcs:ignore
    }];
}
if ($product->has_attributes() || apply_filters('wc_product_enable_dimensions_display', $product->has_weight() || $product->has_dimensions())) {
    $tabs['additional_information'] = ['title' => 'اطلاعات تکمیلی', 'cb' => function () use ($product) {
        wc_display_product_attributes($product);
    }];
}
if (comments_open($product->get_id())) {
    $tabs['reviews'] = ['title' => 'نظرات (' . kidara_fa((string) $product->get_review_count()) . ')', 'cb' => function () {
        comments_template();
    }];
}
if (!$tabs) return;
$first = array_key_first($tabs);
?>
<section class="kd-tabs mb-2.5 border-y border-border py-15 max-[991px]:py-7.5" data-tabs>
    <div class="mx-auto max-w-360 px-3.75 md:px-8.25">
        <ul class="m-0 mb-7.5 flex list-none justify-center overflow-x-auto p-0 whitespace-nowrap max-[991px]:hidden" role="tablist">
            <?php foreach ($tabs as $key => $t) : ?>
                <li class="mx-2.5 p-0" role="presentation">
                    <a href="#tab-<?= esc_attr($key) ?>" role="tab" data-tab-link="<?= esc_attr($key) ?>"
                       class="inline-block rounded-btn px-5 py-1.25 font-display text-xl leading-[34px] text-body transition-colors duration-300 hover:bg-accent hover:text-white [.is-active>&]:bg-accent [.is-active>&]:text-white"><?= esc_html($t['title']) ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php foreach ($tabs as $key => $t) : ?>
            <div class="kd-tab <?= $key === $first ? 'is-active' : '' ?>" data-tab="<?= esc_attr($key) ?>">
                <button type="button" class="kd-tab-head hidden w-full cursor-pointer items-center justify-between border-0 border-b border-border bg-transparent px-0 py-3.75 text-start font-display text-[22px] text-body max-[991px]:flex" aria-expanded="<?= $key === $first ? 'true' : 'false' ?>">
                    <?= esc_html($t['title']) ?><?= kidara_icon('chevron-down', 'kd-chev text-[20px] transition-transform duration-300') ?>
                </button>
                <div id="tab-<?= esc_attr($key) ?>" class="kd-panel text-start leading-[2em] max-[991px]:pt-3.75" role="tabpanel">
                    <?php ($t['cb'])(); ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
