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
						<a href="<?= $site->productsPage() ?>">Back To Products</a>
					</div>
				</div>
				<aside>
					<div class="header">Order Summary</div>
					<div id="other-fees">
						<div id="discount">Discount: <span></span></div>
						<div id="subtotal">Subtotal: <span>$<?= $subtotal ?></span></div>
						<div id="shipping-fee">Shipping Fee: <span>$<?= c::get('shipping_fee') ?></span></div>
						<div id="sales-tax">Sales Tax (<?= c::get('sales_tax') * 100 ?>%): <span>$<?= priceFormat(c::get('sales_tax') * $subtotal) ?></span></div>
					</div>
					<?= FormBuild::text('promo_code', 'Promo Code:') ?>
					<div id="total">Total: <span>$<?= $total ?></span></div>
				</aside>
			</div>
			<div>
				<form id="checkout-process" action="checkout-process" method="POST">
					<button>Proceed To Checkout</button>
				</form>
			</div>
		<?php else: ?>
			<h2>Your Shopping Bag is empty.</h2>
		<?php endif ?>
		</div>
		<?= $page->loading() ?>
	</main>
<?php snippet('footer') ?>