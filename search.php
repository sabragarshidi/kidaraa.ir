<?php
$title = 'نتایج جست‌وجو: ' . get_search_query();
get_template_part('template-parts/blog/list', null, ['title' => $title, 'crumbs' => [['label' => 'خانه', 'url' => home_url('/')], ['label' => 'وبلاگ', 'url' => get_permalink(get_option('page_for_posts'))], ['label' => 'جست‌وجو']]]);
