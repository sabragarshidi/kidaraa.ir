<?php
defined('ABSPATH') || exit;

require_once get_template_directory() . '/inc/helpers.php';

add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', ['height' => 60, 'width' => 200, 'flex-width' => true, 'flex-height' => true]);
    add_theme_support('html5', ['search-form', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('woocommerce');
    register_nav_menus(['primary' => 'Primary menu']);
});

add_action('wp_enqueue_scripts', function () {
    $dir = get_template_directory();
    $uri = get_template_directory_uri();
    wp_enqueue_style('swiper', $uri . '/assets/libs/swiper/swiper-bundle.min.css', [], '11');
    $css = $dir . '/assets/css/dist.css';
    wp_enqueue_style('kidara', $uri . '/assets/css/dist.css', ['swiper'], file_exists($css) ? filemtime($css) : null);
    wp_enqueue_script('swiper', $uri . '/assets/libs/swiper/swiper-bundle.min.js', [], '11', true);
    // Every assets/js/*.js file is enqueued (one file per section/component owner).
    foreach (glob($dir . '/assets/js/*.js') as $js) {
        $h = 'kidara-' . basename($js, '.js');
        wp_enqueue_script($h, $uri . '/assets/js/' . basename($js), ['swiper'], filemtime($js), true);
    }
});

// Disable WC block templates + WC default styles (theme restyles everything)
add_filter('wc_is_block_theme', '__return_false');
add_filter('use_block_template_for_woocommerce', '__return_false');
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

// Template routing for WooCommerce + inner pages (templates live in woocommerce/ and template-parts/)
add_filter('template_include', function ($t) {
    if (function_exists('is_woocommerce')) {
        $d = get_template_directory();
        if ((is_shop() || is_product_taxonomy()) && file_exists($d . '/woocommerce/archive-product.php')) return $d . '/woocommerce/archive-product.php';
        if (is_product() && file_exists($d . '/woocommerce/single-product.php')) return $d . '/woocommerce/single-product.php';
    }
    return $t;
}, 999);

// Favicon / app icon from the theme (unless a Site Icon is set in the Customizer)
add_action('wp_head', function () {
    if (has_site_icon()) return;
    $u = get_template_directory_uri() . '/assets/img/icon-512.png';
    echo '<link rel="icon" href="' . esc_url($u) . '" sizes="512x512" type="image/png">' . "\n" . '<link rel="apple-touch-icon" href="' . esc_url($u) . '">' . "\n";
}, 5);
