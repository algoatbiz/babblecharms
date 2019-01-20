<?php c::get('live') ? redirect::to('/coming-soon') : '' ?>
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

		<script src='https://www.google.com/recaptcha/api.js'></script>

	</head>

	<body id="<?= $product ?? $page->slug() ?>" class="<?= isset($product) ? 'product' : $page->template() ?>">

		<header>
			<div id="topbar">
				<div class="container">
					<div>
						<!-- <div class="welcome">Welcome User</div> -->
						<a href="<?= url('login') ?>">Login</a>
						<span class="divider"></span>
						<!-- <div class="account">Account</div> -->
						<a href="#" class="signup-link">Sign Up</a>
					</div>
					<a href="<?= url() ?>" class="logo"><img src="<?= url('assets/images/babble-charms-logo.png') ?>" alt="Babble Charms Logo"></a>
					<div>
						<form id="search" action="/search-results"><input type="text" id="search-input" name="search-input" placeholder="Search" autocomplete="off"></form>
						<span class="divider"></span>
						<a href="<?= url('cart') ?>" id="shopping-bag">Shopping Bag (<span><?= getCartCount() ?></span>)</a>
					</div>
				</div>
			</div>
			<?php snippet('menu') ?>
		</header>
