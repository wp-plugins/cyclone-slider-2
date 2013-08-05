<?php
/*
Plugin Name: Cyclone Slider 2
Plugin URI: http://www.codefleet.net/cyclone-slider-2/
Description: Create responsive slideshows with ease. Built for both developers and non-developers.
Version: 2.6.1
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
    define('CYCLONE_VERSION', '2.6.1' );
}
if(!defined('CYCLONE_PATH')){
    define('CYCLONE_PATH', realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR );
}
if(!defined('CYCLONE_URL')){
    define('CYCLONE_URL', plugin_dir_url(__FILE__) );
}
if(!defined('CYCLONE_DEBUG')){
    define('CYCLONE_DEBUG', false );
}

// Include common classes
require_once(CYCLONE_PATH.'classes/codefleet/class-codefleet-view.php');
require_once(CYCLONE_PATH.'classes/codefleet/class-codefleet-settings-page.php');
require_once(CYCLONE_PATH.'classes/codefleet/class-codefleet-settings-sub-page.php');

require_once(CYCLONE_PATH.'classes/class-cyclone-slider-settings.php');
require_once(CYCLONE_PATH.'classes/class-cyclone-slider-data.php');
require_once(CYCLONE_PATH.'classes/class-cyclone-slider-admin.php');
require_once(CYCLONE_PATH.'classes/class-cyclone-slider.php');
require_once(CYCLONE_PATH.'classes/class-cyclone-slider-widget.php');
require_once(CYCLONE_PATH.'classes/class-cyclone-templates-manager.php');
require_once(CYCLONE_PATH.'classes/class-image-resizer.php');
require_once(CYCLONE_PATH.'classes/class-nextgen-integration.php');
require_once(CYCLONE_PATH.'classes/class-cyclone-slider-settings.php');
require_once(CYCLONE_PATH.'inc/functions.php');

$cyclone_slider_saved_done = false; //Global variable to limit save_post execution to only once

// Load domain in this hook to work with WPML
add_action('plugins_loaded', 'cycloneslider_plugin_init');
function cycloneslider_plugin_init() {
    load_plugin_textdomain( 'cycloneslider', false, 'cyclone-slider-2/lang' ); 
}

// Store the plugin instance to a global object so that other plugins can use remove_action and remove_filter
// Inject dependencies here
$cyclone_slider_data = new Cyclone_Slider_Data();

$cyclone_slider_settings = new Cyclone_Slider_Settings();
$cyclone_slider_settings->set_option_group('cyclone_option_group');
$cyclone_slider_settings->set_option_name('cyclone_option_name');
$cyclone_slider_settings->set_parent_slug('edit.php?post_type=cycloneslider');
$cyclone_slider_settings->set_page_title('Cyclone Slider Settings');
$cyclone_slider_settings->set_menu_title('Settings');
$cyclone_slider_settings->set_menu_slug('cycloneslider-settings');
$cyclone_slider_settings->show();

$cyclone_slider_admin = new Cyclone_Slider_Admin( new Codefleet_View(), new Cyclone_Templates_Manager() ); 
$cyclone_slider_plugin_instance = new Cyclone_Slider( new Cyclone_Templates_Manager(), $cyclone_slider_settings->get_settings_data() );

