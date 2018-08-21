<?php

return [
	'title' => 'These products are running low in stock',
	'html'  => function() {

		$css = brick('style',
			'#low-stock-widget .dashboard-item > * {
				display: inline-block;
				padding: .532rem .75rem;
			}
			#low-stock-widget .dashboard-item > *:first-child {
				width: 80%;
				border-right: 1px solid #ddd;
			}
			#low-stock-widget .dashboard-item > *:last-child {
				width: 20%;
			}
			#low-stock-widget .dashboard-item:first-child {
				font-weight: bold;
			}'
		);

		$products = brick('li', brick('span', 'Product Name').brick('span', 'Quantity'), ['class'=>'dashboard-item']);

		foreach(site()->productsPage()->products()->toStructure() as $product) {
			$qty = $product->quantity()->value();

			if($qty <= site()->notify_limit()->value())
				$products.= brick('li', brick('a', $product->name(), ['href'=>purl('pages/products/field/products/structure/'.$product->id().'/update')]).brick('span', $qty), ['class'=>'dashboard-item']);
		}

		return $css.brick('div', brick('ul', $products, ['class'=>'dashboard-items']), ['class'=>'dashboard-box']);

	}
];