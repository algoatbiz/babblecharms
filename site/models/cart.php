<?php

class CartPage extends DefaultPage {

	public function cartItems($shoppingBag) {

		if($shoppingBag) {
			$cartItems = '';
			$subtotal = [];
			$addons = addons();

			foreach(site()->productsPage()->products()->toStructure() as $p) {
				foreach($shoppingBag as $id => $qty) {
					if($p->product_id()->value() == $id) {
						$price = $p->price()->value();
						$image = brick('div', '', ['class'=>'image', 'style'=>'background-image: url('.site()->productsPage()->image($p->featured_image())->url().')']);

						$selected_birthstone = $addons[$id]['birthstone'] ?? '';
						$engraving_value = $addons[$id]['engraving'] ?? '';

						$content = brick('h3', $p->name()).brick('div', $p->text()).
								   r($selected_birthstone, brick('span', brick('strong', 'Birthstone: ').$selected_birthstone.' ($'.site()->birthstone_price()->value().')', ['class'=>'addon'])).
								   r($engraving_value, brick('span', brick('strong', 'Engraving: ').$engraving_value.' ($'.site()->engraving_price()->value().')', ['class'=>'addon'])).
								   brick('div', '-', ['class'=>'divider']).
								   brick('div', brick('div', '$'.$price, ['class'=>'price']).brick('a', 'Remove', ['href'=>'#', 'class'=>'remove', 'data-product-id'=>$id]));

						$details = brick('div', $image.brick('div', $content));

						$qtyButtons = brick('div', brick('a', '', ['href'=>'#', 'class'=>'add'.r($qty >= $p->quantity()->value(), ' disabled')]).brick('a', '', ['href'=>'#', 'class'=>'decrease'.r($qty == 1, ' disabled')]), ['class'=>'quantity-buttons', 'data-product-id'=>$id]);
						$details.= brick('div', brick('div', $qty, ['class'=>'quantity']).$qtyButtons);

		                $birthstone_price = $selected_birthstone ? site()->birthstone_price()->value() : 0;
		                $engraving_price = $engraving_value ? site()->engraving_price()->value() : 0;

						$price = ($price + $birthstone_price + $engraving_price) * $qty;

						$cartItems.= brick('li', $details.brick('div', '$'.priceFormat($price), ['class'=>'price total-price']), ['class'=>'product-'.$id]);

						$subtotal[] = $price;
					}
				}
			}

			return ['html'=>brick('ul', $cartItems), 'subtotal'=>array_sum($subtotal)];
		}

		return false;

	}

	public function shippingMethods() {

		$field = 'shipping_method';
		$choices = '';
		$i = 0;
		foreach(site()->shipping_methods()->toStructure() as $method) {
			$id = $method->shipping_id();

			if($i == 0)
				setcookie('shipping-method', $id);

			$choices.= brick('div', '<input type="radio" name="'.$field.'" value="'.$id.'" id="'.$id.'"'.r($i++ == 0, ' checked="checked"').'>'.brick('label', $method->text(), ['for'=>$id]));
		}

		$field = brick('div', $choices, [
			'id' => $field,
			'class' => 'radio-container',
		]);

		return brick('div', $field, ['class'=>'row']);

	}

}