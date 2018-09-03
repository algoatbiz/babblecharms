<?php snippet('header') ?>
	<?= $page->buildHero() ?>
	<main>
		<div class="container">
			<div id="build-container">
				<?= $page->buildABracelet() ?>
			</div>
			<aside>
				<div>Items Used</div>
				<div class="items-used"></div>
				<div class="subtotal">
					<span>Sub Total</span>
					<span></span>
				</div>
				<a href="#" class="button add-bag">Add to Bag</a>
			</aside>
		</div>
	</main>
<?php snippet('footer') ?>