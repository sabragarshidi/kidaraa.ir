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
    ], kidara_card_options($product), $price, $extra);
}

/** Sizes and colors for product cards, from product attributes («گزینه ها»/سایز, Color/رنگ). */
function kidara_card_options(WC_Product $product): array
{
    $out = ['sizes' => [], 'colors' => []];
    foreach ($product->get_attributes() as $attr) {
        if (!$attr instanceof WC_Product_Attribute || !$attr->get_visible() && !$attr->get_variation()) continue;
        $label = kidara_attr_label_fa(wc_attribute_label($attr->get_name(), $product), $product);
        $key = $label === 'سایز' ? 'sizes' : ($label === 'رنگ' ? 'colors' : null);
        if (!$key) continue;
        $vals = $attr->is_taxonomy() ? wc_get_product_terms($product->get_id(), $attr->get_name(), ['fields' => 'names']) : $attr->get_options();
        foreach ($vals as $v) { $v = trim((string) $v); if ($v !== '') $out[$key][] = $v; }
    }
    // sizes in ascending order when they start with a number (۶-۹ ماه, ۹ـ۱۲ ماه, ...)
    $num = function ($v) {
        $v = strtr($v, ['۰'=>'0','۱'=>'1','۲'=>'2','۳'=>'3','۴'=>'4','۵'=>'5','۶'=>'6','۷'=>'7','۸'=>'8','۹'=>'9']);
        $words = ['صفر' => 0, 'یک' => 1, 'دو' => 2, 'سه' => 3, 'چهار' => 4, 'پنج' => 5, 'شش' => 6];
        if (isset($words[$v])) return $words[$v];
        return preg_match('/\d+/', $v, $m) ? (int) $m[0] : null;
    };
    $nums = array_map($num, $out['sizes']);
    if ($out['sizes'] && !in_array(null, $nums, true)) array_multisort($nums, SORT_NUMERIC, $out['sizes']);
    $out['sizes'] = array_values(array_unique(array_map(fn($v) => kidara_fa($v), $out['sizes'])));
    $out['colors'] = array_values(array_unique($out['colors']));
    return $out;
}

/** Persian/English color name → hex for card swatches (null = show as text). */
function kidara_color_hex(string $name): ?string
{
    if (preg_match('/^#[0-9a-f]{3,6}$/i', trim($name))) return trim($name);
    $map = ['سفید' => '#ffffff', 'مشکی' => '#222222', 'سیاه' => '#222222', 'طوسی' => '#9a9a9a', 'خاکستری' => '#9a9a9a', 'ذغالی' => '#4a4a4a',
        'قرمز' => '#d64545', 'زرشکی' => '#8b1e3f', 'صورتی' => '#f4a7b9', 'گلبهی' => '#f7b7a3', 'نارنجی' => '#f0883e', 'زرد' => '#f5d04c', 'لیمویی' => '#f3eb7a',
        'سبز' => '#5aa469', 'یشمی' => '#3e8e7e', 'زیتونی' => '#808a3c', 'آبی' => '#4a90d9', 'آبی آسمانی' => '#8ecae6', 'سرمه‌ای' => '#1f2f5c', 'سرمه ای' => '#1f2f5c',
        'فیروزه‌ای' => '#3cc3c3', 'فیروزه ای' => '#3cc3c3', 'بنفش' => '#8e6cc2', 'یاسی' => '#c8a2d8', 'قهوه‌ای' => '#8b5a3c', 'قهوه ای' => '#8b5a3c',
        'کرم' => '#efe3c8', 'بژ' => '#e4d3b5', 'شیری' => '#f8f3e6', 'نباتی' => '#f6ecd0', 'طلایی' => '#d4af37', 'نقره‌ای' => '#c0c0c0',
        'white' => '#ffffff', 'black' => '#222222', 'red' => '#d64545', 'pink' => '#f4a7b9', 'blue' => '#4a90d9', 'green' => '#5aa469', 'yellow' => '#f5d04c', 'gray' => '#9a9a9a', 'grey' => '#9a9a9a'];
    return $map[mb_strtolower(trim($name))] ?? null;
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


/**
 * Product categories shown in menus/sidebar (no "all", no uncategorized, no toys).
 * Ordered as a tree: each parent (baby, boys, girls first) followed by its children.
 * Each term gets ->kd_depth (0 = top level); counts include child products.
 */
function kidara_shop_cats(bool $top_only = false): array
{
    if (!taxonomy_exists('product_cat')) return [];
    $terms = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => true, 'pad_counts' => true, 'orderby' => 'count', 'order' => 'DESC']);
    if (is_wp_error($terms)) return [];
    $terms = array_filter($terms, fn($t) => !in_array($t->slug, ['all', 'uncategorized', 'toys'], true));
    $kids = [];
    foreach ($terms as $t) $kids[$t->parent][] = $t;
    $rank = array_flip(['baby', 'boys', 'girls']);
    $top = $kids[0] ?? [];
    usort($top, fn($a, $b) => ($rank[$a->slug] ?? 9) <=> ($rank[$b->slug] ?? 9) ?: $b->count <=> $a->count);
    $out = [];
    foreach ($top as $t) {
        $t->kd_depth = 0;
        $out[] = $t;
        if ($top_only) continue;
        foreach ($kids[$t->term_id] ?? [] as $c) {
            $c->kd_depth = 1;
            $out[] = $c;
        }
    }
    return $out;
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
 * Live products for homepage sections: a mix across categories (round-robin over each
 * product's own category, newest first inside each), so one big category can't fill a section.
 * Pass 'featured' => true to prefer featured products; falls back to all when none.
 * $skip drops the first N of the mixed list (lets sections show different products).
 */
function kidara_home_products(int $limit, array $args = [], int $skip = 0): array
{
    if (!function_exists('wc_get_products')) {
        return [];
    }
    $want = $limit + $skip;
    $base = ['status' => 'publish', 'visibility' => 'catalog', 'limit' => max(60, $want * 4), 'orderby' => 'date', 'order' => 'DESC'];
    $pool = [];
    if (!empty($args['featured'])) {
        unset($args['featured']);
        $pool = wc_get_products($args + $base + ['featured' => true]);
        if (count($pool) < $want) $pool = []; // too few featured to fill the section → use everything
    }
    if (!$pool) {
        $pool = wc_get_products($args + $base);
    }
    $groups = [];
    foreach ($pool as $p) {
        $ids = array_values(array_diff($p->get_category_ids(), [(int) get_option('default_product_cat')]));
        $key = $ids[0] ?? 0; // prefer a sub-category (e.g. «ست نوزادی» over «نوزاد») as the group
        foreach ($ids as $id) {
            $t = get_term($id, 'product_cat');
            if ($t && !is_wp_error($t) && $t->parent) { $key = $id; break; }
        }
        $groups[$key][] = $p; // each group keeps newest-first order
    }
    $mixed = [];
    while ($groups && count($mixed) < $want) {
        foreach ($groups as $k => &$g) {
            $mixed[] = array_shift($g);
            if (!$g) unset($groups[$k]);
        }
        unset($g);
    }
    $out = array_slice($mixed, $skip, $limit);
    // Not enough products left after skipping → wrap around instead of an empty section.
    return count($out) < $limit && $skip ? array_slice($mixed, 0, $limit) : $out;
}

// Shop/category archives: 20 products per page.
add_filter('loop_shop_per_page', fn() => 20, 20);

// Local attribute labels shown to customers (product page, cart, checkout, orders):
// 'Color' → رنگ; Tamineto imports sizes as «گزینه ها» → سایز.
function kidara_attr_label_fa(string $label, $product = null): string
{
    $k = strtolower(str_replace(["\u{200C}", ' ', '_', '-'], '', trim($label)));
    $map = ['color' => 'رنگ', 'گزینهها' => 'سایز', 'گزینه' => 'سایز'];
    if (!isset($map[$k])) return $label;
    if ($map[$k] === 'سایز' && $product && kidara_options_have_design($product, $label)) return 'طرح و سایز';
    return $map[$k];
}

/** True when the Tamineto «گزینه ها» values mix a design/colour with the size (e.g. «آبی6», «طرح یک سایز2», «قلبی - 1»). */
function kidara_options_have_design($product, string $label): bool
{
    if (!$product instanceof WC_Product) $product = wc_get_product($product);
    if (!$product) return false;
    $id = $product->is_type('variation') ? $product->get_parent_id() : $product->get_id();
    static $cache = [];
    $ck = $id . '|' . $label;
    if (isset($cache[$ck])) return $cache[$ck];
    $parent = $product->is_type('variation') ? wc_get_product($id) : $product;
    $vals = [];
    foreach ($parent ? $parent->get_attributes() : [] as $a) {
        if ($a instanceof WC_Product_Attribute && !$a->is_taxonomy() && in_array($label, [$a->get_name(), sanitize_title($a->get_name()), 'attribute_' . sanitize_title($a->get_name())], true)) $vals = $a->get_options();
    }
    $size_words = '/(سایز|ماه|سال|تا|صفر|یک|دو|سه|چهار|پنج|شش|بدو|تولد|و|size)/u';
    foreach ($vals as $v) {
        $rest = preg_replace($size_words, '', (string) $v);
        $rest = preg_replace('/[\d۰-۹\s\-–ـ()\/،,.+\x{200C}]+/u', '', $rest);
        if ($rest !== '') return $cache[$ck] = true;
    }
    return $cache[$ck] = false;
}
add_filter('woocommerce_attribute_label', fn($label, $name = '', $product = null) => kidara_attr_label_fa((string) $label, $product), 20, 3);
// Variation dropdown placeholder: «انتخاب سایز» instead of «یک گزینه را انتخاب کنید».
add_filter('woocommerce_dropdown_variation_attribute_options_args', function ($args) {
    $args['show_option_none'] = 'انتخاب ' . kidara_attr_label_fa(wc_attribute_label($args['attribute'], $args['product'] ?? ''), $args['product'] ?? null);
    return $args;
});

// Store contact phone (shown in footer, contact page, product page; required by Torob).
const KIDARA_PHONE = '09337456906';
function kidara_phone_link(string $class = ''): string
{
    return '<a href="tel:+98' . substr(KIDARA_PHONE, 1) . '" dir="ltr" class="' . esc_attr($class) . '">' . esc_html(kidara_fa(substr(KIDARA_PHONE, 0, 4) . ' ' . substr(KIDARA_PHONE, 4, 3) . ' ' . substr(KIDARA_PHONE, 7))) . '</a>';
}

/**
 * Tamineto sends design + size fused in one option («آبی6», «طرح یک سایز2», «3 - پاپیون», «نباتی، سایز 2»).
 * Returns [design, size] or null when the value doesn't follow one of those shapes.
 */
function kidara_split_option(string $v): ?array
{
    $s = trim(strtr($v, ['۰'=>'0','۱'=>'1','۲'=>'2','۳'=>'3','۴'=>'4','۵'=>'5','۶'=>'6','۷'=>'7','۸'=>'8','۹'=>'9', "\u{200C}" => ' ']));
    $clean = fn($d) => trim(preg_replace('/(\s*[،,\-–:]\s*|\s+)?(مدل|طرح)?\s*$/u', '', trim(preg_replace('/[\s،,\-–:]+$/u', '', $d)))) ?: trim($d);
    if (preg_match('/^(.*?\D)\s*(?:سایز|size)?\s*(\d{1,2})\s*$/u', $s, $m)) {
        $d = trim(preg_replace('/(?:سایز|size)\s*$/u', '', trim($m[1])));
        $d = $clean($d);
        if ($d !== '' && preg_match('/\p{L}/u', $d)) return [$d, $m[2]];
    }
    if (preg_match('/^(\d{1,2})\s*[-–]\s*(\D.*)$/u', $s, $m)) return [trim($m[2]), $m[1]];
    // size first: «سایز45 سرمه ای», «سایز1سبز»
    if (preg_match('/^(?:سایز|size)\s*(\d{1,2})\s*[،,\-–:]?\s*(\D.*)$/u', $s, $m) && preg_match('/\p{L}/u', $m[2]) && !preg_match('/\d|ماه|سال|تا/u', $m[2])) return [$clean(trim($m[2])), $m[1]];
    return null;
}

/** For the product page: {"attribute_<key>": {"<value>": ["design","size"], ...}} for every fused design+size attribute. */
function kidara_split_map(WC_Product $product): array
{
    if (!$product->is_type('variable')) return [];
    $out = [];
    foreach ($product->get_attributes() as $key => $a) {
        if (!$a instanceof WC_Product_Attribute || !$a->get_variation() || $a->is_taxonomy()) continue;
        if (kidara_attr_label_fa(wc_attribute_label($a->get_name(), $product), $product) !== 'طرح و سایز') continue;
        $map = [];
        foreach ($a->get_options() as $v) {
            $p = kidara_split_option((string) $v);
            if (!$p) { $map = []; break; }               // one odd value → keep the single combined list
            $map[$v] = $p;
        }
        if ($map) $out['attribute_' . sanitize_title($a->get_name())] = $map;
    }
    return $out;
}
