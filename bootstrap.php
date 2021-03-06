<?php

require_once(__DIR__ . '/vendor/autoload.php');

if(file_exists(__DIR__ . '/.env')):
  $dotenv = new \Dotenv\Dotenv(__DIR__);
  $dotenv->load();
endif;

class EnvHelper {
    /**
     * Gets the value of an environment variable. Supports boolean, empty and null.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public static function env($key, $default = null)
    {
        $value = getenv($key);
        if ($value === false) {
            return static::value($default);
        }
        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return;
        }
        if (strlen($value) > 1 && static::starts_with($value, '"') && static::ends_with($value, '"')) {
            return substr($value, 1, -1);
        }
        return $value;
    }
    /**
     * Return the default value of the given value.
     *
     * @param  mixed  $value
     * @return mixed
     */
    public static function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
    /**
     * Determine if a given string starts with a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
     * @return bool
     */
    public static function starts_with($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ($needle != '' && substr($haystack, 0, strlen($needle)) === (string) $needle) {
                return true;
            }
        }
        return false;
    }
    /**
     * Determine if a given string ends with a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
     * @return bool
     */
    public static function ends_with($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if (substr($haystack, -strlen($needle)) === (string) $needle) {
                return true;
            }
        }
        return false;
    }
};

if(!class_exists('email')):
  $classes = ['obj', 'a', 'v', 'remote', 'email'];
  foreach($classes as $c)
    require_once(__DIR__ . '/kirby/vendor/getkirby/toolkit/lib/'.$c.'.php');
endif;

/**
 * Mailgun mail driver
 */
email::$services['mailgun_html'] = function($email) {

  if(empty($email->options['key']))    throw new Error('Missing Mailgun API key');
  if(empty($email->options['domain'])) throw new Error('Missing Mailgun API domain');

  $url  = 'https://api.mailgun.net/v3/' . $email->options['domain'] . '/messages';
  $auth = base64_encode('api:' . $email->options['key']);

  $headers = array(
    'Accept: application/json',
    'Authorization: Basic ' . $auth
  );

  $data = array(
    'from'       => $email->from,
    'to'         => $email->to,
    'subject'    => $email->subject,
    'html'       => $email->body,
    'h:Reply-To' => $email->replyTo,
  );

  if(isset($email->options['bcc']))
    $data['bcc'] = $email->options['bcc'];

  $email->response = remote::post($url, array(
    'data'    => $data,
    'headers' => $headers
  ));

  if($email->response->code()!=200) {
    throw new Error('The mail could not be sent!');
  }

};

/**
 * Mailgun mail driver
 */
email::$services['mailgun_html_urgent'] = function($email) {

  if(empty($email->options['key']))    throw new Error('Missing Mailgun API key');
  if(empty($email->options['domain'])) throw new Error('Missing Mailgun API domain');

  $url  = 'https://api.mailgun.net/v3/' . $email->options['domain'] . '/messages';
  $auth = base64_encode('api:' . $email->options['key']);

  $headers = array(
    'Accept: application/json',
    'Authorization: Basic ' . $auth
  );

  $data = array(
    'from'       => $email->from,
    'to'         => $email->to,
    'subject'    => $email->subject,
    'html'       => $email->body,
    'h:Reply-To' => $email->replyTo,
    'h:X-Priority' => '1 (Highest)',
    'h:X-MSMail-Priority' => 'High',
    'h:Importance' => 'High'
  );

  if(isset($email->options['bcc']))
    $data['bcc'] = $email->options['bcc'];

  $email->response = remote::post($url, array(
    'data'    => $data,
    'headers' => $headers
  ));

  if($email->response->code()!=200) {
    throw new Error('The mail could not be sent!');
  }

};

function mailgun($urgent=false) {

    $service = ($urgent) ? 'mailgun_html_urgent' : 'mailgun_html';

    return new Email([
        'service' => $service,
        'options' => [
          'domain' => EnvHelper::env('MAILGUN_DOMAIN'),
          'key' => EnvHelper::env('MAILGUN_KEY')
        ]
    ]);

}