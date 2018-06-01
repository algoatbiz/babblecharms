<?php

class ProductListPage extends DefaultPage {
	
	public $css = [
		'assets/css/templates/default.css'
	];

	public function productNavigation() {
		$productList = site()->index()->filterBy('template', 'product-list')->first();
		$categories = $productList->children();

		$content = brick('a', $productList->title(), ['href'=>$productList->url()]);
		foreach($categories as $category) {
			$content.= brick('a', $category->title(), ['href'=>$category->url()]);
		}
		return brick('div', brick('div', $content, ['class'=>'container']), ['id'=>'product-navigation']);
	}

}