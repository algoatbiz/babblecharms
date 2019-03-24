<?php

class ProductsPage extends DefaultPage {

	public function productNavigation($category) {
		if($this->template() != 'products')
			return '';

		$allProductsPage = site()->productsPage();

		$categories = brick('option', 'All '.$allProductsPage->title(), ['value'=>$allProductsPage->url(), 'selected'=>$allProductsPage->isActive() && !$category]);

		foreach(site()->productCategories(true, $this->template()) as $cat)
			$categories.= brick('option', $cat['name'], ['value'=>$cat['link'], 'selected'=>$cat['uri'] == $category]);

		$content = brick('div', brick('div', 'Category:').brick('div', brick('select', $categories), ['class'=>'select-container']));

		$sort = brick('option', 'None', ['value'=>url('products')]);

		foreach(['price-low-high'=>'Price (Low - High)', 'newest'=>'Newest', 'top-sellers'=>'Top Sellers', 'price-high-low'=>'Price (High - Low)', 'top-rated'=>'Top Rated'] as $id => $title)
			$sort.= brick('option', $title, ['value'=>url('products').r($category, '/'.strtolower($category)).'?sort='.$id, 'selected'=>get('sort') == $id]);

		$content.= brick('div', brick('div', 'Sort By:').brick('div', brick('select', $sort), ['class'=>'select-container']));

		return brick('div', brick('div', $content, ['class'=>'container']), ['class'=>'product-navigation']);
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
		$cart = cart();
		$cartIds = array_keys($cart);
		$addons = addons();

		foreach($products as $p) {
			if($productUri == str::slug($p->name())) {
				$id = $p->product_id()->value();
				$category = $p->category() == 'Bracelet' ? 'Bracelet' : $p->category().' Charms';
				$header = brick('div', brick('h1', $p->name()).brick('div', brick('strong', 'Category: ').brick('a', $category, ['href'=>url('products/'.strtolower($p->category()))])));

				$featuredImage = $this->image($p->featured_image())->url();
				$images = brick('img', false, ['src'=>$featuredImage]);
				if($p->images()->isNotEmpty()) {
					foreach($p->images()->yaml() as $img)
						$images.= brick('img', false, ['src'=>$this->image($img)->url()]);
				}

				$selected_birthstone = $addons[$id]['birthstone'] ?? '';

				$birthstones = '<option value="">Birthstone</option>';
				foreach(c::get('birthstones') as $b)
					$birthstones.= brick('option', $b, ['selected'=>($selected_birthstone == $b)]);

                $engraving_value = $addons[$id]['engraving'] ?? '';

				$addons = brick('div', 'Add-Ons', ['class'=>'label']).
						  brick('div', brick('select', $birthstones, ['id'=>'birthstone', 'name'=>'birthstone']), ['class'=>'select-container']).
						  brick('div', brick('div', 'Engraving', ['class'=>'select-container']).brick('textarea', $engraving_value, ['id'=>'engraving', 'name'=>'engraving']), ['class'=>'engraving-container'.r($engraving_value, ' open')]).
						  brick('p', 'Quantity of birthstones are equivalent to the quantity you select for this product.').
						  brick('p', 'The engraving text you add will be applied to all the quantities you have selected for this product.');

			  	$qty = $cart[$id] ?? 0;
				$qtyOptions = '<option value="">Quantity</option>';
				for($i=1; $i<=$p->quantity()->value(); $i++)
					$qtyOptions.= brick('option', $i, ['value'=>$i, 'selected'=>($qty == $i)]);

                $price = $p->price()->value();

				if($qty) {
	                $birthstone_price = $selected_birthstone ? site()->birthstone_price()->value() : 0;
	                $engraving_price = $engraving_value ? site()->engraving_price()->value() : 0;

					$price = ($price + $birthstone_price + $engraving_price) * $qty;
				}

				$content = brick('div',
						   brick('div', brick('div', $images), ['class'=>'photo-container', 'style'=>'background-image: url('.$featuredImage.')']).
						   brick('div', $addons, ['class'=>'add-ons'])).
						   brick('div',
						   brick('div', 'Review: '.site()->getRatings(), ['class'=>'reviews-container']).brick('a', 'Write a review').
						   brick('div', brick('select', $qtyOptions), ['class'=>'select-container']).
						   brick('div', 'Sub Total', ['class'=>'subtotal-label']).brick('div', '$'.priceFormat($price), ['id'=>'subtotal', 'class'=>'price']).
						   brick('button', r(in_array($id, $cartIds), 'Update', 'Add to').' bag', ['class'=>'single add-bag'.r(in_array($id, $cartIds), ' update'), 'href'=>'#', 'product-id'=>$id]));
			}
		}

		return $header.brick('form', $content, ['action'=>'/add-bag-process', 'class'=>'product-details', 'method'=>'POST']).brick('div', brick('div', $p->text()->kt()), ['class'=>'product-description']);
	}

}