<?php

$site = site();

kirby()->routes([
	[
		'pattern' => 'coming-soon',
		'method'  => 'GET',
		'action'  => function() use($site) {

			$content = '<!doctype html>
			<html lang="en">
				<head>
					<title>Babble Charms | Coming Soon</title>
					'.css([
						'assets/css/styles.css',
						'https://fonts.googleapis.com/css?family=Oswald:300,500,700|Raleway:300,400,600,700|Roboto:300,400,500'
					]).'
				</head>
				<body id="coming-soon">
					<div class="container">
						<div>
							'.kirbytag(['image'=>'/assets/images/babble-charms-logo.png', 'alt'=>'Babble Charms Logo']).'
							<div>'.$site->coming_text()->kt().'</div>
							<form action="form-process">
								<h2>Sign Up For Updates</h2>
								<input type="email" name="email" placeholder="Enter your email" aria-required="true">
								<div class="message"></div>
								<button>Submit</button>
							</form>
							'.kirbytag(['image'=>$site->image($site->bottom_image())->url(), 'alt'=>'Babble Charms Logo']).'
						</div>
					</div>

					'.js([
						'https://cdnjs.cloudflare.com/ajax/libs/axios/0.16.2/axios.min.js',
						'https://cdnjs.cloudflare.com/ajax/libs/mobile-detect/1.3.7/mobile-detect.min.js',
						'assets/js/site.js'
					]).'
				</body>
			</html>';

			echo $content;

		}
	]
]);

kirby()->routes([
	[
		'pattern' => 'form-process',
		'method'  => 'POST',
		'action'  => function() use($site) {
			$subscribers = site()->find('subscribers');
			$existing = $subscribers->content()->get('subscribers')->yaml();

			if(!$subscribers->subscribers()->toStructure()->findBy('email', get('email'))) {
				$existing[] = ['email' => get('email'), 'date_created'=>date('Y-m-d H:i:s')];
				$subscribers->update([
					'subscribers' => yaml::encode($existing)
				]);
				$message = 'You are now subscribed!';
			}
			else {
				$message = 'This email is already subscribed.';
			}
			return response::json(['message'=>$message]);
		}
	]
]);