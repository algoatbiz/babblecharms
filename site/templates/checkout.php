<?php snippet('header') ?>
	<?= $page->buildHero() ?>
	<main>
		<div class="container">
			<div>
				<div id="checkout-list">
					<?= $steps ?>
					<form id="shipping-information" action="checkout-process" method="POST">
						<h3>Shipping Information</h3>
						<div>
							<div class="half">
								<?= FormBuild::text('first', 'First Name', true) ?>
								<?= FormBuild::text('last', 'Last Name', true) ?>
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
					</form>
					<form id="payment-details" novalidate action="checkout-process" method="POST">
						<h3>Payment Details</h3>
						<div>
							<div class="row checkbox-container">
								<input type="checkbox" id="use-shipping" name="use-shipping">
								<label for="use-shipping">Use shipping information</label>
							</div>
							<div class="half">
								<?= FormBuild::text('billing_first', 'Billing First Name', true) ?>
								<?= FormBuild::text('billing_last', 'Billing Last Name', true) ?>
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
								<?= FormBuild::text('credit_card', 'Credit Card Number', true, 'number') ?>
								<div class="half">
									<?= FormBuild::text('expiration_date', 'Expiration Date (MM/YY)', true) ?>
									<?= FormBuild::text('csv', 'CSV', true, 'number') ?>
								</div>
							</div>
						</div>
						<button>Submit</button>
						<input type="hidden" id="square-application-id" name="square-application-id" value="<?= c::get('square-application-id') ?>">
						<input type="hidden" id="card-nonce" name="nonce">
					</form>
				</div>
				<aside>
					<div class="header">Your Orders</div>
					<?= $orders ?>
					<div id="other-fees">
						<div id="discount">Discount: <span></span></div>
						<div id="sub-total">Subtotal: <span><?= $subtotal ?></span></div>
						<div id="shipping-method">Shipping Method: <span><?= $shipping_method ?></span></div>
						<div id="state-tax">State Tax: <span></span></div>
					</div>
					<?= FormBuild::text('promo_code', 'Promo Code:') ?>
					<div id="total">Total: <span><?= $total ?></span></div>
				</aside>
			</div>
		</div>
		<?= $page->loading() ?>
	</main>
<?php snippet('footer') ?>