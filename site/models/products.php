<?php

class ProductsPage extends DefaultPage {

	public function productNavigation($category) {
		$allProductsPage = site()->productsPage();
		$categories = site()->productCategories(true, $this->template());

		$content = $this->template() == 'products' ? brick('a', brick('span', 'All '.$allProductsPage->title()), ['href'=>$allProductsPage->url(), 'class'=>r($allProductsPage->isActive() && !$category, 'active')]) : '';
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

	public function buildProduct($productUri) {
		$products = site()->productsPage()->products()->toStructure();
		foreach($products as $p) {
			if($productUri == str::slug($p->name())) {
				$category = $p->category() == 'Bracelet' ? 'Bracelet' : $p->category().' Charms';
				$header = brick('div', brick('h1', $p->name()).brick('div', brick('strong', 'Category: ').brick('a', $category, ['href'=>url('products/'.strtolower($p->category()))])));

				$featuredImage = $this->image($p->featured_image())->url();
				$images = brick('img', false, ['src'=>$featuredImage]);
				if($p->images()->isNotEmpty()) {
					foreach($p->images()->yaml() as $img)
						$images.= brick('img', false, ['src'=>$this->image($img)->url()]);
				}

				$reviews = brick('div', 'Review: '.site()->getRatings()).brick('a', 'Write a review');

				$qtyOptions = '<option value="">Quantity</option>';
				for($i=1; $i<=$p->quantity()->value(); $i++)
					$qtyOptions.= brick('option', $i, ['value'=>$i]);

				$content = brick('div', brick('div', $images), ['class'=>'photo-container', 'style'=>'background-image: url('.$featuredImage.')']).
						   brick('div', brick('div', $p->text()->kt()).brick('div', $reviews, ['class'=>'reviews-container']).
						   brick('div', '$'.$p->price(), ['class'=>'price']).
						   brick('div', brick('a', 'Add to Bag', ['class'=>'button add-bag', 'href'=>'#']).brick('div', brick('select', $qtyOptions), ['class'=>'select-container']), ['class'=>'buy-container']));
			}
		}
		return $header.brick('div', $content, ['class'=>'product-details']);
	}

}