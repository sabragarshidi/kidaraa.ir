<?php
defined('ABSPATH') || exit;

$kd_up = 'https://buny.wpbingosite.com/wp-content/uploads/2020/06/';
$kd_socials = ['tiktok', 'x-twitter', 'facebook', 'behance'];
?>
<footer id="bwp-footer" class="bwp-footer footer-1 relative overflow-hidden border-b border-b-[#0000001a] bg-[url(https://buny.wpbingosite.com/wp-content/uploads/2020/06/footer-1.png)] bg-center bg-no-repeat bg-cover">
	<img src="<?php echo esc_url($kd_up . 'teddy-2.png'); ?>" alt="" width="92" height="166" loading="lazy" class="absolute bottom-0 left-0 max-md:hidden">
	<img src="<?php echo esc_url($kd_up . 'balloon.png'); ?>" alt="" width="75" height="71" loading="lazy" class="absolute bottom-[40%] left-[10%] max-md:hidden">
	<img src="<?php echo esc_url($kd_up . 'leaves-2.png'); ?>" alt="" width="48" height="77" loading="lazy" class="absolute bottom-[53%] right-[18%] max-md:hidden">
	<img src="<?php echo esc_url($kd_up . 'dragonfly.png'); ?>" alt="" width="29" height="34" loading="lazy" class="absolute bottom-[15%] right-[15%] max-md:hidden">

	<div class="relative mx-auto max-w-195 px-3.75 py-30 text-center">
		<div class="mb-8.75 flex justify-center [&_a]:text-[32px]"><?php kidara_logo(); ?></div>

		<ul class="mb-8.75 m-0 p-0 list-none text-sm leading-[1.7] text-body [&>li:not(:first-child)]:mt-1.5 [&>li:not(:last-child)]:pb-1.5">
			<li>Gyan Singh Market, Sector 22 <br>Noida, UP 201301</li>
			<li>Call us now: +91-9871833367</li>
			<li>Email: <a href="mailto:demobuny@gmail.com" class="text-body hover:text-accent">demobuny@gmail.com</a></li>
		</ul>

		<ul class="mb-15 m-0 p-0 list-none flex justify-center gap-x-5 text-accent">
			<?php foreach ($kd_socials as $s) : ?>
				<li><a href="#" target="_blank" rel="noopener" class="inline-block text-accent hover:text-accent-dark" aria-label="<?php echo esc_attr($s); ?>"><?php echo kidara_icon($s, 'text-[14px]'); ?></a></li>
			<?php endforeach; ?>
		</ul>

		<form action="#" method="post" class="mb-13.75 bg-[url(https://buny.wpbingosite.com/wp-content/uploads/2020/06/ellipse-3.png)] bg-center bg-no-repeat bg-cover py-6.25" onsubmit="return false">
			<div class="flex items-center px-27.5 max-md:px-7.5 text-sm">
				<input type="email" name="your-email" placeholder="Email..." class="w-full border-0 bg-transparent p-0 pe-7.5 text-sm text-black outline-0 placeholder:text-muted" aria-label="Email">
				<input type="submit" value="Subscribe" class="cursor-pointer border-0 bg-transparent p-0 font-display text-sm text-heading hover:text-accent">
			</div>
		</form>

		<div class="text-heading">© <?php echo date('Y'); ?> <strong class="text-black">Kidara</strong>. All rights reserved.</div>
	</div>
</footer>
