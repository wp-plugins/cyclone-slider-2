<?php
/**
* Class for import page
*/
class CycloneSlider_ImportPage {
	protected $plugin;
	
	public function run( $plugin ) {
        $this->plugin = $plugin;
        
        // Add page
		add_action( 'admin_menu', array( $this, 'add_menu_and_page'));
        
        // Post
        add_action('init', array( $this, 'catch_posts') );
    }
    
    /**
    * Menu page action hook 
    */
    public function add_menu_and_page(){
        // Use built-in WP function
        add_submenu_page(
            $this->plugin['import_page.parent_slug'],
            $this->plugin['import_page.page_title'],
            $this->plugin['import_page.menu_title'],
            $this->plugin['import_page.capability'],
            $this->plugin['import_page.menu_slug'],
            array( $this, 'render_page')
        );
    }
	
	public function catch_posts(){
		// Verify nonce
		if( isset($_POST[ $this->plugin['nonce_name'] ]) ){
			$nonce = $_POST[ $this->plugin['nonce_name'] ];
			if ( wp_verify_nonce( $nonce, $this->plugin['nonce_action']) ) {
				$uploads = wp_upload_dir(); // Get dir
				if( isset($_POST['cycloneslider_import_step']) ){
					if( $_POST['cycloneslider_import_step'] == 1 ){
						$cyclone_import = array();
						
						// Success
						if( $this->plugin['importer']->import( $_FILES['cycloneslider_import']['tmp_name'], $uploads['basedir'].'/cyclone-slider' ) ){
							$cyclone_import = $this->plugin['importer']->get_results();
							update_option('cycloneslider_import', $cyclone_import);
							wp_redirect( get_admin_url( get_current_blog_id(), 'edit.php?post_type=cycloneslider&page=cycloneslider-import&step=2') );
							exit;
						} else { // Fail
							
						}
					}
				}
			}
		}
	}
	
	/**
	* Render page. This function should output the HTML of the page.
	*/
	public function render_page( $post ){
		$current_step = isset($_GET['step']) ? (int) $_GET['step'] : 1;
		if($current_step == 2){
			$this->step_2();
		} else {
			$this->step_1();
		}
		
	}
	
	public function step_1(){
		$this->plugin['view']->set_view_file( $this->plugin['path'] . 'views/import-step-1.php' );
		$vars = array();
		$vars['nonce_name'] = $this->plugin['nonce_name'];
		$vars['nonce'] = wp_create_nonce( $this->plugin['nonce_action'] );
		$vars['form_url'] = get_admin_url( get_current_blog_id(), 'edit.php?post_type=cycloneslider&page=cycloneslider-import' );
		$vars['export_page_url'] = get_admin_url( get_current_blog_id(), 'edit.php?post_type=cycloneslider&page=cycloneslider-export' );
		$vars['import_page_url'] = get_admin_url( get_current_blog_id(), 'edit.php?post_type=cycloneslider&page=cycloneslider-import' );
		$this->plugin['view']->set_vars( $vars );
		$this->plugin['view']->render();

	}
	public function step_2(){
		$log_results = get_option('cycloneslider_import');
		$defaults = array(
			'oks'=>array(),
			'errors'=>array()
		);
		$log_results = wp_parse_args($log_results, $defaults);
		delete_option('cycloneslider_import');
		
		$this->plugin['view']->set_view_file( $this->plugin['path'] . 'views/import-step-2.php' );
		$vars = array();
		$vars['log_results'] = $log_results;
		$vars['export_page_url'] = get_admin_url( get_current_blog_id(), 'edit.php?post_type=cycloneslider&page=cycloneslider-export' );
		$vars['import_page_url'] = get_admin_url( get_current_blog_id(), 'edit.php?post_type=cycloneslider&page=cycloneslider-import' );
		$this->plugin['view']->set_vars( $vars );
		$this->plugin['view']->render();

	}

} // end class
