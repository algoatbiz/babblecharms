		<section id="footer-contact">
			<div>
				<?= $page->buildForm() ?>
			</div>
			<div>
				<div class="contact-details">
					<div class="title"><?= $site->map_text() ?></div>
					<div>
						<div class="address"><?= $site->address()->kt() ?></div>
						<div>
							<a href="tel: <?= $site->phone() ?>" class="phone-link"><?= $site->phone() ?></a>
							<div class="office-hours"><?= $site->office_hours()->kt() ?></div>
						</div>
					</div>
				</div>
				<div id="map"></div>
			</div>
		</section>
		<footer>
			<div class="container">
				<?= $site->copyright()->kt() ?>
				<div>
					<div class="social-title"><?= $site->social_title() ?></div>
					<div class="social">
						<?php foreach($site->social_media()->toStructure() as $social): ?>
							<a href="<?= $social->social_link() ?>" class="<?= strtolower($social->social_title()) ?>"></a>
						<?php endforeach ?>
					</div>
				</div>
			</div>
		</footer>

		<?= $page->signupForm() ?>

		<?= js([
			'https://cdn.jsdelivr.net/npm/vue',
			'https://cdnjs.cloudflare.com/ajax/libs/axios/0.16.2/axios.min.js',
      		'https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js',
			'https://cdnjs.cloudflare.com/ajax/libs/velocity/1.5.0/velocity.min.js',
			'https://cdnjs.cloudflare.com/ajax/libs/mobile-detect/1.3.7/mobile-detect.min.js',
			'https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/noframework.waypoints.min.js',
			'https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.1.3/js.cookie.min.js',
			'assets/js/site.js'
		]) ?>
		<?= $page->extraJS() ?>
		<?= js('@auto') ?>
		<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?= c::get('google-api-key') ?>&callback=initMap" type="text/javascript"></script>

	</body>
</html>