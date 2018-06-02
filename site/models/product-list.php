<?php

class ProductListPage extends DefaultPage {

	public function productNavigation() {
		$productList = site()->index()->filterBy('template', 'product-list')->first();
		$categories = $productList->children();

		$content = brick('a', brick('span', $productList->title()), ['href'=>$productList->url(), 'class'=>r($productList->isActive(), 'active')]);
		foreach($categories as $category) {
			$content.= brick('a', brick('span', $category->title()), ['href'=>$category->url(), 'class'=>r($category->isActive(), 'active')]);
		}
		return brick('div', brick('div', $content, ['class'=>'container']), ['id'=>'product-navigation']);
	}

	public function categoryContent() {
		$content = brick('div', $this->title(), ['class'=>'title']).brick('div', $this->category_description()->kt());
		return brick('div', brick('div', $content, ['class'=>'container']), ['id'=>'category-description']);
	}

	public function allProducts() {
		return site()->index()->filterBy('template', 'product')->visible();
	}

}