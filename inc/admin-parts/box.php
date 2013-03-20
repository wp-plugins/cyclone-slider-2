<?php if(!defined('CYCLONE_PATH')) die('Direct access denied.'); ?>
<div class="cs-slide">
	<div class="cs-header">
		<span class="cs-icon">
			<?php if ('custom'==$slider_meta['type']) : ?>
			<i class="icon-font"></i>
			<?php elseif ('video'==$slider_meta['type']) : ?>
			<i class="icon-film"></i>
			<?php else: ?>
			<i class="icon-picture"></i>
			<?php endif; ?>
		</span>
		<span class="cs-title">
			<?php echo $box_title; ?>
		</span>
		<span class="cs-controls">
			<span class="cs-drag" title="<?php _e('Drag', 'cycloneslider'); ?>"><?php _e('Drag', 'cycloneslider'); ?></span>
			<span class="cs-toggle" title="<?php _e('Toggle', 'cycloneslider'); ?>"><?php _e('Toggle', 'cycloneslider'); ?></span>
			<span class="cs-delete" title="<?php _e('Delete', 'cycloneslider'); ?>"><?php _e('Delete', 'cycloneslider'); ?></span>
		</span>
		<div class="clear"></div>
	</div>
	<div class="cs-body">
		<div class="cs-slide-type-bar">
			<select class="cs-slide-type-switcher" name="cycloneslider_metas[<?php echo $i; ?>][type]">
				<option value="image" <?php echo ('image'==$slider_meta['type']) ? 'selected="selected"' : ''; ?>><?php _e('Image', 'cycloneslider'); ?></option>
				<option value="video" <?php echo ('video'==$slider_meta['type']) ? 'selected="selected"' : ''; ?>><?php _e('Video', 'cycloneslider'); ?></option>
				<option value="custom" <?php echo ('custom'==$slider_meta['type']) ? 'selected="selected"' : ''; ?>><?php _e('Custom', 'cycloneslider'); ?></option>
			</select>	
		</div>
		<?php
		if($this->debug){
			self::debug($slider_meta);
		}
		?>
		<div class="clear"></div>
		<div class="cs-image">
			<div class="cs-image-preview">
				<div class="cs-image-thumb" <?php echo (empty($image_url)) ? 'style="display:none"' : '';?>>
					<?php if($image_url): ?>
					<img src="<?php echo esc_url($image_url); ?>" alt="thumb">
					<?php endif; ?>
				</div>
				<input class="cs-image-id" name="cycloneslider_metas[<?php echo $i; ?>][id]" type="hidden" value="<?php echo esc_attr($slider_meta['id']); ?>" />
				<input class="button-secondary cs-media-gallery-show" type="button" value="<?php _e('Get Image', 'cycloneslider'); ?>" />
			</div>
			<div class="cs-image-settings">
				<p class="expandable-group-title first"><?php _e('Slide Properties:', 'cycloneslider'); ?></p>
				<div class="expandable-box">
					<div class="expandable-header first"><?php _e('Link', 'cycloneslider'); ?></div>
					<div class="expandable-body">
						<div class="field">
							<label for=""><?php _e('Link URL:', 'cycloneslider'); ?></label> <br>
							<input class="widefat" name="cycloneslider_metas[<?php echo $i; ?>][link]" type="text" value="<?php echo esc_url($slider_meta['link']); ?>" />
						</div>
						<div class="field last">
							<label for=""><?php _e('Open Link in:', 'cycloneslider'); ?></label> <br>
							<select id="" name="cycloneslider_metas[<?php echo $i; ?>][link_target]">
								<option <?php echo ('_self'==$slider_meta['link_target']) ? 'selected="selected"' : ''; ?> value="_self"><?php _e('Same Window', 'cycloneslider'); ?></option>
								<option <?php echo ('_blank'==$slider_meta['link_target']) ? 'selected="selected"' : ''; ?> value="_blank"><?php _e('New Tab or Window', 'cycloneslider'); ?></option>
							</select>
						</div>
					</div>
				</div>
				<div class="expandable-box">
					<div class="expandable-header"><?php _e('Title', 'cycloneslider'); ?></div>
					<div class="expandable-body">
						<div class="field last">
							<input class="widefat cycloneslider-slide-meta-title" name="cycloneslider_metas[<?php echo $i; ?>][title]" type="text" value="<?php echo esc_attr($slider_meta['title']); ?>" />
						</div>
					</div>
				</div>
				<div class="expandable-box">
					<div class="expandable-header"><?php _e('Description', 'cycloneslider'); ?></div>
					<div class="expandable-body">
						<div class="field last">
							<textarea class="widefat cycloneslider-slide-meta-description" name="cycloneslider_metas[<?php echo $i; ?>][description]"><?php echo esc_html($slider_meta['description']); ?></textarea>
						</div>
					</div>
				</div>
				
				<div class="expandable-box">
					<div class="expandable-header"><?php _e('Image Attributes', 'cycloneslider'); ?></div>
					<div class="expandable-body">
						<div class="field">
							<label for=""><?php _e('Alternate Text:', 'cycloneslider'); ?></label> <br>
							<input class="widefat cycloneslider-slide-meta-alt" name="cycloneslider_metas[<?php echo $i; ?>][img_alt]" type="text" value="<?php echo esc_attr($slider_meta['img_alt']); ?>" />
						</div>
						<div class="field last">
							<label for=""><?php _e('Title Text:', 'cycloneslider'); ?></label> <br>
							<input class="widefat cycloneslider-slide-meta-title" name="cycloneslider_metas[<?php echo $i; ?>][img_title]" type="text" value="<?php echo esc_attr($slider_meta['img_title']); ?>" />
						</div>
					</div>
				</div>
				<div class="expandable-box last">
					<div class="expandable-header"><?php _e('Slide Transition Effects', 'cycloneslider'); ?></div>
					<div class="expandable-body">
						
						<select id="" class="cycloneslider_metas_enable_slide_effects" name="cycloneslider_metas[<?php echo $i; ?>][enable_slide_effects]">
							<option <?php echo (0==$slider_meta['enable_slide_effects']) ? 'selected="selected"' : ''; ?> value="0"><?php _e('Disable', 'cycloneslider'); ?></option>
							<option <?php echo (1==$slider_meta['enable_slide_effects']) ? 'selected="selected"' : ''; ?> value="1"><?php _e('Enable Slide Effects', 'cycloneslider'); ?></option>
						</select>
						
						<div class="clear"></div>
						
						<div class="field field-inline">
							<label for=""><?php _e('Transition Effects:', 'cycloneslider'); ?></label>
							<select id="" class="cycloneslider_metas_fx" name="cycloneslider_metas[<?php echo $i; ?>][fx]">
								<option value="default">Default</option>
								<?php foreach($this->effects as $value=>$name): ?>
								<option value="<?php echo $value; ?>" <?php echo ($slider_meta['fx']==$value) ? 'selected="selected"' : ''; ?>><?php echo $name; ?></option>
								<?php endforeach; ?>
							</select>
							<div class="clear"></div>
						</div>
						
						<div class="field field-inline">
							<label for=""><?php _e('Transition Effects Speed:', 'cycloneslider'); ?></label>
							<input class="widefat cycloneslider-slide-meta-speed" name="cycloneslider_metas[<?php echo $i; ?>][speed]" type="number" value="<?php esc_attr_e(@$slider_meta['speed']); ?>" />
							<span class="note"> <?php _e('Milliseconds', 'cycloneslider'); ?></span>
							<div class="clear"></div>
						</div>
						
						<div class="field field-inline">
							<label for=""><?php _e('Next Slide Delay:', 'cycloneslider'); ?></label>
							<input class="widefat cycloneslider-slide-meta-timeout" name="cycloneslider_metas[<?php echo $i; ?>][timeout]" type="number" value="<?php esc_attr_e(@$slider_meta['timeout']); ?>" />
							<span class="note"> <?php _e('Milliseconds', 'cycloneslider'); ?></span>
							<div class="clear"></div>
						</div>
						
						
						<div class="cycloneslider-slide-tile-properties">
							
							<div class="field field-inline">
								<label for=""><?php _e('Tile Count:', 'cycloneslider'); ?></label>
								<input class="widefat cycloneslider-slide-meta-tile-count" name="cycloneslider_metas[<?php echo $i; ?>][tile_count]" type="number" value="<?php esc_attr_e(@$slider_meta['tile_count']); ?>" />
								<span class="note"> <?php _e('The number of tiles to use in the transition.', 'cycloneslider'); ?></span>
								<div class="clear"></div>
							</div>
							<!--
							<label for=""><?php _e('Tile Delay:', 'cycloneslider'); ?></label>
							<input class="widefat cycloneslider-slide-meta-tile-delay" name="cycloneslider_metas[<?php echo $i; ?>][tile_delay]" type="text" value="<?php esc_attr_e(@$slider_meta['tile_delay']); ?>" />
							<span class="note"> <?php _e('Milliseconds to delay each individual tile transition.', 'cycloneslider'); ?></span>
							<div class="cycloneslider-spacer-15"></div>
							-->
							<div class="field field-inline">
								<label for=""><?php _e('Tile Position:', 'cycloneslider'); ?></label>
								<select id="" name="cycloneslider_metas[<?php echo $i; ?>][tile_vertical]">
									<option <?php echo ('true'==$slider_meta['tile_vertical']) ? 'selected="selected"' : ''; ?> value="true"><?php _e('Vertical', 'cycloneslider'); ?></option>
									<option <?php echo ('false'==$slider_meta['tile_vertical']) ? 'selected="selected"' : ''; ?> value="false"><?php _e('Horizontal', 'cycloneslider'); ?></option>
								</select>
								<div class="clear"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="cs-video">
			<div class="cs-video-preview">
				<div class="cs-video-thumb" <?php echo (empty($slider_meta['video_thumb'])) ? 'style="display:none"' : '';?>>
					<?php if($slider_meta['video_thumb']): ?>
					<img src="<?php echo esc_url($slider_meta['video_thumb']); ?>" alt="thumb">
					<?php endif; ?>
				</div>
				
				<div class="field">
					<input class="widefat cs-video-thumb-url" name="cycloneslider_metas[<?php echo $i; ?>][video_thumb]" type="hidden" value="<?php echo esc_attr($slider_meta['video_thumb']); ?>" />
					<label for=""><?php _e('Video URL:', 'cycloneslider'); ?></label>
					<input class="widefat cs-video-url" name="cycloneslider_metas[<?php echo $i; ?>][video_url]" type="text" value="<?php esc_attr_e($slider_meta['video_url']); ?>" />
					<span class="note"><?php _e('Copy and paste a Youtube or Vimeo URL and hit the Get Video button.', 'cycloneslider'); ?></span>
					<div class="clear"></div>
				</div>
				<div class="field last">
					<input class="button-secondary cs-button-update cs-video-get" type="button" value="<?php _e('Get Video', 'cycloneslider'); ?>" />
				</div>
			</div>
			<div class="cs-video-settings">
				<div class="field last">
					<label for=""><?php _e('Embed Code', 'cycloneslider'); ?></label>
					<textarea class="widefat cs-video-embed" rows="10" name="cycloneslider_metas[<?php echo $i; ?>][video]"><?php echo esc_textarea($slider_meta['video']); ?></textarea>
					<span class="note"><?php _e('You can place your embed code directly here. Or you can use the Get Video button to generate the embed code.', 'cycloneslider'); ?></span>
					<br><br>
					<span class="note"><?php _e('<strong>Note:</strong> The Slide Properties and Tile effects are not supported for videos.', 'cycloneslider'); ?></span>
					<br>
					<span class="note"><?php _e('<strong>Note:</strong> Append &wmode=transparent to the embed code src attribute to make HTML elements appear on top of Flash.', 'cycloneslider'); ?></span>
				</div>
			</div>
		</div>
		<div class="cs-custom">
			<div class="field last">
				<label for=""><?php _e('Custom HTML', 'cycloneslider'); ?></label>
				<textarea class="widefat cs-custom-html" name="cycloneslider_metas[<?php echo $i; ?>][custom]"><?php echo esc_textarea($slider_meta['custom']); ?></textarea>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>