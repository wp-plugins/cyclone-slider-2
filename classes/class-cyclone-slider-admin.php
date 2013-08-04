<?php
if(!class_exists('Cyclone_Slider_Admin')):
    
    /**
    * Class for displaying cyclone admin screen
    */
    class Cyclone_Slider_Admin {
        
        private $view; // Holds the instance of Cyclone_Slider_View
        public $slider_count;
        private $message_id;
        public $templates_manager;
        
        /**
         * Initializes the plugin by setting localization, filters, and administration functions.
         */
        public function __construct( $view, $templates_manager ) {
            
            $this->view = $view;
            $this->templates_manager = $templates_manager;
            
            // Set defaults
            $this->slider_count = 0;

            // Register admin styles and scripts
            add_action( 'admin_enqueue_scripts', array( $this, 'register_wp_media' ), 9);
            add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ), 10);
        
            // Add admin menus
            add_action( 'init', array( $this, 'create_post_types' ) );
            
            // Update the messages for our custom post make it appropriate for slideshow
            add_filter('post_updated_messages', array( $this, 'post_updated_messages' ) );
            
            // Remove metaboxes
            add_action( 'admin_menu', array( $this, 'remove_meta_boxes' ) );
            
            // Add slider metaboxes
            add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
            
            // Hacky way to change text in thickbox
            add_filter( 'gettext', array( $this, 'replace_text_in_thickbox' ), 10, 3 );
            
            // Modify html of image
            add_filter( 'image_send_to_editor', array( $this, 'image_send_to_editor'), 1, 8 );
            
            // Custom columns
            add_action( 'manage_cycloneslider_posts_custom_column', array( $this, 'custom_column' ), 10, 2);
            add_filter( 'manage_edit-cycloneslider_columns', array( $this, 'slideshow_columns') );
            
            // Add hook for admin footer
            add_action('admin_footer', array( $this, 'admin_footer') );
            
            // Add body css for custom styling when on our page
            add_filter('admin_body_class', array( $this, 'body_class' ) );

        
            // Add hook for ajax operations if logged in
            add_action( 'wp_ajax_cycloneslider_get_video', array( $this, 'cycloneslider_get_video' ) );
            
          
            $version = get_option('cycloneslider_version');
            
            $this->templates_manager->add_template_location(
                array(
                    'path'=>Cyclone_Slider_Data::get_templates_folder(), //this resides in the plugin
                    'url'=>CYCLONE_URL.'templates/'
                )
            );
            $this->templates_manager->add_template_location(
                array(
                    'path'=> realpath(get_stylesheet_directory()).DIRECTORY_SEPARATOR.'cycloneslider'.DIRECTORY_SEPARATOR,//this resides in the current theme or child theme
                    'url'=> get_stylesheet_directory_uri()."/cycloneslider/"
                )
            );
            
        } // end constructor
        
        /**
         * Add js and css for WP media manager.
         */ 
        function body_class( $classes ) {
            if('cycloneslider' == get_post_type()){
                $classes .= 'cycloneslider';
            }
            return $classes;
        }
        
        /**
         * Add js and css for WP media manager.
         */ 
        public function register_wp_media(){
            global $wp_version;
            
            if('cycloneslider' == get_post_type()){ /* Load only scripts here and not on all admin pages */
                
                if ( version_compare( $wp_version, '3.5', '<' ) ) { // Use old media manager
                    
                    wp_enqueue_style('thickbox');
                    
                    wp_enqueue_script('media-upload');
                    wp_enqueue_script('thickbox');
                    
                } else {
                    // Required media files for new media manager. Since WP 3.5+
                    wp_enqueue_media();
                }
            }
        }
        
        /**
         * Admin js and css
         */ 
        public function register_admin_scripts() {
            
            if('cycloneslider' == get_post_type()){ /* Load only scripts here and not on all admin pages */
                
                wp_enqueue_style( 'cycloneslider-admin-styles', CYCLONE_URL.'css/admin.css', array(), CYCLONE_VERSION  );
                
                //scripts
                wp_dequeue_script( 'autosave' );//disable autosave
                

                wp_enqueue_script('jquery-ui-sortable');
                
                wp_enqueue_script( 'store', CYCLONE_URL.'js/store-json2.min.js', array('jquery'), CYCLONE_VERSION );
                
                wp_register_script( 'cycloneslider-admin-script', CYCLONE_URL.'js/admin.js', array('jquery'), CYCLONE_VERSION  );
                wp_localize_script( 'cycloneslider-admin-script', 'cycloneslider_admin_vars',
                    array(
                        'title'     => __( 'Select an image', 'cycloneslider' ), // This will be used as the default title
                        'button'    => __( 'Add to Slide', 'cycloneslider' )            // This will be used as the default button text
                    )
                );
                wp_enqueue_script( 'cycloneslider-admin-script');
                
            }
        }
        
        /**
         * Create Post Types
         *
         * Create custom post for slideshows
         */
        public function create_post_types() {
            register_post_type( 'cycloneslider',
                array(
                    'labels' => array(
                        'name' => __('Cyclone Slider', 'cycloneslider'),
                        'singular_name' => __('Slideshow', 'cycloneslider'),
                        'add_new' => __('Add Slideshow', 'cycloneslider'),
                        'add_new_item' => __('Add New Slideshow', 'cycloneslider'),
                        'edit_item' => __('Edit Slideshow', 'cycloneslider'),
                        'new_item' => __('New Slideshow', 'cycloneslider'),
                        'view_item' => __('View Slideshow', 'cycloneslider'),
                        'search_items' => __('Search Slideshows', 'cycloneslider'),
                        'not_found' => __('No slideshows found', 'cycloneslider'),
                        'not_found_in_trash' => __('No slideshows found in Trash', 'cycloneslider')
                    ),
                    'supports' => array('title'),
                    'public' => false,
                    'exclude_from_search' => true,
                    'show_ui' => true,
                    'menu_position' => 100
                )
            );
        }
        
        /**
         * Add custom messages
         * 
         * @return array Messages for cyclone
         */
        public function post_updated_messages($messages){
            global $post, $post_ID;
            $messages['cycloneslider'] = array(
                0  => '',
                1  => __( 'Slideshow updated.', 'cycloneslider' ),
                2  => __( 'Custom field updated.', 'cycloneslider' ),
                3  => __( 'Custom field deleted.', 'cycloneslider' ),
                4  => __( 'Slideshow updated.', 'cycloneslider' ),
                5  => __( 'Slideshow updated.', 'cycloneslider' ),
                6  => __( 'Slideshow published.', 'cycloneslider' ),
                7  => __( 'Slideshow saved.', 'cycloneslider' ),
                8  => __( 'Slideshow updated.', 'cycloneslider' ),
                9  => __( 'Slideshow updated.', 'cycloneslider' ),
                10 => __( 'Slideshow updated.', 'cycloneslider' ),
                101 => sprintf( __( 'Templates CSS could not be saved. Make sure %stemplates.css is writable.', 'cycloneslider' ), CYCLONE_PATH.'css'.DIRECTORY_SEPARATOR),
                102 => sprintf( __( 'Templates JS could not be saved. Make sure %stemplates.js is writable.', 'cycloneslider' ), CYCLONE_PATH.'js'.DIRECTORY_SEPARATOR)
            );
            return $messages;
        }
        
        /**
         * Show custom messages
         * 
         * @return array The array of locations containing path and url 
         */
        public function throw_message($location) {
            $location = add_query_arg( 'message', $this->message_id, $location );
            $this->message_id = 0;
            return $location;
        }
        
        /**
         * Remove Meta Boxes
         *
         * Remove built-in metaboxes from our custom post type
         */
        public function remove_meta_boxes(){
            remove_meta_box('slugdiv', 'cycloneslider', 'normal');
        }
        
        /**
         * Add Meta Boxes
         *
         * Add custom metaboxes to our custom post type
         */
        public function add_meta_boxes(){
            
            add_meta_box(
                'cyclone-slides-metabox',
                __('Slides', 'cycloneslider'),
                array( $this, 'render_slides_meta_box' ),
                'cycloneslider' ,
                'normal',
                'high'
            );
            add_meta_box(
                'cyclone-slider-codes',
                __('Get Slider Codes', 'cycloneslider'),
                array( $this, 'render_slider_codes' ),
                'cycloneslider' ,
                'side',
                'low'
            );
            add_meta_box(
                'cyclone-slider-properties-metabox',
                __('Slideshow Settings', 'cycloneslider'),
                array( $this, 'render_slider_properties_meta_box' ),
                'cycloneslider' ,
                'side',
                'low'
            );
            add_meta_box(
                'cyclone-slider-templates-metabox',
                __('Slideshow Templates', 'cycloneslider'),
                array( $this, 'render_slider_templates_meta_box' ),
                'cycloneslider' ,
                'normal',
                'low'
            );
            add_meta_box(
                'cyclone-slider-id',
                __('Slideshow ID', 'cycloneslider'),
                array( $this, 'render_slider_id' ),
                'cycloneslider' ,
                'normal',
                'low'
            );
        }
        
        
        
        /**
         * Metabox for slides
         */
        public function render_slides_meta_box($post){
            
            $slides_html = '';
            
            $slider_settings = Cyclone_Slider_Data::get_slideshow_settings($post->ID);
            $slides = Cyclone_Slider_Data::get_slides($post->ID);

            if(is_array($slides) and count($slides)>0):
                
                $this->view->set_view_file( CYCLONE_PATH . 'views/slide-edit.php' );
                
                foreach($slides as $i=>$slide):
                
                    $image_url = $this->get_slide_img_thumb($slide['id']);
                    $image_url = apply_filters('cycloneslider_preview_url', $image_url, $slide);
                    $box_title = apply_filters('cycloneslider_box_title', __('Slide', 'cycloneslider'), $slide).' '.($i+1);
                    
                    $vars = array();
                    $vars['i'] = $i;
                    $vars['slider_settings'] = $slider_settings;
                    $vars['slide'] = $slide;
                    $vars['image_url'] = $image_url;
                    $vars['box_title'] = $box_title;
                    $vars['debug'] = (CYCLONE_DEBUG) ? Cyclone_Slider_Data::debug_r($slide) : '';
                    $vars['effects'] = Cyclone_Slider_Data::get_slide_effects();
                    
                    $this->view->set_vars( $vars );
            
                    $slides_html .= $this->view->get_render();
                    
                endforeach;
            endif;
            
            $this->view->set_view_file( CYCLONE_PATH . 'views/slides.php' );
            
            $vars = array();
            $vars['slides'] = $slides_html;
            $vars['post_id'] = $post->ID;
            $vars['nonce_name'] = Cyclone_Slider_Data::$nonce_name;
            $vars['nonce'] = wp_create_nonce( Cyclone_Slider_Data::$nonce_action );
            
            $this->view->set_vars( $vars );
            $this->view->render();
        }
        
        /**
         * Metabox for slider codes
         */
        public function render_slider_codes( $post ){
            
            $this->view->set_view_file( CYCLONE_PATH . 'views/slider-codes.php' );
            
            $vars = array();
            $vars['post'] = $post;
            if(empty($post->post_name)){
                $vars['shortcode'] = '';
                $vars['template_code'] = '';
            } else {
                $vars['shortcode'] = '[cycloneslider id="'.$post->post_name.'"]';
                $vars['template_code'] = '<?php echo do_shortcode( \'[cycloneslider id="'.$post->post_name.'"]\' ); ?>';
            }
            $this->view->set_vars( $vars );
            $this->view->render();

        }
        
        /**
         * Metabox for slide properties
         */
        public function render_slider_properties_meta_box($post){
            $slider_settings = Cyclone_Slider_Data::get_slideshow_settings($post->ID);
            
            $this->view->set_view_file( CYCLONE_PATH . 'views/slider-properties.php' );
            
            $vars = array();
            $vars['slider_settings'] = $slider_settings;
            $vars['effects'] = Cyclone_Slider_Data::get_slide_effects();
            $vars['debug'] = (CYCLONE_DEBUG) ? Cyclone_Slider_Data::debug_r($slider_settings) : '';
            
            $this->view->set_vars( $vars );
            $this->view->render();

        }
        
        /**
         * Metabox for templates
         */
        public function render_slider_templates_meta_box($post){

            $slider_settings = Cyclone_Slider_Data::get_slideshow_settings($post->ID);
            $templates = $this->templates_manager->get_all_templates();
            
            $this->view->set_view_file( CYCLONE_PATH . 'views/template-selection.php' );
            
            $vars = array();
            $vars['slider_settings'] = $slider_settings;
            $vars['templates'] = $templates;
            $vars['debug'] = (CYCLONE_DEBUG) ? Cyclone_Slider_Data::debug_r($templates) : '';
            
            $this->view->set_vars( $vars );
            $this->view->render();
        }
        
        /**
         * Metabox for slider ID
         */
        public function render_slider_id( $post ){
            
            $this->view->set_view_file( CYCLONE_PATH . 'views/slider-id.php' );
            
            $vars = array();
            $vars['post_name'] = $post->post_name;

            $this->view->set_vars( $vars );
            $this->view->render();

        }
        
        /**
         * Hook to admin footer
         */
        public function admin_footer() {
            // JS skeleton for adding a slide
            if(get_post_type()=='cycloneslider'){
                // Empty Slide
                $this->view->set_view_file( CYCLONE_PATH . 'views/slide-edit.php' );
                
                $vars = array();
                $vars['box_title'] = __('Slide *', 'cycloneslider');
                $vars['image_url'] = '';
                $vars['i'] = '{id}';
                $vars['slide'] = Cyclone_Slider_Data::get_slide_defaults();
                foreach($vars['slide'] as $key=>$value){
                    $vars['slide'][$key] = '';
                }
                $vars['slide']['type'] = 'image';
                $vars['effects'] = Cyclone_Slider_Data::get_slide_effects();
                $vars['debug'] = (CYCLONE_DEBUG) ? Cyclone_Slider_Data::debug_r($vars['slide']) : '';
                $this->view->set_vars( $vars );
                $empty_slide = $this->view->get_render();
                
                // Main skeleton container
                $this->view->set_view_file( CYCLONE_PATH . 'views/slides-skeleton.php' );
                
                $vars = array();
                $vars['empty_slide'] = $empty_slide;
               
                $this->view->set_vars( $vars );
                $this->view->render();
            }
        }
       
        
        /**
         * Get slide image thumb from id. False on fail
         */
        private function get_slide_img_thumb($attachment_id){
            $attachment_id = (int) $attachment_id;
            if($attachment_id > 0){
                $image_url = wp_get_attachment_image_src( $attachment_id, 'medium', true );
                $image_url = (is_array($image_url)) ? $image_url[0] : '';
                return $image_url;
            }
            return false;
        }
        
        /**
         * Replace text in media button for WP < 3.5
         */
        public function replace_text_in_thickbox($translation, $text, $domain ) {
            $http_referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
            $req_referrer = isset($_REQUEST['referer']) ? $_REQUEST['referer'] : '';
            if(strpos($http_referrer, 'cycloneslider')!==false or $req_referrer=='cycloneslider') {
                if ( 'default' == $domain and 'Insert into Post' == $text )
                {
                    return 'Add to Slide';
                }
            }
            return $translation;
        }
        
        // Add attachment ID as html5 data attr in thickbox
        public function image_send_to_editor( $html, $id, $caption, $title, $align, $url, $size, $alt = '' ){
            if(strpos($html, '<img data-id="')===false){
                $html = str_replace('<img', '<img data-id="'.$id.'" ', $html);
            }
            return $html;
        }
        
        // Modify columns
        public function slideshow_columns($columns) {
            $columns = array();
            $columns['title']= __('Slideshow Name', 'cycloneslider');
            $columns['template']= __('Template', 'cycloneslider');
            $columns['images']= __('No. of Slides', 'cycloneslider');
            $columns['id']= __('Slideshow ID', 'cycloneslider');
            $columns['shortcode']= __('Shortcode', 'cycloneslider');
            return $columns;
        }
        
        // Add content to custom columns
        public function custom_column( $column_name, $post_id ){
            if ($column_name == 'template') {
                $settings = Cyclone_Slider_Data::get_slideshow_settings($post_id);
                echo ucwords($settings['template']);
            }
            if ($column_name == 'images') {
                echo '<div style="text-align:center; max-width:40px;">' . Cyclone_Slider_Data::get_image_count($post_id) . '</div>';
            }
            if ($column_name == 'id') {
                $post = get_post($post_id);
                echo $post->post_name;
            }
            if ($column_name == 'shortcode') {  
                $post = get_post($post_id);
                echo '[cycloneslider id="'.$post->post_name.'"]';
            }  
        }
        
        
        
        // Compare the value from admin and shortcode. If shortcode value is present and not empty, use it, otherwise return admin value
        public function get_comp_slider_setting($admin_val, $shortcode_val){
            if($shortcode_val!==null){//make sure its really null and not just int zero 0
                return $shortcode_val;
            }
            return $admin_val;
        }
    
        // Return array of slide urls from meta
        public function get_slides_from_meta($slider_metas){
            $slides = array();
            if(is_array($slider_metas)){
                foreach($slider_metas as $slider_meta){
                    $attachment_id = (int) $slider_meta['id'];
                    $image_url = wp_get_attachment_url($attachment_id);
                    $image_url = ($image_url===false) ? '' : $image_url;
                    $slides[] = $image_url;
                }
            }
            return $slides;
        }
        
        
        /**
         * YOUTUBE & VIMEO
         */
        
        /**
         * Ajax for getting videos
         */
        public function cycloneslider_get_video(){
            $url = $_POST['url'];
            
            $retval = array(
                'success' => false
            );
            
            if (filter_var($url, FILTER_VALIDATE_URL) !== FALSE) {

                if( $video_id = $this->get_youtube_id($url) ){ //If youtube url
                    if( $embed = wp_oembed_get($url) ){ //Get embed, false on fail
                        $retval = array(
                            'success' => true,
                            'url' => $this->get_youtube_thumb($video_id),
                            'embed' => $embed
                        );
                    }
                    
                } else if( $video_id = $this->get_vimeo_id($url) ){ //If vimeo url
                    if( $embed = wp_oembed_get($url) ){ //Get embed, false on fail
                        $retval = array(
                            'success' => true,
                            'url' => $this->get_vimeo_thumb($video_id),
                            'embed' => $embed
                        );
                    }
                }
            }
            
            echo json_encode($retval);
            die();
        }
        
        /**
         * Get video thumb url
         *
         * @param string $url A valid youtube or vimeo url
         */
        public function get_video_thumb_from_url($url){
            $url = esc_url_raw($url);
                
            if ( $video_id = $this->get_youtube_id($url) ) { // A youtube url

                return $this->get_youtube_thumb($video_id);
                
            } else if( $video_id = $this->get_vimeo_id($url) ){ // A vimeo url
                
                return $this->get_vimeo_thumb($video_id);
            }
            
            return false;
        }
        
        /**
         * Return vimeo video id
         */
        public function get_vimeo_id($url){
            
            $parsed_url = parse_url($url);
            if ($parsed_url['host'] == 'vimeo.com'){
                $vimeo_id = ltrim( $parsed_url['path'], '/');
                if (is_numeric($vimeo_id)) {
                    return $vimeo_id;
                }
            }
            return false;
        }
        
        /**
         * Get vimeo video thumbnail image
         *
         * @param int Vimeo ID.
         * @param string Size can be: thumbnail_small, thumbnail_medium, thumbnail_large.
         *
         * @return string URL of thumbnail image.
         */
        public function get_vimeo_thumb($video_id, $size = 'thumbnail_large'){
            $vimeo = unserialize( file_get_contents('http://vimeo.com/api/v2/video/'.$video_id.'.php') );
            if( isset($vimeo[0][$size]) ){
                return $vimeo[0][$size];
            }
            return '';
        }
    
        /**
         * Get youtube video thumbnail image
         *
         * @param int Youtube ID.
         *
         * @return string URL of thumbnail image.
         */
        public function get_youtube_thumb($video_id){
            return 'http://img.youtube.com/vi/'.$video_id.'/0.jpg';
        }
        
        /**
         * Get youtube ID from different url formats
         *
         * @param string $url Youtube url
         * @return string Youtube URL or boolean false on fail
         */
        public function get_youtube_id($url){
            if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
                return false;
            }
            $parsed_url = parse_url($url);
           
            if(strpos($parsed_url['host'], 'youtube.com')!==false){
                if(strpos($parsed_url['path'], '/watch')!==false){ // Regular url Eg. http://www.youtube.com/watch?v=9bZkp7q19f0
                    parse_str($parsed_url['query'], $parsed_str);
                    if(isset($parsed_str['v']) and !empty($parsed_str['v'])){
                        return $parsed_str['v'];
                    }
                } else if(strpos($parsed_url['path'], '/v/')!==false){ // "v" URL http://www.youtube.com/v/9bZkp7q19f0?version=3&autohide=1
                    $id = str_replace('/v/','',$parsed_url['path']);
                    if( !empty($id) ){
                        return $id;
                    }
                } else if(strpos($parsed_url['path'], '/embed/')!==false){ // Embed URL: http://www.youtube.com/embed/9bZkp7q19f0
                    return str_replace('/embed/','',$parsed_url['path']);
                }
            } else if(strpos($parsed_url['host'], 'youtu.be')!==false){ // Shortened URL: http://youtu.be/9bZkp7q19f0
                return str_replace('/','',$parsed_url['path']);
            }
            
            return false;
        }
    } // end class
    
endif;