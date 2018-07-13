<?php

class ProductsPage extends DefaultPage {

	public function productNavigation($category) {
		$allProductsPage = site()->productsPage();
		$categories = site()->productCategories(true);

		$content = brick('a', brick('span', 'All '.$allProductsPage->title()), ['href'=>$allProductsPage->url(), 'class'=>r($allProductsPage->isActive() && !$category, 'active')]);
		foreach($categories as $cat) {
			$content.= brick('a', brick('span', $cat['name']), ['href'=>$cat['link'], 'class'=>r($cat['uri'] == $category, 'active')]);
		}
		return brick('div', brick('div', $content, ['class'=>'container']), ['id'=>'product-navigation']);
	}

	public function categoryContent($category) {
		if($category) {
			foreach($this->category_descriptions()->toStructure() as $cat_desc) {
				foreach(site()->productCategories(true) as $cat) {
					if(strtolower($cat_desc->category()) == $category && $cat['uri'] == strtolower($category)) {
						$title = $cat['name'];
						$text = $cat_desc->text();
					}
				}
			}
		}
		else {
			$title = $this->title();
			$text = $this->category_description();
		}
		$content = brick('div', $title, ['class'=>'title']).brick('div', kirbytext($text));
		return brick('div', $content, ['id'=>'category-description']);
	}

}