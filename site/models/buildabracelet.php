<?php

class BuildabraceletPage extends DefaultPage {

	public function buildABracelet() {
		$stepLinks = '';
		$i = 0;
		$steps = [
			'bracelet' => 'Select A Bracelet',
			'charm' => 'Select Charms',
			'clasp' => 'Select Clasps'
		];
		foreach($steps as $step => $text)
			$stepLinks.= brick('a', $text, ['href'=>'#', 'class'=>r($i++ == 0, 'current'), 'data-step'=>$step]);

		$content = brick('div', $stepLinks, ['id'=>'steps']);
		$content.= brick('div', brick('div', '', ['class'=>'bracelet']), ['id'=>'canvas']);

		$page = site()->productsPage();
		$products = '';
		foreach($page->products()->toStructure()->sortBy('name') as $product) {
			$category = strtolower($product->category());
			$category.= r(!in_array($category, ['bracelet', 'clasp']), ' charm');
			$products.= brick('a', brick('span', '', ['style'=>'background-image: url('.$page->image($product->featured_image())->url().')']).brick('span', $product->name()), ['href'=>'#', 'class'=>$category]);
		}
		$content.= brick('div', $products, ['id'=>'product-select-container']);

		return $content;
	}

}