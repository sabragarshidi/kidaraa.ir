<?php
/**
 * Google Analytics 4 (gtag.js) on every front-end page.
 * Measurement ID can be overridden with the `kidara_ga4_id` option.
 */
if (!defined('ABSPATH')) exit;

add_action('wp_head', function () {
    if (is_admin()) return;
    $id = get_option('kidara_ga4_id', 'G-L420R5GHPS');
    if (!$id) return;
    $id = esc_attr($id);
    echo "<script async src=\"https://www.googletagmanager.com/gtag/js?id={$id}\"></script>\n";
    echo "<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{$id}');</script>\n";
}, 1);
