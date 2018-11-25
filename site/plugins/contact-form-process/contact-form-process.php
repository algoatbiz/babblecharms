<?php

kirby()->routes([
	[
		'pattern' => 'contact-form-process',
		'method' => 'POST',
		'action' => function() {

			$form_id = get('form_id');
			$required = ['first', 'last', 'email'];

			$form = new ProcessForm($form_id, compact('required'));

			$errors = $form->showErrors();
			$message = empty($errors) ? 'Form is submitted. A representative will contact you shortly.' : '';

			return response::json(compact('message', 'errors'), (count($errors) ? 400 : 200));

		}
	]
]);