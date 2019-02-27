<?php

function ktNoPar($string) {

	return preg_replace('!^<p>(.*?)</p>$!i', '$1', kirbytext($string));

}

function getCartCount() {

	$shoppingBag = $_COOKIE['shopping-bag'] ?? 0;

	return $shoppingBag ? count(json_decode($shoppingBag, true)) : $shoppingBag;

}

function cart($id=false) {

	$shoppingBag = $_COOKIE['shopping-bag'] ?? false;

	if($shoppingBag) {
		$cart = json_decode($shoppingBag, true);

		if($id)
			return array_keys($cart);
	}

	return $cart ?? [];

}

function priceFormat($price) {

	return number_format((float)$price, 2, '.', '');

}

function getCartTotal($subtotal) {

	return priceFormat($subtotal + ($subtotal * c::get('sales_tax')) + c::get('shipping_fee'));

}

function timestamp() {

	return date('Y-m-d H:i:s');

}