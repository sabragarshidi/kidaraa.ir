<?php get_header(); ?>
<main id="main">
    <?php
    // One partial per section-manifest entry, in manifest order (extracted/buny-home.manifest.md).
    foreach (['hero', 'recommended', 'outfits', 'tabs-products', 'blog'] as $part) {
        get_template_part('template-parts/home/' . $part);
    }
    ?>
</main>
<?php get_footer();
