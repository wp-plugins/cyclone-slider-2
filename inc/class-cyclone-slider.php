<?php
if(!class_exists('Cyclone_Slider')):
    class Cyclone_Slider {
        public $slider_count;
        public $effects;
        public $debug;
        private $message_id;
        
        /**
         * Initializes the plugin by setting localization, filters, and administration functions.
         */
        public function __construct() {
            // Set defaults
            $this->slider_count = 0;
            $this->effects = self::get_slide_effects();
            $this->debug = false;
            
            load_plugin_textdomain( 'cycloneslider', false, 'cyclone-slider-2/lang' );
            
            // Register admin styles and scripts
            add_action( 'admin_enqueue_scripts', array( $this, 'register_wp_media' ), 9);
            add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ), 10);
        
            // Register frontend styles and scripts
            add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ), 100 );
            
            
            // Add admin menus
            add_action( 'init', array( $this, 'create_post_types' ) );
            
            // Update the messages for our custom post make it appropriate for slideshow
            add_filter('post_updated_messages', array( $this, 'post_updated_messages' ) );
            
            // Add slider metaboxes
            add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
            
            // Save slides
            add_action( 'save_post', array( $this, 'save_post' ) );
            
            // Hacky way to change text in thickbox
            add_filter( 'gettext', array( $this, 'replace_text_in_thickbox' ), 10, 3 );
            
            // Modify html of image
            add_filter( 'image_send_to_editor', array( $this, 'image_send_to_editor'), 1, 8 );
            
            // Custom columns
            add_action( 'manage_cycloneslider_posts_custom_column', array( $this, 'custom_column' ), 10, 2);
            add_filter( 'manage_edit-cycloneslider_columns', array( $this, 'slideshow_columns') );
            
            // Add hook for admin footer
            add_action('admin_footer', array( $this, 'admin_footer') );
            
            // Our shortcode
            add_shortcode('cycloneslider', array( $this, 'cycloneslider_shortcode') );
            
            // Add hook for ajax operations if logged in
            add_action( 'wp_ajax_cycloneslider_get_video', array( $this, 'cycloneslider_get_video' ) );
            
          
            $version = get_option('cycloneslider_version');
            if($version==false or $version!=CYCLONE_VERSION){
                // Upgrade notice
                add_action('admin_notices', array( $this, 'upgrade_notice') ); 
            }
        } // end constructor
        
        
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
                
                wp_enqueue_style( 'cycloneslider-admin-styles', self::url().'css/admin.css', array(), CYCLONE_VERSION  );
                
                //scripts
                wp_dequeue_script( 'autosave' );//disable autosave
                

                wp_enqueue_script('jquery-ui-sortable');
                
                wp_enqueue_script( 'store', self::url().'js/store-json2.min.js', array('jquery'), CYCLONE_VERSION );
                
                wp_register_script( 'cycloneslider-admin-script', self::url().'js/admin.js', array('jquery'), CYCLONE_VERSION  );
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
         * Registers and enqueues frontend-specific scripts.
         */
        public function register_plugin_scripts() {
            /*** Styles ***/
            wp_enqueue_style( 'cyclone-templates-styles', self::url().'css/templates.css', array(), CYCLONE_VERSION );
            
            /*** Scripts ***/
            wp_enqueue_script( 'cyclone-slider', self::url().'js/cyclone-slider.min.js', array('jquery'), CYCLONE_VERSION ); //Consolidated cycle2 script and plugins
            
            wp_enqueue_script( 'cyclone-templates-scripts', self::url().'js/templates.js', array('jquery'), CYCLONE_VERSION );//Contains our combined css from ALL templates

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
                1  => sprintf( __( 'Slideshow updated. Shortcode is [cycloneslider id="%s"]', 'cycloneslider' ), $post->post_name),
                2  => __( 'Custom field updated.', 'cycloneslider' ),
                3  => __( 'Custom field deleted.', 'cycloneslider' ),
                4  => __( 'Slideshow updated.', 'cycloneslider' ),
                5  => __( 'Slideshow updated.', 'cycloneslider' ),
                6  => sprintf( __( 'Slideshow published. Shortcode is [cycloneslider id="%s"]', 'cycloneslider' ), $post->post_name),
                7  => __( 'Slideshow saved.', 'cycloneslider' ),
                8  => __( 'Slideshow updated.', 'cycloneslider' ),
                9  => __( 'Slideshow updated.', 'cycloneslider' ),
                10 => __( 'Slideshow updated.', 'cycloneslider' ),
                101 => sprintf( __( 'Templates CSS could not be saved. Make sure %stemplates.css is writable.', 'cycloneslider' ), self::path().'css'.DIRECTORY_SEPARATOR),
                102 => sprintf( __( 'Templates JS could not be saved. Make sure %stemplates.js is writable.', 'cycloneslider' ), self::path().'js'.DIRECTORY_SEPARATOR)
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
         * Show upgrade notice
         * 
         * @return void
         */
        public function upgrade_notice() {
            // Only show to admins and cyclone page
            if('cycloneslider' == get_post_type() and current_user_can('manage_options') ) {
               echo '<div id="message" class="error"><p>'.__( 'Please resave any one of your slideshow to update the template CSS and JS files.', 'cycloneslider' ).'</p></div>';
            }
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
        }
        
        /**
         * Metabox for slides
         */
        public function render_slides_meta_box($post){
            
            // Use nonce for verification
            echo '<input type="hidden" name="cycloneslider_metabox_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
            
            $slider_settings = self::get_slideshow_settings($post->ID);
            $slider_metas = self::get_slides($post->ID);

            ?>
            <div class="cs-sortables" data-post-id="<?php echo $post->ID; ?>">
                <?php
                if(is_array($slider_metas) and count($slider_metas)>0):
                
                    foreach($slider_metas as $i=>$slider_meta):
                    
                        $image_url = $this->get_slide_img_thumb($slider_meta['id']);
                        $image_url = apply_filters('cycloneslider_preview_url', $image_url, $slider_meta);
                        $box_title = apply_filters('cycloneslider_box_title', __('Slide', 'cycloneslider'), $slider_meta).' '.($i+1);
                        
                        include(self::get_admin_parts_folder().'box.php');
                    endforeach;
                    
                endif;
                ?>
            </div><!-- end .cycloneslider-sortable -->
            
            <input type="button" value="<?php _e('Add Slide', 'cycloneslider'); ?>" class="cs-add-slide button-secondary" />
            <?php
        }
        
        /**
         * Metabox for slide properties
         */
        public function render_slider_properties_meta_box($post){
            // Use nonce for verification
            echo '<input type="hidden" name="cycloneslider_metabox_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
            
            $slider_settings = self::get_slideshow_settings($post->ID);
            
            if($this->debug){
                self::debug($slider_settings);
            }
    
            include(self::get_admin_parts_folder() . 'slider-properties.php');
        }
        
        /**
         * Metabox for templates
         */
        public function render_slider_templates_meta_box($post){
            // Use nonce for verification
            echo '<input type="hidden" name="cycloneslider_metabox_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
    
            $slider_settings = self::get_slideshow_settings($post->ID);
    
            $templates = self::get_all_templates();
            if($this->debug){
                self::debug($templates);
            }
            include(self::get_admin_parts_folder() . 'template-selection.php');
        }
        
        /**
         * Save post hook
         */
        public function save_post($post_id){
            global $cyclone_slider_saved_done;
            
            // Stop! We have already saved..
            if($cyclone_slider_saved_done){
                return $post_id;
            }
            
            // Verify nonce
            $nonce_name = 'cycloneslider_metabox_nonce';
            if (!empty($_POST[$nonce_name])) {
                if (!wp_verify_nonce($_POST[$nonce_name], basename(__FILE__))) {
                    return $post_id;
                }
            } else {
                return $post_id; // Make sure we cancel on missing nonce!
            }
            
            // Check autosave
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return $post_id;
            }
            
            do_action('cycloneslider_before_save', $post_id);
    
            // Save slides
            $this->save_slides($post_id);
            
            // Save slideshow ettings
            $this->save_settings($post_id);
            
            // Compile css and js
            $this->compile_css($post_id);
            $this->compile_js($post_id);
            
            update_option('cycloneslider_version', CYCLONE_VERSION);
        }
        
        /**
         * Save slides sanitize if needed
         */
        public function save_slides($post_id){
            $slides = array();
            if(isset($_POST['cycloneslider_metas'])){
                
                $i=0;//always start from 0
                foreach($_POST['cycloneslider_metas'] as $slide){
                    $slide = wp_parse_args($slide, self::get_slide_defaults());
                    $slides[$i]['id'] = (int) ($slide['id']);
                    $slides[$i]['type'] = sanitize_text_field($slide['type']);
                    
                    $slides[$i]['link'] = esc_url_raw($slide['link']);
                    $slides[$i]['title'] = wp_kses_post($slide['title']);
                    $slides[$i]['description'] = wp_kses_post($slide['description']);
                    $slides[$i]['link_target'] = sanitize_text_field($slide['link_target']);
                    
                    $slides[$i]['img_alt'] = sanitize_text_field($slide['img_alt']);
                    $slides[$i]['img_title'] = sanitize_text_field($slide['img_title']);
                    
                    $slides[$i]['enable_slide_effects'] = (int) ($slide['enable_slide_effects']);
                    $slides[$i]['fx'] = sanitize_text_field($slide['fx']);
                    $slides[$i]['speed'] = sanitize_text_field($slide['speed']);
                    $slides[$i]['timeout'] = sanitize_text_field($slide['timeout']);
                    $slides[$i]['tile_count'] = sanitize_text_field($slide['tile_count']);
                    $slides[$i]['tile_delay'] = sanitize_text_field($slide['tile_delay']);
                    $slides[$i]['tile_vertical'] = sanitize_text_field($slide['tile_vertical']);
                    
                    $slides[$i]['video_thumb'] = esc_url_raw($slide['video_thumb']);
                    $slides[$i]['video_url'] = esc_url_raw($slide['video_url']);
                    $slides[$i]['video'] = $slide['video'];
                    
                    $slides[$i]['custom'] = $slide['custom'];
                    
                    $i++;
                }
                
                
            }
            $slides = apply_filters('cycloneslider_slides', $slides); //do filter before saving
            
            delete_post_meta($post_id, '_cycloneslider_metas');
            update_post_meta($post_id, '_cycloneslider_metas', $slides);
        }
        
        /**
         * Save slideshow settings
         */
        public function save_settings($post_id){
            if(isset($_POST['cycloneslider_settings'])){
                $_POST['cycloneslider_settings'] = wp_parse_args($_POST['cycloneslider_settings'], self::get_slideshow_defaults());
                $settings = array();
                $settings['template'] = sanitize_text_field($_POST['cycloneslider_settings']['template']);
                $settings['fx'] = sanitize_text_field($_POST['cycloneslider_settings']['fx']);
                $settings['timeout'] = (int) ($_POST['cycloneslider_settings']['timeout']);
                $settings['speed'] = (int) ($_POST['cycloneslider_settings']['speed']);
                $settings['width'] = (int) ($_POST['cycloneslider_settings']['width']);
                $settings['height'] = (int) ($_POST['cycloneslider_settings']['height']);
                $settings['hover_pause'] = sanitize_text_field($_POST['cycloneslider_settings']['hover_pause']);
                $settings['show_prev_next'] = (int) ($_POST['cycloneslider_settings']['show_prev_next']);
                $settings['show_nav'] = (int) ($_POST['cycloneslider_settings']['show_nav']);
                $settings['tile_count'] = (int) ($_POST['cycloneslider_settings']['tile_count']);
                $settings['tile_delay'] = (int) ($_POST['cycloneslider_settings']['tile_delay']);
                $settings['tile_vertical'] = sanitize_text_field($_POST['cycloneslider_settings']['tile_vertical']);
                $settings['random'] = (int) ($_POST['cycloneslider_settings']['random']);
                $settings['resize'] = (int) ($_POST['cycloneslider_settings']['resize']);
                
                $settings = apply_filters('cycloneslider_settings', $settings); //do filter before saving
                
                delete_post_meta($post_id, '_cycloneslider_settings');
                update_post_meta($post_id, '_cycloneslider_settings', $settings);
            }
        }
        
        
        
        /**
        * Pulls style.css from templates and combines it into one
        */
        private function compile_css($post_id){
            $ds = DIRECTORY_SEPARATOR;
            $content = '';
            
            if(file_exists(self::path()."css{$ds}common.min.css")){
                $content .= file_get_contents(self::path()."css{$ds}common.min.css");
            }
            
            $template_folders = self::get_all_templates();
            
            foreach($template_folders as $name=>$folder){
                $style = $folder['path']."{$ds}style.min.css"; //Minified version
                $style2 = $folder['path']."{$ds}style.css"; //Unminified version, for old templates to work
                if(file_exists($style)){
                    $content .= "\r\n".str_replace('$tpl', $folder['url'], file_get_contents($style));//apply url and print css
                } else if(file_exists($style2)){
                    $content .= "\r\n".str_replace('$tpl', $folder['url'], file_get_contents($style2));//apply url and print css
                }
            }
            
            $save_to = self::path()."css{$ds}templates.css";
            
            if( @file_put_contents($save_to, $content, LOCK_EX) === false){
                $this->message_id = 101;
                add_filter('redirect_post_location', array($this, 'throw_message'));
            }
        }
        
        /**
        * Pulls script.js from templates and combines it into one
        */
        private function compile_js($post_id){
            $ds = DIRECTORY_SEPARATOR;
            $content = '';
            
            $template_folders = self::get_all_templates();
            
            foreach($template_folders as $name=>$folder){
                
                $js = $folder['path']."{$ds}script.min.js"; //Minified version
                $js2 = $folder['path']."{$ds}script.js"; //Unminified version, for old templates to work
                if(file_exists($js)){
                    $content .= file_get_contents($js)."\r\n";//Pull contents
                } else if(file_exists($js2)){
                    $content .= file_get_contents($js2)."\r\n";
                }
                
            }
            
            $save_to = self::path()."js{$ds}templates.js";
            if( @file_put_contents($save_to, $content, LOCK_EX) === false){
                $this->message_id = 102;
                add_filter('redirect_post_location', array($this, 'throw_message'));
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
            $columns['images']= __('Images', 'cycloneslider');
            $columns['id']= __('Slideshow ID', 'cycloneslider');
            $columns['shortcode']= __('Shortcode', 'cycloneslider');
            return $columns;
        }
        
        // Add content to custom columns
        public function custom_column( $column_name, $post_id ){
            if ($column_name == 'template') {
                $settings = self::get_slideshow_settings($post_id);
                echo ucwords($settings['template']);
            }
            if ($column_name == 'images') {
                echo '<div style="text-align:center; max-width:40px;">' . self::get_image_count($post_id) . '</div>';
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
        
        // Hook to admin footer
        public function admin_footer() {
            $this->slide_box_for_js();
        }
        
        // For js adding of box
        public function slide_box_for_js(){
            if(get_post_type()=='cycloneslider'){
                $box_title = __('Slide *', 'cycloneslider');
                $image_url = '';
                $i = '{id}';
                $slider_meta = self::get_slide_defaults();
                foreach($slider_meta as $key=>$value){
                    $slider_meta[$key] = '';
                }
                $slider_meta['type'] = 'image';
            ?>
                <div class="cs-slide-skeleton">
                    <?php
                    include(self::get_admin_parts_folder().'box.php');
                    ?>
                </div><!-- end .cycloneslider-box-template -->
            <?php
            }
        }
        
        // Compare the value from admin and shortcode. If shortcode value is present and not empty, use it, otherwise return admin value
        public function get_comp_slider_setting($admin_val, $shortcode_val){
            if($shortcode_val!==null){//make sure its really null and not just int zero 0
                return $shortcode_val;
            }
            return $admin_val;
        }
        
        /* Our shortcode function.
          Slider settings comes from both admin settings and shortcode attributes.
          Shortcode attributes, if present, will override the admin settings.
        */
        public function cycloneslider_shortcode($shortcode_settings) {
            // Process shortcode settings and return only allowed vars
            $shortcode_settings = shortcode_atts(
                array(
                    'id' => 0,
                    'template' => null,
                    'fx' => null,
                    'speed' => null,
                    'timeout' => null,
                    'width' => null,
                    'height' => null,
                    'hover_pause' => null,
                    'show_prev_next' => null,
                    'show_nav' => null,
                    'tile_count' => null,
                    'tile_delay' => null,
                    'tile_vertical' => null,
                    'random' => null,
                    'resize' => null
                ),
                $shortcode_settings
            );
            $slider_id = esc_attr($shortcode_settings['id']);// Slideshow slug
    
            $cycle_options = array();
            $this->slider_count++;//make each call to shortcode unique
            // Get slideshow by id
            $args = array(
                'post_type' => 'cycloneslider',
                'order'=>'ASC',
                'posts_per_page' => 1,
                'name'=> $slider_id
            );
            
            $args = apply_filters('cycloneslider_wp_query_args', $args);
            
            $my_query = new WP_Query($args);
            
            if($my_query->have_posts()):
                while ( $my_query->have_posts() ) : $my_query->the_post();
                    
                    $meta = get_post_custom();
                    $admin_settings = self::get_slideshow_settings(get_the_ID());
                    $slider_metas = self::get_slides(get_the_ID());
                    
                    $image_count = 0; // Number of image slides
                    $video_count = 0; // Number of video slides
                    $custom_count = 0; // Number of custom slides
                    foreach($slider_metas as $i=>$slider_meta){
                        $slider_metas[$i]['title'] = __($slider_meta['title']);
                        $slider_metas[$i]['description'] = __($slider_meta['description']);
                        if($slider_metas[$i]['type']=='image'){
                            $image_count++;
                        } else if($slider_metas[$i]['type']=='video'){
                            $video_count++;
                        } else if($slider_metas[$i]['type']=='custom'){
                            $custom_count++;
                        }
                    }
                    $slides = $this->get_slides_from_meta($slider_metas);
                    
                    $template = $this->get_comp_slider_setting($admin_settings['template'], $shortcode_settings['template']);
                    $template = esc_attr($template===null ? 'default' : $template);//fallback to default
                    $slider_settings['fx'] = esc_attr($this->get_comp_slider_setting($admin_settings['fx'], $shortcode_settings['fx']));
                    $slider_settings['speed'] = (int) $this->get_comp_slider_setting($admin_settings['speed'], $shortcode_settings['speed']);
                    $slider_settings['timeout'] = (int) $this->get_comp_slider_setting($admin_settings['timeout'], $shortcode_settings['timeout']);
                    $slider_settings['width'] = (int) $this->get_comp_slider_setting($admin_settings['width'], $shortcode_settings['width']);
                    $slider_settings['height'] = (int) $this->get_comp_slider_setting($admin_settings['height'], $shortcode_settings['height']);
                    $slider_settings['hover_pause'] = $this->get_comp_slider_setting($admin_settings['hover_pause'], $shortcode_settings['hover_pause']);
                    $slider_settings['show_prev_next'] = (int) $this->get_comp_slider_setting($admin_settings['show_prev_next'], $shortcode_settings['show_prev_next']);
                    $slider_settings['show_nav'] = (int) $this->get_comp_slider_setting($admin_settings['show_nav'], $shortcode_settings['show_nav']);
                    
                    $slider_settings['tile_count'] = $this->get_comp_slider_setting($admin_settings['tile_count'], $shortcode_settings['tile_count']);
                    $slider_settings['tile_delay'] = $this->get_comp_slider_setting($admin_settings['tile_delay'], $shortcode_settings['tile_delay']);
                    $slider_settings['tile_vertical'] = $this->get_comp_slider_setting($admin_settings['tile_vertical'], $shortcode_settings['tile_vertical']);
                    
                    $slider_settings['random'] = $this->get_comp_slider_setting($admin_settings['random'], $shortcode_settings['random']);
                    $slider_settings['resize'] = $this->get_comp_slider_setting($admin_settings['resize'], $shortcode_settings['resize']);
                    
                    if($slider_settings['random']){
                        shuffle($slider_metas);
                    }
                    
                    $slider = $this->get_slider_template($slider_id, $template, $slides, $slider_metas, $slider_settings, $this->slider_count, $image_count, $video_count, $custom_count);
                    
                endwhile;
                
                wp_reset_postdata();
    
            else:
                $slider = sprintf(__('[Slideshow "%s" not found]', 'cycloneslider'), $slider_id);
            endif;
            
            return $slider;
        }
        
        // Get slideshow template
        public function get_slider_template($slider_id, $template_name, $slides, $slider_metas, $slider_settings, $slider_count, $image_count, $video_count, $custom_count){
    
            $template = get_stylesheet_directory()."/cycloneslider/{$template_name}/slider.php";
            if(@is_file($template)){
                ob_start();
                include($template);
                $html = ob_get_clean();
                return $html = $this->trim_white_spaces($html);
            }
            
            $template = self::get_templates_folder()."{$template_name}/slider.php";
            if(@is_file($template)) {
                ob_start();
                include($template);
                $html = ob_get_clean();
                return $html = $this->trim_white_spaces($html);
            }
            
            return sprintf(__('[Template "%s" not found]', 'cycloneslider'), $template_name);
        }
        
        public function trim_white_spaces($buffer){
            $search = array(
                '/\>[^\S ]+/s', //strip whitespaces after tags, except space
                '/[^\S ]+\</s', //strip whitespaces before tags, except space
                '/(\s)+/s'  // shorten multiple whitespace sequences
            );
            $replace = array(
                '>',
                '<',
                '\\1'
            );
            return preg_replace($search, $replace, $buffer);
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
         * GLOBAL STATIC FUNCTIONS
         */
        
        /**
        * Get All Slideshows
        *
        * Get all saves slideshow
        * 
        * @return array The array of slideshows
        */
        public static function get_all_slideshows(){
            $args = array(
                'post_type' => 'cycloneslider',
                'posts_per_page' => -1
            );
            $my_query = new WP_Query($args);
            $slideshows = array();
            while ( $my_query->have_posts() ) : $my_query->the_post();
                $slideshows[] = $my_query->post;
            endwhile;
            
            wp_reset_postdata();
            
            return $slideshows;
        }
        
        /**
        * Get Templates in used
        *
        * Get all templates that are used by slideshow
        * 
        * @return array The array of templates
        */
        public static function get_templates_in_used(){
            $slideshows = self::get_all_slideshows();
            $templates_used = array();
            foreach($slideshows as $slideshow) {
                $settings = self::get_slideshow_settings($slideshow->ID);
                $templates_used[$settings['template']] = $settings['template'];
            }
            
            return $templates_used;
        }
        
        /**
        * Get All Locations
        *
        * Get templates folders in plugin and theme folders
        * 
        * @return array The array of locations containing path and url 
        */
        public static function get_all_locations(){
            $ds = DIRECTORY_SEPARATOR;
            $template_locations = array();
            $template_locations[0] = array(
                'path'=>self::get_templates_folder(), //this resides in the plugin
                'url'=>self::url().'templates/'
            );
            $template_locations[1] = array(
                'path'=> realpath(get_stylesheet_directory())."{$ds}cycloneslider{$ds}",//this resides in the current theme or child theme
                'url'=> get_stylesheet_directory_uri()."/cycloneslider/"
            );
            return $template_locations;
        }
        
        /**
        * Get All Templates
        *
        * Get all templates from all locations. Returns array of templates with keys as name containing array of path and url
        * 
        * @return array The array of templates containing path and url 
        */
        public static function get_all_templates(){
            $template_locations = self::get_all_locations();
            $template_folders = array();
            foreach($template_locations as $location){
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
                                'supports' => $ini_array['slide_type']
                            );
                        }
                    }
                }
            }
            return $template_folders;
        }
        
        /**
        * Gets the slideshow settings. Defaults and filters are applied.
        *
        * @param int $slideshow_id Post ID of the slideshow custom post.
        * @return array The array of slideshow settings
        */
        public static function get_slideshow_settings($slideshow_id) {
            $meta = get_post_custom($slideshow_id);
            $slideshow_settings = array();
            if(isset($meta['_cycloneslider_settings'][0]) and !empty($meta['_cycloneslider_settings'][0])){
                $slideshow_settings = maybe_unserialize($meta['_cycloneslider_settings'][0]);
            }
            $slideshow_settings = wp_parse_args($slideshow_settings, self::get_slideshow_defaults() );
            return apply_filters('cycloneslider_get_slideshow', $slideshow_settings);
        }
        
        /**
        * Gets the slides. Defaults and filters are applied.
        *
        * @param int $slideshow_id Post ID of the slideshow custom post.
        * @return array The array of slides settings
        */
        public static function get_slides($slideshow_id){
            $meta = get_post_custom($slideshow_id);
            
            if(isset($meta['_cycloneslider_metas'][0]) and !empty($meta['_cycloneslider_metas'][0])){
                $slides = maybe_unserialize($meta['_cycloneslider_metas'][0]);
                $defaults = self::get_slide_defaults();
                
                foreach($slides as $i=>$slide){
                    $slides[$i] = wp_parse_args($slide, $defaults);
                }
                
                return apply_filters('cycloneslider_get_slides', $slides);
            }
            return false;
        }
        
        /**
        * Gets the slideshow default settings. 
        *
        * @return array The array of slideshow defaults
        */
        public static function get_slideshow_defaults(){
            return array(
                'template' => 'default',
                'fx' => 'fade',
                'timeout' => '4000',
                'speed' => '1000',
                'width' => '960',
                'height' => '300',
                'hover_pause' => 'true',
                'show_prev_next' => '1',
                'show_nav' => '1',
                'tile_count' => '7',
                'tile_delay' => '100',
                'tile_vertical' => 'true',
                'random' => 0,
                'resize' => 1
            );
        }
        
        /**
        * Gets the slide default settings. 
        *
        * @return array The array of slide defaults
        */
        public static function get_slide_defaults(){
            return array(
                'enable_slide_effects'=>0,
                'type' => 'image',
                'id' => '',
                'link' => '',
                'title' => '',
                'description' => '',
                'link_target' => '_self',
                'fx' => 'default',
                'speed' => '',
                'timeout' => '',
                'tile_count' => '7',
                'tile_delay' => '100',
                'tile_vertical' => 'true',
                'img_alt' => '',
                'img_title' => '',
                'video_thumb' => '',
                'video_url' => '',
                'video' => '',
                'custom' => ''
            );
        }
        
        /**
        * Gets the slide effects. 
        *
        * @return array The array of slide effects
        */
        public static function get_slide_effects(){
            return array(
                'fade'=>'Fade',
                'fadeout'=>'Fade Out',
                'none'=>'None',
                'scrollHorz'=>'Scroll Horizontally',
                'tileBlind'=>'Tile Blind',
                'tileSlide'=>'Tile Slide'
            );
        }
        
        /**
        * Gets the path to plugin
        *
        * @return string Path to plugin in the filesystem with trailing slash
        */
        public static function path(){
            return CYCLONE_PATH;
        }
        
        /**
        * Gets the URL to plugin
        *
        * @return string URL to plugin with trailing slash
        */
        public static function url(){
            return CYCLONE_URL;
        }
        
        /**
        * Gets the path to folder of admin user interface parts with trailing slash
        *
        * @return string Path to folder
        */
        public static function get_admin_parts_folder(){
            return self::path() . 'inc'.DIRECTORY_SEPARATOR.'admin-parts'.DIRECTORY_SEPARATOR;
        }
        
        /**
        * Gets the path to templates folder
        *
        * @return string Path to templates inside the plugin with trailing slash
        */
        public static function get_templates_folder(){
            return self::path() . 'templates'.DIRECTORY_SEPARATOR;
        }
        
        /**
        * Gets the number of images of slideshow
        *
        * @param int Slideshow id
        * @return int Total images or zero
        */
        public static function get_image_count($slideshow_id){
            $meta = get_post_custom($slideshow_id);
            
            if(isset($meta['_cycloneslider_metas'][0]) and !empty($meta['_cycloneslider_metas'][0])){
                $slides = maybe_unserialize($meta['_cycloneslider_metas'][0]);
                
                return count($slides);
            }
            return 0;
        }
        
        /**
        * Print with a twist
        */
        public static function debug($out){
            echo '<pre>'.print_r($out, true).'</pre>';
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