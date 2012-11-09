<?php
/*
Plugin Name: Cyclone Slider 2
Plugin URI: http://www.codefleet.net/cyclone-slider-2/
Description: Create responsive slideshows with ease. Built for both developers and non-developers.
Version: 2.0.1
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
require_once('inc/class-image-resizer.php');

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
// 
function cycloneslider_thumb( $original_attachment_id, $width, $height, $refresh = false ){
	$dir = wp_upload_dir();
	
	// Get full path to the slide image
	$image_path = get_attached_file($original_attachment_id);
	if(empty($image_path)){
		return false;
	}
	
	// Full url to the slide image
	$image_url = wp_get_attachment_url($original_attachment_id);
	if(empty($image_path)){
		return false;
	}
	
	$info = pathinfo($image_path);
	$dirname = isset($info['dirname']) ? $info['dirname'] : '';
	$ext = isset($info['extension']) ? $info['extension'] : '';
	$thumb = wp_basename($image_path, ".$ext")."-{$width}x{$height}.{$ext}";
	
	// Check if thumb already exists. If it is, return its url, unless refresh is true
	if(file_exists($dirname.'/'.$thumb ) and !$refresh){
		return dirname($image_url).'/'.$thumb;
	}
	
	$resizeObj = new Image_Resizer($image_path);
	$resizeObj -> resizeImage($width, $height);
	$resizeObj -> saveImage($dirname.'/'.$thumb, 80);
	
	return dirname($image_url).'/'.$thumb;

}