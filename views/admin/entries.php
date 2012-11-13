<section class="title">
	<h4><?php echo lang('slider:sections:images'); ?></h4>
</section>

	<section class="item">
		<?php if (isset($entries['entries']) AND ! empty($entries['entries'])): ?>
			<div class="images">
				<?php $i = 1; ?>
				<?php foreach ($entries['entries'] as $entry): ?>
					<div id="<?php echo $entry['id']; ?>">
						<img src="<?php echo site_url('files/thumb/'.$entry['image']['id'].'/300/77/fit'); ?>" width="300" height="77">
						<span>
							<div class="clear"><?php echo $entry['title']; ?></div class="clear">
							<a class="btn blue" href="<?php echo site_url('admin/slider/edit/'.$entry['id']); ?>">
								<span>Edit</span>
							</a>
							<a class="btn red confirm" href="<?php echo site_url('admin/slider/delete/'.$entry['id']); ?>">
								<span>Delete</span>
							</a>
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