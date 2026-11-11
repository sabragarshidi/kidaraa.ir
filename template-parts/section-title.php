<?php
/**
 * Centered section title with pink blob. Args: title, subtitle.
 */
$a = wp_parse_args($args ?? [], ['title' => '', 'subtitle' => '']);
?>
<div class="relative isolate text-center">
    <h2 class="mb-3.75 text-3xl leading-none max-[767px]:text-[25px]"><?= esc_html($a['title']) ?></h2>
    <img src="https://buny.wpbingosite.com/wp-content/uploads/2023/12/ellipse.png" alt="" width="64" height="61" class="absolute start-[46%] -top-[25%] -z-10 h-[61px] w-16 max-w-none" loading="lazy">
    <?php if ($a['subtitle']) : ?><p class="m-0 text-base"><?= esc_html($a['subtitle']) ?></p><?php endif; ?>
</div>
