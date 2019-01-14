<?php

class ProcessForm {

	public function __construct($id, $options) {

        $this->id = $id;

        $this->data = r::get();

        $this->options = $options;

        $this->errorFields = $this->missing($this->data, array_values(a::get($options, 'required', [])));

        $this->db = new Database([
            'host'     => EnvHelper::env('DB_HOST', 'localhost'),
            'database' => EnvHelper::env('DB_NAME'),
            'user'     => EnvHelper::env('DB_USER'),
            'password' => EnvHelper::env('DB_PASS')
        ]);

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

                foreach ($array[$r] as $value)
                    $hasValues |= $value !== '';

                if (!$hasValues)
                    $missing[] = $r;
            }
        }

        return $missing;

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

            $table = $this->db->table('form_log');

            if(!$id = $table->insert($formData))
                throw new Exception($this->db->lastError());

        } catch (Exception $e) {

            exit(var_dump($e));

        }

	}

	protected function saveToDb() {

		$table = $this->db->table(a::get($this->options, 'table', 'contact_data'));

		$insert_array['referer'] = r::referer();

		foreach($this->data as $key => $value) {
			if (str::startsWith($key, '_'))
				continue;

			if (in_array($key, a::get($this->options, 'noInserts', [])))
				continue;

			$insert_array[$key] = $value;
		}

        if(s::get('customer_id', false)) {
            $insert_array['updated_at'] = date('Y-m-d H:i:s');
            $table->where('id', '=', s::get('customer_id'))->update($insert_array);
        }

		else if(!$id = $table->insert($insert_array))
		    throw new Exception($this->db->lastError());

        if(isset($id))
            s::set('customer_id', $id);

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

}