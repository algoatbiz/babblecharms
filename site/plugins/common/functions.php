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

function addons() {

    $addons = $_COOKIE['add-ons'] ?? false;

    return $addons ? json_decode($addons, true) : [];

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

function validateUserData($data) {

	$errors = [];
    $password = $data['password'] ?? false;

    $messages = [];

    if($password && !v::minLength($password, 8)) {
        $errors[] = 'password';
        $messages[] = 'Password must be at least 8 characters';
    }

    if($password && (!v::match($password, '@[A-Z]@') || !v::match($password, '@[a-z]@') || !v::match($password, '@[0-9]@'))) {
        $errors[] = 'password';
        $messages[] = 'Password must contain at least 1 number, uppercase and lowercase letters';
    }

    if($password && isset($data['confirm_password']) && v::different($password, $data['confirm_password'])) {
        $errors[] = 'confirm_password';
        $messages[] = 'Passwords do not match';
    }

    if(isset($data['accept_pp']) && $data['accept_pp'] == 'No') {
        $errors[] = 'accept_pp';
        $messages[] = 'Please read and accept our Privacy Policy';
    }

    if(isset($data['email']) && db::select('users', 'email', ['email'=>$data['email']])->first()) {
        $errors[] = 'email';
        $messages[] = 'This email already exists';
    }

    $i = 0;
    $errorMessages = '';
    foreach($messages as $m)
        $errorMessages.= r($i++, '<br>').$m;

    return compact('errors', 'errorMessages');

}