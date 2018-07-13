<?php snippet('header') ?>
<?= $page->buildHero() ?>
<?= $page->productNavigation($category) ?>
	<main>
		<div class="container">
			<?= $page->categoryContent($category) ?>
			<div class="product-list-container"><?= $site->productList($category) ?></div>
		</div>
	</main>
<?php snippet('footer') ?>