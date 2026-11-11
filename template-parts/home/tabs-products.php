<?php
// Manifest #5: tabs + 4x2 product grid + View All. Only the "All" list exists in the capture; tabs filter it by category.
$products = 
[
    ['url'=>'/shop/the-rest-shortie-snowberry/', 'image'=>'https://buny.wpbingosite.com/wp-content/uploads/2022/08/the-rest-shortie-snowberry-4-600x836.jpg', 'image_hover'=>'https://buny.wpbingosite.com/wp-content/uploads/2022/08/the-rest-shortie-snowberry-3-600x836.jpg', 'category'=>'Baby', 'category_url'=>'/product-category/baby/', 'title'=>'The Rest Shortie Snowberry', 'regular_price'=>'25.00$', 'price'=>'20.00$', 'badges'=>['sale'=>'-20%'], 'swatches'=>[]],
    ['url'=>'/shop/100-pure-cotton-jumpsuit/', 'image'=>'https://buny.wpbingosite.com/wp-content/uploads/2022/08/100-pure-cotton-jumpsuit-3-600x836.jpg', 'image_hover'=>'https://buny.wpbingosite.com/wp-content/uploads/2022/08/100-pure-cotton-jumpsuit-2-600x836.jpg', 'category'=>'Boys', 'category_url'=>'/product-category/boys/', 'title'=>'100% Pure Cotton Jumpsuit', 'regular_price'=>'100.00$', 'price'=>'80.00$', 'badges'=>['sale'=>'-20%'], 'swatches'=>[['name'=>'Black', 'color'=>'#000000'], ['name'=>'Green', 'color'=>'#5e6762'], ['name'=>'Viola', 'color'=>'#b09294']]],
    ['url'=>'/shop/dress-pansy-floral-mist/', 'image'=>'https://buny.wpbingosite.com/wp-content/uploads/2020/12/dress-pansy-floral-mist-4-600x836.jpg', 'image_hover'=>'https://buny.wpbingosite.com/wp-content/uploads/2020/12/dress-pansy-floral-mist-3-600x836.jpg', 'category'=>'Baby', 'category_url'=>'/product-category/baby/', 'title'=>'Dress Pansy Floral Mist', 'regular_price'=>'100.00$', 'price'=>'90.00$', 'badges'=>['sale'=>'-10%'], 'swatches'=>[]],
    ['url'=>'/shop/quincy-mae-luca-pants-pecan/', 'image'=>'https://buny.wpbingosite.com/wp-content/uploads/2020/12/quincy-mae-luca-pants-pecan-4-600x836.jpg', 'image_hover'=>'https://buny.wpbingosite.com/wp-content/uploads/2020/12/quincy-mae-luca-pants-pecan-3-600x836.jpg', 'category'=>'Baby', 'category_url'=>'/product-category/baby/', 'title'=>'Quincy Mae Luca Pants Pecan', 'regular_price'=>'120.00$', 'price'=>'100.00$', 'badges'=>['sale'=>'-17%'], 'swatches'=>[]],
    ['url'=>'/shop/organic-flutter-dress/', 'image'=>'https://buny.wpbingosite.com/wp-content/uploads/2020/12/organic-flutter-dress-5-600x836.jpg', 'image_hover'=>'https://buny.wpbingosite.com/wp-content/uploads/2020/12/organic-flutter-dress-3-600x836.jpg', 'category'=>'Baby', 'category_url'=>'/product-category/baby/', 'title'=>'Organic Flutter Dress', 'regular_price'=>'200.00$', 'price'=>'116.00$', 'badges'=>['hot'=>true, 'sale'=>'-42%', 'stock'=>'pre order'], 'swatches'=>[]],
    ['url'=>'/shop/nature-baby-drawstring-pants-nettle/', 'image'=>'https://buny.wpbingosite.com/wp-content/uploads/2022/08/100-pure-cotton-jumpsuit-6-600x836.jpg', 'image_hover'=>'https://buny.wpbingosite.com/wp-content/uploads/2022/08/100-pure-cotton-jumpsuit-5-600x836.jpg', 'category'=>'Boys', 'category_url'=>'/product-category/boys/', 'title'=>'Nature Baby Drawstring Pants Nettle', 'price'=>'25.00$', 'badges'=>[], 'swatches'=>[['name'=>'Black', 'color'=>'#000000'], ['name'=>'Blue', 'color'=>'#5173a6'], ['name'=>'Pink', 'color'=>'#ffc0cb']]],
    ['url'=>'/shop/dress-petite-fleur-soft-peony/', 'image'=>'https://buny.wpbingosite.com/wp-content/uploads/2020/12/dress-petite-fleur-soft-peony-4-600x836.jpg', 'image_hover'=>'https://buny.wpbingosite.com/wp-content/uploads/2020/12/dress-petite-fleur-soft-peony-2-600x836.jpg', 'category'=>'Baby', 'category_url'=>'/product-category/baby/', 'title'=>'Dress Petite Fleur Soft Peony', 'price'=>'50.00$', 'badges'=>['hot'=>true], 'swatches'=>[['name'=>'Viola', 'color'=>'#b09294'], ['name'=>'Black', 'color'=>'#000000']]],
    ['url'=>'/shop/peplum-set-cherries-blush/', 'image'=>'https://buny.wpbingosite.com/wp-content/uploads/2020/12/peplum-set-cherries-blush-4-600x836.jpg', 'image_hover'=>'https://buny.wpbingosite.com/wp-content/uploads/2020/12/peplum-set-cherries-blush-3-600x836.jpg', 'category'=>'Baby', 'category_url'=>'/product-category/baby/', 'title'=>'Peplum Set Cherries Blush', 'regular_price'=>'150.00$', 'price'=>'100.00$', 'badges'=>['sale'=>'-33%'], 'swatches'=>[]],
];
$tabs = ['all' => 'All', 'baby' => 'Baby', 'boys' => 'Boys', 'girls' => 'Girls', 'toys' => 'Toys'];
?>
<section class="container mb-16.25" data-product-tabs>
    <ul class="flex flex-nowrap items-center justify-center py-3.75 max-[767px]:justify-start" role="tablist">
        <?php $i = 0; foreach ($tabs as $slug => $label) : ?>
            <li class="group/tab <?= $i ? 'ps-[58px] max-[767px]:ps-5' : '' ?>" role="presentation">
                <button type="button" role="tab" aria-selected="<?= $i ? 'false' : 'true' ?>" data-tab="<?= esc_attr($slug) ?>" class="relative cursor-pointer border-0 bg-transparent p-0 font-display text-xl max-[767px]:text-lg leading-[34px] text-heading">
                    <img src="https://buny.wpbingosite.com/wp-content/uploads/2023/12/ellipse.png" alt="" width="64" height="61" class="pointer-events-none absolute start-1/2 top-1/2 -z-10 h-[61px] w-16 max-w-none -translate-x-1/2 -translate-y-1/2 opacity-0 transition-opacity duration-300 group-has-[[aria-selected=true]]/tab:opacity-100" loading="lazy">
                    <?= esc_html($label) ?>
                </button>
            </li>
        <?php $i++; endforeach; ?>
    </ul>
    <div class="relative isolate -mx-3.75 max-[767px]:-mx-[7.5px] mt-5 grid grid-cols-2 min-[768px]:grid-cols-3 min-[1200px]:grid-cols-4" data-grid>
        <?php foreach ($products as $p) : ?>
            <div class="px-3.75 max-[767px]:px-[7.5px]" data-cat="<?= esc_attr(strtolower($p['category'])) ?>">
                <?php get_template_part('template-parts/product-card', null, $p); ?>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="mt-5 text-center">
        <a href="<?= esc_url(home_url('/shop/')) ?>" class="inline-flex h-12.5 items-center rounded-btn bg-accent px-13.75 text-sm font-bold leading-[50px] text-white transition-colors duration-300 hover:bg-accent-dark">View All</a>
    </div>
</section>
