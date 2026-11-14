<?php
/** Closing banner: sends visitors to the shop. */
?>
<section class="container mt-25 mb-24">
    <div class="relative isolate overflow-hidden rounded-[32px] bg-accent text-white max-[767px]:pt-12 min-[768px]:flex min-[768px]:min-h-[340px] min-[768px]:items-center">
        <span class="pointer-events-none absolute -start-16 -top-20 -z-10 size-72 rounded-full bg-white/10" aria-hidden="true"></span>
        <span class="pointer-events-none absolute -bottom-24 end-1/3 -z-10 size-56 rounded-full bg-[#f3dfe3]/20" aria-hidden="true"></span>
        <div class="@container w-full px-8 min-[768px]:w-[58%] min-[768px]:px-14 min-[1025px]:px-20">
            <h2 class="m-0 whitespace-nowrap text-[min(42px,calc(100cqw/12.5))] leading-normal text-white">لباس کوچولوها، یک‌جا</h2>
            <p class="mb-8 mt-3 max-w-[440px] text-base leading-8 text-white/85">نوزاد، پسرانه و دخترانه؛ همه را ببینید و بهترین را برای کوچولوی‌تان انتخاب کنید.</p>
            <a href="<?= esc_url(home_url('/shop/')) ?>" class="inline-flex items-center gap-2.5 rounded-btn bg-white px-9 text-sm font-bold leading-[50px] text-accent-dark transition-colors duration-200 hover:bg-[#f3dfe3]">رفتن به فروشگاه <?php kidara_icon_e('arrow-left', 'kd-dir'); ?></a>
        </div>
        <div class="mt-10 flex justify-center min-[768px]:mt-12 min-[768px]:w-[42%] min-[768px]:self-end min-[768px]:justify-center">
            <img src="<?= esc_url(get_theme_file_uri('assets/img/hero-2.webp')) ?>" alt="" width="996" height="1494" loading="lazy"
                 class="block h-[280px] w-[220px] rounded-t-[999px] bg-[#f3dfe3] object-cover object-top min-[768px]:h-[320px] min-[768px]:w-[240px] min-[1025px]:h-[340px] min-[1025px]:w-[260px]">
        </div>
    </div>
</section>
