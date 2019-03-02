<?php

class ProcessForm {

	public function __construct($id, $options) {

        $this->id = $id;

        $this->data = r::get();

        $this->options = $options;

        $this->db_id = 0;

        $this->errorFields = $this->missing($this->data, array_values(a::get($options, 'required', [])));

        $this->errorMessages = '';

        if($id == 'signup-form')
            $this->validateUserData();

        if(isset($this->data['credit_card']))
            $this->data['credit_card'] = substr($this->data['credit_card'], -4);

        if(r::method() == 'POST') {
            $this->logInfo();

            if(empty($this->errorFields))
                $this->saveToDb();
        }

	}

	protected function missing($array, $required = []) {

        $missing = [];

        foreach($required as $r) {
            if(!array_key_exists($r, $array) || ($array[$r] === '')) {
                $missing[] = $r;
            }
            elseif(is_array($array[$r])) {
                $hasValues = false;

                foreach($array[$r] as $value)
                    $hasValues |= $value !== '';

                if(!$hasValues)
                    $missing[] = $r;
            }
        }

        return $missing;

    }

    protected function validateUserData() {

        $errors = [];
        $password = $this->data['password'];

        $messages = [];

        if(!v::minLength($password, 8)) {
            $errors[] = 'password';
            $messages[] = 'Password must be at least 8 characters';
        }

        if(!v::match($password, '@[A-Z]@') || !v::match($password, '@[a-z]@') || !v::match($password, '@[0-9]@')) {
            $errors[] = 'password';
            $messages[] = 'Password must contain at least 1 number, uppercase and lowercase letters';
        }

        if(v::different($password, $this->data['confirm_password'])) {
            $errors[] = 'confirm_password';
            $messages[] = 'Passwords do not match';
        }

        if($this->data['accept_pp'] == 'No') {
            $errors[] = 'accept_pp';
            $messages[] = 'Please read and accept our Privacy Policy';
        }

        if(db::select('users', 'email', ['email'=>$this->data['email']])->first()) {
            $errors[] = 'email';
            $messages[] = 'This email already exists';
        }

        $this->errorFields = array_unique(array_merge($this->errorFields, $errors));

        $i = 0;
        foreach($messages as $m)
            $this->errorMessages.= r($i++, '<br>').$m;

    }

	protected function logInfo() {

		if(empty($this->data))
            return false;

        try {

            $logData = $this->data;

            foreach(a::get($this->options, 'noLog', []) as $noLog)
                unset($logData[$noLog]);

            if(count($this->errorFields))
                $errors = a::json($this->errorFields);

            // $browserInfo = browserInfo();

            $formData = [
                'form_id' => $this->id,
                'form_data' => a::json($logData),
                'spam_timer' => r::data('spam_timer'),
                'errors' => $errors ?? '',
                'referer' => r::referer(),
                'useragent' => server::get('HTTP_USER_AGENT'),
                // 'mobile' => ($browserInfo->mobile) ? 1 : 0,
                // 'browser' => $browserInfo->browser,
                // 'platform' => $browserInfo->platform,
                // 'device' => $browserInfo->device,
                'successful' => r(empty($this->errorFields),1,0),
            ];

            if(!$id = db::insert('form_log', $formData))
                throw new Exception(database::lastError());

            $this->db_id = $id;

        } catch (Exception $e) {

            exit(var_dump($e));

        }

	}

	protected function saveToDb() {

		$insert_array['referer'] = r::referer();

		foreach($this->data as $key => $value) {
			if(str::startsWith($key, '_'))
				continue;

			if(in_array($key, a::get($this->options, 'noInserts', [])))
				continue;

            if($key == 'password')
                $value = Password::hash($value);

			$insert_array[$key] = $value;
		}

		if(!$id = db::insert(a::get($this->options, 'table', 'contact_data'), $insert_array))
		    throw new Exception(database::lastError());

		return [
			'success' => true,
			'message' => 'Inserted to database successfully.'
		];
	}

    public function showErrors() {

        $errors = [];

        foreach($this->errorFields as $f)
            $errors[$f] = true;

        return $errors;

    }

    public function errorMessages() {

        return $this->errorMessages;

    }

    public function getDbId() {

        return $this->db_id;

    }

}