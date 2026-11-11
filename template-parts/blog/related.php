<?php
/** Related posts: same category, excluding current. */
$cat_ids = wp_get_post_categories(get_the_ID());
if (!$cat_ids) return;
$rel = get_posts(['category__in' => $cat_ids, 'post__not_in' => [get_the_ID()], 'numberposts' => 2, 'post_status' => 'publish']);
if (!$rel) return;
?>
<section class="mt-15">
    <h3 class="m-0 mb-7.5 text-center text-[30px] leading-[1.5]">نوشته‌های مرتبط</h3>
    <div class="grid grid-cols-1 gap-7.5 md:grid-cols-2">
        <?php foreach ($rel as $p) : ?>
            <article>
                <a href="<?= esc_url(get_permalink($p)) ?>" class="block overflow-hidden bg-bg-light">
                    <?php if (has_post_thumbnail($p)) echo get_the_post_thumbnail($p, 'large', ['class' => 'block aspect-[1400/900] w-full object-cover', 'loading' => 'lazy']); ?>
                </a>
                <div class="mt-3.5 flex items-center gap-1.5 text-sm text-accent"><?php kidara_icon_e('calendar', 'text-[14px]'); ?><time datetime="<?= esc_attr(get_the_date('c', $p)) ?>"><?= esc_html(kidara_post_date($p)) ?></time></div>
                <h4 class="m-0 mt-1.5 text-[22px] leading-[1.5]"><a class="text-heading hover:text-accent-dark" href="<?= esc_url(get_permalink($p)) ?>"><?= esc_html(get_the_title($p)) ?></a></h4>
            </article>
        <?php endforeach; ?>
    </div>
</section>
