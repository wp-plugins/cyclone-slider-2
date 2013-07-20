<?php
/**
* Class for compiling css and js assets for cyclone. 
*/
define('APP_PATH', get_file_dir_path() );
define('APP_URL', get_file_dir_url() );

/**
* Functions for guessing the path and urls since this script is decoupled from WP and we cannot use the built-in WP functions
*/
function get_file_dir_path(){
    return realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR;
}
function get_file_dir_url(){
    $pathinfo = pathinfo($_SERVER['SCRIPT_NAME']);
    if(is_array($pathinfo) and isset($pathinfo['dirname'])){
        return $pathinfo['dirname'].'/';
    }
    return false;
}
function wp_content_path(){
    $path = get_file_dir_path();
    $path = str_replace('plugins'.DIRECTORY_SEPARATOR.'cyclone-slider-2'.DIRECTORY_SEPARATOR, '', $path);
    return $path;
}
function wp_content_url(){
    $url = get_file_dir_url();
    $url = str_replace('plugins/cyclone-slider-2/', '', $url);
    return $url;
}
function theme_path($theme){
    return wp_content_path().'themes'.DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR;
}
function theme_url($theme){
    return wp_content_url().'themes/'.$theme.'/';
}
/**
* Pulls style.css from templates and combines it into one
*/
function compile_css($templates){
    $ds = DIRECTORY_SEPARATOR;
    $content = '';
    $minify = isset($_GET['minify']) ? (int) $_GET['minify'] : '1';
     
    if(file_exists(APP_PATH."css{$ds}common.min.css")){
        $content .= file_get_contents(APP_PATH."css{$ds}common.min.css");
    } else {
        $content .= file_get_contents(APP_PATH."css{$ds}common.css");
    }
    
    $template_folders = $templates->get_all_templates();
    
    foreach($template_folders as $name=>$folder){
        $style = $folder['path']."{$ds}style.min.css"; //Minified version
        $style2 = $folder['path']."{$ds}style.css"; //Unminified version, for old templates to work
        if(file_exists($style) and $minify != '0'){
            $content .= "\r\n".str_replace('$tpl', $folder['url'], file_get_contents($style));//apply url and print css
        } else if(file_exists($style2)){
            $content .= "\r\n".str_replace('$tpl', $folder['url'], file_get_contents($style2));//apply url and print css
        }
    }
    header("Content-Type: text/css");
    echo $content;
}

/**
* Pulls script.js from templates and combines it into one
*/
function compile_js($templates){
    $ds = DIRECTORY_SEPARATOR;
    $content = '';
    $minify = isset($_GET['minify']) ? (int) $_GET['minify'] : '1';
    
    $template_folders = $templates->get_all_templates();
    
    foreach($template_folders as $name=>$folder){
        
        $js = $folder['path']."{$ds}script.min.js"; //Minified version
        $js2 = $folder['path']."{$ds}script.js"; //Unminified version, for old templates to work
        
        if(file_exists($js) and $minify != '0'){
            $content .= file_get_contents($js)."\r\n";//Pull contents
        } else if(file_exists($js2)){
            $content .= file_get_contents($js2)."\r\n";
        }
        
    }
    header("Content-Type: application/javascript");
    echo $content;

}

/**
* Pass in the theme value via GET since we have know way of knowing the current theme used. Default is set to twentytwelve 
*/
$theme = isset($_GET['theme']) ? htmlentities(strip_tags($_GET['theme'])) : 'twentytwelve';
$type = isset($_GET['type']) ? htmlentities(strip_tags($_GET['type'])) : '';


require_once(APP_PATH.'classes'.DIRECTORY_SEPARATOR.'class-cyclone-templates.php');

$templates = new Cyclone_Templates();
$templates->add_template_location(
    array(
        'path'=>APP_PATH.'templates'.DIRECTORY_SEPARATOR, //this resides in the plugin
        'url'=>APP_URL.'templates/'
    )
);
$templates->add_template_location(
    array(
        'path'=> theme_path($theme).'cycloneslider'.DIRECTORY_SEPARATOR,//this resides in the current theme or child theme
        'url'=> theme_url($theme)."cycloneslider/"
    )
);

if($type=='css'){
    echo compile_css($templates);
}
if($type=='js'){
    echo compile_js($templates);
}
die();


