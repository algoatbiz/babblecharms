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

                $bag = $_COOKIE['shopping-bag'];

                $addons = addons();

                $subtotal = [];

                $line_items = [];
                foreach(site()->productsPage()->products()->toStructure() as $p) {
                    foreach(json_decode($bag, true) as $id => $qty) {
                        if($p->product_id()->value() == $id) {
                            $subtotal[] = $p->price()->value() * $qty;

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

                            if($birthstone = $addons[$id]['birthstone']) {
                                $line_items[] = [
                                    'name' => $p->name()->value().' Birthstone: '.$birthstone,
                                    'quantity' => (string)$qty,
                                    'base_price_money' => [
                                        // multiply by 100 due to it being in cents
                                        'amount' => intval(site()->birthstone_price()->value() * 100),
                                        'currency' => 'USD'
                                    ],
                                    'taxes' => [[
                                        'name' => 'Sales Tax',
                                        'percentage' => (string)(c::get('sales_tax') * 100)
                                    ]]
                                ];

                                $subtotal[] = site()->birthstone_price()->value() * $qty;
                            }

                            if($engraving = $addons[$id]['engraving']) {
                                $line_items[] = [
                                    'name' => $p->name()->value().' Engraving: '.$engraving,
                                    'quantity' => (string)$qty,
                                    'base_price_money' => [
                                        // multiply by 100 due to it being in cents
                                        'amount' => intval(site()->engraving_price()->value() * 100),
                                        'currency' => 'USD'
                                    ],
                                    'taxes' => [[
                                        'name' => 'Sales Tax',
                                        'percentage' => (string)(c::get('sales_tax') * 100)
                                    ]]
                                ];

                                $subtotal[] = site()->engraving_price()->value() * $qty;
                            }
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

            if(!$id = db::insert('transactions', ['checkout_id'=>$response->getCheckout()->getId(), 'bag'=>$bag, 'addons'=>$_COOKIE['add-ons'], 'user_id'=>s::get('user_id'), 'total'=>getCartTotal(array_sum($subtotal)), 'status'=>'New']))
                throw new Exception(database::lastError());

			return response::json(compact('checkoutUrl'), 200);

		}
	],
    [
        'pattern' => 'thank-you',
        'action' => function() {

            if($checkoutId = get('checkoutId'))
                db::update('transactions', ['transaction_id'=>get('transactionId'), 'updated_at'=>timestamp()], ['checkout_id'=>$checkoutId]);

            return ['thank-you', []];

        }
    ],
    [
        'pattern' => 'add-bag-process',
        'method' => 'POST',
        'action' => function() {

            foreach(site()->productsPage()->products()->toStructure() as $p)
                if($p->product_id()->value() == get('product_id'))
                    $subtotal = $p->price()->value();

            $qty = get('qty');

            if($qty) {
                $birthstone = get('birthstone') ? site()->birthstone_price()->value() : 0;
                $engraving = get('engraving') ? site()->engraving_price()->value() : 0;
                $subtotal = ($subtotal + $birthstone + $engraving) * $qty;
            }

            $subtotal = '$'.priceFormat($subtotal);

            return response::json(compact('subtotal'), 200);

        }
    ]
]);