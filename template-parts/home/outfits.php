<?php
/**
 * Manifest #3: "Baby Outfits & Sets" — two-up photo slider + text block.
 */
$up = 'https://buny.wpbingosite.com/wp-content/uploads/';
$images = [$up . '2023/11/banner-2.jpg', $up . '2023/11/banner-1.jpg', $up . '2023/11/banner-2.jpg', $up . '2023/11/banner-1.jpg'];
?>
<section class="container relative">
<div class="-mx-3.75 flex flex-col items-stretch min-[768px]:flex-row">
    <img src="<?= esc_url($up . '2023/11/sun.png') ?>" alt="" width="112" height="103" class="pointer-events-none absolute -start-8.75 -top-8.75 z-0 max-w-none" loading="lazy">
    <div class="relative z-[1] w-full min-w-0 px-3.75 min-[768px]:w-[66%]" data-outfits-slider>
        <div class="swiper !overflow-hidden">
            <div class="swiper-wrapper">
                <?php foreach ($images as $src) : ?>
                    <div class="swiper-slide">
                        <a href="<?= esc_url(home_url('/shop/')) ?>" class="block"><?= kidara_img($src, 'Image Slider', 'block aspect-[436/610] w-full object-cover') ?></a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="mt-3.75 flex justify-end gap-5 pe-15 max-[767px]:hidden">
            <button type="button" class="js-outfits-prev text-[22px] text-heading transition-colors hover:text-accent" aria-label="Previous"><?php kidara_icon_e('arrow-left'); ?></button>
            <button type="button" class="js-outfits-next text-[22px] text-heading transition-colors hover:text-accent" aria-label="Next"><?php kidara_icon_e('arrow-right'); ?></button>
        </div>
    </div>
    <div class="flex w-full flex-col justify-center px-3.75 max-[767px]:mt-6 max-[767px]:items-center max-[767px]:text-center min-[768px]:w-[34%]">
        <div class="relative isolate ps-0 min-[1200px]:ps-10">
            <h2 class="relative m-0 max-w-[394px] text-[25px] leading-none text-[#000022] min-[1025px]:text-[40px]">Baby Outfits &amp; Sets</h2>
            <img src="<?= esc_url($up . '2023/12/ellipse.png') ?>" alt="" width="64" height="61" class="pointer-events-none absolute -top-3.75 start-[133px] -z-10 h-[61px] w-16 max-w-none max-[767px]:start-[60%]" loading="lazy">
            <p class="mb-8.75 mt-5 max-w-[394px] text-sm leading-[1.7]">Dress your little one in our adorable Baby Outfits &amp; Sets, crafted with soft fabrics for maximum comfort and style. From snuggly onesies to charming two-piece sets, each ensemble is designed to keep your baby cozy and looking oh-so-adorable.</p>
            <a href="<?= esc_url(home_url('/shop/')) ?>" class="inline-block rounded-btn bg-accent px-13.75 text-sm font-bold leading-[50px] text-white transition-colors duration-200 hover:bg-accent-dark">Shop Now</a>
        </div>
    </div>
</div>
</section>
