<?php
/** Closing banner: watercolor clothesline garland on brand green, short pitch + shop button. */
?>
<section class="container mt-25 mb-24">
    <div class="relative isolate overflow-hidden rounded-[32px] bg-accent pb-12 pt-6 text-center text-white min-[768px]:pb-14">
        <span class="pointer-events-none absolute -start-16 -top-20 -z-10 size-72 rounded-full bg-white/10" aria-hidden="true"></span>
        <span class="pointer-events-none absolute -bottom-24 end-1/4 -z-10 size-56 rounded-full bg-white/10" aria-hidden="true"></span>
        <img src="<?= esc_url(get_theme_file_uri('assets/img/cta-garland.webp')) ?>" alt="" width="1332" height="750" loading="lazy"
             class="block aspect-[4/3] w-full object-cover object-top [mask-image:linear-gradient(to_bottom,#000_82%,transparent)] min-[768px]:aspect-[2.4/1]">
        <div class="@container mx-auto mt-2 px-6 min-[768px]:mt-4">
            <h2 class="m-0 whitespace-nowrap text-[min(42px,calc(100cqw/12.5))] leading-normal text-white">لباس کوچولوها، یک‌جا</h2>
            <p class="mx-auto mb-7 mt-2 max-w-[460px] text-base leading-8 text-white/85">نوزاد، پسرانه و دخترانه؛ همه را ببینید و بهترین را برای کوچولوی‌تان انتخاب کنید.</p>
            <a href="<?= esc_url(home_url('/shop/')) ?>" class="inline-flex items-center gap-2.5 rounded-btn bg-white px-9 text-sm font-bold leading-[50px] text-accent-dark transition-colors duration-200 hover:bg-[#f3dfe3]">رفتن به فروشگاه <?php kidara_icon_e('arrow-left', 'kd-dir'); ?></a>
        </div>
    </div>
</section>
