<?php get_header(); ?>
<main class="container py-17.5 <?php echo (function_exists('is_cart') && (is_cart() || is_checkout() || is_account_page())) ? 'woocommerce' : ''; ?>">
    <?php while (have_posts()) : the_post(); ?>
        <h1 class="text-[40px] leading-[1.2] text-center mb-12.5"><?php the_title(); ?></h1>
        <div class="entry-content"><?php the_content(); ?></div>
    <?php endwhile; ?>
</main>
<?php get_footer();
