<?php
if(!class_exists('Cyclone_Slider_Data')):

    /**
     * Class for saving and getting data  
     */
    class Cyclone_Slider_Data {
        
        private $nonce_name;
        private $nonce_action;
        
        /**
         * Initializes the class
         */
        public function __construct(){
            
            // Intialize properties
            $this->nonce_name = 'cyclone_slider_builder_nonce'; //Must match with the one in class-cyclone-slider-builder.php
            $this->nonce_action = 'cyclone-slider-save'; //Must match with the one in class-cyclone-slider-builder.php
            
            // Save slides
            add_action( 'save_post', array( $this, 'save_post' ) );
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
            $nonce_name = $this->nonce_name;
            if (!empty($_POST[$nonce_name])) {
                if (!wp_verify_nonce($_POST[$nonce_name], $this->nonce_action)) {
                    return $post_id;
                }
            } else {
                return $post_id; // Make sure we cancel on missing nonce!
            }
            
            // Check autosave
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return $post_id;
            }
    
            // Save slides
            $this->save_slides($post_id);
            
            // Save slideshow ettings
            $this->save_settings($post_id);
            
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
    }
    
endif;
