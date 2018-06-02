<?php snippet('header') ?>
<?= $page->buildHero() ?>
<?= $page->productNavigation() ?>
	<main>
		<?= $page->categoryContent() ?>
	</main>
<?php snippet('footer') ?>