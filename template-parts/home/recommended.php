<?php
// Manifest #1 (title) + #2 (product slider). Data copied from captured demo.
$products = 
[
    ['url'=>'https://buny.wpbingosite.com/shop/braced-bloomer-fawn/', 'image'=>'https://buny.wpbingosite.com/wp-content/uploads/2018/05/braced-bloomer-fawn-7-600x836.jpg', 'image_hover'=>'https://buny.wpbingosite.com/wp-content/uploads/2018/05/braced-bloomer-fawn-6-600x836.jpg', 'category'=>'Baby', 'category_url'=>'https://buny.wpbingosite.com/product-category/baby/', 'title'=>'Braced Bloomer Fawn', 'price'=>'100.00$ – 120.00$', 'badges'=>['hot'=>true, 'sale'=>'-33%'], 'swatches'=>[['name'=>'Green', 'color'=>'#5e6762'], ['name'=>'Viola', 'color'=>'#b09294'], ['name'=>'Yellow', 'color'=>'#c69a02']]],
    ['url'=>'https://buny.wpbingosite.com/shop/short-sleeve-belle-dress/', 'image'=>'https://buny.wpbingosite.com/wp-content/uploads/2019/04/short-sleeve-belle-dress-4-600x836.jpg', 'image_hover'=>'https://buny.wpbingosite.com/wp-content/uploads/2019/04/short-sleeve-belle-dress-3-600x836.jpg', 'category'=>'All', 'category_url'=>'https://buny.wpbingosite.com/product-category/all/', 'title'=>'Short Sleeve Belle Dress', 'regular_price'=>'150.00$', 'price'=>'100.00$', 'badges'=>['hot'=>true, 'sale'=>'-33%', 'stock'=>'out of stock'], 'swatches'=>[]],
    ['url'=>'https://buny.wpbingosite.com/shop/gathered-bloomers-basil/', 'image'=>'https://buny.wpbingosite.com/wp-content/uploads/2020/04/gathered-bloomers-basil-4-600x836.jpg', 'image_hover'=>'https://buny.wpbingosite.com/wp-content/uploads/2020/04/gathered-bloomers-basil-3-600x836.jpg', 'category'=>'Boys', 'category_url'=>'https://buny.wpbingosite.com/product-category/boys/', 'title'=>'Gathered Bloomers Basil', 'price'=>'200.00$', 'badges'=>[], 'swatches'=>[]],
    ['url'=>'https://buny.wpbingosite.com/shop/organic-belle-dress-ditsy-clay/', 'image'=>'https://buny.wpbingosite.com/wp-content/uploads/2020/04/organic-belle-dress-ditsy-clay-4-600x836.jpg', 'image_hover'=>'https://buny.wpbingosite.com/wp-content/uploads/2020/04/organic-belle-dress-ditsy-clay-3-600x836.jpg', 'category'=>'All', 'category_url'=>'https://buny.wpbingosite.com/product-category/all/', 'title'=>'Organic Belle Dress Ditsy Clay', 'price'=>'200.00$', 'badges'=>[], 'swatches'=>[]],
    ['url'=>'https://buny.wpbingosite.com/shop/zanti-humpback-belt-for-babies/', 'image'=>'https://buny.wpbingosite.com/wp-content/uploads/2018/10/zanti-humpback-belt-for-babies-24-600x836.jpg', 'image_hover'=>'https://buny.wpbingosite.com/wp-content/uploads/2018/10/zanti-humpback-belt-for-babies-23-600x836.jpg', 'category'=>'Toys', 'category_url'=>'https://buny.wpbingosite.com/product-category/toys/', 'title'=>'Zanti-Humpback Belt for babies', 'price'=>'250.00$', 'badges'=>['hot'=>true], 'swatches'=>[]],
];
?>
<section class="container mt-25">
    <?php get_template_part('template-parts/section-title', null, ['title' => 'Recommended For You', 'subtitle' => 'Fashion Meets Function, On-trend Style']); ?>
</section>
<section class="container mt-10 mb-16.25">
    <div class="group/slider relative -mx-3.75" data-product-slider>
        <div class="swiper">
            <div class="swiper-wrapper">
                <?php foreach ($products as $p) {
                    get_template_part('template-parts/product-card', null, $p + ['slide' => true]);
                } ?>
            </div>
        </div>
        <button type="button" class="js-prev absolute start-3.75 top-[35%] z-[1] text-[30px] text-heading opacity-0 invisible transition-all duration-300 hover:text-accent-dark group-hover/slider:opacity-100 group-hover/slider:visible max-[1199px]:opacity-100 max-[1199px]:visible max-[480px]:hidden cursor-pointer" aria-label="Previous"><?= kidara_icon('chevron-left') ?></button>
        <button type="button" class="js-next absolute end-3.75 top-[35%] z-[1] text-[30px] text-heading opacity-0 invisible transition-all duration-300 hover:text-accent-dark group-hover/slider:opacity-100 group-hover/slider:visible max-[1199px]:opacity-100 max-[1199px]:visible max-[480px]:hidden cursor-pointer" aria-label="Next"><?= kidara_icon('chevron-right') ?></button>
    </div>
</section>
