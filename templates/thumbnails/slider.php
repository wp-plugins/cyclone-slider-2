<?php if($slider_count>0) $slider_id = $slider_id.'-'.$slider_count; ?>
<div class="cycloneslider cycloneslider-template-thumbnails" id="cycloneslider-<?php echo $slider_id; ?>" style="max-width:<?php echo $slider_settings['width']; ?>px">
	<div class="cycloneslider-slides cycle-slideshow"
		data-cycle-slides="> div"
		data-cycle-auto-height="<?php echo $slider_settings['width']; ?>:<?php echo $slider_settings['height']; ?>"
		data-cycle-fx="<?php echo $slider_settings['fx']; ?>"
		data-cycle-speed="<?php echo $slider_settings['speed']; ?>"
		data-cycle-timeout="<?php echo $slider_settings['timeout']; ?>"
		data-cycle-pause-on-hover="<?php echo $slider_settings['hover_pause']; ?>"
		data-cycle-pager="#cycloneslider-<?php echo $slider_id; ?> .cycloneslider-pager"
		data-cycle-prev="#cycloneslider-<?php echo $slider_id; ?> .cycloneslider-prev"
        data-cycle-next="#cycloneslider-<?php echo $slider_id; ?> .cycloneslider-next"
		>
		<?php foreach($slides as $i=>$slide): ?>
			<div <?php echo ($slider_metas[$i]['fx']!='default') ? 'data-cycle-fx="'.$slider_metas[$i]['fx'].'"' : ''; ?> <?php echo ($slider_metas[$i]['speed']!='') ? 'data-cycle-speed="'.$slider_metas[$i]['speed'].'"' : ''; ?> <?php echo ($slider_metas[$i]['timeout']!='') ? 'data-cycle-timeout="'.$slider_metas[$i]['timeout'].'"' : ''; ?> class="cycloneslider-slide">
				<?php if ($slider_metas[$i]['link']!='') : ?><a target="<?php echo ('_blank'==$slider_metas[$i]['link_target']) ? '_blank' : '_self'; ?>" href="<?php echo $slider_metas[$i]['link'];?>"><?php endif; ?>
				<img src="<?php echo cycloneslider_thumb($slider_metas[$i]['id'], $slider_settings['width'], $slider_settings['height']);//$slide; ?>" alt="" />
				<?php if ($slider_metas[$i]['link']!='') : ?></a><?php endif; ?>
				<?php if(!empty($slider_metas[$i]['title']) or !empty($slider_metas[$i]['description'])) : ?>
				<div class="cycloneslider-caption">
					<div class="cycloneslider-caption-title"><?php echo $slider_metas[$i]['title'];?></div>
					<div class="cycloneslider-caption-description"><?php echo $slider_metas[$i]['description'];?></div>
				</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
	<?php if ($slider_settings['show_prev_next']) : ?>
	<div class="cycloneslider-prev">Prev</div>
	<div class="cycloneslider-next">Next</div>
	<?php endif; ?>
</div>
<?php if ($slider_settings['show_nav']) : ?>
<div id="cycloneslider-thumbnails-<?php echo $slider_id; ?>" class="cycloneslider-template-thumbnails cycloneslider-thumbnails" style="max-width:<?php echo $slider_settings['width']; ?>px">
	<ul class="clearfix">
		<?php foreach($slider_metas as $i=>$slider_meta): ?>
		<li>
			<img src="<?php echo cycloneslider_thumb( $slider_meta['id'], 30, 30 ) ?>" alt="" />
		</li>
		<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>
<script type="text/javascript">
jQuery(document).ready(function(){
	(function() {
		var start = true;
		var slider = jQuery('#cycloneslider-<?php echo $slider_id; ?> .cycloneslider-slides');
		var thumbnails = jQuery('#cycloneslider-thumbnails-<?php echo $slider_id; ?>');
		thumbnails.find('li:first').addClass('current');
		slider.on( 'cycle-before', function( event, optionHash, outgoingSlideEl, incomingSlideEl, forwardFlag ) {
			var i = optionHash.nextSlide;
			thumbnails.find('li').removeClass('current').eq(i).addClass('current');
		});
		thumbnails.on('click', 'li', function(){
			var i = jQuery(this).index();
			slider.cycle('goto', i);
		});
	})();
});
</script>