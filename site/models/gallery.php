<?php

class GalleryPage extends ProductsPage {

	public function productNavigation($category) {
		$allProductsPage = site()->productsPage();
		$categories = site()->productCategories(true, $this->template());

		$content = $this->template() == 'products' ? brick('a', brick('span', 'All '.$allProductsPage->title()), ['href'=>$allProductsPage->url(), 'class'=>r($allProductsPage->isActive() && !$category, 'active')]) : '';

		foreach($categories as $cat)
			$content.= brick('a', brick('span', $cat['name']), ['href'=>$cat['link'], 'class'=>r($cat['uri'] == $category, 'active')]);

		return brick('div', brick('div', $content, ['class'=>'container']), ['class'=>'product-navigation']);
	}

	public function buildGallery($category) {
		$firstImage = '';
		$images = '';
		$i = 0;

		foreach($this->images()->sortBy('sort') as $img) {
			if($img->category() == $category) {
				$images.= brick('figure', brick('img', '', ['src'=>$img->url()]), ['class'=>r($i==0, 'current'), 'data-index'=>$i]);

				if($i++ == 0)
					$firstImage = $img->url();
			}
		}

		return brick('div', brick('div', brick('span', '', ['class'=>'prev']).brick('span', '', ['class'=>'next']), ['style'=>'background-image: url('.$firstImage.')']).brick('div', $images), ['id'=>'gallery-container']);
	}

}