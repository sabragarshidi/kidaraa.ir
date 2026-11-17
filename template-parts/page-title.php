<?php
/**
 * Inner-page title banner + breadcrumb (demo: .bwp-main .page-title.dark).
 * Args:
 *   title  string  H1 text
 *   crumbs array   [['label'=>'خانه','url'=>'/'], ['label'=>'Shop']]  last item = current (no url)
 *   bg     string  background image URL (default demo bg-breadcrumb.jpg)
 *   after  string  optional extra HTML inside the banner, below the breadcrumb (e.g. shop category circles)
 * The header is absolutely positioned, so this banner starts below it (118px desktop / 72px < 1200px).
 */
$a = wp_parse_args($args ?? [], [
    'title'  => get_the_title(),
    'crumbs' => null,
    'bg'     => '/wp-content/themes/kidara/assets/img/demo/uploads/2024/01/bg-breadcrumb.jpg',
    'after'  => '',
]);
if ($a['crumbs'] === null) {
    $a['crumbs'] = [['label' => 'خانه', 'url' => home_url('/')], ['label' => $a['title']]];
}
?>
<div class="mt-18 min-[1200px]:mt-29.5 mb-22.5 relative bg-cover bg-center bg-no-repeat pt-10 pb-10 text-center text-lg md:pt-17.5 md:pb-18.75"
     style="background-image:url('<?= esc_url($a['bg']) ?>')">
    <div class="container">
        <div class="relative inline-flex items-center">
            <h1 class="mt-0 text-center text-3xl leading-normal text-heading md:text-[40px]"><?= esc_html($a['title']) ?></h1>
        </div>
        <nav class="flex w-full flex-nowrap items-center justify-center overflow-hidden whitespace-nowrap text-base text-heading" aria-label="مسیر صفحه">
            <?php $last = count($a['crumbs']) - 1; foreach ($a['crumbs'] as $i => $c) : ?>
                <?php if ($i < $last && !empty($c['url'])) : ?>
                    <a href="<?= esc_url($c['url']) ?>" class="shrink-0 text-heading hover:text-accent-dark"><?= esc_html($c['label']) ?></a>
                    <span class="mx-2 shrink-0 text-body"><?php kidara_icon_e('chevron-right', 'text-[14px] kd-dir'); ?></span>
                <?php else : ?>
                    <span class="min-w-0 truncate text-body"><?= esc_html($c['label']) ?></span>
                <?php endif; ?>
            <?php endforeach; ?>
        </nav>
        <?= $a['after'] // phpcs:ignore -- trusted template HTML ?>
    </div>
</div>
