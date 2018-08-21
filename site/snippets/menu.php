<nav>
	<ul id="menu">
	<?php
		$pages = $site->pages()->visible();
	?>
	<?php foreach($pages as $pg): ?>
		<li class="top<?= r($pg->hasMenuPages() || $pg->template() == 'products', ' has-children') ?><?= r($pg->isOpen(), ' active') ?>">
			<a href="<?= r($pg->hasMenuPages() || $pg->template() == 'products', '#', $pg->url()) ?>"><?= $pg->menuTitle() ?></a>
			<?php if($pg->hasMenuPages()): ?>
				<ul>
					<li class="<?= r($pg->isActive(), 'active') ?>"><a href="<?= $pg->url() ?>"><?= $pg->title() ?></a></li>
					<?php foreach($pg->menuPages() as $ch): ?>
						<li class="<?= r($ch->hasMenuPages(), 'has-children') ?><?= r($ch->isOpen(), ' active') ?>">
							<a href="<?= r($ch->hasMenuPages(), '#', $ch->url()) ?>"><?= $ch->menuTitle() ?></a>
							<?php if($ch->hasMenuPages()): ?>
								<ul>
									<li class="<?= r($ch->isActive(), 'active') ?>"><a href="<?= $ch->url() ?>"><?= $ch->title() ?></a></li>
									<?php foreach($ch->menuPages() as $gh): ?>
										<li class="<?= r($gh->isActive(), 'active') ?>">
											<a href="<?= $gh->url() ?>"><?= $gh->menuTitle() ?></a>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php elseif($pg->template() == 'products'): ?>
				<div class="product-submenu">
					<div>
						<ul>
							<?php foreach($site->productCategories(true) as $cat): ?>
								<li class="<?= $page->template() == 'products' && isset($category) && $category == $cat['uri'] ? 'active' : false ?>">
									<a href="<?= $cat['link'] ?>" data-image="<?= $cat['image'] ?>"><?= $cat['name'] ?></a>
								</li>
							<?php endforeach; ?>
							<li class="<?= r($pg->isActive() && (!isset($category) || !$category), 'active') ?>">
								<a href="<?= $pg->url() ?>" data-image="<?= $pg->file($pg->menu_image())->url() ?>">All <?= $pg->title() ?></a>
							</li>
							<li>
								<a href="#" data-image="<?= $pg->file($pg->menu_image())->url() ?>">Build a Bracelet</a>
							</li>
						</ul>
						<div class="product-image" style="background-image: url('<?= $pg->file($pg->menu_image())->url() ?>')"></div>
					</div>
				</div>
			<?php endif; ?>
		</li>
	<?php endforeach; ?>
	</ul>
</nav>