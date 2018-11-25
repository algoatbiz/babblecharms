<?php snippet('header') ?>
	<?= $page->buildHero() ?>
	<main class="<?= r(!$shoppingBag, 'empty-bag') ?>">
		<div class="container">
		<?php if($shoppingBag): ?>
			<div>
				<div id="cart-list">
					<div class="header">
						<div>Item</div>
						<div>Quantity</div>
						<div>Total</div>
					</div>
					<?= $cartItems ?>
					<div>
						<a href="#">Back To Products</a>
						<div>Sub Total</div>
					</div>
				</div>
				<aside>
					<div class="header">Shipping Method</div>
					<?= $page->shippingMethods() ?>
					<?= FormBuild::text('promo_code', 'Promo Code:') ?>
					<div id="subtotal"><?= $subtotal ?></div>
				</aside>
			</div>
			<div>
				<div><a href="<?= url('checkout') ?>" class="button">Proceed To Checkout</a></div>
			</div>
		<?php else: ?>
			<h2>Your Shopping Bag is empty.</h2>
		<?php endif ?>
		</div>
		<div id="loading">
			<div>
				<div></div><div></div><div></div><div></div>
			</div>
		</div>
	</main>
<?php snippet('footer') ?>