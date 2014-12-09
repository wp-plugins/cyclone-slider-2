<?php
/**
* In charge in getting templates from various template locations.
*/
class CycloneSlider_TemplatesManager extends CycloneSlider_Base {
	
	protected $template_locations;
	
	/**
	 * Initializes class
	 */
	public function __construct() {
		$this->template_locations = array();
	}
	
	
	public function bootstrap(){
		
		// Add directories to get templates
		$this->add_template_location(
			array(
				'path' => $this->plugin['path'].'templates'.DIRECTORY_SEPARATOR, // This resides in the plugin
				'url' => $this->plugin['url'].'templates/',
				'location_name' => 'core'
			)
		);
		$this->add_template_location(
			array(
				'path' => realpath(get_stylesheet_directory()).DIRECTORY_SEPARATOR.'cycloneslider'.DIRECTORY_SEPARATOR,// This resides in the current theme or child theme
				'url' => get_stylesheet_directory_uri()."/cycloneslider/",
				'location_name' => 'active-theme'
			)
		);
		$cyclone_upload_dir = wp_upload_dir();
		$cyclone_template_folder = realpath( dirname( $cyclone_upload_dir['basedir'] ) );
		
		$this->add_template_location(
			array(
				'path' => $cyclone_template_folder.DIRECTORY_SEPARATOR.'cycloneslider'.DIRECTORY_SEPARATOR,// This resides in the wp-content folder to prevent deleting when upgrading themes
				'url' => content_url()."/cycloneslider/",
				'location_name' => 'wp-content'
			)
		);
	}
	
	/**
	 * Add a directory to read templates from
	 *
	 * @param string $location - The full path to a directory
	 */
	public function add_template_location( $location ){
		$this->template_locations[] = $location;
	}
	
	/**
	 * Get all templates in array format
	 */
	public function get_all_templates(){
		if(is_array($this->template_locations) and !empty($this->template_locations)){
			$template_folders = array();
			foreach($this->template_locations as $location){
				if($files = @scandir($location['path'])){
					$c = 0;
					foreach($files as $name){
						if($name!='.' and $name!='..' and is_dir($location['path'].$name) and @file_exists($location['path'].$name.DIRECTORY_SEPARATOR.'slider.php') ){ // Check if its a directory
							$ini_array['slide_type'] = array('image');// Default
							if(@file_exists($location['path'].$name.DIRECTORY_SEPARATOR.'config.txt')){
								$ini_array = parse_ini_file($location['path'].$name.DIRECTORY_SEPARATOR.'config.txt'); //Parse ini to get slide types supported
							}
							
							$name = sanitize_title($name);// Change space to dash and all lowercase
							$template_folders[$name] = array( // Here we override template of the same names. If there is a template with the same name in plugin and theme directory, the one in theme will take over
								'path'=>$location['path'].$name,
								'url'=>$location['url'].$name,
								'supports' => $ini_array['slide_type'],
								'location_name' => $location['location_name']
							);
						}
					}
				}
			}
			return $template_folders;
		}
		
	}
	
	/**
	 * Get Active Templates
	 *
	 * Get templates that are enabled in settings page
	 *
	 * @return array Template locations
	 */
	public function get_active_templates(){
		
		$settings_data = $this->plugin['settings_page']->get_settings_data();
		$templates = $this->get_all_templates();

		foreach($templates as $name=>$template){
			
			if( !isset($settings_data['load_templates'][$name]) ){
				$settings_data['load_templates'][$name] = 1;
			}
		}
		return $settings_data['load_templates'];
	}
	
	/**
	 * Get Template Locations
	 *
	 * @return array Template locations
	 */
	public function get_template_locations(){
		return $this->template_locations;
	}
}
