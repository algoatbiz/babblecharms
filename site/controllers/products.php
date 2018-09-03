<?php

return function($site, $pages, $page, $args = false) {

	$category = $args ? $args['category'] : false;
	$product = $args && isset($args['product']) ? $args['product'] : false;
	$hero = !$product ? $page->buildHero() : '';

	$productNavigation = $product ? '' : $page->productNavigation($category);
	$content = $product ? $page->buildProduct($product) : $page->categoryContent($category);
	$productList = $product ? brick('h2', 'Related Products').$site->productList($category, 4) : $site->productList($category);
	$otherProductList = $product ? brick('h2', 'Goes Well With These Products').$site->productList($category, 4) : '';

	return compact('hero', 'productNavigation', 'content', 'productList', 'otherProductList');

};