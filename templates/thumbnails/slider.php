<?php if(!defined('ABSPATH')) die('Direct access denied.'); ?>

<?php
// For description of variables go to: http://www.codefleet.net/cyclone-slider-2/#template-variables
?>
<div class="cycloneslider cycloneslider-template-thumbnails" id="<?php echo $slider_html_id; ?>" style="max-width:<?php echo $slider_settings['width']; ?>px">
	<div class="cycloneslider-slides cycle-slideshow" 
		data-cycle-slides="> div"
		data-cycle-auto-height="<?php echo $slider_settings['width']; ?>:<?php echo $slider_settings['height']; ?>"
		data-cycle-fx="<?php echo $slider_settings['fx']; ?>"
		data-cycle-speed="<?php echo $slider_settings['speed']; ?>"
		data-cycle-timeout="<?php echo $slider_settings['timeout']; ?>"
		data-cycle-pause-on-hover="<?php echo $slider_settings['hover_pause']; ?>"
		data-cycle-pager="#<?php echo $slider_html_id; ?> .cycloneslider-pager"
		data-cycle-prev="#<?php echo $slider_html_id; ?> .cycloneslider-prev"
		data-cycle-next="#<?php echo $slider_html_id; ?> .cycloneslider-next"
		data-cycle-tile-count="<?php echo $slider_settings['tile_count']; ?>"
		data-cycle-tile-delay="<?php echo $slider_settings['tile_delay']; ?>"
		data-cycle-tile-vertical="<?php echo $slider_settings['tile_vertical']; ?>"
		data-cycle-log="false"
		>
		<?php foreach($slides as $i=>$slide): ?>
			<div class="cycloneslider-slide" <?php echo cyclone_slide_settings($slide, $slider_settings); ?>>
				<?php if ($slide['type']=='image') : ?>
					<?php if ($slide['link']!='') : ?><a target="<?php echo ('_blank'==$slide['link_target']) ? '_blank' : '_self'; ?>" href="<?php echo $slide['link'];?>"><?php endif; ?>
					<img src="<?php echo cyclone_slide_image_url($slide['id'], $slider_settings['width'], $slider_settings['height'], array('current_slide_settings'=>$slide, 'slideshow_settings'=>$slider_settings) ); ?>" alt="<?php echo $slide['img_alt'];?>" title="<?php echo $slide['img_title'];?>" />
					<?php if ($slide['link']!='') : ?></a><?php endif; ?>
					<?php if(!empty($slide['title']) or !empty($slide['description'])) : ?>
					<div class="cycloneslider-caption">
						<div class="cycloneslider-caption-title"><?php echo $slide['title'];?></div>
						<div class="cycloneslider-caption-description"><?php echo $slide['description'];?></div>
					</div>
					<?php endif; ?>
				<?php elseif ($slide['type']=='video') : ?>
					<?php echo $slide['video']; ?>
				<?php elseif ($slide['type']=='custom') : ?>
					<?php echo $slide['custom']; ?>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
	<?php if ($slider_settings['show_nav'] && ($video_count<=0) ) : ?>
	<div class="cycloneslider-prev">Prev</div>
	<div class="cycloneslider-next">Next</div>
	<?php endif; ?>
</div>
<?php if ($slider_settings['show_nav']) : ?>
<div id="<?php echo $slider_html_id; ?>-pager" class="cycloneslider-template-thumbnails cycloneslider-thumbnails" style="max-width:<?php echo $slider_settings['width']; ?>px">
	<ul class="clearfix">
		<?php foreach($slides as $i=>$slide): ?>
		<li>
			<?php if ($slide['type']=='video') : ?>
			<div class="thumb-video">
				<img src="<?php echo $slide['video_thumb'];?>" width="40" height="40" alt="">
			</div>
			<?php elseif($slide['type']=='custom'): ?>
			<div class="thumb-custom">HTML</div>
			<?php else: ?>
			<img src="<?php echo cyclone_slide_image_url($slide['id'], 40, 40, array('current_slide_settings'=>$slide, 'slideshow_settings'=>$slider_settings, 'resize_option'=>'crop') ); ?>" width="40" height="40" alt="<?php echo $slide['img_alt'];?>" title="<?php echo $slide['img_title'];?>" />
			<?php endif; ?>
		</li>
		<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>