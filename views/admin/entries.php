<section class="title">
	<h4><?php echo lang('slider:sections:images'); ?></h4>
</section>

	<section class="item">
		<?php if (isset($entries['entries']) AND ! empty($entries['entries'])): ?>
			<div class="images">
				<?php $i = 1; ?>
				<?php foreach ($entries['entries'] as $entry): ?>
					<div class="slider_image" id="<?php echo $entry['id']; ?>">
						<img src="<?php echo site_url('files/thumb/'.$entry['image']['id'].'/200'); ?>">
						<span>
							<div class="clear"><?php echo $entry['title']; ?></div>
							<div class="buttons">
								<?php if ($entry['status']['key'] == 'live'): ?>
									<a class="btn green confirm" title="Are you sure you want to set this image to draft? It will no longer appear on the site!" href="<?php echo site_url('admin/slider/draft/'.$entry['id']); ?>">
										<span>Live</span>
									</a>
								<?php else: ?>
									<a class="btn orange confirm" title="Are you sure you want to set this image to live? It will be visible on the site!" href="<?php echo site_url('admin/slider/live/'.$entry['id']); ?>">
										<span>Draft</span>
									</a>
								<?php endif; ?>
								<a class="btn blue" href="<?php echo site_url('admin/slider/edit/'.$entry['id']); ?>">
									<span>Edit</span>
								</a>
								<a class="btn red confirm" href="<?php echo site_url('admin/slider/delete/'.$entry['id']); ?>">
									<span>Delete</span>
								</a>
							</div>
						</span>
					</div>
				<?php endforeach; ?>
			</div>
		<?php else: ?>
			<div class="no_data">
				<?php echo lang('slider:label:none'); ?>
			</div>
		<?php endif; ?>
	</section>