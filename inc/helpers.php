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

/** Site logo: custom logo if set, else assets/img/logo.png, else text. */
function kidara_logo(string $class = '', int $height = 60): void
{
    if (has_custom_logo()) {
        the_custom_logo();
        return;
    }
    $file = get_template_directory() . '/assets/img/logo.png';
    if (file_exists($file)) {
        [$w, $h] = getimagesize($file);
        $width = (int) round($height * $w / $h);
        echo '<a href="' . esc_url(home_url('/')) . '" class="inline-block ' . esc_attr($class) . '" aria-label="' . esc_attr(get_bloginfo('name')) . '">'
            . '<img src="' . esc_url(kidara_asset('img/logo.png')) . '?v=' . filemtime($file) . '" alt="' . esc_attr(get_bloginfo('name')) . '" width="' . $width . '" height="' . $height . '" class="block w-auto max-w-none" style="height:' . $height . 'px">'
            . '</a>';
        return;
    }
    echo '<a href="' . esc_url(home_url('/')) . '" class="font-display text-[34px] font-black leading-none text-hot ' . esc_attr($class) . '">' . esc_html(get_bloginfo('name')) . '</a>';
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
    if ($product->is_featured()) $badges['hot'] = true; // label text 'ویژه' is rendered by product-card
    if ($product->is_on_sale()) {
        $reg  = (float) $product->get_regular_price();
        $sale = (float) $product->get_sale_price();
        if ($product->is_type('variable')) { $reg = (float) $product->get_variation_regular_price('max'); $sale = (float) $product->get_variation_sale_price('min'); }
        if ($reg > 0 && $sale > 0) $badges['sale'] = kidara_fa((string) round((1 - $sale / $reg) * 100)) . '٪';
    }
    if (!$product->is_in_stock()) $badges['stock'] = 'ناموجود';
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

/* ===== Persian / RTL helpers ===== */

/** Latin digits -> Persian digits (and , -> ٬ thousands separator when $sep). */
function kidara_fa(string $s, bool $sep = false): string
{
    $s = strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']);
    return $sep ? str_replace(',', '٬', $s) : $s;
}

/** Price in toman: 1500000 -> ۱٬۵۰۰٬۰۰۰ تومان */
function kidara_money($n, bool $unit = true): string
{
    return kidara_fa(number_format((float) $n, 0, '.', ','), true) . ($unit ? ' تومان' : '');
}

/** Gregorian -> Jalali (jy, jm, jd). */
function kidara_to_jalali(int $gy, int $gm, int $gd): array
{
    $g_d_m = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
    $gy2 = $gm > 2 ? $gy + 1 : $gy;
    $days = 355666 + (365 * $gy) + intdiv($gy2 + 3, 4) - intdiv($gy2 + 99, 100) + intdiv($gy2 + 399, 400) + $gd + $g_d_m[$gm - 1];
    $jy = -1595 + (33 * intdiv($days, 12053));
    $days %= 12053;
    $jy += 4 * intdiv($days, 1461);
    $days %= 1461;
    if ($days > 365) {
        $jy += intdiv($days - 1, 365);
        $days = ($days - 1) % 365;
    }
    if ($days < 186) { $jm = 1 + intdiv($days, 31); $jd = 1 + ($days % 31); }
    else { $jm = 7 + intdiv($days - 186, 30); $jd = 1 + (($days - 186) % 30); }
    return [$jy, $jm, $jd];
}

/** Jalali date string, e.g. ۲۲ دی ۱۴۰۱. $ts = unix timestamp (default now). */
function kidara_jdate(?int $ts = null, string $format = 'j F Y'): string
{
    $ts = $ts ?? time();
    $months = ['', 'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'];
    $dt = new DateTime('@' . $ts);
    $dt->setTimezone(new DateTimeZone('Asia/Tehran'));
    [$jy, $jm, $jd] = kidara_to_jalali((int) $dt->format('Y'), (int) $dt->format('n'), (int) $dt->format('j'));
    $out = strtr($format, ['j' => (string) $jd, 'F' => $months[$jm], 'Y' => (string) $jy, 'n' => (string) $jm, 'm' => sprintf('%02d', $jm), 'd' => sprintf('%02d', $jd)]);
    return kidara_fa($out);
}

/** Jalali date of a post: kidara_post_date() inside the loop or kidara_post_date($post_id). */
function kidara_post_date($post = null): string
{
    return kidara_jdate((int) get_post_time('U', true, $post));
}

// WooCommerce: toman, no decimals, Persian digits everywhere prices are formatted
add_filter('woocommerce_currency_symbol', fn($s, $c) => in_array($c, ['IRT', 'IRR'], true) ? 'تومان' : $s, 10, 2);
add_filter('formatted_woocommerce_price', fn($f) => kidara_fa($f, true), 10);
add_filter('woocommerce_price_format', fn() => '%2$s&nbsp;%1$s');
add_filter('wc_get_price_thousand_separator', fn() => ',');
// Persian digits in human dates / counts produced by WP core
add_filter('date_i18n', fn($d) => kidara_fa((string) $d), 20);
add_filter('number_format_i18n', fn($n) => kidara_fa((string) $n), 20);


/** Product categories shown in menus/sidebar (no "all", no uncategorized, no toys). */
function kidara_shop_cats(): array
{
    if (!taxonomy_exists('product_cat')) return [];
    $terms = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC']);
    if (is_wp_error($terms)) return [];
    return array_values(array_filter($terms, fn($t) => !in_array($t->slug, ['all', 'uncategorized', 'toys'], true)));
}

/** Cart-count badge (updated live through WooCommerce cart fragments). */
function kidara_cart_badge(string $cls): string {
    $n = function_exists('WC') && WC()->cart ? (int) WC()->cart->get_cart_contents_count() : 0;
    return '<span class="kd-cart-badge ' . esc_attr($cls) . ($n ? '' : ' hidden') . '">' . esc_html(kidara_fa((string) $n)) . '</span>';
}
add_filter('woocommerce_add_to_cart_fragments', function ($f) {
    // Same badge markup as the header; one selector refreshes every instance.
    $f['span.kd-cart-badge'] = kidara_cart_badge('absolute -top-1.25 -end-1.25 w-3.75 h-3.75 rounded-full bg-accent text-white text-[10px] leading-3.75 text-center');
    return $f;
});
// Coupons live on the checkout page only, not in the cart.
add_filter('woocommerce_coupons_enabled', function ($on) {
    return (function_exists('is_cart') && is_cart()) ? false : $on;
});

// "Update cart" sits beside "Proceed to checkout"; it proxies the real (hidden) button.
add_action('woocommerce_proceed_to_checkout', function () {
    echo '<button type="button" class="button kd-update-proxy" data-kd-update-cart>به‌روزرسانی سبد</button>';
}, 5);

/**
 * Live products for homepage sections (newest first, published, in stock-visible).
 * Pass 'featured' => true to prefer featured products; falls back to newest when none.
 */
function kidara_home_products(int $limit, array $args = []): array
{
    if (!function_exists('wc_get_products')) {
        return [];
    }
    $base = ['status' => 'publish', 'visibility' => 'catalog', 'limit' => $limit, 'orderby' => 'date', 'order' => 'DESC'];
    if (!empty($args['featured'])) {
        unset($args['featured']);
        $featured = wc_get_products($args + $base + ['featured' => true]);
        if ($featured) {
            return $featured;
        }
    }
    return wc_get_products($args + $base);
}
