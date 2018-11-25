<?php

return function($site, $pages, $page) {

	$shoppingBag = isset($_COOKIE['shopping-bag']) ? json_decode($_COOKIE['shopping-bag'], true) : false;

	$cartItems = $page->cartItems($shoppingBag);

	$subtotal = '$'.priceFormat($cartItems['subtotal'] + $site->shipping_methods()->toStructure()->first()->price()->value());

	$cartItems = $cartItems['html'];

	return compact('shoppingBag', 'cartItems', 'subtotal');

};