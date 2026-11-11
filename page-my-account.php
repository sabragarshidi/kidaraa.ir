<?php
/** WooCommerce My Account page: demo-style banner + classic shortcode (styled by assets/css/wc.css). */
get_header();
while (have_posts()) : the_post();
    get_template_part('template-parts/page-title', null, ['title' => get_the_title()]);
    ?>
    <main class="container pb-17.5">
        <div class="woocommerce kd-wc kd-wc-account">
            <?php echo do_shortcode('[woocommerce_my_account]'); ?>
        </div>
    </main>
    <?php
endwhile;
get_footer();
