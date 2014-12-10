<?php
/**
* Class for initializing widgets
*/
class CycloneSlider_Widgets extends CycloneSlider_Base {

    /**
     * Initialize
     */
    public function bootstrap() {
        add_action('widgets_init', array( $this, 'register_widgets') );
    }
    
    /**
     * Register to WP
     */
    public function register_widgets(){
        register_widget('CycloneSlider_WidgetSlider');
    }
    
} // end class