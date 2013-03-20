<?php if(!defined('CYCLONE_PATH')) die('Direct access denied.'); ?>
<div class="cycloneslider-field last">
	<div class="template-scroller">
		<ul class="template-choices">
			<?php foreach($templates as $name=>$template): ?>
			<li <?php echo ($name==$slider_settings['template']) ? 'class="active"' : ''; ?>>
				<label for="template-<?php echo esc_attr($name); ?>">
					<?php if(file_exists($template['path'].DIRECTORY_SEPARATOR.'screenshot.jpg')) {
						?>
						<img src="<?php echo $template['url'];?>/screenshot.jpg" alt="" />
						<?php
					} else {
						?>
						<img src="<?php echo self::url();?>images/screenshot.png" alt="" />
						<?php
					}
					?>
					<input <?php echo ($name==$slider_settings['template']) ? 'checked="checked"' : ''; ?> id="template-<?php echo esc_attr($name); ?>" type="radio" name="cycloneslider_settings[template]" value="<?php echo esc_attr($name); ?>" />
				</label>
				<span class="title"><?php echo esc_attr(ucwords(str_replace('-',' ',$name))); ?></span>
				<span class="check"></span>
				<div class="supported">
					<?php if(in_array('custom', $template['supports'])): ?>
					<span class="cs-icon" title="Custom"><i class="icon-font"></i></span>
					<?php endif; ?>
					<?php if(in_array('video', $template['supports'])): ?>
					<span class="cs-icon" title="Video"><i class="icon-film"></i></span>
					<?php endif; ?>
					<?php if(in_array('image', $template['supports'])): ?>
					<span class="cs-icon" title="Image"><i class="icon-picture"></i></span>
					<?php endif; ?>
				</div>
			</li>
			<?php endforeach; ?>
		</ul>
		<div class="clear"></div>
	</div>
	<span class="note"><?php _e("Select a template to use. Check the template icons to see what slide type it supports.", 'cycloneslider'); ?></span>
	<div class="cycloneslider-get-more">
		<a target="_blank" class="button-primary" href="http://www.codefleet.net/cyclone-slider-2/templates/"><?php _e("Get more templates..", 'cycloneslider'); ?></a>
	</div>
	<div class="clear"></div>
</div>