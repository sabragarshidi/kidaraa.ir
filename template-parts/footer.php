<?php
defined('ABSPATH') || exit;

$kd_up = 'https://buny.wpbingosite.com/wp-content/uploads/2020/06/';
$kd_socials = ['tiktok' => 'تیک‌تاک', 'x-twitter' => 'ایکس', 'facebook' => 'فیسبوک', 'behance' => 'بیهنس'];
?>
<footer id="bwp-footer" class="bwp-footer footer-1 relative overflow-hidden border-b border-b-[#0000001a] bg-[url(https://buny.wpbingosite.com/wp-content/uploads/2020/06/footer-1.png)] bg-center bg-no-repeat bg-cover">
	<img src="<?php echo esc_url($kd_up . 'teddy-2.png'); ?>" alt="" width="92" height="166" loading="lazy" class="absolute bottom-0 start-0 max-md:hidden">
	<img src="<?php echo esc_url($kd_up . 'balloon.png'); ?>" alt="" width="75" height="71" loading="lazy" class="absolute bottom-[40%] start-[10%] max-md:hidden">
	<img src="<?php echo esc_url($kd_up . 'leaves-2.png'); ?>" alt="" width="48" height="77" loading="lazy" class="absolute bottom-[53%] end-[18%] max-md:hidden">
	<img src="<?php echo esc_url($kd_up . 'dragonfly.png'); ?>" alt="" width="29" height="34" loading="lazy" class="absolute bottom-[15%] end-[15%] max-md:hidden">

	<div class="relative mx-auto max-w-195 px-3.75 py-30 text-center">
		<div class="mb-8.75 flex justify-center [&_a]:text-[32px]"><?php kidara_logo(); ?></div>

		<ul class="mb-8.75 m-0 p-0 list-none text-sm leading-[1.7] text-body [&>li:not(:first-child)]:mt-1.5 [&>li:not(:last-child)]:pb-1.5">
			<li>تهران، خیابان نمونه، پلاک ۱۰</li>
			<li>تماس با ما: <a href="tel:+982112345678" class="text-body hover:text-accent"><bdi dir="ltr">۰۲۱-۱۲۳۴۵۶۷۸</bdi></a></li>
			<li>ایمیل: <a href="mailto:info@kidaraa.ir" dir="ltr" class="text-body hover:text-accent"><bdi dir="ltr">info@kidaraa.ir</bdi></a></li>
		</ul>

		<ul class="mb-15 m-0 p-0 list-none flex justify-center gap-x-5 text-accent">
			<?php foreach ($kd_socials as $s => $kd_social_label) : ?>
				<li><a href="#" target="_blank" rel="noopener" class="inline-block text-accent hover:text-accent-dark" aria-label="<?php echo esc_attr($kd_social_label); ?>"><?php echo kidara_icon($s, 'text-[14px]'); ?></a></li>
			<?php endforeach; ?>
		</ul>

		<form action="#" method="post" class="mb-13.75 bg-[url(https://buny.wpbingosite.com/wp-content/uploads/2020/06/ellipse-3.png)] bg-center bg-no-repeat bg-cover py-6.25" onsubmit="return false">
			<div class="flex items-center px-27.5 max-md:px-7.5 text-sm">
				<input type="email" name="your-email" placeholder="ایمیل شما…" dir="ltr" class="w-full border-0 bg-transparent p-0 pe-7.5 text-start text-sm text-black outline-0 placeholder:text-muted" aria-label="ایمیل">
				<input type="submit" value="عضویت در خبرنامه" class="cursor-pointer border-0 bg-transparent p-0 font-display text-sm text-heading hover:text-accent">
			</div>
		</form>

		<div class="text-heading">© <?php echo esc_html(kidara_jdate(null, 'Y')); ?> <strong class="text-black">کیدارا</strong>. تمامی حقوق محفوظ است.</div>
	</div>
</footer>
