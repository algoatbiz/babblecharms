<nav>
	<ul id="menu">
	<?php
		$pages = $site->pages()->visible();
	?>
	<?php foreach($pages as $pg): ?>
		<li class="top<?= r($pg->hasMenuPages(), ' has-children') ?><?= r($pg->isOpen(), ' active') ?>">
			<a href="<?= r($pg->hasMenuPages(), '#', $pg->url()) ?>"><?= $pg->menuTitle() ?></a>
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
			<?php endif; ?>
		</li>
	<?php endforeach; ?>
	</ul>
</nav>