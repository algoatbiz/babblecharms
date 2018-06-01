<?php

page::$methods['extraCSS'] = function($page) {

	if(!isset($page->css))
		return false;

	return css($page->css);

};

page::$methods['extraJS'] = function($page) {

	if(!isset($page->js))
		return false;

	return js($page->js);

};

page::$methods['ancestor'] = function($page) {

	if($page->parents()->last())
		return $page->parents()->last();

	return $page;

};

page::$methods['menuPages'] = function($page)
{

	return $page->children()->visible()->filterBy('template', 'not in', ['contact', 'post', 'landing']);

};

page::$methods['hasMenuPages'] = function($page)
{

	return ($page->menuPages()->count() > 0) ? true : false;

};

page::$methods['menuTitle'] = function($page) {

	if($page->content()->menu_title()->isEmpty())
		return $page->content()->title()->value();

	return $page->content()->menu_title()->value();

};