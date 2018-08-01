<?php snippet('header') ?>
<?= $page->productNavigation($category) ?>
	<main>
		<div class="container">
			<?= $page->categoryContent($category) ?>
			<?= $page->buildGallery($category) ?>
		</div>
	</main>
<?php snippet('footer') ?>