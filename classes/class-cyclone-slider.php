<?php
if(!class_exists('Cyclone_Slider')):

    class Cyclone_Slider {
        public $slider_count;
        private $message_id;
        public $templates;
        private $templates_manager; // Holds template manager object
        private $cyclone_settings; // Holds cyclone settings array
        
        /**
         * Initializes the plugin by setting localization, filters, and administration functions.
         */
        public function __construct( $templates_manager, $cyclone_settings ) {
            
             // Inject dependencies
            $this->templates_manager = $templates_manager;
            
            // Set defaults
            $this->slider_count = 0;
            $this->cyclone_settings = $cyclone_settings;
            
            // Register frontend styles and scripts
            add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ), 100 );
            
            // Our shortcode
            add_shortcode('cycloneslider', array( $this, 'cycloneslider_shortcode') );
            
            // Add directories to get templates
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
         * Registers and enqueues frontend-specific scripts.
         */
        public function register_plugin_scripts() {
            $theme_folder = basename(get_stylesheet_directory());
            
            $in_footer = true;
            if($this->cyclone_settings['load_scripts_in'] == 'header'){
                $in_footer = false;
            }
            
            /*** Styles ***/
            $this->enqueue_templates_css(); // Templates styles

            /*** Core Cycle2 Scripts ***/
            if($this->cyclone_settings['load_cycle2'] == 1){
                wp_enqueue_script( 'jquery-cycle2', CYCLONE_URL.'js/jquery.cycle2.min.js', array('jquery'), CYCLONE_VERSION, $in_footer );
            }
            if($this->cyclone_settings['load_cycle2_carousel'] == 1){
                wp_enqueue_script( 'jquery-cycle2-carousel', CYCLONE_URL.'js/jquery.cycle2.carousel.min.js', array('jquery', 'jquery-cycle2'), CYCLONE_VERSION, $in_footer );
            }
            if($this->cyclone_settings['load_cycle2_tile'] == 1){
                wp_enqueue_script( 'jquery-cycle2-tile', CYCLONE_URL.'js/jquery.cycle2.tile.min.js', array('jquery', 'jquery-cycle2'), CYCLONE_VERSION, $in_footer );
            }
            if($this->cyclone_settings['load_cycle2_video'] == 1){
                wp_enqueue_script( 'jquery-cycle2-video', CYCLONE_URL.'js/jquery.cycle2.video.min.js', array('jquery', 'jquery-cycle2'), CYCLONE_VERSION, $in_footer );
            }
            
            $this->enqueue_templates_scripts(); // Templates scripts
        }
        
        /**
         * Enqueues templates styles.
         */
        private function enqueue_templates_css(){
            $ds = DIRECTORY_SEPARATOR;
             
            $template_folders = $this->templates_manager->get_all_templates();

            foreach($template_folders as $name=>$folder){
                
                $file = $folder['path']."/style.css"; // Path to file
                
                if( file_exists( $file ) ){ // Check existence
                    wp_enqueue_style( 'cyclone-template-style-'.sanitize_title($name), $folder['url'].'/style.css', array(), CYCLONE_VERSION );
                }
            }
        }
        
        /**
         * Enqueues templates scripts.
         */
        private function enqueue_templates_scripts(){
            $ds = DIRECTORY_SEPARATOR;

            $in_footer = true;
            if($this->cyclone_settings['load_scripts_in'] == 'header'){
                $in_footer = false;
            }
            
            $template_folders = $this->templates_manager->get_all_templates();

            foreach($template_folders as $name=>$folder){
                
                $file = $folder['path']."/script.js"; // Path to file
                
                if( file_exists( $file ) ){ // Check existence
                    wp_enqueue_script( 'cyclone-template-script-'.sanitize_title($name), $folder['url'].'/script.js', array(), CYCLONE_VERSION, $in_footer );
                }
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
            $slider_id = esc_attr($shortcode_settings['id']);// Slideshow slug passed from shortcode
    
            $cycle_options = array();
            $this->slider_count++;//make each call to shortcode unique
            
            // Get slider
            if( $slider = Cyclone_Slider_Data::get_slider_by_name( $slider_id ) ):

                $admin_settings = Cyclone_Slider_Data::get_slideshow_settings( $slider->ID );
                $slides = Cyclone_Slider_Data::get_slides( $slider->ID );
                
                $image_count = 0; // Number of image slides
                $video_count = 0; // Number of video slides
                $custom_count = 0; // Number of custom slides
                foreach($slides as $i=>$slide){
                    $slides[$i]['title'] = __($slide['title']);
                    $slides[$i]['description'] = __($slide['description']);
                    if($slides[$i]['type']=='image'){
                        $image_count++;
                    } else if($slides[$i]['type']=='video'){
                        $video_count++;
                    } else if($slides[$i]['type']=='custom'){
                        $custom_count++;
                    }
                }
                
                
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
                
                $slider_settings['template'] = $template;
                
                if($slider_settings['random']){
                    shuffle($slides);
                }
                
                $slider_metas = $slides; // $slider_metas deprecated since 2.5.5. Future removal
                
                $slider_slug = $slider->post_name;
                $slider = $this->get_slider_template($slider_id, $template, $slides, $slider_metas, $slider_settings, $this->slider_count, $image_count, $video_count, $custom_count, $slider_slug);
    
            else:
                $slider = sprintf(__('[Slideshow "%s" not found]', 'cycloneslider'), $slider_id);
            endif;
            
            return $slider;
        }
        
        // Get slideshow template
        public function get_slider_template($slider_id, $template_name, $slides, $slider_metas, $slider_settings, $slider_count, $image_count, $video_count, $custom_count, $slider_slug){
            $slider_html_id = 'cycloneslider-'.$slider_slug.'-'.$slider_count; // The unique HTML ID for slider
            
            $template = get_stylesheet_directory()."/cycloneslider/{$template_name}/slider.php";
            if(@is_file($template)){
                ob_start();
                include($template);
                $html = ob_get_clean();
                return $html = $this->trim_white_spaces($html);
            }
            
            $template = Cyclone_Slider_Data::get_templates_folder()."{$template_name}/slider.php";
            if(@is_file($template)) {
                ob_start();
                include($template);
                $html = ob_get_clean();
                return $html = $this->trim_white_spaces($html);
            }
            
            return sprintf(__('[Template "%s" not found]', 'cycloneslider'), $template_name);
        }
        
        public function trim_white_spaces($buffer, $off=false){
            if($off){
                return $buffer;
            }
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
        
    } // end class
    
endif;