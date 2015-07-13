<section class="title">
	<h4><?php echo lang('slider:sections:slides')." ".$title; ?></h4>
</section>

<section class="item">
	<?php if (isset($entries['entries']) AND ! empty($entries['entries'])): ?>
		<div class="content images">
			<?php $i = 1; ?>
			<?php foreach ($entries['entries'] as $entry): ?>
				<div class="slider_image" id="<?php echo $entry['id']; ?>">
					<div class="thumbnail clear"><img src="<?php echo site_url('files/thumb/'.$entry['slide_image']['id'].'/200'); ?>" style="display:block;" /></div>
					<div class="clear"><?php echo $entry['title']; ?></div>
					<div class="buttons clear">
						<?php if ($entry['status']['key'] == 'live'): ?>
							<a class="btn green confirm" title="<?php echo lang("slider:slide:set_draft"); ?>" href="<?php echo site_url('admin/slider/slides/draft/'.$entry['id']); ?>">
								<span><?php echo lang("slider:buttons:live"); ?></span>
							</a>
						<?php else: ?>
							<a class="btn orange confirm" title="<?php echo lang("slider:slide:set_live"); ?>" href="<?php echo site_url('admin/slider/slides/live/'.$entry['id']); ?>">
								<span><?php echo lang("slider:buttons:draft"); ?></span>
							</a>
						<?php endif; ?>
						<a class="btn blue" href="<?php echo site_url('admin/slider/slides/edit/'.$entry['id']."/".$entry['slider_id']); ?>">
							<span><?php echo lang("slider:buttons:edit"); ?></span>
						</a><br/>
						<?php if (group_has_role('slider', 'slide_delete')) { ?>
						<a class="btn red confirm" href="<?php echo site_url('admin/slider/slides/delete/'.$entry['id']); ?>">
							<span><?php echo lang("slider:buttons:delete"); ?></span>
						</a>
						<?php } ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php else: ?>
		<div class="no_data">
			<?php echo lang('slider:slide:label:none'); ?>
		</div>
	<?php endif; ?>
</section>