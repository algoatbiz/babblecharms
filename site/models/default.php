<?php

class DefaultPage extends Page {

	public function buildHero($search = false) {
		$tpl = $this->template();

		$options = [
			'id' => 'hero',
			'class' => r($search || $tpl == 'cart', 'shorter', r(in_array($tpl, ['contact', 'gallery', 'products']), 'short'))
		];

		$content = brick('div', false, ['style'=>'background-image: url("'.$this->bg().'")']);

		$text = $search ?: ($tpl == 'cart' ? brick('h1', 'Shopping Bag') : ($tpl == 'checkout' ? brick('h1', 'Checkout') : $this->text()->kt()));
		$content.= brick('div', brick('div', $text), ['class'=>'container']);

		return brick('section', $content, $options);
	}

	public function bg() {
		$ancestor = $this->ancestor();
		if($this->background()->isNotEmpty())
			return $this->file($this->background())->url();
		else if($ancestor->background()->isNotEmpty())
			return $ancestor->file($ancestor->background())->url();
		else
			return site()->file(site()->background())->url();
	}

	public function buildSections() {
		$content = '';

		foreach($this->sections()->toStructure() as $section) {
			$bg = $section->image();
			$bg = $bg->isNotEmpty() ? brick('div', '', ['style'=>'background-image: url('.$this->image($bg)->url().')']) : '';
			$content.= brick('section', brick('div', brick('div', $section->text()->kt()), ['class'=>'container']).$bg, ['class'=>r($bg, 'half')]);
		}

		return $content;
	}

	public function buildForm() {
		if($this->template() == 'contact') {
			$half = brick('div', FormBuild::text('first', 'First Name', true).FormBuild::text('last', 'Last Name', true).FormBuild::text('email', 'Email', true, 'email'), ['class'=>'half']);
			$half.= brick('div', FormBuild::text('phone', 'Phone').FormBuild::textarea('message', 'Message'), ['class'=>'half']);
			$content = brick('div', $half);
			$id = 'contact-form';
		}
		else {
			$content = brick('div', site()->form_title(), ['class'=>'title']).
					   FormBuild::text('first', 'First Name', true).
					   FormBuild::text('last', 'Last Name', true).
					   FormBuild::text('email', 'Email', true, 'email').
					   FormBuild::text('phone', 'Phone');
			$id = 'sign-up-form';
		}

		// $content.= brick('div', '', ['class'=>'g-recaptcha', 'data-sitekey'=>'6LeeIHcUAAAAAJcYq2yEyyBfnaou3sXGsBfSepKm']);
		$content.= brick('div', brick('button', 'Submit').brick('div', '', ['id'=>'form-message']), ['class'=>'button-message-container']);

		return brick('form', $content, ['id'=>$id, 'action'=>'contact-form-process', 'method'=>'POST']);
	}

	public function buildSteps($steps) {
		$stepLinks = '';
		$i = 0;
		foreach($steps as $step => $text)
			$stepLinks.= brick('a', $text, ['href'=>'#', 'class'=>r($i++ == 0, 'current'), 'data-step'=>$step]);

		return brick('div', $stepLinks, ['id'=>'steps']);
	}

	public function loading() {
		return brick('div', brick('div', brick('div').brick('div').brick('div').brick('div')), ['id'=>'loading']);
	}

	public function signupForm() {
		$content = brick('a', '', ['href'=>'#', 'class'=>'close']).
				   brick('img', '', ['src'=>url('assets/images/babble-charms-logo.png'), 'alt'=>'Babble Charms Logo']).
				   brick('div', 'Create An Account', ['class'=>'title']);

		$form = brick('div', '', ['id'=>'form-message']).
				FormBuild::text('first', 'First Name', true, 'text', false, false, null, true).
				FormBuild::text('last', 'Last Name', true, 'text', false, false, null, true).
				FormBuild::text('dob', 'Date of Birth', true, 'date', false, false, null, true).
				FormBuild::text('email', 'Email', true, 'email', false, false, null, true).
				FormBuild::text('password', 'Password', true, 'password', false, false, null, true).
				FormBuild::text('confirm_password', 'Confirm Password', true, 'password', false, false, null, true).
				FormBuild::yesno('nl_signup', 'Receive Updates and Special Offers').
				FormBuild::yesno('accept_pp', "I've read and accept Babble Charms' ".brick('a', 'Privacy Policy', ['href'=>url('privacy-policy')]), true).
				brick('button', 'Create An Account', ['class'=>'big']);

		$content.= brick('form', $form, ['id'=>'signup-form', 'action'=>'signup-form-process', 'method'=>'POST']);

		return brick('div', brick('div', $content), ['id'=>'popup-signup']);
	}

}