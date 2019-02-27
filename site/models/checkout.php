<?php

class CheckoutPage extends DefaultPage {

	public $css = [
		'assets/css/templates/cart.css'
	];

	public function orders($shoppingBag) {

		if($shoppingBag) {
			$cartItems = '';
			$subtotal = [];

			foreach(site()->productsPage()->products()->toStructure() as $p) {
				foreach($shoppingBag as $id => $qty) {
					if($p->product_id()->value() == $id) {
						$price = $p->price()->value();
						$image = brick('div', '', ['class'=>'image', 'style'=>'background-image: url('.site()->productsPage()->image($p->featured_image())->url().')']);

						$content = brick('h4', $p->name()).brick('div', '$'.$price.' x '.$qty, ['class'=>'item-price']);

						$cartItems.= brick('li', $image.brick('div', $content));

						$subtotal[] = $price * $qty;
					}
				}
			}

			return ['html'=>brick('ul', $cartItems, ['class'=>'sidebar-products']), 'subtotal'=>array_sum($subtotal)];
		}

		return false;

	}

}