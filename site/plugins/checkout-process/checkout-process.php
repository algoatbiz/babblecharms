<?php

require_once(kirby()->roots()->index() . DS . 'bootstrap.php');

kirby()->routes([
	[
		'pattern' => 'checkout-process',
		'method' => 'POST',
		'action' => function() {

			// setup authorization
            \SquareConnect\Configuration::getDefaultConfiguration()->setAccessToken(EnvHelper::env('SQUARE_ACCESS_TOKEN'));

            try {
                $checkout_api = new \SquareConnect\Api\CheckoutApi();

                $line_items = [];
                foreach(site()->productsPage()->products()->toStructure() as $p) {
                    foreach(json_decode($_COOKIE['shopping-bag'], true) as $id => $qty) {
                        if($p->product_id()->value() == $id) {
                            $line_items[] = [
                                'name' => $p->name()->value(),
                                'quantity' => (string)$qty,
                                'base_price_money' => [
                                    // multiply by 100 due to it being in cents
                                    'amount' => intval($p->price()->value() * 100),
                                    'currency' => 'USD'
                                ],
				                'taxes' => [[
			                        'name' => 'Sales Tax',
			                        'percentage' => (string)(c::get('sales_tax') * 100)
				                ]]
                            ];
                        }
                    }
                }

                // Shipping Fee
                $line_items[] = [
                    'name' => 'Shipping Fee',
                    'quantity' => '1',
                    'base_price_money' => [
                        'amount' => intval(c::get('shipping_fee') * 100),
                        'currency' => 'USD'
                    ]
                ];

                $request_body = new \SquareConnect\Model\CreateCheckoutRequest([
                    'idempotency_key' => uniqid(),
                    'merchant_support_email' => EnvHelper::env('SUPPORT_EMAIL'),
                    'ask_for_shipping_address' => true,
                    'redirect_url' => url('thank-you'),
                    'order' => [
                        'line_items' => $line_items
                    ]
                ]);

                $response = $checkout_api->createCheckout(EnvHelper::env('SQUARE_LOCATION_ID'), $request_body);

            } catch (Exception $e) {
                // if an error occurs, output the message
                return $e->getMessage();
            }

            $checkoutUrl = $response->getCheckout()->getCheckoutPageUrl();

			return response::json(compact('checkoutUrl'), 200);

		}
	]
]);