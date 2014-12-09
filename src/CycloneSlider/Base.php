<?php
abstract class CycloneSlider_Base {
    protected $plugin;
    
    public function run( $plugin ) {
        $this->plugin = $plugin;
    }
}