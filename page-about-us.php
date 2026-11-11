<?php
/** About Us (slug about-us). Demo copy verbatim; partials reused from the homepage. */
get_header();
$up = 'https://buny.wpbingosite.com/wp-content/uploads/';
$testimonials = [
    ['Your commitment to quality and customer satisfaction has been evident in every interaction. I value our partnership and look...', 'Linda Maria'],
    ['“A great company to buy from. Excellent quality products at good value. Delivery is efficient and quick.”', 'Ann Smith'],
    ['5-star rating 100%. So amazing and helpful. Stress-free and fun.Eileen was amazing. So wonderful.', 'Anana'],
    ['“I look forward to future transactions with your company and will gladly recommend your services to others.”', 'Linda'],
];
$perks = [
    ['truck', 'Free Shipping', 'Sed molestie tempus neque, ac mollis nulla fermentum at.'],
    ['shield-check', 'Securety Payments', 'Sed molestie tempus neque, ac mollis nulla fermentum at.'],
    ['rotate-ccw', '14-Day Returns', 'Sed molestie tempus neque, ac mollis nulla fermentum at.'],
];
?>
<main class="about-us">
    <!-- intro -->
    <section class="mb-23.75 bg-cover bg-center bg-no-repeat px-3.75 pt-40 pb-28 min-[768px]:pt-70 min-[768px]:pb-80 min-[1200px]:pt-105 min-[1200px]:pb-100" style="background-image:url('<?= esc_url($up . '2024/01/bg-about.jpg') ?>')">
        <div class="mx-auto flex w-full max-w-[1410px] flex-col items-start gap-6 min-[768px]:flex-row min-[768px]:items-center">
            <div class="w-full min-[768px]:w-1/2 min-[768px]:ps-[105px]"><h1 class="m-0 font-display text-[35px] leading-[1.2] text-heading min-[1025px]:text-[60px]">About Us</h1></div>
            <div class="w-full min-[768px]:w-1/2 min-[768px]:ps-[89px]"><p class="m-0 text-sm leading-[1.7]">Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>Praesent lobortis, est id malesuada tincidunt, nisi ante accumsan nisi,<br>blandit eleifend orci odio nec nisl.</p></div>
        </div>
    </section>

    <?php get_template_part('template-parts/home/outfits'); ?>

    <!-- Anana testimonial strip -->
    <section class="container relative my-18 text-center" data-about-testimonials>
        <img src="<?= esc_url($up . '2020/06/leaves-2.png') ?>" alt="" class="pointer-events-none absolute -top-12 end-[6%] max-w-none max-[767px]:hidden" loading="lazy">
        <div class="swiper !overflow-hidden mx-auto max-w-[1000px]">
            <div class="swiper-wrapper">
                <?php foreach ($testimonials as $t) : ?>
                    <div class="swiper-slide">
                        <p class="m-0 text-base leading-[1.7]"><?= esc_html($t[0]) ?></p>
                        <h3 class="mt-6.5 mb-0 font-display text-sm leading-[1.2] text-heading"><?= esc_html($t[1]) ?></h3>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="js-testimonials-dots mt-7.5 flex justify-center gap-2"></div>
    </section>

    <!-- lookbook -->
    <section class="container mb-23.75">
        <div class="relative flex items-center bg-cover bg-center bg-no-repeat px-7.5 py-20 min-[768px]:bg-fixed min-[768px]:py-50 min-[1200px]:ps-25 min-[1200px]:pt-61.25 min-[1200px]:pb-50" style="background-image:url('<?= esc_url($up . '2024/01/bg-paralax.jpg') ?>')">
            <div class="relative isolate">
                <img src="<?= esc_url($up . '2024/01/ellipse-3.png') ?>" alt="" width="64" height="61" class="pointer-events-none absolute -top-3.75 start-[140px] -z-10 h-[61px] w-16 max-w-none max-[767px]:start-[100px]" loading="lazy">
                <h2 class="mb-8.75 mt-0 text-[35px] leading-[1.2] text-white min-[1025px]:text-[40px]">Kids Clothes Lookbook</h2>
                <p class="m-0 text-sm font-bold leading-[1.7] text-white">Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>Maecenas dui purus, fermentum at velit lobortis, elementum<br>lacinia velit. Donec pharetra in orci et convallis.</p>
            </div>
        </div>
    </section>

    <!-- perks -->
    <section class="container mb-23.75">
        <div class="-mx-3.75 grid grid-cols-1 gap-y-8 min-[768px]:grid-cols-3">
            <?php foreach ($perks as $p) : ?>
                <div class="px-3.75">
                    <div class="text-accent text-[48px] leading-none"><?php kidara_icon_e($p[0], 'text-[48px]'); ?></div>
                    <h3 class="mb-2.5 mt-6 text-center font-body text-lg font-bold leading-[1.2] text-heading"><?= esc_html($p[1]) ?></h3>
                    <p class="m-0 text-center text-sm"><?= esc_html($p[2]) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <?php get_template_part('template-parts/home/blog'); ?>
    <?php get_template_part('template-parts/home/brands'); ?>
</main>
<?php get_footer();
