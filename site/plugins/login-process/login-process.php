<?php

kirby()->routes([
	[
		'pattern' => 'login-process',
		'method' => 'POST',
		'action' => function() {

			$email = get('email');

			$message = '';

			$errors = [];

			foreach(['email', 'password'] as $field)
				if(get($field) === '')
					$errors[$field] = true;

			if($user = db::select('users', 'id, email, password', ['email'=>$email])->first()) {
				if(Password::match(get('password'), $user->password)) {
					db::update('users', ['last_login'=>date('Y-m-d H:i:s')], ['id'=>$user->id]);
					// redirect, set session
				}
				else {
					$errors['password'] = true;
					$message = 'Password is incorrect';
				}
			}
			else if($email && !$user) {
				$errors['email'] = true;
				$message = 'This email does not exist';
			}

			return response::json(compact('message', 'errors'), (count($errors) ? 400 : 200));

		}
	]
]);