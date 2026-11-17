<?php
/** Round category thumbnails inside the shop banner (real product_cat terms). */
$terms = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => true, 'exclude' => [get_option('default_product_cat')], 'orderby' => 'term_id']);
if (is_wp_error($terms) || !$terms) return;
$current = is_product_category() ? get_queried_object_id() : 0;
$fallback = ['all' => 'category-5', 'baby' => 'category-8', 'boys' => 'category-7', 'girls' => 'category-6'];
?>
<div class="kd-cat-circles mx-auto mt-7.5 max-w-195 overflow-x-auto [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
    <ul class="m-0 flex list-none p-0">
        <?php foreach ($terms as $t) :
            $ids = wc_get_products(['limit' => 1, 'status' => 'publish', 'category' => [$t->slug], 'return' => 'ids', 'orderby' => 'date', 'order' => 'ASC']);
            $img = $ids ? get_the_post_thumbnail_url($ids[0], 'woocommerce_thumbnail') : '';
            if (!$img) $img = '/wp-content/themes/kidara/assets/img/demo/uploads/2023/12/' . ($fallback[$t->slug] ?? 'category-9') . '.jpg';
            $active = $current === $t->term_id;
        ?>
            <li class="w-1/2 shrink-0 px-2.5 text-center min-[768px]:w-1/4">
                <a href="<?= esc_url(get_term_link($t)) ?>" aria-label="<?= esc_attr($t->name) ?>"
                   class="group mx-auto block size-28.75 overflow-hidden rounded-full border-4 transition-colors duration-300 <?= $active ? 'border-accent' : 'border-transparent hover:border-accent' ?> max-[767px]:size-37.5">
                    <?= kidara_img($img, $t->name, 'size-full object-cover') ?>
                </a>
                <h2 class="mt-3 mb-0 font-body text-sm font-normal text-heading"><a href="<?= esc_url(get_term_link($t)) ?>" class="hover:text-accent-dark max-[767px]:text-lg"><?= esc_html($t->name) ?></a></h2>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
