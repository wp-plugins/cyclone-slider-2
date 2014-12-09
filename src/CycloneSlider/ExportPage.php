<?php
/**
* Class for export page
*/
class CycloneSlider_ExportPage {
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
            $this->plugin['export_page.parent_slug'],
            $this->plugin['export_page.page_title'],
            $this->plugin['export_page.menu_title'],
            $this->plugin['export_page.capability'],
            $this->plugin['export_page.menu_slug'],
            array( $this, 'render_page')
        );
    }
        
    public function catch_posts(){
        // Verify nonce
        if( isset($_POST[ $this->plugin['nonce_name'] ]) ){
            $nonce = $_POST[ $this->plugin['nonce_name'] ];
            if ( wp_verify_nonce( $nonce, $this->plugin['nonce_action'] ) ) {
                
                if( isset($_POST['cycloneslider_export_step']) ){
                    if( $_POST['cycloneslider_export_step'] == 1 ){
                        $cyclone_export = array();
                        if( isset($_POST['cycloneslider_export']) ){
                            if(!empty($_POST['cycloneslider_export'])){
                                $cyclone_export = $_POST['cycloneslider_export'];
                                update_option('cycloneslider_export', $cyclone_export);
                                wp_redirect( get_admin_url( get_current_blog_id(), 'edit.php?post_type=cycloneslider&page=cycloneslider-export&step=2') );
                                exit;
                            }
                        }
                        update_option('cycloneslider_export', $cyclone_export);
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
        $cycloneslider_export = get_option('cycloneslider_export');
        $defaults = array(
            'all' => 0,
            'sliders' => array()
        );
        $cycloneslider_export = wp_parse_args($cycloneslider_export, $defaults);
        
        $this->plugin['view']->set_view_file( $this->plugin['path'] . 'views/export-step-1.php' );
        $vars = array();
        $vars['sliders'] = $this->plugin['data']->get_sliders();
        $vars['nonce_name'] = $this->plugin['nonce_name'];
        $vars['nonce'] = wp_create_nonce( $this->plugin['nonce_action'] );
        $vars['cycloneslider_export'] = $cycloneslider_export;
        $vars['form_url'] = get_admin_url( get_current_blog_id(), 'edit.php?post_type=cycloneslider&page=cycloneslider-export' );
        $vars['export_page_url'] = get_admin_url( get_current_blog_id(), 'edit.php?post_type=cycloneslider&page=cycloneslider-export' );
        $vars['import_page_url'] = get_admin_url( get_current_blog_id(), 'edit.php?post_type=cycloneslider&page=cycloneslider-import' );
        $this->plugin['view']->set_vars( $vars );
        $this->plugin['view']->render();

    }
    
    public function step_2(){
        $cycloneslider_export = get_option('cycloneslider_export');
        $defaults = array(
            'all' => 0,
            'sliders' => array()
        );
        $cycloneslider_export = wp_parse_args($cycloneslider_export, $defaults);
        
        $uploads = wp_upload_dir();
        $zip_file = $uploads['basedir'].'/cyclone-slider.zip';
        
        $this->plugin['exporter']->export( $zip_file, $cycloneslider_export['sliders'] );
        
        $this->plugin['view']->set_view_file( $this->plugin['path'] . 'views/export-step-2.php' );
        $vars = array();
        $vars['nonce_name'] = $this->plugin['nonce_name'];
        $vars['nonce'] = wp_create_nonce( $this->plugin['nonce_action'] );
        $vars['form_url'] = get_admin_url( get_current_blog_id(), 'edit.php?post_type=cycloneslider&page=cycloneslider-export&step=3' );
        $vars['export_page_url'] = get_admin_url( get_current_blog_id(), 'edit.php?post_type=cycloneslider&page=cycloneslider-export' );
        $vars['import_page_url'] = get_admin_url( get_current_blog_id(), 'edit.php?post_type=cycloneslider&page=cycloneslider-import' );
        $vars['zip_url'] = $uploads['baseurl'].'/cyclone-slider.zip';
        $vars['log_results'] = $this->plugin['exporter']->get_results();
        $this->plugin['view']->set_vars( $vars );
        $this->plugin['view']->render();
    }
} // end class
