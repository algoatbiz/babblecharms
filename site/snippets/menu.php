<nav>
	<ul id="menu">
	<?php
		$pages = $site->pages()->visible();
	?>
	<?php foreach($pages as $pg): ?>
		<li class="top<?= r($pg->hasMenuPages(), ' has-children') ?><?= r($pg->isOpen(), ' active') ?>">
			<a href="<?= r($pg->hasMenuPages(), '#', $pg->url()) ?>"><?= $pg->menuTitle() ?></a>
			<?php if($pg->hasMenuPages() && $pg->template() != 'product-list'): ?>
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
			<?php elseif($pg->template() == 'product-list'): ?>
				<div class="product-submenu">
					<div>
						<ul>
							<?php foreach($pg->menuPages() as $ch): ?>
								<li><a href="<?= $ch->url() ?>" data-image="<?= $ch->file($ch->menu_image())->url() ?>"><?= $ch->menuTitle() ?></a></li>
							<?php endforeach; ?>
							<li><a href="<?= $pg->url() ?>" data-image="<?= $pg->file($pg->menu_image())->url() ?>"><?= $pg->title() ?></a></li>
							<li><a href="#" data-image="<?= $pg->file($pg->menu_image())->url() ?>">Build a Bracelet</a></li>
						</ul>
						<div class="product-image"></div>
					</div>
				</div>
			<?php endif; ?>
		</li>
	<?php endforeach; ?>
	</ul>
</nav>