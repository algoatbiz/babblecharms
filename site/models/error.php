<?php

class ErrorPage extends DefaultPage {

	public function buildHero($search = false) {
		$content = brick('div', false, ['style'=>'background-image: url("'.$this->bg().'")']).
				   brick('div', brick('div', brick('h1', 'Page could not be found')), ['class'=>'container']);

		return brick('section', $content, ['id'=>'hero', 'class'=>'shorter']);
	}

}