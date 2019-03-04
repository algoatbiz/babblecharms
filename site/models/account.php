<?php

use Carbon\Carbon;

class AccountPage extends DefaultPage {

	public $css = [
		'assets/css/templates/cart.css'
	];

	public function buildHero($search = false) {
		$content = brick('div', false, ['style'=>'background-image: url("'.$this->bg().'")']);

		$text = brick('h1', 'Hello '.db::select('users', 'first', ['id'=>s::get('user_id')])->first()->first.'!');
		$content.= brick('div', brick('div', $text), ['class'=>'container']);

		return brick('section', $content, ['id'=>'hero', 'class'=>'shorter']);
	}

	public function buildMiddleContent($uri = false) {
		$user = db::select('users', '*', ['id'=>s::get('user_id')])->first();

		$structure = [
			'Personal Information' => ['last', 'first', 'dob'],
			'Contact Information' => ['address', 'address2', 'city', 'state', 'zip', 'email', 'phone'],
			'Change Password' => ['password']
		];

		$content = brick('div', '', ['id'=>'form-message']);

		foreach($structure as $title => $fields) {
			$content.= brick('div', $title, ['class'=>'title']);

			foreach($fields as $field) {
				$label = $field == 'dob' ? 'Date of Birth' : r($field == 'password', 'New ').str::ucfirst($field).r(in_array($field, ['last', 'first']), ' Name');
				$value = r($field != 'password', $user->{$field});
				$type = in_array($field, ['email', 'password']) ? $field : ($field == 'dob' ? 'date' : 'text');

				$content.= brick('div', brick('label', $label).brick('input', '', ['type'=>$type, 'name'=>$field, 'id'=>$field, 'value'=>$value]), ['class'=>'row']);
			}
		}

		return brick('form', $content, ['id'=>'edit-account', 'action'=>'edit-account-process', 'method'=>'POST']);
	}

	public function previousOrders() {
		$orders = db::select('transactions', 'bag', ['user_id'=>s::get('user_id')], 'updated_at desc');

		if($orders) {
			$ids = [];
			$i = 0;

			foreach($orders as $order)
				foreach(json_decode($order->bag(), true) as $id => $qty)
					if($i++ < 5)
						$ids[] = $id;

			$previousOrders = '';

			foreach(site()->productsPage()->products()->toStructure() as $p) {
				foreach($ids as $id) {
					if($p->product_id()->value() == $id) {
						$productUrl = url('products/'.strtolower($p->category()).'/'.str::slug($p->name()));
						$price = $p->price()->value();
						$image = brick('a', '', ['href'=>$productUrl, 'class'=>'image', 'style'=>'background-image: url('.site()->productsPage()->image($p->featured_image())->url().')']);

						$content = brick('h4', brick('a', $p->name(), ['href'=>$productUrl])).brick('div', '$'.$price, ['class'=>'item-price']);

						$previousOrders.= brick('li', $image.brick('div', $content));
					}
				}
			}

			return brick('ul', $previousOrders, ['class'=>'sidebar-products']);
		}
	}

}