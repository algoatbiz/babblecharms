<!doctype html>
<html lang="en">
	<head>

		<?= r($page->noindex()->isTrue(), '<meta name="robots" content="noindex, nofollow">') ?>
		<meta name="robots" content="noindex, nofollow">
		<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0,maximum-scale=1.0" />

		<link rel="alternate" href="<?= url() ?>" hreflang="en-us" />

		<?= css([
			'assets/css/styles.css',
			'assets/css/header.css',
			'assets/css/footer.css',
			'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
			'https://fonts.googleapis.com/css?family=Oswald:300,500,700|Raleway:300,400,600,700|Roboto:300,400,500'
		]) ?>
		<?= $page->extraCSS() ?>
		<?= css('@auto') ?>

	</head>

	<body id="<?= $product ?? $page->slug() ?>" class="<?= isset($product) ? 'product' : $page->template() ?>">

		<header>
			<div id="topbar">
				<div class="container">
					<div>
						<div class="welcome">Welcome User</div>
						<span class="divider"></span>
						<div class="account">Account</div>
					</div>
					<a href="<?= url() ?>" class="logo"><img src="/assets/images/babble-charms-logo.png" alt="Babble Charms Logo"></a>
					<div>
						<div class="search">Search</div>
						<span class="divider"></span>
						<a href="#" class="shopping-bag">Shopping Bag (0)</a>
					</div>
				</div>
			</div>
			<?php snippet('menu') ?>
		</header>
