<?php
/**
 * Size guide pulled from the product description (Tamineto imports write it as plain paragraphs):
 *   «سایز 6 تا 9 ماه:<br>– قد تیشرت: 28<br>– پهنا: 27» → table rows; other size/measure notes → list below.
 */
defined('ABSPATH') || exit;

function kidara_size_guide(WC_Product $product): ?array
{
    $desc = (string) $product->get_description();
    if ($desc === '' || !preg_match_all('#<p[^>]*>(.*?)</p>#su', wpautop($desc), $ps)) {
        return null;
    }
    $rows = [];
    $cols = [];
    $notes = [];
    foreach ($ps[1] as $p) {
        $lines = array_values(array_filter(array_map(
            fn($l) => trim(html_entity_decode(wp_strip_all_tags($l), ENT_QUOTES, 'UTF-8')),
            preg_split('#<br\s*/?>#i', $p)
        ), 'strlen'));
        if (!$lines) continue;
        $head = $lines[0];
        // Measurement block: heading «سایز …:» followed by «– key: value» lines.
        if (preg_match('/^سایز\s*(.+?)\s*[:：]$/u', $head, $m) && count($lines) > 1) {
            $vals = [];
            foreach (array_slice($lines, 1) as $l) {
                if (preg_match('/^[–\-•]\s*(.+?)\s*[:：]\s*(.+)$/u', $l, $kv)) {
                    $vals[$kv[1]] = $kv[2];
                    $cols[$kv[1]] = true;
                }
            }
            if ($vals) { $rows[$m[1]] = $vals; continue; }
        }
        $text = implode(' ', $lines);
        if (preg_match('/سایز|اندازه/u', $text) && !preg_match('/^[^:：]{0,60}[:：]$/u', $text)) {
            $notes[] = $lines;
        } elseif (preg_match('/سایز|اندازه/u', $text) && $rows === []) {
            // lone heading like «سایزبندی دقیق محصول:» — skip
        }
    }
    if (!$rows && !$notes) {
        return null;
    }
    return ['cols' => array_keys($cols), 'rows' => $rows, 'notes' => $notes];
}

function kidara_size_guide_render(WC_Product $product): void
{
    $g = kidara_size_guide($product);
    if (!$g) return;
    $id = 'kd-size-guide-' . $product->get_id();
    ?>
    <button type="button" class="mb-3 inline-flex cursor-pointer items-center gap-1.5 border-0 bg-transparent p-0 text-sm font-bold text-heading underline decoration-dotted underline-offset-4 transition-colors hover:text-accent" data-size-guide-open="<?= esc_attr($id) ?>">
        <?= kidara_icon('ruler', 'text-[18px]') ?>راهنمای سایز
    </button>
    <dialog id="<?= esc_attr($id) ?>" class="kd-size-guide m-auto w-[min(640px,calc(100%-24px))] max-h-[85vh] rounded-2xl border-0 bg-white p-0 text-body shadow-2xl backdrop:bg-black/50" aria-label="راهنمای سایز">
        <div class="sticky top-0 flex items-center justify-between border-b border-border bg-white px-5 py-4">
            <h2 class="m-0 text-lg font-bold text-heading">راهنمای سایز</h2>
            <button type="button" class="inline-flex size-9 cursor-pointer items-center justify-center rounded-full border-0 bg-[#f7f4f0] p-0 text-heading hover:bg-accent hover:text-white" data-size-guide-close aria-label="بستن"><?= kidara_icon('x', 'text-[18px]') ?></button>
        </div>
        <div class="px-5 py-4">
            <?php if ($g['rows']) : ?>
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-center text-sm">
                        <thead><tr class="bg-[#f7f4f0] text-heading">
                            <th class="p-2.5 font-bold">سایز</th>
                            <?php foreach ($g['cols'] as $c) : ?><th class="p-2.5 font-bold"><?= esc_html($c) ?></th><?php endforeach; ?>
                        </tr></thead>
                        <tbody>
                        <?php foreach ($g['rows'] as $size => $vals) : ?>
                            <tr class="border-b border-border">
                                <td class="p-2.5 font-bold text-heading whitespace-nowrap"><?= esc_html(kidara_fa($size)) ?></td>
                                <?php foreach ($g['cols'] as $c) : ?><td class="p-2.5"><?= esc_html(kidara_fa($vals[$c] ?? '—')) ?></td><?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <p class="mb-0 mt-2 text-xs text-body/80">اندازه‌ها به سانتی‌متر است.</p>
            <?php endif; ?>
            <?php foreach ($g['notes'] as $lines) : ?>
                <div class="mt-4 text-sm leading-7">
                    <?php foreach ($lines as $l) : ?><div><?= esc_html(kidara_fa($l)) ?></div><?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </dialog>
    <?php
}
