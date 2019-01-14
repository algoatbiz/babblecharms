<?php

return function($site, $pages, $page) {

	$shoppingBag = isset($_COOKIE['shopping-bag']) ? json_decode($_COOKIE['shopping-bag'], true) : false;

	$cartItems = $page->cartItems($shoppingBag);

	$subtotal = priceFormat($cartItems['subtotal']);

	$total = getCartTotal($subtotal);

	$cartItems = $cartItems['html'];

	return compact('shoppingBag', 'cartItems', 'subtotal', 'total');

};