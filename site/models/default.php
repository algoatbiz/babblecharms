<?php

class DefaultPage extends Page {

	public function buildHero() {
		$options = [
			'id' => 'hero',
			'class' => r(in_array($this->template(), ['contact', 'gallery', 'products']), 'short')
		];
		$content = brick('div', false, ['style'=>'background-image: url("'.$this->bg().'")']);

		$text = $this->template() == 'cart' ? brick('h1', 'Shopping Bag') : ($this->template() == 'checkout' ? brick('h1', 'Checkout') : $this->text()->kt());
		$content.= brick('div', brick('div', $text), ['class'=>'container']);
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

	public function buildSections() {
		$content = '';

		foreach($this->sections()->toStructure() as $section)
			$content.= brick('section', brick('div', brick('div', $section->text()->kt()), ['class'=>'container']).brick('div', '', ['style'=>'background-image: url('.$this->image($section->image())->url().')']));

		return $content;
	}

	public function buildForm() {
		if($this->template() == 'contact') {
			$half = brick('div', FormBuild::text('first_name', 'First Name', true).FormBuild::text('last_name', 'Last Name', true).FormBuild::text('email', 'Email', true, 'email'), ['class'=>'half']);
			$half.= brick('div', FormBuild::text('phone', 'Phone').FormBuild::textarea('message', 'Message'), ['class'=>'half']);
			$content = brick('div', $half);
		}
		else {
			$content = brick('div', site()->form_title(), ['class'=>'title']);
			$content.= FormBuild::text('first_name', 'First Name', true);
			$content.= FormBuild::text('last_name', 'Last Name', true);
			$content.= FormBuild::text('email', 'Email', true, 'email');
			$content.= FormBuild::text('phone', 'Phone');
		}

		$content.= brick('button', 'Submit');

		return brick('form', $content);
	}

	public function buildSteps($steps) {
		$stepLinks = '';
		$i = 0;
		foreach($steps as $step => $text)
			$stepLinks.= brick('a', $text, ['href'=>'#', 'class'=>r($i++ == 0, 'current'), 'data-step'=>$step]);

		return brick('div', $stepLinks, ['id'=>'steps']);
	}

}