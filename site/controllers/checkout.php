<?php

return function($site, $pages, $page) {

	$steps = [
		'shipping-information' => 'Shipping Information',
		'payment-details' => 'Payment Details',
		'order-confirmation' => 'Order Confirmation'
	];

	$steps = $page->buildSteps($steps);

	return compact('steps');

};