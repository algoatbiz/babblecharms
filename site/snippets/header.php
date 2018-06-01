<!doctype html>
<html lang="en">
	<head>

		<?= r($page->noindex()->isTrue(), '<meta name="robots" content="noindex, nofollow">') ?>
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

	<body id="<?= $page->slug() ?>" class="<?= $page->template() ?>">

		<header>
			<div id="topbar">
				<div class="container">
					<div></div>
					<a href="<?= url() ?>" class="logo"><img src="/assets/images/build-a-bracelet-logo.png" alt="Build A Bracelet Logo"></a>
					<div></div>
				</div>
			</div>
			<?php snippet('menu') ?>
		</header>
