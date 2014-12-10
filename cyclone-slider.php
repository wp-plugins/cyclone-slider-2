<?php
/*
Plugin Name: Cyclone Slider 2
Plugin URI: http://www.codefleet.net/cyclone-slider-2/
Description: Create and manage sliders with ease. Built for both casual users and developers.
Version: 2.9.1
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

function cslider_autoloader($classname) {
    if(false !== strpos($classname, 'CycloneSlider')){
        $plugin_dir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR;
        $classname = str_replace('_', DIRECTORY_SEPARATOR, $classname);
        $file = $plugin_dir .'src'.DIRECTORY_SEPARATOR. $classname . '.php';
        require_once $file;
    }
}
spl_autoload_register('cslider_autoloader');

$cyclone_slider_plugin_instance = null;

// Hook the plugin
add_action('plugins_loaded', 'cslider_init', 10);
function cslider_init() {
    global $cyclone_slider_plugin_instance;
    
    $plugin = new CycloneSlider_Main();
    
    $plugin['path'] = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR;
    $plugin['url'] = plugin_dir_url(__FILE__);
    
    $plugin['debug'] = false;
    $plugin['version'] = '2.9.1';
	$plugin['textdomain'] = 'cycloneslider';
    $plugin['slug'] = 'cyclone-slider-2/cyclone-slider.php'; 
    $plugin['nonce_name'] = 'cyclone_slider_builder_nonce';
    $plugin['nonce_action'] = 'cyclone-slider-save';
    
    require_once($plugin['path'].'src/functions.php');
    
    $plugin['view'] = new CycloneSlider_View();
    
    $plugin['image_resizer'] = new CycloneSlider_ImageResizer();
    
    $plugin['data'] = new CycloneSlider_Data();

    $plugin['nextgen_integration'] = new CycloneSlider_NextgenIntegration();
    
    $plugin['templates_manager'] = new CycloneSlider_TemplatesManager();
    
    $plugin['settings_page'] = new CycloneSlider_SettingsPage();
    $plugin['settings_page.page_title'] =  __('Cyclone Slider Settings', $plugin['textdomain']);
    $plugin['settings_page.menu_title'] =  __('Settings', $plugin['textdomain']);
    $plugin['settings_page.option_group'] = 'cyclone_option_group';
    $plugin['settings_page.option_name'] = 'cyclone_option_name';
    $plugin['settings_page.parent_slug'] = 'edit.php?post_type=cycloneslider';
    $plugin['settings_page.menu_slug'] = 'cycloneslider-settings';
    
    
    $plugin['youtube'] = new CycloneSlider_Youtube();
    
    $plugin['vimeo'] = new CycloneSlider_Vimeo();
    
    $plugin['asset_loader'] = new CycloneSlider_AssetLoader();
    
    $plugin['admin'] = new CycloneSlider_Admin();
    
    $plugin['frontend'] = new CycloneSlider_Frontend();
    
    $plugin['widgets'] = new CycloneSlider_Widgets();
    
    $plugin->run();
    
    $cyclone_slider_plugin_instance = $plugin;
}