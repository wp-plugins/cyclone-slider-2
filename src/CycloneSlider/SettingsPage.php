<?php
/**
* Class for settings page
*/
class CycloneSlider_SettingsPage extends CycloneSlider_Base {
	
	
	public function run() {

		// Add settings
		add_action( 'admin_init', array( $this, 'register_settings') );
	
		// Add settings page
		add_action( 'admin_menu', array( $this, 'add_menu_and_page'));
	}
	
	public function add_menu_and_page(){
		// Use built-in WP function
		add_submenu_page(
			$this->plugin['settings_page.parent_slug'],
			$this->plugin['settings_page.page_title'],
			$this->plugin['settings_page.menu_title'],
			'manage_options',
			$this->plugin['settings_page.menu_slug'],
			array( $this, 'render_settings_page')
		);
	}
	
	/**
	* Render settings page. This function should echo the HTML form of the settings page.
	*/
	public function render_settings_page($post){
		
		
		$settings_data = $this->get_settings_data();
		$templates = $this->plugin['templates_manager']->get_all_templates();

		$settings_data['load_templates'] = $this->plugin['templates_manager']->get_active_templates( $settings_data );// Filter load templates

		$vars = array();
		$vars['page_title'] = $this->plugin['settings_page.page_title'];
		$vars['screen_icon'] = $this->get_screen_icon('options-general'); ;
		$vars['textdomain'] = $this->plugin['textdomain'];
		$vars['settings_fields'] = $this->settings_fields( $this->plugin['settings_page.option_group'] );
		$vars['option_name'] = $this->plugin['settings_page.option_name'];
		$vars['templates'] = $templates;
		$vars['settings_data'] = $settings_data;
		$vars['debug'] = ($this->plugin['debug']) ? cyclone_slider_debug( $vars['settings_data'] ) : '';
		
		$this->plugin['view']->render( 'settings-page.php', $vars);
	}
	
	/**
	* Prepare option data
	*/
	public function register_settings() {
		register_setting(
			$this->plugin['settings_page.option_group'],
			$this->plugin['settings_page.option_name'],
			array( $this, 'validate_options')
		);
	}
		
	/**
	* Validate data from HTML form
	*/
	public function validate_options( $input ) {
		$input = wp_parse_args($input, $this->get_settings_data());
		
        delete_site_transient('update_plugins'); // Force check. Regenerate package url for updater
		
		if( isset($_POST['reset']) ){
			$input = $this->get_default_settings_data();
			add_settings_error( $this->menu_slug, 'restore_defaults', __( 'Default options restored.', 'cycloneslider'), 'updated fade' );
		}
		return $input;
	}
	
	/**
	* Get settings data. If there is no data from database, use default values
	*/
	public function get_settings_data(){
		return get_option( $this->plugin['settings_page.option_name'], $this->get_default_settings_data() );
	}
	
	/**
	* Apply default values
	*/
	public function get_default_settings_data() {
		$defaults = array();
		$defaults['load_scripts_in'] = 'footer';
		
		$defaults['load_cycle2'] = 1;
		$defaults['load_cycle2_carousel'] = 1;
		$defaults['load_cycle2_swipe'] = 1;
		$defaults['load_cycle2_tile'] = 1;
		$defaults['load_cycle2_video'] = 1;

		$defaults['load_easing'] = 1;
		
		$defaults['load_magnific'] = 1;
		
		$defaults['load_templates'] = array();
		
		$defaults['script_priority'] = 100;
		
		$defaults['license_id'] = '';
		$defaults['license_key'] = '';
		
		return $defaults;
	}
	
	/**
	* Output needed fields for security
	*/
	function settings_fields( $option_group ) {
		$fields = "<input type='hidden' name='option_page' value='" . esc_attr($option_group) . "' />";
		$fields .= '<input type="hidden" name="action" value="update" />';
		$fields .= wp_nonce_field("$option_group-options", '_wpnonce', true, false);
		return $fields;
	}
	
		
	
	/**
	* Get settings data by uid
	*/
	public function get_data($uid){
		$settings_data = $this->get_settings_data();
		if(isset($settings_data[$uid])){
			return $settings_data[$uid];
		}
		return false;
	}
	
	protected function get_screen_icon( $icon ){
		global $wp_version;
		
		if ( version_compare( $wp_version, '3.7', '<=' ) ) { // WP 3.7 and below
			return get_screen_icon( $icon );
		}
		return ''; // Screen icons are no longer used as of WordPress 3.8
	}
	
} // end class
