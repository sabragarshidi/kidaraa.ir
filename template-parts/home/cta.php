<?php
/** Compact closing banner: text + shop button, watercolor garland tucked in the corner. */
?>
<section class="container mt-25 mb-24">
    <div class="relative isolate overflow-hidden rounded-[28px] bg-gradient-to-br from-accent to-accent-dark text-white max-[767px]:flex max-[767px]:flex-col max-[767px]:text-center min-[768px]:flex min-[768px]:h-[260px] min-[768px]:items-center">
        <span class="pointer-events-none absolute max-[767px]:hidden -top-14 start-[44%] -z-10 size-36 rounded-full bg-[#f3dfe3]" aria-hidden="true"></span>
        <span class="pointer-events-none absolute max-[767px]:hidden -bottom-6 start-[38%] -z-10 size-16 rounded-full bg-[#f6d97a]" aria-hidden="true"></span>
        <div class="@container relative z-[1] px-7 pb-9 pt-1 min-[768px]:w-[56%] min-[768px]:px-12 min-[768px]:py-0">
            <h2 class="m-0 text-balance text-[22px] leading-[1.6] text-white min-[768px]:text-[28px] min-[1200px]:text-[32px]">یه دنیا لباسای ناز برای کوچولوهای دوست‌داشتنی 🧸</h2>
            <p class="mx-auto mb-6 mt-2 max-w-[440px] text-sm leading-7 text-[#f3dfe3] min-[768px]:mx-0">توی کیدارا کلی انتخاب قشنگ منتظر کوچولوهای شماست! 🤍</p>
            <a href="<?= esc_url(home_url('/shop/')) ?>" class="inline-flex items-center gap-2 rounded-btn bg-[#f6d97a] px-7 text-sm font-bold leading-[44px] text-[#3f5a45] transition-colors duration-200 hover:bg-white">رفتن به فروشگاه <?php kidara_icon_e('arrow-left', 'kd-dir'); ?></a>
        </div>
        <img src="<?= esc_url(get_theme_file_uri('assets/img/cta-garland.webp')) ?>" alt="" width="1332" height="750" loading="lazy"
             class="pointer-events-none order-first -mt-2 block h-auto w-full object-cover object-top min-[768px]:absolute min-[768px]:end-0 min-[768px]:-top-1 min-[768px]:!h-[280px] min-[768px]:w-[46%] min-[768px]:object-left min-[768px]:[mask-image:linear-gradient(to_left,#000_92%,transparent)] rtl:min-[768px]:[mask-image:linear-gradient(to_right,#000_92%,transparent)]">
    </div>
</section>
