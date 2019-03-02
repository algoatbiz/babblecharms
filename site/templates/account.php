<?php snippet('header') ?>
<?= $page->buildHero() ?>
<main>
	<div class="container">
		<div>
			<div id="user-menu">
				<div>
					<a href="<?= url('account') ?>" style="background-image: url('<?= url('content/2-products/dsc00055_20180703103745689.jpg') ?>')"></a>
				</div>
				<div>
					<a href="<?= url('account') ?>">My Profile</a>
					<a href="<?= url('account/purchases') ?>">My Purchases</a>
				</div>
			</div>

			<?= $content ?>

			<aside>
				<div class="header">Your Previous Orders</div>
				<?= $page->previousOrders() ?>
			</aside>
		</div>
	</div>
</main>
<?php snippet('footer') ?>