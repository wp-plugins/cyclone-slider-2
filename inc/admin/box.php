<div class="cycloneslider-box">
	<div class="cycloneslider-box-title ui-state-default">
		<span class="cycloneslider-box-title-left">
			<?php _e('Slide', 'cycloneslider'); ?>
		</span>
		<span class="cycloneslider-box-title-right">
			<span class="cycloneslider-box-drag" title="<?php _e('Drag', 'cycloneslider'); ?>"><?php _e('Drag', 'cycloneslider'); ?></span>
			<a href="#" class="cycloneslider-box-toggle" title="<?php _e('Toggle', 'cycloneslider'); ?>"><?php _e('Toggle', 'cycloneslider'); ?></a>
			<a href="#" class="cycloneslider-box-delete" title="<?php _e('Delete', 'cycloneslider'); ?>"><?php _e('Delete', 'cycloneslider'); ?></a>
		</span>
		<div class="clear"></div>
	</div>
	<div class="cycloneslider-box-body">
		<div class="cycloneslider-body-left">
			<img class="cycloneslider-slide-thumb" src="<?php echo esc_url($image_url); ?>" alt="" />
			<input class="cycloneslider-slide-meta-id" name="cycloneslider_metas[<?php echo $i; ?>][id]" type="hidden" value="<?php echo esc_attr($slider_metas[$i]['id']); ?>" />
			<input class="button-secondary cycloneslider-upload-button" type="button" value="<?php _e('Get Image', 'cycloneslider'); ?>" />
		</div>
		<div class="cycloneslider-body-right">
			<p class="cycloneslider-sub-title"><?php _e('Extra slide elements:', 'cycloneslider'); ?></p>
			<div class="cycloneslider-slide-metas">
				<div class="cycloneslider-meta-field">
					<div class="cycloneslider-field-title first">
						<?php _e('Slide Link', 'cycloneslider'); ?>
					</div>
					<div class="cycloneslider-field-body">
						<label for=""><?php _e('Link:', 'cycloneslider'); ?></label>
						<input class="widefat cycloneslider-slide-meta-link" name="cycloneslider_metas[<?php echo $i; ?>][link]" type="text" value="<?php echo esc_url($slider_metas[$i]['link']); ?>" />
						<label for=""><?php _e('Open Link in:', 'cycloneslider'); ?></label>
						<select id="" name="cycloneslider_metas[<?php echo $i; ?>][link_target]">
							<option <?php echo ('_self'==$slider_metas[$i]['link_target']) ? 'selected="selected"' : ''; ?> value="_self"><?php _e('Same Window', 'cycloneslider'); ?></option>
							<option <?php echo ('_blank'==$slider_metas[$i]['link_target']) ? 'selected="selected"' : ''; ?> value="_blank"><?php _e('New Tab or Window', 'cycloneslider'); ?></option>
						</select>
					</div>
				</div>
				<div class="cycloneslider-meta-field">
					<div class="cycloneslider-field-title">
						<?php _e('Title', 'cycloneslider'); ?>
					</div>
					<div class="cycloneslider-field-body">
						<input class="widefat cycloneslider-slide-meta-title" name="cycloneslider_metas[<?php echo $i; ?>][title]" type="text" value="<?php echo esc_attr($slider_metas[$i]['title']); ?>" />
					</div>
				</div>
				<div class="cycloneslider-meta-field">
					<div class="cycloneslider-field-title">
						<?php _e('Description', 'cycloneslider'); ?>
					</div>
					<div class="cycloneslider-field-body">
						<textarea class="widefat cycloneslider-slide-meta-description" name="cycloneslider_metas[<?php echo $i; ?>][description]"><?php echo esc_html($slider_metas[$i]['description']); ?></textarea>
					</div>
				</div>
			</div>
			
			<p class="cycloneslider-sub-title"><?php _e('Slide Properties:', 'cycloneslider'); ?></p>
			<div class="cycloneslider-slide-metas">
				<div class="cycloneslider-meta-field">
					<div class="cycloneslider-field-title first">
						<?php _e('Slide Effects', 'cycloneslider'); ?>
					</div>
					<div class="cycloneslider-field-body">
						<label for=""><?php _e('Effects:', 'cycloneslider'); ?></label>
						<select id="" name="cycloneslider_metas[<?php echo $i; ?>][fx]">
							<option value="default">Default</option>
							<?php foreach($this->effects as $value=>$name): ?>
							<option value="<?php echo $value; ?>" <?php echo ($slider_metas[$i]['fx']==$value) ? 'selected="selected"' : ''; ?>><?php echo $name; ?></option>
							<?php endforeach; ?>
						</select> 
						<br /><br />
						<label for=""><?php _e('Transition Effects Speed:', 'cycloneslider'); ?></label>
						<input class="widefat cycloneslider-slide-meta-speed" name="cycloneslider_metas[<?php echo $i; ?>][speed]" type="text" value="<?php esc_attr_e(@$slider_metas[$i]['speed']); ?>" />
						<span class="note"> <?php _e('Milliseconds', 'cycloneslider'); ?></span>
						<br /><br />
						<label for=""><?php _e('Next Slide Delay:', 'cycloneslider'); ?></label>
						<input class="widefat cycloneslider-slide-meta-timeout" name="cycloneslider_metas[<?php echo $i; ?>][timeout]" type="text" value="<?php esc_attr_e(@$slider_metas[$i]['timeout']); ?>" />
						<span class="note"> <?php _e('Milliseconds', 'cycloneslider'); ?></span>
						<br /><br />
						<span class="note"><?php _e('Settings here will override the main slideshow settings. Leave blank to disable.', 'cycloneslider'); ?></span>
					</div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>