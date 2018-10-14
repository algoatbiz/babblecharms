<?php snippet('header') ?>
	<?= $page->buildHero() ?>
	<main>
		<form id="form-checkout" class="container">
			<div>
				<div id="checkout-list">
					<?= $steps ?>
					<div id="shipping-information">
						<h3>Shipping Information</h3>
						<div>
							<div class="half">
								<?= FormBuild::text('first_name', 'First Name', true) ?>
								<?= FormBuild::text('last_name', 'Last Name', true) ?>
							</div>
							<div class="half">
								<?= FormBuild::text('email', 'Email', true, 'email') ?>
								<?= FormBuild::text('phone', 'Phone', true) ?>
							</div>
							<div class="half">
								<?= FormBuild::text('address1', 'Address 1', true) ?>
								<?= FormBuild::text('address2', 'Address 2') ?>
							</div>
							<div class="four">
								<?= FormBuild::text('city', 'City', true) ?>
								<?= FormBuild::select('state', 'State', c::get('states'), true) ?>
								<?= FormBuild::text('zip', 'Zip', true) ?>
								<?= FormBuild::text('country', 'Country', true) ?>
							</div>
						</div>
						<button>Continue</button>
					</div>
					<div id="payment-details">
						<h3>Payment Details</h3>
						<div>
							<div class="half">
								<?= FormBuild::text('billing_first_name', 'Billing First Name', true) ?>
								<?= FormBuild::text('billing_last_name', 'Billing Last Name', true) ?>
							</div>
							<div class="half">
								<?= FormBuild::text('billing_address1', 'Billing Address 1', true) ?>
								<?= FormBuild::text('billing_address2', 'Billing Address 2') ?>
							</div>
							<div class="four">
								<?= FormBuild::text('billing_city', 'Billing City', true) ?>
								<?= FormBuild::select('billing_state', 'Billing State', c::get('states'), true) ?>
								<?= FormBuild::text('billing_zip', 'Billing Zip', true) ?>
								<?= FormBuild::text('billing_country', 'Billing Country', true) ?>
							</div>
							<div class="half">
								<?= FormBuild::text('credit_card', 'Credit Card Number', true) ?>
								<div class="half">
									<?= FormBuild::text('expiration_date', 'Expiration Date', true) ?>
									<?= FormBuild::text('csv', 'CSV', true) ?>
								</div>
							</div>
						</div>
						<button>Submit</button>
					</div>
				</div>
				<aside>
					<div class="header">Your Orders</div>
					<ul>
						<?php foreach($site->productsPage()->products()->toStructure()->limit(4) as $item): ?>
							<li>
								<div style="background-image: url('<?= $site->productsPage()->image($item->featured_image())->url() ?>')" class="image"></div>
								<div>
									<h4><?= $item->name() ?></h4>
									<div class="item-price">$<?= $item->price() ?> x <span>1</span></div>
								</div>
							</li>
						<?php endforeach ?>
					</ul>
					<div id="other-fees">
						<div id="discount">Discount: <span></span></div>
						<div id="sub-total">Subtotal: <span></span></div>
						<div id="shipping-method">Shipping Method: <span><?= c::get('shipping-methods')['standard'] ?></span></div>
						<div id="state-tax">State Tax: <span></span></div>
					</div>
					<?= FormBuild::text('promo_code', 'Promo Code:') ?>
					<div id="total">Total: <span>$1952.00</span></div>
				</aside>
			</div>
		</form>
	</main>
<?php snippet('footer') ?>