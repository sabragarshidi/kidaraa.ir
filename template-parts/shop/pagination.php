<?php
/** Styled pagination from the main query. */
global $wp_query;
if ($wp_query->max_num_pages < 2) return;
$links = paginate_links([
    'type' => 'array', 'current' => max(1, (int) get_query_var('paged')), 'total' => $wp_query->max_num_pages,
    'mid_size' => 1, 'end_size' => 1,
    'prev_text' => kidara_icon('chevron-right', 'text-[16px]'), 'next_text' => kidara_icon('chevron-left', 'text-[16px]'),
]);
if (!$links) return;
?>
<nav class="kd-pagination mt-5 mb-5" aria-label="صفحه‌بندی">
    <ul class="m-0 flex list-none items-center justify-center gap-2.5 p-0">
        <?php foreach ($links as $l) : ?><li><?= preg_replace_callback('/>([^<]+)</', fn($m) => '>' . kidara_fa($m[1]) . '<', $l) // Persian digits in link text only, never in href. phpcs:ignore ?></li><?php endforeach; ?>
    </ul>
</nav>
