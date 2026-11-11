<?php get_header(); ?>
<main class="container py-17.5">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article <?php post_class('mb-10'); ?>>
            <h2 class="text-2xl mb-2.5"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <?php the_excerpt(); ?>
        </article>
    <?php endwhile; the_posts_pagination(); endif; ?>
</main>
<?php get_footer();
