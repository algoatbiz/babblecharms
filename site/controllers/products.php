<?php

return function($site, $pages, $page, $args = false) {

	$category = $args ? $args['category'] : false;

	return compact('category');

};