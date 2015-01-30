<?php
abstract class CycloneSlider_Base {
    protected $plugin;
    
    final public function inject( $plugin ) {
        $this->plugin = $plugin;
    }
}