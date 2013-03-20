<?php if($slider_count>0) $slider_id = $slider_id.'-'.$slider_count; ?>
<div class="cycloneslider cycloneslider-template-myrtle" id="cycloneslider-<?php echo $slider_id; ?>" style="max-width:<?php echo $slider_settings['width']; ?>px">
	<div class="cycloneslider-slides cycle-slideshow" <?php echo cyclone_settings($slider_settings, $slider_id); ?>>
		<?php foreach($slider_metas as $i=>$slider_meta): ?>
			<div class="cycloneslider-slide" <?php echo cyclone_slide_settings($slider_meta, $slider_settings); ?>>
				<?php if ($slider_meta['type']=='image') : ?>
					<?php if ($slider_meta['link']!='') : ?><a target="<?php echo ('_blank'==$slider_meta['link_target']) ? '_blank' : '_self'; ?>" href="<?php echo $slider_meta['link'];?>"><?php endif; ?>
					<img src="<?php echo cyclone_slide_image_url($slider_meta['id'], $slider_settings['width'], $slider_settings['height'], array('current_slide_settings'=>$slider_meta, 'slideshow_settings'=>$slider_settings) ); ?>" alt="<?php echo $slider_meta['img_alt'];?>" title="<?php echo $slider_meta['img_title'];?>" />
					<?php if ($slider_meta['link']!='') : ?></a><?php endif; ?>
					<?php if(!empty($slider_meta['title']) or !empty($slider_meta['description'])) : ?>
					<div class="cycloneslider-caption">
						<div class="cycloneslider-caption-title"><?php echo $slider_meta['title'];?></div>
						<div class="cycloneslider-caption-description"><?php echo $slider_meta['description'];?></div>
					</div>
					<?php endif; ?>
				<?php elseif ($slider_meta['type']=='video') : ?>
					<?php echo $slider_meta['video']; ?>
				<?php elseif ($slider_meta['type']=='custom') : ?>
					<?php echo $slider_meta['custom']; ?>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
	<?php if ($slider_settings['show_nav']) : ?>
	<div class="cycloneslider-pager"></div>
	<?php endif; ?>
	<?php if ($slider_settings['show_prev_next']) : ?>
	<div class="cycloneslider-prev">Prev</div>
	<div class="cycloneslider-next">Next</div>
	<?php endif; ?>
</div>