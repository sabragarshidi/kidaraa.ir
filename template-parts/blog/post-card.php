<?php
/** Blog list card (demo: .list-post article). Used by home/archive/search. */
$cats = get_the_category();
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('list-post mb-15 max-[767px]:mb-5 max-[767px]:pb-5'); ?>>
    <div class="relative">
        <a class="block" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
            <?php if (has_post_thumbnail()) {
                the_post_thumbnail('full', ['class' => 'block aspect-[1400/900] w-full object-cover', 'loading' => 'lazy']);
            } else { ?>
                <span class="block aspect-[1400/900] w-full bg-bg-light"></span>
            <?php } ?>
        </a>
        <?php if ($cats) : ?>
            <ul class="absolute bottom-5 left-5 m-0 flex list-none flex-wrap gap-2.5 p-0">
                <?php foreach ($cats as $c) : ?>
                    <li><a class="inline-block rounded-btn bg-white px-3.75 py-[5px] text-sm leading-[27px] text-heading transition-colors hover:text-accent-dark min-[768px]:text-base" href="<?= esc_url(get_category_link($c)) ?>"><?= esc_html($c->name) ?></a></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
    <div class="pt-3.75">
        <div class="mb-2.5 flex items-center gap-1.5 text-sm text-accent">
            <?php kidara_icon_e('calendar', 'text-[14px]'); ?>
            <a href="<?php the_permalink(); ?>" rel="bookmark" class="text-accent"><time datetime="<?= esc_attr(get_the_date('c')) ?>"><?= esc_html(get_the_date()) ?></time></a>
        </div>
        <h3 class="m-0 mb-2.5 text-[30px] leading-[1.15] max-[767px]:text-2xl"><a class="text-heading hover:text-accent-dark" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <p class="mb-5 text-sm"><?= esc_html(wp_trim_words((strlen(trim(get_the_excerpt(), " .…")) > 3) ? get_the_excerpt() : wp_strip_all_tags(strip_shortcodes(get_the_content())), 22, '...')) ?></p>
        <a class="inline-block rounded-btn bg-[#d33] px-10 py-2.5 text-sm font-bold text-white transition-all duration-300 hover:bg-accent-dark" href="<?php the_permalink(); ?>">Read More</a>
    </div>
</article>
