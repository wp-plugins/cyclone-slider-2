<?php
/*
Plugin Name: Cyclone Slider 2
Plugin URI: http://www.codefleet.net/cyclone-slider-2/
Description: Create responsive slideshows with ease. Built for both developers and non-developers.
Version: 2.5.1
Author: Nico Amarilla
Author URI: http://www.codefleet.net/
License:

  Copyright 2013 (kosinix@codefleet.net)

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
if(!defined('CYCLONE_VERSION')){
    define('CYCLONE_VERSION', '2.5.1' );
}
if(!defined('CYCLONE_PATH')){
    define('CYCLONE_PATH', realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR );
}
if(!defined('CYCLONE_URL')){
    define('CYCLONE_URL', plugin_dir_url(__FILE__) );
}

require_once(CYCLONE_PATH.'inc/class-cyclone-slider.php');
require_once(CYCLONE_PATH.'inc/functions.php');
require_once(CYCLONE_PATH.'inc/class-cyclone-slider-widget.php');
require_once(CYCLONE_PATH.'inc/class-image-resizer.php');
require_once(CYCLONE_PATH.'inc/class-nextgen-integration.php');

$cyclone_slider_saved_done = false; //Global variable to limit save_post execution to only once

if(class_exists('Cyclone_Slider')):
    $cyclone_slider_plugin_instance = new Cyclone_Slider(); //Store the plugin instance to a global object so that other plugins can use remove_action and remove_filter against cyclones class functions if needed.
endif;
