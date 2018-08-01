<?php

class GalleryPage extends ProductsPage {

	public function buildGallery($category) {
		$firstImage = '';
		$images = '';
		$i = 0;

		foreach($this->gallery_images()->yaml() as $img) {
			$image = $this->image($img);
			if($image->category() == $category) {
				$images.= brick('img', false, ['src'=>$image->url()]);

				if($i++ == 0)
					$firstImage = $image->url();
			}
		}

		return brick('div', brick('div', false, ['style'=>'background-image: url('.$firstImage.')']).brick('div', $images), ['id'=>'gallery-container']);
	}

}