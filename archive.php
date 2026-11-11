<?php
$title = get_the_archive_title();
$title = wp_strip_all_tags(preg_replace('/^[^:]+:\s*/', '', $title));
get_template_part('template-parts/blog/list', null, ['title' => $title, 'crumbs' => [['label' => 'Home', 'url' => home_url('/')], ['label' => 'Blog', 'url' => get_permalink(get_option('page_for_posts'))], ['label' => $title]]]);
