<?php
/** Blog sidebar (demo: .sidebar-blog): search, categories, latest posts, social, tag cloud. */
$wt = 'mb-5 mt-0 text-[22px] leading-[1.5] text-heading';
$recent = get_posts(['numberposts' => 3, 'post_status' => 'publish']);
$cats = get_categories(['hide_empty' => false]);
$tags = get_tags(['hide_empty' => true]);
$social = [['tiktok', 'تیک‌تاک'], ['x-twitter', 'ایکس'], ['facebook', 'فیس‌بوک'], ['behance', 'بیهنس']];
?>
<aside class="kd-blog-sidebar">
    <div class="mb-10">
        <form role="search" method="get" action="<?= esc_url(home_url('/')) ?>" class="relative">
            <input type="search" name="s" value="<?= esc_attr(get_search_query()) ?>" placeholder="جست‌وجو…" aria-label="جست‌وجو"
                   class="h-12 w-full rounded-btn border border-border bg-white py-0 pe-14 ps-5 text-sm text-heading outline-none transition-colors placeholder:text-muted focus:border-accent">
            <button type="submit" aria-label="جست‌وجو" class="absolute top-1.25 end-1.25 flex size-9.5 cursor-pointer items-center justify-center rounded-full border-0 bg-[#d33] text-white transition-colors hover:bg-accent-dark">
                <?php kidara_icon_e('search', 'text-[18px]'); ?>
            </button>
        </form>
    </div>

    <?php if ($cats) : ?>
    <div class="mb-10">
        <h3 class="<?= $wt ?>">دسته‌بندی‌ها</h3>
        <ul class="m-0 list-none p-0">
            <?php foreach ($cats as $c) : ?>
                <li class="pb-1.25 text-base"><a class="text-body hover:text-accent-dark" href="<?= esc_url(get_category_link($c)) ?>"><?= esc_html($c->name) ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <?php if ($recent) : ?>
    <div class="mb-10">
        <h3 class="<?= $wt ?>">آخرین نوشته‌ها</h3>
        <div class="flex flex-col gap-7.5">
            <?php foreach ($recent as $p) : ?>
                <div class="flex items-center">
                    <a href="<?= esc_url(get_permalink($p)) ?>" class="block size-25 shrink-0 overflow-hidden bg-bg-light" aria-hidden="true" tabindex="-1">
                        <?php if (has_post_thumbnail($p)) echo get_the_post_thumbnail($p, 'medium', ['class' => 'size-full object-cover', 'loading' => 'lazy']); ?>
                    </a>
                    <div class="min-w-0 ps-7.5">
                        <span class="mb-1 flex items-center gap-1.5 text-sm text-accent"><?php kidara_icon_e('calendar', 'text-[14px]'); ?><time datetime="<?= esc_attr(get_the_date('c', $p)) ?>"><?= esc_html(kidara_post_date($p)) ?></time></span>
                        <h4 class="m-0 font-body text-base leading-[1.6] font-bold"><a class="text-heading hover:text-accent-dark" href="<?= esc_url(get_permalink($p)) ?>"><?= esc_html(get_the_title($p)) ?></a></h4>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="mb-10">
        <h3 class="<?= $wt ?>">شبکه‌های اجتماعی</h3>
        <ul class="m-0 flex list-none gap-5 p-0">
            <?php foreach ($social as [$ic, $label]) : ?>
                <li><a href="#" aria-label="<?= esc_attr($label) ?>" class="flex text-heading transition-colors hover:text-accent"><?php kidara_icon_e($ic, 'text-[14px]'); ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <?php if ($tags) : ?>
    <div class="mb-10">
        <h3 class="<?= $wt ?>">برچسب‌ها</h3>
        <div class="flex flex-wrap gap-2.5">
            <?php foreach ($tags as $t) : ?>
                <a href="<?= esc_url(get_tag_link($t)) ?>" class="rounded-btn border border-border px-5 py-2.5 text-[15px] leading-[18px] text-body transition-colors hover:border-accent hover:bg-accent hover:text-white"><?= esc_html($t->name) ?></a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</aside>
