<?php snippet('header') ?>
<?= $page->buildSlider() ?>
	<main>
		<section id="collections">
			<div class="container">
				<div>
					<h2>Collections</h2>
					<?= $page->collections_text()->kt() ?>
				</div>
				<?= $page->buildCollections() ?>
			</div>
		</section>
		<section id="featured">
			<div class="container">
				<div>
					<h2>Featured</h2>
					<?= $page->featured_text()->kt() ?>
				</div>
				<?= $page->buildFeatured() ?>
			</div>
		</section>
		<section id="cta" class="cta">
			<div class="bg" style="background-image: url('<?= $page->file($page->cta_image())->url() ?>');"></div>
			<div class="container">
				<div><?= $page->cta_text()->kt() ?></div>
			</div>
		</section>
	</main>

<?php snippet('footer') ?>