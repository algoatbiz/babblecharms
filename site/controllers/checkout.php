<?php

return function($site, $pages, $page) {

	$shoppingBag = isset($_COOKIE['shopping-bag']) ? json_decode($_COOKIE['shopping-bag'], true) : false;

	$cartItems = $page->orders($shoppingBag);

	$shipping_method = $_COOKIE['shipping-method'] ?? '';

	foreach($site->shipping_methods()->toStructure() as $method) {
		if($method->shipping_id() == $shipping_method) {
			$shipping_price = $method->price()->value();
			$shipping_method = $method->text();
			break;
		}
	}

	$subtotal = '$'.priceFormat($cartItems['subtotal'] + $shipping_price);

	$orders = $cartItems['html'];

	$steps = [
		'shipping-information' => 'Shipping Information',
		'payment-details' => 'Payment Details',
		'order-confirmation' => 'Order Confirmation'
	];

	$steps = $page->buildSteps($steps);

	return compact('steps', 'orders', 'subtotal', 'shipping_method');

};