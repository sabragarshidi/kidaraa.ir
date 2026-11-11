<?php
get_header();
while (have_posts()) : the_post();
    $cats = get_the_category();
    $tags = get_the_tags();
    $first = $cats ? $cats[0] : null;
    $url = urlencode(get_permalink());
    $img = urlencode((string) get_the_post_thumbnail_url(null, 'full'));
    $share = [
        ['facebook', 'Facebook', 'https://www.facebook.com/sharer.php?u=' . $url],
        ['x-twitter', 'X', 'https://twitter.com/intent/tweet?url=' . $url],
        ['linkedin', 'LinkedIn', 'https://www.linkedin.com/shareArticle?mini=true&url=' . $url],
        ['pinterest', 'Pinterest', 'https://pinterest.com/pin/create/button/?url=' . $url . '&media=' . $img],
    ];
    $prev = get_previous_post();
    $next = get_next_post();
    $chip = 'rounded-btn border border-border px-3.75 text-[15px] leading-[30px] text-body transition-colors hover:border-accent hover:bg-accent hover:text-white';
    ?>
    <div class="mt-18 min-[1200px]:mt-29.5 py-5 text-base">
        <div class="container">
            <nav class="flex flex-wrap items-center text-body" aria-label="Breadcrumb">
                <a href="<?= esc_url(home_url('/')) ?>" class="text-body hover:text-accent-dark">Home</a>
                <span class="mx-2"><?php kidara_icon_e('chevron-right', 'text-[14px]'); ?></span>
                <?php if ($first) : ?>
                    <a href="<?= esc_url(get_category_link($first)) ?>" class="text-body hover:text-accent-dark"><?= esc_html($first->name) ?></a>
                    <span class="mx-2"><?php kidara_icon_e('chevron-right', 'text-[14px]'); ?></span>
                <?php endif; ?>
                <span class="text-heading"><?php the_title(); ?></span>
            </nav>
        </div>
    </div>

    <div class="container pb-22.5">
        <?php if (has_post_thumbnail()) : ?>
            <div class="mb-0"><?php the_post_thumbnail('full', ['class' => 'block aspect-[1400/900] w-full object-cover']); ?></div>
        <?php endif; ?>
        <div class="mx-auto flex flex-col items-center pt-13 text-center">
            <?php if ($cats) : ?>
                <ul class="m-0 mb-3.75 flex list-none flex-wrap justify-center gap-2 p-0">
                    <?php foreach ($cats as $c) : ?>
                        <li><a class="inline-block <?= $chip ?>" href="<?= esc_url(get_category_link($c)) ?>"><?= esc_html($c->name) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <h1 class="m-0 max-w-[900px] text-3xl leading-[1.2] md:text-[40px]"><?php the_title(); ?></h1>
            <div class="mt-3.75 flex items-center gap-1.5 text-sm text-accent">
                <?php kidara_icon_e('calendar', 'text-[14px]'); ?>
                <time datetime="<?= esc_attr(get_the_date('c')) ?>"><?= esc_html(get_the_date()) ?></time>
                
            </div>
            <span class="mt-15 mb-12.5 block h-px w-52.5 bg-border"></span>
        </div>

        <div class="-mx-3.75 flex flex-wrap">
            <div class="order-2 w-full px-3.75 max-[991px]:mt-10 min-[992px]:order-1 min-[992px]:w-1/4 min-[992px]:pr-0">
                <?php get_template_part('template-parts/blog/sidebar'); ?>
            </div>
            <article id="post-<?php the_ID(); ?>" <?php post_class('order-1 w-full min-w-0 px-3.75 min-[992px]:order-2 min-[992px]:w-3/4 min-[992px]:pl-12.5'); ?>>
                <div class="entry-content kd-entry"><?php the_content(); ?></div>
                <?php wp_link_pages(); ?>

                <div class="mt-12.5 text-center">
                    <?php if ($tags) : ?>
                        <div class="mb-5 flex flex-wrap items-center justify-center gap-2.5">
                            <label class="mr-2 text-sm font-medium text-heading">Tags :</label>
                            <?php foreach ($tags as $t) : ?>
                                <a class="<?= $chip ?> px-6.25" href="<?= esc_url(get_tag_link($t)) ?>" rel="tag"><?= esc_html($t->name) ?></a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <div class="flex items-center justify-center gap-4 text-sm">
                        <label class="font-medium text-heading">Share :</label>
                        <?php foreach ($share as [$ic, $label, $href]) : ?>
                            <a href="<?= esc_url($href) ?>" title="<?= esc_attr($label) ?>" aria-label="<?= esc_attr($label) ?>" target="_blank" rel="noopener" class="flex text-accent transition-colors hover:text-heading"><?php kidara_icon_e($ic, 'text-[14px]'); ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php if ($prev || $next) : ?>
                    <nav class="mt-15 grid grid-cols-1 border-y border-border py-7.5 md:grid-cols-2" aria-label="Posts">
                        <div class="md:border-r md:border-border md:pr-7.5">
                            <?php if ($prev) : ?><a href="<?= esc_url(get_permalink($prev)) ?>" rel="prev" class="block">
                                <span class="block text-xs tracking-wide text-muted uppercase">Previous</span>
                                <span class="mt-1 block font-display text-[22px] leading-[1.2] text-heading"><?= esc_html(get_the_title($prev)) ?></span></a><?php endif; ?>
                        </div>
                        <div class="max-md:mt-5 md:pl-7.5 md:text-right">
                            <?php if ($next) : ?><a href="<?= esc_url(get_permalink($next)) ?>" rel="next" class="block">
                                <span class="block text-xs tracking-wide text-muted uppercase">Next</span>
                                <span class="mt-1 block font-display text-[22px] leading-[1.2] text-heading"><?= esc_html(get_the_title($next)) ?></span></a><?php endif; ?>
                        </div>
                    </nav>
                <?php endif; ?>

                <?php get_template_part('template-parts/blog/related'); ?>

                <?php if (comments_open() || get_comments_number()) comments_template(); ?>
            </article>
        </div>
    </div>
<?php endwhile;
get_footer();
