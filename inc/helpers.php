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
