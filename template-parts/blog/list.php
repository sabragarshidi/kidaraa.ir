<?php
/** Shared index layout for home/archive/search. Args: title, crumbs, heading (optional H2 shown above list). */
get_header();
get_template_part('template-parts/page-title', null, ['title' => $args['title'], 'crumbs' => $args['crumbs']]);
?>
<div class="container">
    <div class="-mx-3.75 flex flex-wrap">
        <section class="w-full px-3.75 min-[992px]:w-3/4">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); get_template_part('template-parts/blog/post-card'); endwhile; ?>
                <?php
                $links = paginate_links(['type' => 'array', 'prev_text' => kidara_icon('chevron-left', 'text-[14px]'), 'next_text' => kidara_icon('chevron-right', 'text-[14px]'), 'mid_size' => 1]);
                if ($links) : ?>
                    <nav class="mb-22.5 pt-0" aria-label="Pagination">
                        <ul class="m-0 flex list-none flex-wrap items-center gap-2.5 p-0">
                            <?php foreach ($links as $l) :
                                $cur = str_contains($l, 'current');
                                $l = preg_replace('/class="(page-numbers)( current)?"/', 'class="flex size-10 items-center justify-center rounded-full text-base font-medium leading-10 ' . ($cur ? 'bg-accent text-white' : 'text-body hover:bg-accent hover:text-white') . '"', $l);
                                ?>
                                <li><?= $l // phpcs:ignore ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php else : ?>
                <div class="mb-22.5 text-center">
                    <p class="mb-5 text-base">Nothing found. Try another search.</p>
                </div>
            <?php endif; ?>
        </section>
        <div class="w-full px-3.75 max-[991px]:mt-5 min-[992px]:w-1/4">
            <?php get_template_part('template-parts/blog/sidebar'); ?>
        </div>
    </div>
</div>
<?php get_footer();
