<?php

return function($site, $pages, $page, $args = false) {

	$category = $args['category'] ?? false;
	$product = $args['product'] ?? false;
	$search = $args['keyword'] ?? false;

	$productList = $product ? brick('h2', 'Related Products').$site->productList($category, 4, $product) : $site->productList($category, false, false, $search);

	$heroText = $search ? brick('h1', r(!$productList, 'No ').'Results for: "'.$search.'"') : false;
	$hero = !$product ? $page->buildHero($heroText) : '';

	$productNavigation = $product || $search ? '' : $page->productNavigation($category);
	$content = $product ? $page->buildProduct($product) : ($search ? '' : $page->categoryContent($category));
	$otherProductList = $product ? brick('h2', 'Goes Well With These Products').$site->productList($category, 4, $product) : '';

	return compact('hero', 'productNavigation', 'content', 'productList', 'otherProductList', 'search');

};