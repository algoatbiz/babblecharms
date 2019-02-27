<?php

return function($site, $pages, $page, $args) {

	$uri = $args['uri'] ?? false;

	$content = $page->buildMiddleContent($uri);

	return compact('content');

};