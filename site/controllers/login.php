<?php

return function($site, $pages, $page) {

	$site_bg = $site->file($site->background())->url();

	$login_bg = $page->login_bg()->isNotEmpty() ? $page->file($page->login_bg())->url() : $site_bg;

	$signup_bg = $page->signup_bg()->isNotEmpty() ? $page->file($page->signup_bg())->url() : $site_bg;

	return compact('login_bg', 'signup_bg');

};