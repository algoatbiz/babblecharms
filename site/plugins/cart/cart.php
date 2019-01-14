<?php

kirby()->routes([
	[
		'pattern' => 'cart-process',
		'method' => 'POST',
		'action' => function() {

			$product_id = get('product_id');

			$shoppingBag = isset($_COOKIE['shopping-bag']) ? json_decode($_COOKIE['shopping-bag'], true) : false;

			if(!$shoppingBag)
				return response::json(['empty_bag_msg'=>'Your Shopping Bag is empty.']);

			// $shipping_method = $_COOKIE['shipping-method'] ?? '';

			$subtotal = [];

			// foreach(site()->shipping_methods()->toStructure() as $method) {
			// 	if($method->shipping_id() == $shipping_method) {
			// 		$subtotal[] = $method->price()->value();
			// 		break;
			// 	}
			// }

			foreach(site()->productsPage()->products()->toStructure() as $p) {
				foreach($shoppingBag as $id => $qty) {
					if($id == $p->product_id()->value()) {
						$p_total = $p->price()->value() * $qty;
						$subtotal[] = $p_total;

						if($id == $product_id) {
							$product_total = '$'.priceFormat($p_total);
							$max = $qty >= $p->quantity()->value() ? true : false;
						}
					}
				}
			}

			$subtotal = priceFormat(array_sum($subtotal));
			$sales_tax = '$'.priceFormat($subtotal * c::get('sales_tax'));
			$total = '$'.getCartTotal($subtotal);
			$subtotal = '$'.$subtotal;

			return response::json(compact('max', 'product_total', 'subtotal', 'sales_tax', 'total'));

		}
	]
]);