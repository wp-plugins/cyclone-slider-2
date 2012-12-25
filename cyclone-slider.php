<?php
/*
Plugin Name: Cyclone Slider 2
Plugin URI: http://www.codefleet.net/cyclone-slider-2/
Description: Create responsive slideshows with ease. Built for both developers and non-developers.
Version: 2.2.1
Author: Nico Amarilla
Author URI: http://www.codefleet.net/
License:

  Copyright 2012 (kosinix@codefleet.net)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  
*/
require_once('inc/class-cyclone-slider.php');
require_once('inc/class-cyclone-slider-widget.php');
require_once('inc/class-image-resizer.php');
require_once('inc/class-nextgen-integration.php');

if(!defined('CYCLONE_PATH')){
	define('CYCLONE_PATH', realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR );
}
if(!defined('CYCLONE_URL')){
	define('CYCLONE_URL', plugin_dir_url(__FILE__) );
}


if(class_exists('Cyclone_Slider')):
	new Cyclone_Slider();
endif;

/**
 * Thumbnailer
 *
 * Creates thumbnail of the slide image using the specified attachment ID, width and height
 *
 *
 * @param int $original_attachment_id Attachment ID.
 * @param int $width Width of thumbnail in pixels.
 * @param int $height Height of thumbnail in pixels.
 * @param bool $refresh Recreate thumbnail if it already exists if set to true. Default to false, will not recreate thumbnails if it already exist.
 * @return string The url to the thumbnail. False on failure.
 */
function cycloneslider_thumb( $original_attachment_id, $width, $height, $refresh = false, $slide_meta = array(), $option="auto" ){
	$dir = wp_upload_dir();
	
	// Get full path to the slide image
	$image_path = get_attached_file($original_attachment_id);
	$image_path = apply_filters('cycloneslider_image_path', $image_path, $slide_meta);
	if(empty($image_path)){
		return false;
	}
	
	// Full url to the slide image
	$image_url = wp_get_attachment_url($original_attachment_id);
	$image_url = apply_filters('cycloneslider_image_url', $image_url, $slide_meta);
	if(empty($image_url)){
		return false;
	}

	$info = pathinfo($image_path);
	$dirname = isset($info['dirname']) ? $info['dirname'] : ''; // Path to directory
	$ext = isset($info['extension']) ? $info['extension'] : ''; // File extension Eg. "jpg"
	$thumb = wp_basename($image_path, ".$ext")."-{$width}x{$height}.{$ext}"; // Thumbname. Eg. [imagename]-[width]x[height].hpg
	
	// Check if thumb already exists. If it is, return its url, unless refresh is true
	if(file_exists($dirname.'/'.$thumb ) and !$refresh){
		return dirname($image_url).'/'.$thumb; //We used dirname() since we need the URL format not the path
	}
	
	$resizeObj = new Image_Resizer($image_path);
	$resizeObj -> resizeImage($width, $height, $option);
	$resizeObj -> saveImage($dirname.'/'.$thumb, 80);
	
	return dirname($image_url).'/'.$thumb;
}

/**
 * Cycle Settings Printer
 *
 * Prints out cycle slideshow settings in templates
 *
 *
 * @param array $slider_settings Slider settings array.
 * @param string $slider_id HTML ID of slideshow.
 * @param int $slider_count Current slideshow count.
 * @return string Data attributes for slideshow.
 */
function cycloneslider_settings($slider_settings, $slider_id='', $slider_count=1){
	$out = ' data-cycle-slides="> div"';
	$out .= ' data-cycle-auto-height="'.$slider_settings['width'].':'.$slider_settings['height'].'"';
	$out .= ' data-cycle-fx="'.$slider_settings['fx'].'"';
	$out .= ' data-cycle-speed="'.$slider_settings['speed'].'"';
	$out .= ' data-cycle-timeout="'.$slider_settings['timeout'].'"';
	$out .= ' data-cycle-pause-on-hover="'.$slider_settings['hover_pause'].'"';
	$out .= ' data-cycle-pager="#cycloneslider-'.$slider_id.' .cycloneslider-pager"';
	$out .= ' data-cycle-prev="#cycloneslider-'.$slider_id.' .cycloneslider-prev"';
    $out .= ' data-cycle-next="#cycloneslider-'.$slider_id.' .cycloneslider-next"';
	$out .= ' data-cycle-tile-count="'.$slider_settings['tile_count'].'"';
	$out .= ' data-cycle-tile-delay="'.$slider_settings['tile_delay'].'"';
	$out .= ' data-cycle-tile-vertical="'.$slider_settings['tile_vertical'].'"';
	$out .= ' data-cycle-log="false"';
	$out = apply_filters('cycloneslider_cycle_settings', $out);
	return $out;
}

/**
 * Cycle Slide Settings Printer
 *
 * Prints out cycle slide settings in templates
 *
 *
 * @param array $slider_meta Slide settings array.
 * @param array $slider_settings Slider settings array.
 * @param string $slider_id HTML ID of slideshow.
 * @param int $slider_count Current slideshow count.
 * @return string Data attributes for slide.
 */
function cycloneslider_slide_settings($slider_meta, $slider_settings=array(), $slider_id='', $slider_count=1){
	$out = '';
	if(empty($slider_meta['enable_slide_effects'])){
		return $out;
	}
	if($slider_meta['fx']!='default') {
		$out .= ' data-cycle-fx="'.$slider_meta['fx'].'"';
	}
	if(!empty($slider_meta['speed'])) {
		$out .= ' data-cycle-speed="'.$slider_meta['speed'].'"';
	}
	if(!empty($slider_meta['timeout'])) {
		$out .= ' data-cycle-timeout="'.$slider_meta['timeout'].'"';
	}
	if($slider_meta['fx']=='tileBlind' or $slider_meta['fx']=='tileSlide'){
		if(!empty($slider_meta['tile_count'])) {
			$out .= ' data-cycle-tile-count="'.$slider_meta['tile_count'].'"';
		}
		if(!empty($slider_meta['tile_delay'])) {
			$out .= ' data-cycle-tile-delay="'.$slider_meta['tile_delay'].'"';
		}
		$out .= ' data-cycle-tile-vertical="'.$slider_meta['tile_vertical'].'"';
	}
	$out = apply_filters('cycloneslider_cycle_slide_settings', $out);
	return $out;
}