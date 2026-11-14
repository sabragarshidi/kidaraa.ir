<?php
defined('ABSPATH') || exit;

$kd_up = 'https://buny.wpbingosite.com/wp-content/uploads/2020/06/';
$kd_socials = ['tiktok' => 'تیک‌تاک', 'x-twitter' => 'ایکس', 'facebook' => 'فیسبوک', 'behance' => 'بیهنس'];
?>
<footer id="bwp-footer" class="bwp-footer footer-1 relative overflow-hidden border-b border-b-[#0000001a] bg-[url(https://buny.wpbingosite.com/wp-content/uploads/2020/06/footer-1.png)] bg-center bg-no-repeat bg-cover">
	<img src="<?php echo esc_url($kd_up . 'teddy-2.png'); ?>" alt="" width="92" height="166" loading="lazy" class="absolute bottom-0 end-0 max-md:hidden">
	<img src="<?php echo esc_url($kd_up . 'balloon.png'); ?>" alt="" width="75" height="71" loading="lazy" class="absolute bottom-[40%] start-[10%] max-md:hidden">
	<img src="<?php echo esc_url($kd_up . 'leaves-2.png'); ?>" alt="" width="48" height="77" loading="lazy" class="absolute bottom-[53%] end-[18%] max-md:hidden">
	<img src="<?php echo esc_url($kd_up . 'dragonfly.png'); ?>" alt="" width="29" height="34" loading="lazy" class="absolute bottom-[15%] end-[15%] max-md:hidden">

	<div class="relative mx-auto max-w-195 px-3.75 py-20 text-center">
		<div class="mb-6 flex justify-center"><?php kidara_logo('', 110); ?></div>

		<ul class="mb-10 m-0 p-0 list-none flex justify-center gap-x-6 text-accent">
			<?php foreach ($kd_socials as $s => $kd_social_label) : ?>
				<li><a href="#" target="_blank" rel="noopener" class="inline-block text-accent hover:text-accent-dark" aria-label="<?php echo esc_attr($kd_social_label); ?>"><?php echo kidara_icon($s, 'text-[18px]'); ?></a></li>
			<?php endforeach; ?>
		</ul>

		<div class="text-heading">© <?php echo esc_html(kidara_jdate(null, 'Y')); ?> <strong class="text-black">کیدارا</strong>. تمامی حقوق محفوظ است.</div>

		<?php /* نماد اعتماد الکترونیکی (اینماد): کد رسمی؛ referrerpolicy=origin برای تأیید دامنه الزامی است */ ?>
		<div class="mt-6 flex justify-center">
			<a referrerpolicy="origin" target="_blank" rel="noopener" href="https://trustseal.enamad.ir/?id=8265347&amp;Code=BZsNzsmezxjWBjphZ4rhbMNnmgDJ1DNZ">
				<img referrerpolicy="origin" src="https://trustseal.enamad.ir/logo.aspx?id=8265347&amp;Code=BZsNzsmezxjWBjphZ4rhbMNnmgDJ1DNZ" alt="نماد اعتماد الکترونیکی" width="90" height="90" class="block h-auto w-[90px] cursor-pointer" loading="lazy" code="BZsNzsmezxjWBjphZ4rhbMNnmgDJ1DNZ">
			</a>
		</div>
	</div>
</footer>
