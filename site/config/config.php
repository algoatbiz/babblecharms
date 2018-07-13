<?php

require_once(kirby()->roots()->index() . DS . 'bootstrap.php');

c::set('license', EnvHelper::env('KIRBY_LICENSE', null));
c::set('google-api-key', EnvHelper::env('GOOGLE_API_KEY', null));

c::set('cache', false);
c::set('cache.driver', 'memcached');

c::set('languages', [
    [
      'code'    => 'en',
      'name'    => 'English',
      'locale'  => 'en_US',
      'default' => true,
      'url'     => '/'
    ],
]);

// c::set('autoid.name', 'product_id');
// c::set('autoid.type', 'hash');

c::set('kirbytext.snippets.pre', [
  'domain' => url()
]);

c::set('kirbytext.snippets.post',array(
  '{' => '(',
  '}' => ')'
));

c::set('language.detect', true);

if(function_exists('panel')) { c::set('MinifyHTML', false); }

c::set('timezone', 'America/New_York');

c::set('panel.stylesheet', 'assets/css/panel.css');

c::set('routes', array(
  [
    'pattern' => 'products/(:any)',
    'method' => 'GET',
    'action' => function($category) {
      $data = [
        'category' => $category
      ];
      site()->visit('products');
      return ['products', $data];
    }
  ]
));