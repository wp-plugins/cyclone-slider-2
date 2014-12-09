<?php
/**
* Class for handling styles and scripts
*/
class CycloneSlider_AssetLoader {
    
    protected $plugin;
    protected $cyclone_settings_data;
	
	public function run( $plugin ) {
        $this->plugin = $plugin;
        
		$this->cyclone_settings_data = $this->plugin['settings_page']->get_settings_data();
		
		// Register frontend styles and scripts
        add_action( 'wp_enqueue_scripts', array( $this, 'register_frontend_scripts' ), 100 );
		
		// Add scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ), 10);
		
		add_action( 'admin_enqueue_scripts', array( $this, 'register_frontend_scripts_in_admin' ), 10);
    }
	
    /**
	* Scripts and styles for slider admin area
	*/ 
	public function register_admin_scripts( $hook ) {
 
		if( 'cycloneslider' == get_post_type() || $hook == 'cycloneslider_page_cycloneslider-settings' || $hook == 'cycloneslider_page_cycloneslider-export' ||$hook == 'cycloneslider_page_cycloneslider-import' ){ // Limit loading to certain admin pages
			
			// Required media files for new media manager. Since WP 3.5+
			wp_enqueue_media();
			
			// Fontawesome style
			wp_enqueue_style( 'font-awesome', $this->plugin['url'].'libs/font-awesome/css/font-awesome.min.css', array(), $this->plugin['version'] );
			
			// Main style
			wp_enqueue_style( 'cycloneslider-admin-styles', $this->plugin['url'].'css/admin.css', array(), $this->plugin['version']  );
			
			// Disable autosave
			wp_dequeue_script( 'autosave' );
			
			// For sortable elements
			wp_enqueue_script('jquery-ui-sortable');
			
			// For localstorage
			wp_enqueue_script( 'store', $this->plugin['url'].'js/store-json2.min.js', array('jquery'), $this->plugin['version'] );
			
			// Allow translation to script texts
			wp_register_script( 'cycloneslider-admin-script', $this->plugin['url'].'js/admin.js', array('jquery'), $this->plugin['version']  );
			wp_localize_script( 'cycloneslider-admin-script', 'cycloneslider_admin_vars',
				array(
					'title'     => __( 'Select an image', 'cycloneslider' ), // This will be used as the default title
					'title2'     => __( 'Select Images - Use Ctrl + Click or Shift + Click', 'cycloneslider' ),
					'button'    => __( 'Add to Slide', 'cycloneslider' ), // This will be used as the default button text
					'button2'    => __( 'Add Images as Slides', 'cycloneslider' ),
					'youtube_url_error'    => __( 'Error. Make sure its a valid YouTube URL.', 'cycloneslider' ) 
				)
			);
			wp_enqueue_script( 'cycloneslider-admin-script');
			
		}
	}
    
	/**
	 * Scripts and styles for slider to run in admin preview. Must be hook to either admin_enqueue_scripts or wp_enqueue_scripts
	 *
	 * @param string $hook Hook name passed by WP
	 * @return void
	 */
	public function register_frontend_scripts_in_admin( $hook ) {
		if( get_post_type() == 'cycloneslider' || 'cycloneslider_page_cycloneslider-settings' == $hook || 'cycloneslider_page_cycloneslider-export' == $hook || 'cycloneslider_page_cycloneslider-import' == $hook ){ // Limit loading to certain admin pages
			$this->register_frontend_scripts( $hook );
		}
	}
	
	/**
	 * Scripts and styles for slider to run. Must be hook to either admin_enqueue_scripts or wp_enqueue_scripts
	 *
	 * @param string $hook Hook name passed by WP
	 * @return void
	 */
	public function register_frontend_scripts( $hook ) {

		$in_footer = true;
		if($this->cyclone_settings_data['load_scripts_in'] == 'header'){
			$in_footer = false;
		}
		
		/*** Magnific Popup Style ***/
		if($this->cyclone_settings_data['load_magnific'] == 1){
			wp_enqueue_style( 'jquery-magnific-popup', $this->plugin['url'].'libs/magnific-popup/magnific-popup.css', array(), $this->plugin['version'] );
		}
		
		/*** Templates Styles ***/
		$this->enqueue_templates_css();
		
		/*****************************/
		
		/*** Core Cycle2 Scripts ***/
		if($this->cyclone_settings_data['load_cycle2'] == 1){
			wp_enqueue_script( 'jquery-cycle2', $this->plugin['url'].'libs/cycle2/jquery.cycle2.min.js', array('jquery'), $this->plugin['version'], $in_footer );
		}
		if($this->cyclone_settings_data['load_cycle2_carousel'] == 1){
			wp_enqueue_script( 'jquery-cycle2-carousel', $this->plugin['url'].'libs/cycle2/jquery.cycle2.carousel.min.js', array('jquery', 'jquery-cycle2'), $this->plugin['version'], $in_footer );
		}
		if($this->cyclone_settings_data['load_cycle2_swipe'] == 1){
			wp_enqueue_script( 'jquery-cycle2-swipe', $this->plugin['url'].'libs/cycle2/jquery.cycle2.swipe.min.js', array('jquery', 'jquery-cycle2'), $this->plugin['version'], $in_footer );
		}
		if($this->cyclone_settings_data['load_cycle2_tile'] == 1){
			wp_enqueue_script( 'jquery-cycle2-tile', $this->plugin['url'].'libs/cycle2/jquery.cycle2.tile.min.js', array('jquery', 'jquery-cycle2'), $this->plugin['version'], $in_footer );
		}
		if($this->cyclone_settings_data['load_cycle2_video'] == 1){
			wp_enqueue_script( 'jquery-cycle2-video', $this->plugin['url'].'libs/cycle2/jquery.cycle2.video.min.js', array('jquery', 'jquery-cycle2'), $this->plugin['version'], $in_footer );
		}
		
		/*** Easing Script***/
		if($this->cyclone_settings_data['load_easing'] == 1){
			wp_enqueue_script( 'jquery-easing', $this->plugin['url'].'libs/jquery-easing/jquery.easing.1.3.1.min.js', array('jquery'), $this->plugin['version'], $in_footer );
		}
		
		/*** Magnific Popup Scripts ***/
		if($this->cyclone_settings_data['load_magnific'] == 1){
			wp_enqueue_script( 'jquery-magnific-popup', $this->plugin['url'].'libs/magnific-popup/jquery.magnific-popup.min.js', array('jquery'), $this->plugin['version'], $in_footer );
		}
		
		/*** Templates Scripts ***/
		$this->enqueue_templates_scripts();
		
		/*** Client Script ***/
		wp_enqueue_script( 'cyclone-client', $this->plugin['url'].'js/client.js', array('jquery'), $this->plugin['version'], $in_footer );

	}
	
	/**
	* Enqueues templates styles.
	*/
	private function enqueue_templates_css(){
		$ds = DIRECTORY_SEPARATOR;
		 
		$template_folders = $this->plugin['templates_manager']->get_all_templates();
		$active_templates = $this->plugin['templates_manager']->get_active_templates( $this->cyclone_settings_data );
		
		foreach($template_folders as $name=>$folder){
			
			if( 1 == $active_templates[$name] ){
				$file = $folder['path']."/style.css"; // Path to file
				
				if( file_exists( $file ) ){ // Check existence
					wp_enqueue_style( 'cyclone-template-style-'.sanitize_title($name), $folder['url'].'/style.css', array(), $this->plugin['version'] );
				}
			}
		}
	}
   
	/**
	* Enqueues templates scripts.
	*/
	private function enqueue_templates_scripts(){
		$ds = DIRECTORY_SEPARATOR;
 
		$in_footer = true;
		if($this->cyclone_settings_data['load_scripts_in'] == 'header'){
			$in_footer = false;
		}
		
		$template_folders = $this->plugin['templates_manager']->get_all_templates();
		$active_templates = $this->plugin['templates_manager']->get_active_templates( $this->cyclone_settings_data );
		
		foreach($template_folders as $name=>$folder){
			
			if( 1 == $active_templates[$name] ){
				$file = $folder['path']."/script.js"; // Path to file
				
				if( file_exists( $file ) ){ // Check existence
					wp_enqueue_script( 'cyclone-template-script-'.sanitize_title($name), $folder['url'].'/script.js', array(), $this->plugin['version'], $in_footer );
				}
			}
		}
	}
}