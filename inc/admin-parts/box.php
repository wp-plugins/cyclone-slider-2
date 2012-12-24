<?php if(!defined('CYCLONE_PATH')) die('Direct access denied.'); ?>
<div class="cycloneslider-box">
	<div class="cycloneslider-box-title ui-state-default">
		<span class="cycloneslider-box-title-left">
			<?php echo $box_title; ?>
		</span>
		<span class="cycloneslider-box-title-right">
			<span class="cycloneslider-box-drag" title="<?php _e('Drag', 'cycloneslider'); ?>"><?php _e('Drag', 'cycloneslider'); ?></span>
			<span class="cycloneslider-box-toggle" title="<?php _e('Toggle', 'cycloneslider'); ?>"><?php _e('Toggle', 'cycloneslider'); ?></span>
			<span class="cycloneslider-box-delete" title="<?php _e('Delete', 'cycloneslider'); ?>"><?php _e('Delete', 'cycloneslider'); ?></span>
		</span>
		<div class="clear"></div>
	</div>
	<div class="cycloneslider-box-body">
		<div class="cycloneslider-body-left">
			<img class="cycloneslider-slide-thumb" src="<?php echo esc_url($image_url); ?>" alt="" />
			<input class="cycloneslider-slide-meta-id" name="cycloneslider_metas[<?php echo $i; ?>][id]" type="hidden" value="<?php echo esc_attr($slider_meta['id']); ?>" />
			<input class="cycloneslider-slide-meta-type" name="cycloneslider_metas[<?php echo $i; ?>][type]" type="hidden" value="<?php echo esc_attr($slider_meta['type']); ?>" />
			<input class="button-secondary cycloneslider-upload-button" type="button" value="<?php _e('Get Image', 'cycloneslider'); ?>" />
		</div>
		<div class="cycloneslider-body-right">
			<p class="cycloneslider-sub-title"><?php _e('Slide Elements:', 'cycloneslider'); ?></p>
			<div class="cycloneslider-slide-metas">
				<div class="cycloneslider-meta-field">
					<div class="cycloneslider-field-title first">
						<?php _e('Slide Link', 'cycloneslider'); ?>
					</div>
					<div class="cycloneslider-field-body">
						<label for=""><?php _e('Link:', 'cycloneslider'); ?></label>
						<input class="widefat cycloneslider-slide-meta-link" name="cycloneslider_metas[<?php echo $i; ?>][link]" type="text" value="<?php echo esc_url($slider_meta['link']); ?>" />
						<label for=""><?php _e('Open Link in:', 'cycloneslider'); ?></label>
						<select id="" name="cycloneslider_metas[<?php echo $i; ?>][link_target]">
							<option <?php echo ('_self'==$slider_meta['link_target']) ? 'selected="selected"' : ''; ?> value="_self"><?php _e('Same Window', 'cycloneslider'); ?></option>
							<option <?php echo ('_blank'==$slider_meta['link_target']) ? 'selected="selected"' : ''; ?> value="_blank"><?php _e('New Tab or Window', 'cycloneslider'); ?></option>
						</select>
					</div>
				</div>
				<div class="cycloneslider-meta-field">
					<div class="cycloneslider-field-title">
						<?php _e('Title', 'cycloneslider'); ?>
					</div>
					<div class="cycloneslider-field-body">
						<input class="widefat cycloneslider-slide-meta-title" name="cycloneslider_metas[<?php echo $i; ?>][title]" type="text" value="<?php echo esc_attr($slider_meta['title']); ?>" />
					</div>
				</div>
				<div class="cycloneslider-meta-field">
					<div class="cycloneslider-field-title">
						<?php _e('Description', 'cycloneslider'); ?>
					</div>
					<div class="cycloneslider-field-body">
						<textarea class="widefat cycloneslider-slide-meta-description" name="cycloneslider_metas[<?php echo $i; ?>][description]"><?php echo esc_html($slider_meta['description']); ?></textarea>
					</div>
				</div>
			</div>
			<p class="cycloneslider-sub-title"><?php _e('Image Properties:', 'cycloneslider'); ?></p>
			<div class="cycloneslider-slide-metas">
				<div class="cycloneslider-meta-field">
					<div class="cycloneslider-field-title first">
						<?php _e('Alternate Text', 'cycloneslider'); ?>
					</div>
					<div class="cycloneslider-field-body">
						<input class="widefat cycloneslider-slide-meta-alt" name="cycloneslider_metas[<?php echo $i; ?>][img_alt]" type="text" value="<?php echo esc_attr($slider_meta['img_alt']); ?>" />
					</div>
				</div>
				<div class="cycloneslider-meta-field">
					<div class="cycloneslider-field-title">
						<?php _e('Title Text', 'cycloneslider'); ?>
					</div>
					<div class="cycloneslider-field-body">
						<input class="widefat cycloneslider-slide-meta-title" name="cycloneslider_metas[<?php echo $i; ?>][img_title]" type="text" value="<?php echo esc_attr($slider_meta['img_title']); ?>" />
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
						
						<select id="" class="cycloneslider_metas_enable_slide_effects" name="cycloneslider_metas[<?php echo $i; ?>][enable_slide_effects]">
							<option <?php echo (0==$slider_meta['enable_slide_effects']) ? 'selected="selected"' : ''; ?> value="0"><?php _e('Disable', 'cycloneslider'); ?></option>
							<option <?php echo (1==$slider_meta['enable_slide_effects']) ? 'selected="selected"' : ''; ?> value="1"><?php _e('Enable Slide Effects', 'cycloneslider'); ?></option>
						</select>
						
						<div class="clear"></div>
						
						<label for=""><?php _e('Transition Effects:', 'cycloneslider'); ?></label>
						<select id="" class="cycloneslider_metas_fx" name="cycloneslider_metas[<?php echo $i; ?>][fx]">
							<option value="default">Default</option>
							<?php foreach($this->effects as $value=>$name): ?>
							<option value="<?php echo $value; ?>" <?php echo ($slider_meta['fx']==$value) ? 'selected="selected"' : ''; ?>><?php echo $name; ?></option>
							<?php endforeach; ?>
						</select> 
						<div class="cycloneslider-spacer-15"></div>
						
						<label for=""><?php _e('Transition Effects Speed:', 'cycloneslider'); ?></label>
						<input class="widefat cycloneslider-slide-meta-speed" name="cycloneslider_metas[<?php echo $i; ?>][speed]" type="text" value="<?php esc_attr_e(@$slider_meta['speed']); ?>" />
						<span class="note"> <?php _e('Milliseconds', 'cycloneslider'); ?></span>
						<div class="cycloneslider-spacer-15"></div>
						
						<label for=""><?php _e('Next Slide Delay:', 'cycloneslider'); ?></label>
						<input class="widefat cycloneslider-slide-meta-timeout" name="cycloneslider_metas[<?php echo $i; ?>][timeout]" type="text" value="<?php esc_attr_e(@$slider_meta['timeout']); ?>" />
						<span class="note"> <?php _e('Milliseconds', 'cycloneslider'); ?></span>
						
						
						<div class="cycloneslider-slide-tile-properties">
							<div class="cycloneslider-spacer-15"></div>
							<label for=""><?php _e('Tile Count:', 'cycloneslider'); ?></label>
							<input class="widefat cycloneslider-slide-meta-tile-count" name="cycloneslider_metas[<?php echo $i; ?>][tile_count]" type="text" value="<?php esc_attr_e(@$slider_meta['tile_count']); ?>" />
							<span class="note"> <?php _e('The number of tiles to use in the transition.', 'cycloneslider'); ?></span>
							<div class="cycloneslider-spacer-15"></div>
							<!--
							<label for=""><?php _e('Tile Delay:', 'cycloneslider'); ?></label>
							<input class="widefat cycloneslider-slide-meta-tile-delay" name="cycloneslider_metas[<?php echo $i; ?>][tile_delay]" type="text" value="<?php esc_attr_e(@$slider_meta['tile_delay']); ?>" />
							<span class="note"> <?php _e('Milliseconds to delay each individual tile transition.', 'cycloneslider'); ?></span>
							<div class="cycloneslider-spacer-15"></div>
							-->
							<label for=""><?php _e('Tile Position:', 'cycloneslider'); ?></label>
							<select id="" name="cycloneslider_metas[<?php echo $i; ?>][tile_vertical]">
								<option <?php echo ('true'==$slider_meta['tile_vertical']) ? 'selected="selected"' : ''; ?> value="true"><?php _e('Vertical', 'cycloneslider'); ?></option>
								<option <?php echo ('false'==$slider_meta['tile_vertical']) ? 'selected="selected"' : ''; ?> value="false"><?php _e('Horizontal', 'cycloneslider'); ?></option>
							</select>
						</div>
						
					</div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>