<?php

require_once(kirby()->roots()->index() . DS . 'bootstrap.php');

c::set('license', EnvHelper::env('KIRBY_LICENSE', null));
c::set('google-api-key', EnvHelper::env('GOOGLE_API_KEY', null));

c::set('cache', false);
c::set('cache.driver', 'memcached');

c::set('cache.ignore', []);

c::set('db.host', EnvHelper::env('DB_HOST', 'localhost'));
c::set('db.user', EnvHelper::env('DB_USER'));
c::set('db.password', EnvHelper::env('DB_PASS'));
c::set('db.name', EnvHelper::env('DB_NAME'));

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

c::set('kirbytext.snippets.post', [
  '{' => '(',
  '}' => ')'
]);

c::set('language.detect', true);

if(function_exists('panel')) { c::set('MinifyHTML', false); }

c::set('timezone', 'America/Denver');

c::set('panel.stylesheet', 'assets/css/panel.css');

c::set('square-application-id', EnvHelper::env('SQUARE_APPLICATION_ID', null));

c::set('states', [
  'AL', 'AK', 'AZ',
  'AR', 'CA', 'CO',
  'CT', 'DE', 'FL',
  'GA', 'HI', 'ID',
  'IL', 'IN', 'IA',
  'KS', 'KY', 'LA',
  'ME', 'MD', 'MA',
  'MI', 'MN', 'MS',
  'MO', 'MT', 'NE',
  'NV', 'NH', 'NJ',
  'NM', 'NY', 'NC',
  'ND', 'OH', 'OK',
  'OR', 'PA', 'RI',
  'SC', 'SD', 'TN',
  'TX', 'UT', 'VT',
  'VA', 'WA', 'WV',
  'WI', 'WY', 'AS',
  'DC', 'FM', 'GU',
  'MH', 'MP', 'PW',
  'PR', 'VI',
  'Outside of US'
]);

c::set('shipping_fee', 3.99);

c::set('sales_tax', .068);

c::set('routes', [
  [
    'pattern' => 'products/(:any)',
    'method' => 'GET',
    'action' => function($category) {
      site()->visit('products');
      return ['products', ['category' => $category]];
    }
  ],
  [
    'pattern' => 'products/(:any)/(:any)',
    'method' => 'GET',
    'action' => function($category, $product) {
      $data = [
        'category' => $category,
        'product' => $product
      ];
      site()->visit('products');
      return ['products', $data];
    }
  ],
  [
    'pattern' => 'search-results',
    'method' => 'GET',
    'action' => function() {
      site()->visit('products');
      return ['products', ['keyword'=>get('search-input')]];
    }
  ],
  [
    'pattern' => 'photo-gallery/(:any)',
    'method' => 'GET',
    'action' => function($category) {
      site()->visit('photo-gallery');
      return ['photo-gallery', ['category' => $category]];
    }
  ],
  [
    'pattern' => 'photo-gallery',
    'method' => 'GET',
    'action' => function() {
      $categories = site()->productsPage()->categories()->split(',');
      return go('photo-gallery/'.strtolower($categories[0]));
    }
  ],
  [
    'pattern' => 'api/categories.json',
    'method' => 'GET',
    'action' => function() {
      $data = [];
      foreach(site()->productsPage()->categories()->split(',') as $category)
        $data[strtolower($category)] = $category;
      
      return response::json($data);
    }
  ],
  [
    'pattern' => 'subscribers',
    'action' => function() {
      go('error');
    }
  ]
]);