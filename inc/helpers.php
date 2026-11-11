<?php
defined('ABSPATH') || exit;

/**
 * Inline SVG icon from assets/icons/<name>.svg (Lucide, ISC license).
 * kidara_icon('search', 'text-[22px]') -> <span class="kd-icon text-[22px]"><svg…></span>
 */
function kidara_icon(string $name, string $class = ''): string
{
    static $cache = [];
    if (!isset($cache[$name])) {
        $file = get_template_directory() . '/assets/icons/' . sanitize_file_name($name) . '.svg';
        $svg = file_exists($file) ? file_get_contents($file) : '';
        $svg = preg_replace('/<!--.*?-->/s', '', $svg);
        $svg = preg_replace('/\s(width|height)="[^"]*"/', '', $svg, 2);
        $cache[$name] = trim($svg);
    }
    return '<span class="kd-icon ' . esc_attr($class) . '" aria-hidden="true">' . $cache[$name] . '</span>';
}

function kidara_icon_e(string $name, string $class = ''): void
{
    echo kidara_icon($name, $class); // phpcs:ignore
}

/** Theme asset URL. */
function kidara_asset(string $path): string
{
    return get_template_directory_uri() . '/assets/' . ltrim($path, '/');
}

/**
 * <img> with graceful fallback when the file is missing (user-supplied content slots).
 */
function kidara_img(string $src, string $alt = '', string $class = '', array $attrs = []): string
{
    $extra = '';
    foreach ($attrs as $k => $v) {
        $extra .= ' ' . esc_attr($k) . '="' . esc_attr($v) . '"';
    }
    return '<img src="' . esc_url($src) . '" alt="' . esc_attr($alt) . '" class="' . esc_attr($class) . '" loading="lazy" decoding="async" onerror="this.onerror=null;this.classList.add(\'kd-img-missing\');this.removeAttribute(\'src\')"' . $extra . '>';
}

/** Site logo: custom logo if set, else text fallback in the brand style. */
function kidara_logo(string $class = ''): void
{
    if (has_custom_logo()) {
        the_custom_logo();
        return;
    }
    echo '<a href="' . esc_url(home_url('/')) . '" class="font-display text-[32px] leading-none text-hot ' . esc_attr($class) . '">' . esc_html(get_bloginfo('name')) . '</a>';
}

/** Currency format used by the demo: 100.00$ */
function kidara_money($n): string
{
    return number_format((float) $n, 2, '.', '') . '$';
}

/**
 * WC_Product -> product-card args (the ONLY place that knows both shapes).
 */
function kidara_card_args($product, array $extra = []): array
{
    $product = is_numeric($product) ? wc_get_product($product) : $product;
    if (!$product instanceof WC_Product) {
        return [];
    }
    $terms = get_the_terms($product->get_id(), 'product_cat');
    $term  = $terms && !is_wp_error($terms) ? $terms[0] : null;
    $img   = wp_get_attachment_image_url($product->get_image_id(), 'woocommerce_single') ?: '';
    $gal   = $product->get_gallery_image_ids();
    $hover = $gal ? wp_get_attachment_image_url($gal[0], 'woocommerce_single') : '';

    $badges = [];
    if ($product->is_featured()) $badges['hot'] = true;
    if ($product->is_on_sale()) {
        $reg  = (float) $product->get_regular_price();
        $sale = (float) $product->get_sale_price();
        if ($product->is_type('variable')) { $reg = (float) $product->get_variation_regular_price('max'); $sale = (float) $product->get_variation_sale_price('min'); }
        if ($reg > 0 && $sale > 0) $badges['sale'] = '-' . round((1 - $sale / $reg) * 100) . '%';
    }
    if (!$product->is_in_stock()) $badges['stock'] = 'out of stock';
    elseif (get_post_meta($product->get_id(), '_kd_label', true)) $badges['stock'] = get_post_meta($product->get_id(), '_kd_label', true);

    $swatches = [];
    foreach ((array) get_post_meta($product->get_id(), '_kd_swatches', true) as $s) {
        if (is_array($s) && count($s) >= 2) $swatches[] = ['name' => $s[0], 'color' => $s[1]];
    }

    $price = []; $regular = '';
    if ($product->is_type('variable')) {
        $min = (float) $product->get_variation_price('min'); $max = (float) $product->get_variation_price('max');
        $price = ['price' => kidara_money($min) . ($max > $min ? ' – ' . kidara_money($max) : '')];
    } elseif ($product->is_on_sale()) {
        $price = ['price' => kidara_money($product->get_sale_price()), 'regular_price' => kidara_money($product->get_regular_price())];
    } else {
        $price = ['price' => kidara_money($product->get_price())];
    }

    return array_merge([
        'url' => get_permalink($product->get_id()), 'title' => $product->get_name(),
        'category' => $term ? $term->name : '', 'category_url' => $term ? get_term_link($term) : '#',
        'image' => $img, 'image_hover' => $hover, 'badges' => $badges, 'swatches' => $swatches,
        'product_id' => $product->get_id(),
    ], $price, $extra);
}
