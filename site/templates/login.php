<?php snippet('header') ?>
<main id="login-container">
	<div style="background-image: url('<?= $login_bg ?>')" class="half-container">
		<div>
			<?= $page->login_text()->kt() ?>
			<form id="login-form" action="login-process" method="POST">
				<div id="form-message"></div>
				<input type="email" id="email" name="email" placeholder="Email">
				<input type="password" id="password" name="password" placeholder="Password">

				<a href="#">Forgot password?</a>

				<button class="big">Login</button>
			</form>
		</div>
	</div>

	<div style="background-image: url('<?= $signup_bg ?>')" class="half-container">
		<div>
			<?= $page->signup_text()->kt() ?>
			<p><a href="#" class="signup-link button big">Sign Up Now</a></p>
		</div>
	</div>
</main>
<?php snippet('footer') ?>