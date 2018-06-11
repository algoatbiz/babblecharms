<?php snippet('header') ?>
<?= $page->buildHero() ?>
<?= $page->productNavigation() ?>
	<main>
		<div class="container">
			<?= $page->categoryContent() ?>
			<div class="product-list-container"><?= $site->allProducts() ?></div>
		</div>
	</main>
<?php snippet('footer') ?>