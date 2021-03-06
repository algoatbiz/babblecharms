<?php

class HomePage extends DefaultPage {

	public function buildSlider() {
		$sliders = $this->sliders()->toStructure();

		$content = '';
		foreach($sliders as $slider) {
			$options = [
				'class' => 'slide',
				'style' => 'background-image: url("'.$this->file($slider->slider_image())->url().'")'
			];

			$text = brick('div', $slider->slider_title(), ['class'=>'title']).$slider->slider_text()->kt();
			$text = brick('div', brick('div', $text), ['class'=>'container']);
			$content.= brick('div', $text, $options);
		}

		return brick('section', $content, ['id'=>'slider']);
	}

	public function buildCollections() {
		$collections = $this->collections_cards()->toStructure();

		$content = '';
		foreach($collections as $collection) {
			$url = page($collection->card_link())->url();

			$title = brick('h3', brick('a', $collection->card_title(), ['href'=>$url]));
			$image = brick('a', '', ['href'=> $url, 'class'=>'image', 'style'=>'background-image: url("'.$this->file($collection->card_image())->url().'")']);
			$link = brick('p', brick('a', brick('span', $collection->link_text()), ['href'=>$url]));

			$content.= brick('div', $image.brick('div', $title.$collection->card_text()->kt().$link, ['class'=>'text']), ['class'=>'card']);
		}

		return brick('div', $content);
	}

	public function buildFeatured() {
		$button = brick('a', 'View all products', ['href'=>site()->productsPage()->url(), 'class'=>'button big']);

		return brick('div', site()->productList(false, 8), ['class'=>'product-list-container']).$button;
	}

}