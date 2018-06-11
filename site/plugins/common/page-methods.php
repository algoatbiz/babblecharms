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

page::$methods['allProducts'] = function($site) {

	return $site->buildProductList($site->index()->filterBy('template', 'product')->visible());

};

page::$methods['products'] = function($page) {

	return site()->buildProductList($page->children()->visible());

};

page::$methods['buildProductList'] = function($site, $products) {

	$content = '';
	foreach($products as $product) {
		$image = brick('a', brick('img', false, ['src'=>$product->file($product->primary_image())->url(), 'alt'=>$product->title()]), ['href'=>$product->url(), ['class'=>'product-image']]);
		$details = brick('h3', brick('a', $product->title(), ['href'=>$product->url()]));
		$details.= $product->short_description()->kt();
		$details.= brick('div', '$'.$product->price(), ['class'=>'price']);
		$details.= $site->getRatings();
		$details.= brick('a', 'Add to bag', ['href'=>'#', 'class'=>'button small add-bag']);
		$content.= brick('div', $image.brick('div', $details, ['class'=>'details']), ['class'=>'product-item']);
	}

	return $content;

};

page::$methods['getRatings'] = function($site) {

	$rating = 4;
	$stars = '';
	for($i=0; $i<5; $i++)
		$stars.= brick('span', false, ['class'=>r($i<$rating, 'full')]);

	return brick('div', $stars, ['class'=>'ratings']);

};