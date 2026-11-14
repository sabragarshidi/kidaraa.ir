<?php
/** Compact closing banner: text + shop button, watercolor garland tucked in the corner. */
?>
<section class="container mt-25 mb-24">
    <div class="relative isolate overflow-hidden rounded-[28px] bg-gradient-to-br from-accent to-accent-dark text-white max-[767px]:flex max-[767px]:flex-col max-[767px]:text-center min-[768px]:flex min-[768px]:h-[260px] min-[768px]:items-center">
        <span class="pointer-events-none absolute max-[767px]:hidden -top-14 start-[44%] -z-10 size-36 rounded-full bg-[#f3dfe3]" aria-hidden="true"></span>
        <span class="pointer-events-none absolute max-[767px]:hidden -bottom-6 start-[38%] -z-10 size-16 rounded-full bg-[#f6d97a]" aria-hidden="true"></span>
        <div class="@container relative z-[1] px-5 pb-7 pt-1 min-[768px]:w-[56%] min-[768px]:px-12 min-[768px]:py-0">
            <h2 class="m-0 text-balance text-[18px] leading-[1.7] text-white min-[768px]:text-[28px] min-[1200px]:text-[32px]">یه دنیا لباسای ناز برای کوچولوهای دوست‌داشتنی<svg class="ms-1 inline-block size-[1.1em] align-[-.15em]" viewBox="0 0 32 32" aria-hidden="true"><circle cx="7" cy="8" r="5" fill="#c58f67"/><circle cx="25" cy="8" r="5" fill="#c58f67"/><circle cx="7" cy="8" r="2.4" fill="#f3c9c4"/><circle cx="25" cy="8" r="2.4" fill="#f3c9c4"/><circle cx="16" cy="18" r="12.5" fill="#d9a67c"/><ellipse cx="16" cy="22" rx="5.6" ry="4.4" fill="#f3dfc9"/><circle cx="11.5" cy="15.5" r="1.7" fill="#3b2a20"/><circle cx="20.5" cy="15.5" r="1.7" fill="#3b2a20"/><ellipse cx="16" cy="20.4" rx="2" ry="1.4" fill="#3b2a20"/></svg></h2>
            <p class="mx-auto mb-5 mt-1.5 max-w-[440px] text-[13px] leading-7 min-[768px]:text-sm text-[#f3dfe3] min-[768px]:mx-0">توی کیدارا کلی انتخاب قشنگ منتظر کوچولوهای شماست!<svg class="ms-1 inline-block size-[1.05em] align-[-.15em]" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s-7.5-4.6-9.6-9.2C.8 8.2 3 4.5 6.6 4.5c2 0 3.5 1 5.4 3 1.9-2 3.4-3 5.4-3 3.6 0 5.8 3.7 4.2 7.3C19.5 16.4 12 21 12 21z" fill="#f3dfe3"/></svg></p>
            <a href="<?= esc_url(home_url('/shop/')) ?>" class="inline-flex items-center gap-2 rounded-btn bg-[#f6d97a] px-7 text-sm font-bold leading-[44px] text-[#3f5a45] transition-colors duration-200 hover:bg-white">رفتن به فروشگاه <?php kidara_icon_e('arrow-left', 'kd-dir'); ?></a>
        </div>
        <img src="<?= esc_url(get_theme_file_uri('assets/img/cta-garland.webp')) ?>" alt="" width="1332" height="750" loading="lazy"
             class="pointer-events-none order-first -mt-2 block h-auto w-full object-cover max-[767px]:mx-auto max-[767px]:w-[92%] object-top min-[768px]:absolute min-[768px]:end-0 min-[768px]:-top-1 min-[768px]:!h-[280px] min-[768px]:w-[46%] min-[768px]:object-left min-[768px]:[mask-image:linear-gradient(to_left,#000_92%,transparent)] rtl:min-[768px]:[mask-image:linear-gradient(to_right,#000_92%,transparent)]">
    </div>
</section>
