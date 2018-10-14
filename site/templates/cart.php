<?php snippet('header') ?>
	<?= $page->buildHero() ?>
	<main>
		<form id="form-cart" class="container">
			<div>
				<div id="cart-list">
					<div class="header">
						<div>Item</div>
						<div>Quantity</div>
						<div>Total</div>
					</div>
					<ul>
						<?php foreach($site->productsPage()->products()->toStructure()->limit(4) as $item): ?>
							<li>
								<div>
									<div style="background-image: url('<?= $site->productsPage()->image($item->featured_image())->url() ?>')" class="image"></div>
									<div>
										<h3><?= $item->name() ?></h3>
										<div><?= $item->text() ?></div>
										<div class="divider">-</div>
										<div>
											<div class="price">$<?= $item->price() ?></div>
											<a href="#" class="remove">Remove</a>
										</div>
									</div>
								</div>
								<div>
									<div class="quantity">2</div>
									<div class="quantity-buttons">
										<a href="#" class="add"></a>
										<a href="#" class="decrease"></a>
									</div>
								</div>
								<div class="price">$<?= $item->price() ?></div>
							</li>
						<?php endforeach ?>
					</ul>
					<div>
						<a href="#">Back To Products</a>
						<div>Sub Total</div>
					</div>
				</div>
				<aside>
					<div class="header">Shipping Method</div>
					<?= FormBuild::radio('shipping_method', '', c::get('shipping-methods'), true) ?>
					<?= FormBuild::text('promo_code', 'Promo Code:') ?>
					<div id="subtotal">$1952.00</div>
				</aside>
			</div>
			<div>
				<div><a href="<?= url('checkout') ?>" class="button">Proceed To Checkout</a></div>
			</div>
		</form>
	</main>
<?php snippet('footer') ?>