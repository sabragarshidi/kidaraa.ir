<?php
defined('ABSPATH') || exit;

$kd_shop    = home_url('/shop/');
$kd_account = home_url('/my-account/');
$kd_wish    = home_url('/wishlist/');
$kd_cart    = home_url('/cart/');

$kd_demo_menu = [
    ['خانه', home_url('/'), []],
    ['فروشگاه', $kd_shop, [['شبکه‌ای', $kd_shop], ['فهرستی', add_query_arg('shop_view', 'list', $kd_shop)], ['سبد خرید', $kd_cart], ['تسویه‌حساب', home_url('/checkout/')]]],
    ['محصولات', $kd_shop, []],
    ['وبلاگ', home_url('/blog/'), []],
    ['صفحات', '#', [['دربارهٔ ما', home_url('/about-us/')], ['تماس با ما', home_url('/contact/')], ['سؤالات متداول', home_url('/faq/')]]],
];

/** Static demo menu, same markup as wp_nav_menu output. */
$kd_fallback = function (array $args) use ($kd_demo_menu) {
    $out = '<ul class="' . esc_attr($args['menu_class']) . '">';
    foreach ($kd_demo_menu as $i => [$label, $url, $subs]) {
        $cls = 'menu-item' . ($subs ? ' menu-item-has-children' : '') . ($i === 0 && is_front_page() ? ' current-menu-item' : '');
        $out .= '<li class="' . $cls . '"><a href="' . esc_url($url) . '">' . esc_html($label) . '</a>';
        if ($subs) {
            $out .= '<ul class="sub-menu">';
            foreach ($subs as [$sl, $su]) {
                $out .= '<li class="menu-item"><a href="' . esc_url($su) . '">' . esc_html($sl) . '</a></li>';
            }
            $out .= '</ul>';
        }
        $out .= '</li>';
    }
    echo $out . '</ul>';
};

$kd_nav_desktop = 'menu flex items-center gap-x-7.5 m-0 p-0 list-none text-sm leading-6 text-heading '
    . '[&>li]:relative [&>li>a]:relative [&>li>a]:z-10 [&>li>a]:text-heading [&>li>a:hover]:text-accent '
    . '[&>li.current-menu-item>a]:before:content-[\'\'] [&>li.current-menu-item>a]:before:absolute [&>li.current-menu-item>a]:before:z-[-1] '
    . '[&>li.current-menu-item>a]:before:start-1.25 [&>li.current-menu-item>a]:before:top-0 [&>li.current-menu-item>a]:before:w-8 [&>li.current-menu-item>a]:before:h-6 '
    . '[&>li.current-menu-item>a]:before:bg-[url(https://buny.wpbingosite.com/wp-content/themes/buny/images/ellipse.png)] [&>li.current-menu-item>a]:before:bg-no-repeat [&>li.current-menu-item>a]:before:bg-center [&>li.current-menu-item>a]:before:bg-contain '
    . '[&_.sub-menu]:absolute [&_.sub-menu]:start-0 [&_.sub-menu]:top-full [&_.sub-menu]:z-[9999] [&_.sub-menu]:min-w-56.25 [&_.sub-menu]:bg-white [&_.sub-menu]:border [&_.sub-menu]:border-bg-light [&_.sub-menu]:py-6.25 [&_.sub-menu]:px-5 [&_.sub-menu]:list-none [&_.sub-menu]:m-0 '
    . '[&_.sub-menu]:opacity-0 [&_.sub-menu]:invisible [&_.sub-menu]:translate-y-2.5 [&_.sub-menu]:transition-all [&_.sub-menu]:duration-300 '
    . '[&>li:hover>.sub-menu]:opacity-100 [&>li:hover>.sub-menu]:visible [&>li:hover>.sub-menu]:translate-y-0 [&>li:focus-within>.sub-menu]:opacity-100 [&>li:focus-within>.sub-menu]:visible [&>li:focus-within>.sub-menu]:translate-y-0 '
    . '[&_.sub-menu_li]:py-1.25 [&_.sub-menu_a]:text-body [&_.sub-menu_a:hover]:text-accent';

$kd_nav_mobile = 'menu m-0 p-0 list-none text-base text-heading '
    . '[&>li]:border-b [&>li]:border-border [&>li]:py-3 [&_a]:text-heading [&_a:hover]:text-accent '
    . '[&_.sub-menu]:list-none [&_.sub-menu]:m-0 [&_.sub-menu]:pt-2 [&_.sub-menu]:ps-4 [&_.sub-menu_li]:py-1.5 [&_.sub-menu_a]:text-body [&_.sub-menu_a]:text-sm';

$kd_badge = 'absolute -top-1.25 -end-1.25 w-3.75 h-3.75 rounded-full bg-accent text-white text-[10px] leading-3.75 text-center';
$kd_icon_btn = 'relative inline-flex items-center text-heading hover:text-accent transition-colors bg-transparent border-0 p-0 cursor-pointer';
$kd_sticky = 'group-[.is-sticky]/h:fixed group-[.is-sticky]/h:top-0 group-[.is-sticky]/h:inset-x-0 group-[.is-sticky]/h:bg-white group-[.is-sticky]/h:shadow-[1px_1px_10px_rgba(0,0,0,0.08)] group-[.is-sticky]/h:z-[10000]';
?>
<header id="bwp-header" class="bwp-header group/h absolute top-0 inset-x-0 z-50 w-full">
	<!-- Mobile / tablet -->
	<div class="header-mobile min-[1200px]:hidden py-3.75">
		<div class="hm-bar <?php echo $kd_sticky; ?> group-[.is-sticky]/h:py-3.75">
			<div class="container px-3.75 md:px-3.75">
				<div class="header-mobile-container flex items-center">
					<div class="flex-1 text-start">
						<button type="button" class="<?php echo $kd_icon_btn; ?>" data-kd-menu-open aria-label="<?php esc_attr_e('منو', 'kidara'); ?>"><?php echo kidara_icon('menu', 'text-[24px]'); ?></button>
					</div>
					<div class="flex-[3] flex justify-center"><?php kidara_logo('', 52); ?></div>
					<div class="flex-1 text-end">
						<a href="<?php echo esc_url($kd_cart); ?>" class="<?php echo $kd_icon_btn; ?>" aria-label="<?php esc_attr_e('سبد خرید', 'kidara'); ?>">
							<?php echo kidara_icon('shopping-bag', 'text-[24px]'); ?><span class="<?php echo $kd_badge; ?>">۰</span>
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="header-mobile-fixed fixed bottom-0 start-0 z-[100] w-full bg-white flex items-center py-1.25 px-3.75 shadow-[0px_1px_12px_2px_rgba(144,144,144,0.3)] [&>*]:flex-1 [&>*]:text-center [&_span.lbl]:block [&_span.lbl]:text-sm [&_span.lbl]:leading-none [&_span.lbl]:mt-0.75">
			<a href="<?php echo esc_url($kd_shop); ?>" class="text-heading hover:text-accent"><?php echo kidara_icon('grid-2x2', 'text-[20px]'); ?><span class="lbl"><?php esc_html_e('فروشگاه', 'kidara'); ?></span></a>
			<a href="<?php echo esc_url($kd_account); ?>" class="text-heading hover:text-accent"><?php echo kidara_icon('user', 'text-[20px]'); ?><span class="lbl"><?php esc_html_e('حساب', 'kidara'); ?></span></a>
			<button type="button" class="bg-transparent border-0 p-0 text-heading hover:text-accent cursor-pointer" data-kd-search-open><?php echo kidara_icon('search', 'text-[20px]'); ?><span class="lbl"><?php esc_html_e('جست‌وجو', 'kidara'); ?></span></button>
			<a href="<?php echo esc_url($kd_wish); ?>" class="text-heading hover:text-accent"><span class="relative inline-block"><?php echo kidara_icon('heart', 'text-[20px]'); ?><span class="<?php echo $kd_badge; ?> -end-3.75">۰</span></span><span class="lbl"><?php esc_html_e('علاقه‌مندی', 'kidara'); ?></span></a>
		</div>
	</div>

	<!-- Desktop -->
	<div class="header-desktop max-[1199px]:hidden">
		<div class="header-wrapper py-7.5 group-[.is-sticky]/h:py-4 <?php echo $kd_sticky; ?>" data-sticky_header="1">
			<div class="container">
				<div class="header-container flex items-center relative min-h-14.5">
					<div class="header-menu flex-1">
						<nav id="main-navigation" class="bwp-navigation" aria-label="<?php esc_attr_e('منوی اصلی', 'kidara'); ?>">
							<?php
							wp_nav_menu([
								'theme_location' => 'primary',
								'container'      => false,
								'menu_class'     => $kd_nav_desktop,
								'fallback_cb'    => $kd_fallback,
							]);
							?>
						</nav>
					</div>
					<div class="header-logo flex justify-center"><?php kidara_logo('', 80); ?></div>
					<div class="header-icon flex-1 flex items-center justify-end gap-x-4.5">
						<button type="button" class="<?php echo $kd_icon_btn; ?>" data-kd-search-open aria-label="<?php esc_attr_e('جست‌وجو', 'kidara'); ?>"><?php echo kidara_icon('search', 'text-[26px]'); ?></button>
						<a href="<?php echo esc_url($kd_account); ?>" class="<?php echo $kd_icon_btn; ?>" aria-label="<?php esc_attr_e('حساب کاربری', 'kidara'); ?>"><?php echo kidara_icon('user', 'text-[26px]'); ?></a>
						<a href="<?php echo esc_url($kd_wish); ?>" class="<?php echo $kd_icon_btn; ?>" aria-label="<?php esc_attr_e('علاقه‌مندی‌ها', 'kidara'); ?>"><?php echo kidara_icon('heart', 'text-[26px]'); ?><span class="<?php echo $kd_badge; ?>">۰</span></a>
						<a href="<?php echo esc_url($kd_cart); ?>" class="<?php echo $kd_icon_btn; ?>" aria-label="<?php esc_attr_e('سبد خرید', 'kidara'); ?>"><?php echo kidara_icon('shopping-bag', 'text-[26px]'); ?><span class="<?php echo $kd_badge; ?>">۰</span></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>

<!-- Off-canvas menu -->
<div id="kd-offcanvas" class="fixed inset-0 z-[100000] invisible [&.is-open]:visible" aria-hidden="true">
	<div class="absolute inset-0 bg-black/50 opacity-0 transition-opacity duration-300 [#kd-offcanvas.is-open_&]:opacity-100" data-kd-menu-close></div>
	<aside class="absolute top-0 start-0 h-full w-75 max-w-[85%] bg-white p-6 overflow-y-auto -translate-x-full rtl:translate-x-full transition-transform duration-300 [#kd-offcanvas.is-open_&]:translate-x-0 rtl:[#kd-offcanvas.is-open_&]:translate-x-0">
		<div class="flex items-center justify-between mb-6">
			<?php kidara_logo(); ?>
			<button type="button" class="<?php echo $kd_icon_btn; ?>" data-kd-menu-close aria-label="<?php esc_attr_e('بستن', 'kidara'); ?>"><?php echo kidara_icon('x', 'text-[24px]'); ?></button>
		</div>
		<nav aria-label="<?php esc_attr_e('منوی موبایل', 'kidara'); ?>">
			<?php
			wp_nav_menu([
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => $kd_nav_mobile,
				'fallback_cb'    => $kd_fallback,
			]);
			?>
		</nav>
	</aside>
</div>

<!-- Search overlay -->
<div id="kd-search" class="fixed inset-0 z-[100001] invisible opacity-0 transition-opacity duration-300 [&.is-open]:visible [&.is-open]:opacity-100" aria-hidden="true">
	<div class="absolute inset-0 bg-black/50" data-kd-search-close></div>
	<div class="relative bg-white w-full py-12 shadow-lg">
		<div class="container">
			<button type="button" class="<?php echo $kd_icon_btn; ?> absolute top-4 end-4" data-kd-search-close aria-label="<?php esc_attr_e('بستن', 'kidara'); ?>"><?php echo kidara_icon('x', 'text-[24px]'); ?></button>
			<form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="max-w-195 mx-auto flex items-center border-b border-heading">
				<input type="search" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="<?php esc_attr_e('جست‌وجوی محصولات…', 'kidara'); ?>" class="flex-1 bg-transparent border-0 outline-0 py-3 text-lg text-heading placeholder:text-muted" data-kd-search-input>
				<input type="hidden" name="post_type" value="product">
				<button type="submit" class="<?php echo $kd_icon_btn; ?>" aria-label="<?php esc_attr_e('جست‌وجو', 'kidara'); ?>"><?php echo kidara_icon('search', 'text-[24px]'); ?></button>
			</form>
		</div>
	</div>
</div>
