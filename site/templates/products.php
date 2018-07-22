<?php snippet('header') ?>
<?= $hero ?>
<?= $productNavigation ?>
	<main>
		<div class="container">
			<?= $content ?>
			<div class="product-list-container"><?= $productList ?></div>
			<div class="product-list-container"><?= $otherProductList ?></div>
		</div>
	</main>
<?php snippet('footer') ?>