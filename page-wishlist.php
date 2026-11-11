<?php
/** Wishlist: static empty state (logic comes later). */
get_header();
get_template_part('template-parts/page-title', null, ['title' => 'Wishlist']);
?>
<main class="container mb-25">
    <div class="woosw-list" data-wishlist>
        <h2 class="m-0 font-body text-base font-bold leading-[1.7] text-body">Wishlist</h2>
        <p class="mt-8 mb-0 text-sm">There are no products on the Wishlist!</p>
    </div>
</main>
<?php get_footer();
