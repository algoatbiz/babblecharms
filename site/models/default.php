<?php

class DefaultPage extends Page {

	public function buildHero() {
		$options = [
			'id' => 'hero',
			'class' => r(in_array($this->template(), ['contact', 'gallery', 'products']), 'short')
		];
		$content = brick('div', false, ['style'=>'background-image: url("'.$this->bg().'")']);

		$text = in_array($this->template(), ['product-category', 'product']) ? $this->ancestor() : $this;
		$content.= brick('div', brick('div', $text->text()->kt()), ['class'=>'container']);
		return brick('section', $content, $options);
	}

	public function bg() {
		$ancestor = $this->ancestor();
		if($this->background()->isNotEmpty()) {
			return $this->file($this->background())->url();
		}
		else if($ancestor->background()->isNotEmpty()) {
			return $ancestor->file($ancestor->background())->url();
		}
		else {
			return site()->file(site()->background())->url();
		}
	}

	public function buildForm() {
		if($this->template() == 'contact') {
			$half = brick('div', FormBuild::text('first_name', 'First Name').FormBuild::text('last_name', 'Last Name').FormBuild::text('email', 'Email', null, false, 'email'), ['class'=>'half']);
			$half.= brick('div', FormBuild::text('phone', 'Phone').FormBuild::textarea('message', 'Message'), ['class'=>'half']);
			$content = brick('div', $half);
		}
		else {
			$content = brick('div', site()->form_title(), ['class'=>'title']);
			$content.= FormBuild::text('first_name', 'First Name');
			$content.= FormBuild::text('last_name', 'Last Name');
			$content.= FormBuild::text('email', 'Email', null, false, 'email');
			$content.= FormBuild::text('phone', 'Phone');
		}

		$content.= brick('button', 'Submit');

		return brick('form', $content);
	}

}