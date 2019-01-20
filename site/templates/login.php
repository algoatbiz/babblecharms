<?php snippet('header') ?>
<main id="login-container">
	<div style="background-image: url('<?= $login_bg ?>')" class="half-container">
		<div>
			<?= $page->login_text()->kt() ?>
			<form action="/login">
				<input type="text" name="email" type="email" placeholder="Email">
				<input type="password" name="password" type="password" placeholder="Password">

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