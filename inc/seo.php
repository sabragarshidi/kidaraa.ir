<?php
/**
 * Lightweight SEO layer (no plugin): meta description, Open Graph/Twitter,
 * JSON-LD (Organization, WebSite, Product, BreadcrumbList), robots + sitemap hygiene.
 */
if (!defined('ABSPATH')) exit;

const KIDARA_SEO_DEFAULT_DESC = 'کیدارا، فروشگاه آنلاین لباس نوزاد و کودک؛ ست نوزادی، سرهمی، لباس دخترانه و پسرانه با پارچهٔ نرم و ضدحساسیت، پرداخت امن و ارسال سریع به سراسر ایران.';

function kidara_seo_clip(string $s, int $len = 158): string
{
    $s = trim(preg_replace('/\s+/u', ' ', wp_strip_all_tags(strip_shortcodes($s))));
    if (mb_strlen($s) <= $len) return $s;
    $cut = mb_substr($s, 0, $len);
    $sp  = mb_strrpos($cut, ' ');
    return rtrim($sp ? mb_substr($cut, 0, $sp) : $cut, ' ،,.؛:') . '…';
}

/** Pages that must never be indexed (WooCommerce flow + wishlist). */
function kidara_seo_private_page_ids(): array
{
    $ids = [];
    foreach (['cart', 'checkout', 'myaccount'] as $p) {
        $id = function_exists('wc_get_page_id') ? (int) wc_get_page_id($p) : 0;
        if ($id > 0) $ids[] = $id;
    }
    foreach (['wishlist'] as $slug) {
        $pg = get_page_by_path($slug);
        if ($pg) $ids[] = (int) $pg->ID;
    }
    if (!kidara_seo_has_posts() && ($blog = (int) get_option('page_for_posts'))) $ids[] = $blog;
    return array_values(array_unique($ids));
}

/** product_cat ids with no products anywhere in their sub-tree. */
function kidara_seo_empty_cats(): array
{
    static $ids = null;
    if ($ids !== null) return $ids;
    $ids = [];
    $all = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => false]);
    if (!is_array($all)) return $ids;
    $total = [];
    foreach ($all as $t) $total[$t->term_id] = (int) $t->count;
    foreach ($all as $t) {           // roll each term's own count up its ancestor chain
        if ((int) $t->count === 0) continue;
        foreach (get_ancestors($t->term_id, 'product_cat') as $a) $total[$a] = ($total[$a] ?? 0) + (int) $t->count;
    }
    foreach ($total as $id => $n) if ($n === 0) $ids[] = (int) $id;
    return $ids;
}

function kidara_seo_has_posts(): bool
{
    static $has = null;
    if ($has === null) $has = (int) wp_count_posts('post')->publish > 0;
    return $has;
}

/** Context for the current request: description, image, type. */
function kidara_seo_context(): array
{
    $desc = KIDARA_SEO_DEFAULT_DESC;
    $img  = get_theme_file_uri('assets/img/og-default.jpg');
    $type = 'website';

    if (is_front_page()) {
        // default
    } elseif (function_exists('is_product') && is_product()) {
        $p = wc_get_product(get_queried_object_id());
        if ($p) {
            $src  = $p->get_short_description() ?: $p->get_description();
            $desc = $src ? kidara_seo_clip($src) : kidara_seo_clip($p->get_name() . ' در فروشگاه کیدارا؛ خرید آنلاین با پرداخت امن و ارسال سریع.');
            if ($u = wp_get_attachment_image_url($p->get_image_id(), 'woocommerce_single')) $img = $u;
            $type = 'product';
        }
    } elseif (is_product_category() || is_category() || is_tag() || is_tax()) {
        $t = get_queried_object();
        if ($t instanceof WP_Term) {
            $desc = $t->description
                ? kidara_seo_clip($t->description)
                : kidara_seo_clip('خرید آنلاین ' . ($t->parent ? '' : 'لباس ') . $t->name . ' از کیدارا؛ لباس کودک با پارچهٔ نرم و باکیفیت، تنوع سایز و رنگ، پرداخت امن و ارسال سریع.');
            $thumb = (int) get_term_meta($t->term_id, 'thumbnail_id', true);
            if ($thumb && ($u = wp_get_attachment_image_url($thumb, 'large'))) $img = $u;
        }
    } elseif (function_exists('is_shop') && is_shop()) {
        $desc = 'فروشگاه کیدارا: خرید آنلاین لباس نوزاد، دخترانه و پسرانه؛ ست، سرهمی، کاپشن و پالتو کودک با پرداخت امن و ارسال سریع.';
    } elseif (is_singular()) {
        $post = get_queried_object();
        $map = [
            'about-us' => 'دربارهٔ کیدارا؛ فروشگاه آنلاین لباس کودک با تمرکز بر پارچهٔ نرم و ایمن، طرح‌های شاد و قیمت منصفانه.',
            'contact'  => 'راه‌های تماس با فروشگاه کیدارا برای پیگیری سفارش، مشاوره سایز و پشتیبانی خرید لباس کودک.',
            'faq'      => 'پرسش‌های پرتکرار دربارهٔ خرید از کیدارا: ارسال، پرداخت، انتخاب سایز، تعویض و مرجوعی کالا.',
        ];
        if ($post instanceof WP_Post) {
            if (isset($map[$post->post_name])) {
                $desc = $map[$post->post_name];
            } elseif ($post->post_excerpt || $post->post_content) {
                $c = kidara_seo_clip($post->post_excerpt ?: $post->post_content);
                if (mb_strlen($c) > 50) $desc = $c;
            }
            if (has_post_thumbnail($post)) $img = get_the_post_thumbnail_url($post, 'large');
            if ($post->post_type === 'post') $type = 'article';
        }
    }
    return ['desc' => $desc, 'img' => $img, 'type' => $type];
}

/* ---------- robots ---------- */
add_filter('wp_robots', function (array $r) {
    $noindex = is_author() || is_date() || is_attachment() || is_404()
        || (is_singular() && in_array((int) get_queried_object_id(), kidara_seo_private_page_ids(), true))
        || (is_home() && !kidara_seo_has_posts())
        || (is_product_category() && in_array((int) get_queried_object_id(), kidara_seo_empty_cats(), true))
        // filtered / sorted shop listings are duplicates of the base listing
        || (kidara_seo_is_listing() && preg_grep('/^(?!utm_|paged$|product-page$|gclid$|fbclid$)/', array_keys($_GET)));
    if ($noindex) {
        unset($r['max-image-preview']);
        $r['noindex'] = true;
        $r['follow']  = true;
    } else {
        $r['max-snippet']       = '-1';
        $r['max-video-preview'] = '-1';
    }
    return $r;
});

function kidara_seo_is_listing(): bool
{
    return function_exists('is_shop') && (is_shop() || is_product_taxonomy());
}

/* Author archives expose the admin login name and carry no content → home. */
add_action('template_redirect', function () {
    if (is_author()) { wp_safe_redirect(home_url('/'), 301); exit; }
});
add_filter('rest_endpoints', function ($e) {
    if (!is_user_logged_in()) {
        unset($e['/wp/v2/users'], $e['/wp/v2/users/(?P<id>[\d]+)']);
    }
    return $e;
});

/* ---------- sitemap ---------- */
add_filter('wp_sitemaps_add_provider', fn($p, $name) => $name === 'users' ? false : $p, 10, 2);
add_filter('wp_sitemaps_post_types', function ($t) {
    if (!kidara_seo_has_posts()) unset($t['post']);
    return $t;
});
add_filter('wp_sitemaps_posts_query_args', function ($args, $type) {
    if ($type === 'page') $args['post__not_in'] = array_merge($args['post__not_in'] ?? [], kidara_seo_private_page_ids());
    return $args;
}, 10, 2);
add_filter('wp_sitemaps_taxonomies', function ($t) {
    return array_intersect_key($t, array_flip(['product_cat']));
});
// Parent categories hold no products directly but list their children's — keep them.
add_filter('wp_sitemaps_taxonomies_query_args', function ($args, $tax) {
    if ($tax === 'product_cat') {
        // keep parents whose sub-tree has products; drop truly empty categories
        $args['hide_empty'] = false;
        $args['exclude'] = array_values(array_unique(array_filter(array_merge(kidara_seo_empty_cats(), [(int) get_option('default_product_cat')]))));
    }
    return $args;
}, 10, 2);

/* ---------- head tags ---------- */
add_action('wp_head', function () {
    if (is_admin()) return;
    $c     = kidara_seo_context();
    $title = wp_get_document_title();
    $url   = wp_get_canonical_url() ?: home_url(user_trailingslashit($GLOBALS['wp']->request ?? ''));
    if (is_front_page()) $url = home_url('/');
    elseif (is_tax() || is_category() || is_tag()) $url = get_term_link(get_queried_object());
    elseif (function_exists('is_shop') && is_shop()) $url = get_permalink(wc_get_page_id('shop'));

    $tags = [
        ['name', 'description', $c['desc']],
        ['property', 'og:locale', 'fa_IR'],
        ['property', 'og:type', $c['type'] === 'article' ? 'article' : ($c['type'] === 'product' ? 'product' : 'website')],
        ['property', 'og:site_name', get_bloginfo('name')],
        ['property', 'og:title', $title],
        ['property', 'og:description', $c['desc']],
        ['property', 'og:url', is_wp_error($url) ? home_url('/') : $url],
        ['property', 'og:image', $c['img']],
        ['name', 'twitter:card', 'summary_large_image'],
        ['name', 'twitter:title', $title],
        ['name', 'twitter:description', $c['desc']],
        ['name', 'twitter:image', $c['img']],
    ];
    foreach ($tags as [$attr, $key, $val]) {
        if ($val === '' || $val === null) continue;
        printf('<meta %s="%s" content="%s">' . "\n", $attr, esc_attr($key), esc_attr($val));
    }
    // Category / shop canonical (WP core only emits canonical for singular views)
    if (!is_singular() && !is_wp_error($url) && (is_front_page() || is_tax() || is_category() || is_tag() || (function_exists('is_shop') && is_shop())) && !is_paged()) {
        if (!is_front_page()) printf('<link rel="canonical" href="%s">' . "\n", esc_url($url));
    }

    foreach (kidara_seo_schema() as $node) {
        echo '<script type="application/ld+json">' . wp_json_encode($node, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "</script>\n";
    }
}, 2);

/* ---------- JSON-LD ---------- */
function kidara_seo_schema(): array
{
    $home  = home_url('/');
    $logo  = get_theme_file_uri('assets/img/logo.png');
    $out   = [];

    if (is_front_page()) {
        $out[] = [
            '@context' => 'https://schema.org',
            '@type'    => 'OnlineStore',
            '@id'      => $home . '#org',
            'name'     => get_bloginfo('name'),
            'url'      => $home,
            'logo'     => $logo,
            'image'    => get_theme_file_uri('assets/img/og-default.jpg'),
            'description' => KIDARA_SEO_DEFAULT_DESC,
            'telephone'   => '+989337456906',
            'areaServed'  => 'IR',
        ];
        $out[] = [
            '@context' => 'https://schema.org',
            '@type'    => 'WebSite',
            '@id'      => $home . '#website',
            'name'     => get_bloginfo('name'),
            'url'      => $home,
            'inLanguage' => 'fa-IR',
            'publisher'  => ['@id' => $home . '#org'],
            'potentialAction' => [
                '@type'  => 'SearchAction',
                'target' => ['@type' => 'EntryPoint', 'urlTemplate' => $home . '?s={search_term_string}&post_type=product'],
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }

    if (function_exists('is_product') && is_product() && ($p = wc_get_product(get_queried_object_id()))) {
        $imgs = [];
        foreach (array_merge([$p->get_image_id()], $p->get_gallery_image_ids()) as $iid) {
            if ($iid && ($u = wp_get_attachment_image_url($iid, 'full'))) $imgs[] = $u;
        }
        // Store currency is Toman (IRT, not ISO 4217) → express in Rial for schema.org.
        $mult  = get_woocommerce_currency() === 'IRT' ? 10 : 1;
        $cur   = in_array(get_woocommerce_currency(), ['IRT', 'IRR'], true) ? 'IRR' : get_woocommerce_currency();
        $avail = $p->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock';
        $offer = ['@type' => 'Offer', 'url' => get_permalink($p->get_id()), 'priceCurrency' => $cur, 'availability' => $avail,
                  'itemCondition' => 'https://schema.org/NewCondition', 'seller' => ['@type' => 'Organization', 'name' => get_bloginfo('name')]];
        if ($p->is_type('variable')) {
            $offer['@type']     = 'AggregateOffer';
            $offer['lowPrice']  = (string) round((float) $p->get_variation_price('min') * $mult);
            $offer['highPrice'] = (string) round((float) $p->get_variation_price('max') * $mult);
            $offer['offerCount'] = count($p->get_children());
        } else {
            $offer['price'] = (string) round((float) $p->get_price() * $mult);
        }
        $node = [
            '@context' => 'https://schema.org',
            '@type'    => 'Product',
            'name'     => $p->get_name(),
            'url'      => get_permalink($p->get_id()),
            'image'    => $imgs,
            'description' => kidara_seo_clip($p->get_short_description() ?: $p->get_description(), 500),
            'sku'      => $p->get_sku() ?: (string) $p->get_id(),
            'offers'   => $offer,
        ];
        if ($p->get_review_count() > 0) {
            $node['aggregateRating'] = ['@type' => 'AggregateRating', 'ratingValue' => $p->get_average_rating(), 'reviewCount' => $p->get_review_count()];
        }
        $out[] = $node;
    }

    // Breadcrumbs: product → its primary category chain; category → ancestors.
    $crumbs = [];
    if (function_exists('is_product') && is_product()) {
        $terms = get_the_terms(get_queried_object_id(), 'product_cat');
        if ($terms && !is_wp_error($terms)) {
            usort($terms, fn($a, $b) => count(get_ancestors($b->term_id, 'product_cat')) <=> count(get_ancestors($a->term_id, 'product_cat')));
            $crumbs = kidara_seo_term_chain($terms[0]);
        }
        $crumbs[] = [get_the_title(get_queried_object_id()), get_permalink(get_queried_object_id())];
    } elseif (function_exists('is_product_category') && is_product_category()) {
        $crumbs = kidara_seo_term_chain(get_queried_object());
    }
    if ($crumbs) {
        array_unshift($crumbs, ['خانه', $home]);
        $items = [];
        foreach ($crumbs as $i => [$name, $link]) {
            $items[] = ['@type' => 'ListItem', 'position' => $i + 1, 'name' => html_entity_decode($name), 'item' => $link];
        }
        $out[] = ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $items];
    }
    return $out;
}

function kidara_seo_term_chain(WP_Term $t): array
{
    $chain = [];
    foreach (array_reverse(get_ancestors($t->term_id, $t->taxonomy)) as $aid) {
        $a = get_term($aid, $t->taxonomy);
        if ($a && !is_wp_error($a)) $chain[] = [$a->name, get_term_link($a)];
    }
    $chain[] = [$t->name, get_term_link($t)];
    return $chain;
}
