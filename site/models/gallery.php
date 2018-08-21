<?php

class GalleryPage extends ProductsPage {

	public function buildGallery($category) {
		$firstImage = '';
		$images = '';
		$i = 0;

		foreach($this->images()->sortBy('sort') as $img) {
			if($img->category() == $category) {
				$images.= brick('figure', brick('img', '', ['src'=>$img->url()]), ['class'=>r($i==0, 'current'), 'data-index'=>$i]);

				if($i++ == 0)
					$firstImage = $img->url();
			}
		}

		return brick('div', brick('div', brick('span', '', ['class'=>'prev']).brick('span', '', ['class'=>'next']), ['style'=>'background-image: url('.$firstImage.')']).brick('div', $images), ['id'=>'gallery-container']);
	}

}