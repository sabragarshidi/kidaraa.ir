<?php
/** Wishlist: static empty state (logic comes later). */
get_header();
get_template_part('template-parts/page-title', null, ['title' => 'علاقه‌مندی‌ها', 'crumbs' => [['label' => 'خانه', 'url' => home_url('/')], ['label' => 'علاقه‌مندی‌ها']]]);
?>
<main class="container mb-25">
    <div class="woosw-list" data-wishlist>
        <h2 class="m-0 font-body text-base font-bold leading-[1.8] text-body">علاقه‌مندی‌ها</h2>
        <p class="mt-8 mb-0 text-sm">هنوز محصولی به فهرست علاقه‌مندی‌های شما اضافه نشده است.</p>
    </div>
</main>
<?php get_footer();
