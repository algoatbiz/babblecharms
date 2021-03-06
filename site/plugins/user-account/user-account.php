<?php

kirby()->routes([
	[
		'pattern' => 'signup-form-process',
		'method' => 'POST',
		'action' => function() {

			$table = 'users';
			$required = ['first', 'last', 'dob', 'email', 'password', 'confirm_password', 'accept_pp'];
			$noLog = ['password', 'confirm_password'];
			$noInserts = ['form_id', 'confirm_password', 'accept_pp'];

			$form = new ProcessForm(get('form_id'), compact('table', 'required', 'noInserts', 'noLog'));

			$errors = $form->showErrors();

			$message = count($errors) ? ($form->errorMessages() ?: '') : 'Your account has been created!';

			$id = $form->getDbId();

			if($id && empty($errors))
				UserAccount::login($id);

			return response::json(compact('message', 'errors'), (count($errors) ? 400 : 200));

		}
	],
	[
		'pattern' => 'login-process',
		'method' => 'POST',
		'action' => function() {

			$email = get('email');

			$password = get('password');

			$message = '';

			$errors = [];

			foreach(['email', 'password'] as $field)
				if(get($field) === '')
					$errors[$field] = true;

			if($user = db::select('users', 'id, email, password', ['email'=>$email])->first()) {
				if($hasUser = Password::match($password, $user->password))
					UserAccount::login($user->id);
				else if($password && !$hasUser) {
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
	],
	[
		'pattern' => 'edit-account-process',
		'method' => 'POST',
		'action' => function() {

			$required = ['last', 'first', 'dob', 'email'];

			$errors = [];
			$message = '';

			$key = array_keys(r::get())[0];
			$value = get($key);

			if(in_array($key, $required) && $value == '')
				$errors[$key] = true;
			if(get('email') || get('password')) {
				$validate = validateUserData(r::get());

				if(count($validate['errors'])) {
					$errors[$key] = true;
					$message = $validate['errorMessages'];
				}
			}

			if(empty($errors))
				db::update('users', [$key=>r($key == 'password', Password::hash($value), $value), 'updated_at'=>timestamp()], ['id'=>s::get('user_id')]);

			return response::json(compact('message', 'errors'), (count($errors) ? 400 : 200));

		}
	],
	[
		'pattern' => 'account',
		'action' => function() {

			return s::get('user_id') ? site()->visit('account') : go('login');

		}
	],
	[
		'pattern' => 'account/purchases',
		'action' => function() {

			return s::get('user_id') ? site()->visit('account') : go('login');

		}
	],
	[
		'pattern' => 'logout',
		'action' => function() {

			return UserAccount::logout();

		}
	]
]);

class UserAccount {

	public static function login($id) {

		db::update('users', ['last_login'=>timestamp()], ['id'=>$id]);

		s::start();

		s::set('user_id', $id);

	}

	public static function logout() {

		s::destroy();

		s::remove('user_id');

		go();

	}

}