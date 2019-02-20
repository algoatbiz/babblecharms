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

			$message = $form->errorMessages() ?: 'Your account has been created!';

			return response::json(compact('message', 'errors'), (count($errors) ? 400 : 200));

		}
	]
]);