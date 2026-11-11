<?php
/** 404 */
get_header(); ?>
<main class="container mt-18 pt-17.5 pb-25 text-center min-[1200px]:mt-29.5 min-[1200px]:pt-20">
    <p class="m-0 font-body text-[150px] leading-none font-normal text-heading min-[768px]:text-[300px]" aria-hidden="true">404</p>
    <h1 class="mx-auto mt-9 mb-0 font-body text-[32px] leading-[1.5] text-heading min-[768px]:text-[50px]">اوه! این صفحه پیدا نشد.</h1>
    <hr class="mx-auto my-7.5 w-[126px] border-0 border-t border-[#ccc]">
    <p class="mx-auto mb-7.5 mt-0 max-w-[420px] text-sm leading-7">متأسفیم، صفحه‌ای که دنبالش بودید پیدا نشد. شاید نشانی را اشتباه وارد کرده‌اید یا صفحه جابه‌جا شده است.</p>
    <a href="<?= esc_url(home_url('/')) ?>" class="inline-flex h-12.5 items-center gap-2 rounded-btn bg-[#d33] px-10.25 text-sm font-bold text-white transition-colors duration-300 hover:bg-accent-dark">بازگشت به صفحهٔ اصلی <?php kidara_icon_e('arrow-right', 'kd-dir text-[16px]'); ?></a>
</main>
<?php get_footer();
