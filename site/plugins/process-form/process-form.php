<?php

class ProcessForm {

	public function __construct($id, $options) {

        $this->id = $id;

        $this->data = r::get();

        $this->options = $options;

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

        $validate = validateUserData($this->data);

        $this->errorFields = array_unique(array_merge($this->errorFields, $validate['errors']));

        $this->errorMessages = $validate['errorMessages'];

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

        $this->db_id = $id;

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