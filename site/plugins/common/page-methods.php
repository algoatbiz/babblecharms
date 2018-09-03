<?php

page::$methods['extraCSS'] = function($page) {

	if(!isset($page->css))
		return false;

	return css($page->css);

};

page::$methods['extraJS'] = function($page) {

	if(!isset($page->js))
		return false;

	return js($page->js);

};

page::$methods['ancestor'] = function($page) {

	if($page->parents()->last())
		return $page->parents()->last();

	return $page;

};

page::$methods['menuPages'] = function($page)
{

	return $page->children()->visible()->filterBy('template', 'not in', ['contact', 'post', 'landing']);

};

page::$methods['hasMenuPages'] = function($page)
{

	return ($page->menuPages()->count() > 0) ? true : false;

};

page::$methods['menuTitle'] = function($page) {

	if($page->content()->menu_title()->isEmpty())
		return $page->content()->title()->value();

	return $page->content()->menu_title()->value();

};

page::$methods['productsPage'] = function($site) {

	return $site->index()->filterBy('template', 'products')->first();

};

page::$methods['productList'] = function($site, $category = false, $limit = false) {

	$products = $site->productsPage()->products()->toStructure();

	if($limit)
		$products = $products->limit($limit);

	if($category) {
		$categoryProducts = new Structure();

		foreach($products as $key => $product) {
			if(strtolower($product->category()) == $category)
				$categoryProducts->append($key, $product);
		}

		$products = $categoryProducts;
	}

	return $site->buildProductList($products);

};

page::$methods['productCategories'] = function($site, $menu = false, $template = 'products') {

	$categories = $site->productsPage()->category_descriptions()->toStructure();

	if($menu) {
		$categoriesForMenu = [];
		foreach($categories as $c) {
			$category = $c->category();
			$categoriesForMenu[] = [
				'name' => r($category == 'Bracelet', $category.'s', $category).r($category != 'Bracelet', ' Charms'),
				'link' => url(r($template == 'gallery', 'photo-gallery', 'products').'/'.strtolower($category)),
				'uri' => strtolower($category),
				'image' => $site->productsPage()->image($c->featured_image())->url()
			];
		}

		return $categoriesForMenu;
	}

	return $categories;

};

page::$methods['buildProductList'] = function($site, $products) {

	$page = $site->productsPage();

	$content = '';
	foreach($products = $products->paginate(16) as $product) {
		$productUrl = url('products/'.strtolower($product->category()).'/'.str::slug($product->name()));
		$image = brick('a', brick('img', false, ['src'=>$page->file($product->featured_image())->url(), 'alt'=>$product->name()]), ['href'=>$productUrl, ['class'=>'product-image']]);
		$details = brick('h3', brick('a', $product->name(), ['href'=>$productUrl]));
		$details.= $product->short_description()->kt();
		$details.= brick('div', '$'.$product->price(), ['class'=>'price']);
		$details.= $site->getRatings();
		$details.= brick('a', 'Add to bag', ['href'=>'#', 'class'=>'button small add-bag']);
		$content.= brick('div', $image.brick('div', $details, ['class'=>'details']), ['class'=>'product-item '.strtolower($product->category())]);
	}

	return $content.$site->pagination($products);

};

page::$methods['pagination'] = function($site, $products) {

	$pagination = $products->pagination();

	$pages = '';
	foreach($pagination->range(3) as $p) {
		$pages.= brick('li', brick('a', $p, ['href' => $pagination->pageURL($p), 'class'=>r($pagination->page() == $p, 'active')]));
	}

	$content = brick('a', '', ['href'=>$pagination->prevPageUrl(), 'class'=>'prev'.r(!$pagination->hasPrevPage(), ' disabled')]);
	$content.= brick('ul', $pages, ['class'=>'pages']);
	$content.= brick('a', '', ['href'=>$pagination->nextPageUrl(), 'class'=>'next'.r(!$pagination->hasNextPage(), ' disabled')]);

	return r($pagination->hasPrevPage() || $pagination->hasNextPage(), brick('div', $content, ['class'=>'pagination']));

};

page::$methods['getRatings'] = function($site) {

	$rating = 4;
	$stars = '';
	for($i=0; $i<5; $i++)
		$stars.= brick('span', false, ['class'=>r($i<$rating, 'full')]);

	return brick('div', $stars, ['class'=>'ratings']);

};