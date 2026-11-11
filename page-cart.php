<?php
/** WooCommerce Cart page: demo-style banner + classic shortcode (styled by assets/css/wc.css). */
get_header();
while (have_posts()) : the_post();
    get_template_part('template-parts/page-title', null, ['title' => get_the_title()]);
    ?>
    <main class="container pb-17.5">
        <div class="woocommerce kd-wc kd-wc-cart">
            <?php echo do_shortcode('[woocommerce_cart]'); ?>
        </div>
    </main>
    <?php
endwhile;
get_footer();
