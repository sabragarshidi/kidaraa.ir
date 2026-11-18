<?php
/**
 * Tamineto sync overwrites a variable product's attributes with spec-only ones
 * («سایر توضیحات», «کد کالا») and drops the variation attribute («گزینه ها»).
 * Variations keep their attribute_* meta, so the parent attribute is rebuilt
 * from them after every product save, plus an hourly sweep as a safety net.
 */
if (!defined('ABSPATH')) exit;

function kidara_repair_variation_attrs(int $product_id): bool
{
    global $wpdb;
    if (get_post_type($product_id) !== 'product') return false;
    $children = $wpdb->get_col($wpdb->prepare(
        "SELECT ID FROM {$wpdb->posts} WHERE post_parent = %d AND post_type = 'product_variation' AND post_status IN ('publish','private')", $product_id));
    if (!$children) return false;

    $rows = $wpdb->get_results("SELECT post_id, meta_key, meta_value FROM {$wpdb->postmeta} WHERE post_id IN (" . implode(',', array_map('intval', $children)) . ") AND meta_key LIKE 'attribute\\_%'");
    $values = [];
    foreach ($rows as $r) {
        $k = substr($r->meta_key, 10);
        if ($k === '' || strpos($k, 'pa_') === 0) continue;   // global attributes are stored on terms, not handled here
        $v = trim((string) $r->meta_value);
        if ($v !== '') $values[$k][$v] = true;
    }
    if (!$values) return false;

    $attrs = get_post_meta($product_id, '_product_attributes', true);
    $attrs = is_array($attrs) ? $attrs : [];
    $changed = false;
    foreach ($values as $k => $set) {
        $opts = array_keys($set);
        if (isset($attrs[$k]) && !empty($attrs[$k]['is_variation'])) {
            $have = array_map('trim', explode(WC_DELIMITER, (string) $attrs[$k]['value']));
            if (!array_diff($opts, $have)) continue;            // already complete
            $opts = array_values(array_unique(array_merge($have, $opts)));
        }
        $attrs[$k] = [
            'name'         => isset($attrs[$k]['name']) ? $attrs[$k]['name'] : str_replace('-', ' ', urldecode($k)),
            'value'        => implode(' ' . WC_DELIMITER . ' ', $opts),
            'position'     => -1,                              // show the selectable attribute first
            'is_visible'   => 1,
            'is_variation' => 1,
            'is_taxonomy'  => 0,
        ];
        $changed = true;
    }
    if (!$changed) return false;

    uasort($attrs, fn($a, $b) => ((int) $a['position']) <=> ((int) $b['position']));
    $i = 0;
    foreach ($attrs as &$a) $a['position'] = $i++;
    unset($a);
    update_post_meta($product_id, '_product_attributes', $attrs);
    wc_delete_product_transients($product_id);
    clean_post_cache($product_id);
    return true;
}

// After every save of a product (admin, REST, Tamineto API).
add_action('woocommerce_update_product', fn($id) => kidara_repair_variation_attrs((int) $id), 999);
add_action('woocommerce_new_product', fn($id) => kidara_repair_variation_attrs((int) $id), 999);
add_action('woocommerce_update_product_variation', function ($vid) {
    $parent = (int) wp_get_post_parent_id($vid);
    if ($parent) kidara_repair_variation_attrs($parent);
}, 999);
add_action('woocommerce_new_product_variation', function ($vid) {
    $parent = (int) wp_get_post_parent_id($vid);
    if ($parent) kidara_repair_variation_attrs($parent);
}, 999);

// Safety sweep: every 10 minutes check the next 8 variable products (keeps each run short on this host).
function kidara_repair_all_variation_attrs(int $batch = 0): int
{
    $ids = wc_get_products(['type' => 'variable', 'limit' => -1, 'return' => 'ids', 'status' => ['publish', 'private', 'draft'], 'orderby' => 'ID', 'order' => 'ASC']);
    if ($batch > 0 && $ids) {
        $cur = (int) get_option('kidara_varattr_cursor', 0) % count($ids);
        $ids = array_slice(array_merge($ids, $ids), $cur, min($batch, count($ids)));
        update_option('kidara_varattr_cursor', $cur + count($ids), false);
    }
    $n = 0;
    foreach ($ids as $id) if (kidara_repair_variation_attrs((int) $id)) $n++;
    return $n;
}
add_action('init', function () {
    if (!function_exists('wc_get_products') || wp_doing_ajax() || get_transient('kidara_varattr_sweep')) return;
    set_transient('kidara_varattr_sweep', 1, 10 * MINUTE_IN_SECONDS);
    kidara_repair_all_variation_attrs(8);
}, 99);
