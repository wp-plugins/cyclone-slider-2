=== Cyclone Slider 2 ===
Contributors: kosinix
Donate link: http://www.codefleet.net/donate/
Tags: slider, slideshow, wordpress-slider, wordpress-slideshow, cycle 2, jquery, responsive, translation-ready, custom-post, cyclone-slider
Requires at least: 3.5
Tested up to: 3.6
Stable tag: 2.5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create responsive slideshows with ease. Built for both developers and non-developers.

== Description ==

Cyclone Slider 2 is an easy to use slider plugin for WordPress. It leverages WordPress' built-in features. It uses custom post for the slideshow, custom fields to store settings, and media uploader for the images. It also uses a template system that allows developers to easily customize the slideshow to their needs. Its simple yet flexible.

= Features: =
* Very easy to use interface! Blends seamlessly with your WordPress workflow.
* Supports image, video, and custom HTML slides.
* Powered by [Cycle 2](http://jquery.malsup.com/cycle2/), the most flexible jQuery slideshow plugin.
* A template system that allow developers to easily customize the slideshow appearance and behavior.
* Customizable tile transition effects.
* Ability to add per-slide transition effects.
* Unlimited slideshows.
* Unique settings for each slideshow.
* Supports random slide order.
* Shortcode for displaying slideshows anywhere in your site.
* Ability to import images from NextGEN (NextGEN must be installed and active).
* Translation ready.
* Ability to use qTranslate quick tags for slide title and descriptions (qTranslate must be installed and active).
* Allows title and alt to be specified for each slide images.
* Comes with a widget to display your slideshow easily in widget areas.
* It's totally FREE!

= Homepage =
Learn more about [Cyclone Slider 2](http://www.codefleet.net/cyclone-slider-2/)

= Credits =
* Cyclone Slider 2 was based on [Cycle 2](http://jquery.malsup.com/cycle2/) by [Mike Alsup](http://jquery.malsup.com/).
* Special thanks to [Cea Bacolor](http://blog.ceabacolor.com/) for the wonderful photos.
* Aubin BERTHE for the French translation.

= License =
GPLv2 or later - http://www.gnu.org/licenses/gpl-2.0.html

== Installation ==

= Install via WordPress Admin =
1. Ready the zip file of the plugin
1. Go to Admin > Plugins > Add New
1. On the upper portion click the Upload link
1. Using the file upload field, upload the plugin zip file here and activate the plugin

= Install via FTP =
1. First unzip the plugin file
1. Using FTP go to your server's wp-content/plugins directory
1. Upload the unzipped plugin here
1. Once finished login into your WP Admin and go to Admin > Plugins
1. Look for Cyclone Slider 2 and activate it

= Usage =
1. Start adding slideshows in 'Cyclone Slider' menu in WordPress
1. You can then use a shortcode to display your slideshow. Example: `[cycloneslider id ="my-slideshow"]`
1. Function do_shortcode can be used inside template files. Example: `<?php echo do_shortcode('[cycloneslider id ="my-slideshow"]'); ?>`


== Frequently Asked Questions ==

= Why is my slider not working? =
Check for javascript errors in your page. This is the most common cause of the slider not running.
`cycle not a function` error - most probably you have double jquery (jquery.js) included from improperly coded plugins. Remove the duplicate jquery or deactivate the plugin causing the double jquery include.

Also check if you are using cycle1 by viewing your page source. cycle2 wont work if both are present.

= Why is there is an extra slide that I didn't add? = 
Most probably its wordpress adding paragpraphs on line breaks next to the slides therefore adding a blank `<p>` slide. You can try adding this to functions.php:
`remove_filter('the_content', 'wpautop');`

= How to display it in post/page? =
Use the shortcode `[cycloneslider id="my-slideshow"]`. Change my-slideshow to the ID of your slideshow.

= How to display it inside template files (header.php, index.php, page.php, etc.)? =
Use `<?php echo do_shortcode('[cycloneslider id ="my-slideshow"]'); ?>`. Change my-slideshow to the ID of your slideshow.

= What are the shortcode options? =
`[cycloneslider id="my-slideshow" fx="fade" timeout="5000" speed="1000" width="500" height="300" show_prev_next="true" show_nav="true"]`

= How can I use templates? =
`[cycloneslider id="my-slideshow" template="custom-name"]` 

= Where do I add my own templates? =
Inside your current active theme create a folder named "cycloneslider". Add your templates inside.

== Screenshots ==

1. All Slideshow Screen
2. Slideshow Editing Screen
3. Slideshow in Action
4. Slideshow Widget

== Changelog ==

= 2.6.0 - 2013-08-04 = 
* Warning: Old templates will break in this version! You can either use the new templates or migrate the older templates. [Check this post](http://www.codefleet.net/pre-2-6-0-templates-migration/).
* Load scripts and styles normally as separate requests for better compatibility with other plugins and server setup. Removed template-assets.php which consolidates assets into a single request.
* Added cyclone slider settings page.
* Language files now loaded when using WPML.
* Added Get Codes metabox to easily grab the slider codes.
* Added Slideshow ID metabox to easily change the slideshow ID.

= 2.5.6 - 2013-07-30 = 
* Fix broken nextgen importer from last update.
* Refactor code for better template management in admin.

= 2.5.5 - 2013-07-25 = 
* Removed templates Black, Blue, and Myrtle from plugin's folder for better performance.
* Used get_posts instead of WP_Query when getting a slideshow to avoid filters that might cause conflict.

= 2.5.4 - 2013-07-20 = 
* Added Youtube template that pauses the video when slider is transitioning. 

= 2.5.3 - 2013-05-10 = 
* Bug fix for child themes where slider is not working

= 2.5.2 - 2013-04-26 = 
* Added template asset loader to get rid of the compiled css and js that are rewritten on the file system on every save
* Move template handling logic to its own class to be used by the template asset loader independently
* Removed upgrade notice

= 2.5.1 - 2013-03-29 = 
* Bug fix to allow small images to be inserted.
* Improved cyclone_settings.
* Improved slideshow not found message.
* Updated cycle 2 js files.
* Added plugin version to fix caching problem on JS and CSS.
* Added upgrade notice.

= 2.5.0 - 2013-03-21 - This is a major release = 
* More slide types to choose from: image, video (youtube and vimeo) and custom HTML.
* Added icons to the UI to indicate different slide types.
* Replaced cookies with localstorage to store UI status.
* Updated the templates to support the various slide types.
* Added resize and random options.
* Bug fix for fatal error when no GD library. Added gd_info check.
* Bug fix for js error on WP below 3.5 caused by the 3.5 media library object being undefined.
* Deprecated cycloneslider_thumb use cyclone_slide_image_url instead.
* Deprecated cycloneslider_settings use cyclone_settings instead.
* Deprecated cycloneslider_slide_settings use cyclone_slide_settings instead.
* Various UI fixes and code refactoring.

= 2.2.5 - 2013-02-23 = 
* Bug fix for 2.2.4

= 2.2.4 - 2013-02-22 = 
* Now compiles the template CSS and JS files instead of using template_redirect hook. This is to fix problems with some users reporting broken css and js.
* Minified CSS and JS for templates.
* Compiles needed CSS and JS only instead of loading all CSS and JS from all templates.
* Added template column to all slideshow screen.
* Updated language files

= 2.2.3 - 2013-02-14 = 
* Added option for random slide order on every page visit.
* Refactored some code.
* Added image count to all slideshow screen.

= 2.2.2 - 2013-02-05 = 
* Updated language files.
* Bug Fix. Post Type Switcher fix via jquery.
* UI Enhancement. Removed overflow for templates.
* Ignore image resize if slideshow dimension is equal to the image dimension.
* UI Enhancement. Decrease drag delay for slide sortables in editor.

= 2.2.1 - 2012-12-25 = 
* Added Cyclone Slider 2 widget. 

= 2.2.0 - 2012-12-24 = 
* Updated cycle 2 to latest version.
* Updated template selection interface to be more visual. A screenshot of each slideshow template is now shown.
* Added Tile Count and Tile Position for both slideshow and per-slide settings.
* Cleanup Quick Edit screen to hide unused user interface.
* Slide box titles can now be clicked to open and close the slide box.
* Removed drag icon from slide box title. Slide box can now be dragged by click-holding the slide title area.
* Updated template API functions.
* Updated plugin screenshot.
* Refactored various code parts.
* Added ability to add script.js in templates
* Added ability to add screenshot.jpg in templates.
* Updated templates.
* Added fix to preserved PNG transparency.
* Fix save routine to allow saving empty slides and to preserve order of slides after drag and/or deletion of slide.

= 2.1.1 - 2012-11-16 = 
* Fix for a code typo error

= 2.1.0 - 2012-11-16 = 
* Fix for slideshow not working when NextGEN 1.9.7 is active
* You can now import images from NextGEN

= 2.0.1 - 2012-11-09 = 
* Bug fix for hover pause

= 2.0.0 - 2012-10-28 = 
* Initial


== Upgrade Notice ==

= 2.6.0 - 2013-08-04 = 
* Warning: Old templates will break in this version! You can either use the new templates or migrate the older templates. [Check this post](http://www.codefleet.net/pre-2-6-0-templates-migration/).
* Load scripts and styles normally as separate requests for better compatibility with other plugins and server setup. Removed template-assets.php which consolidates assets into a single request.
* Added cyclone slider settings page.
* Language files now loaded when using WPML.
* Added Get Codes metabox to easily grab the slider codes.
* Added Slideshow ID metabox to easily change the slideshow ID.

= 2.5.6 - 2013-07-30 = 
* Fix broken nextgen importer from last update.
* Refactor code for better template management in admin.

= 2.5.5 - 2013-07-25 = 
* Removed templates Black, Blue, and Myrtle from plugin's folder for better performance.
* Used get_posts instead of WP_Query when getting a slideshow to avoid filters that might cause conflict.

= 2.5.4 - 2013-07-20 = 
* Added Youtube template that pauses the video when slider is transitioning. 

= 2.5.3 - 2013-05-10 = 
* Bug fix for child themes where slider is not working

= 2.5.2 - 2013-04-26 = 
* Added template asset loader to get rid of the compiled css and js that are rewritten on the file system on every save
* Move template handling logic to its own class to be used by the template asset loader independently
* Removed upgrade notice

= 2.5.1 - 2013-03-29 = 
* Bug fix to allow small images to be inserted.
* Improved cyclone_settings.
* Improved slideshow not found message.
* Updated cycle 2 js files.
* Added plugin version to fix caching problem on JS and CSS.
* Added upgrade notice.

= 2.5.0 - 2013-03-21 - This is a major release = 
* More slide types to choose from: image, video (youtube and vimeo) and custom HTML.
* Added icons to the UI to indicate different slide types.
* Replaced cookies with localstorage to store UI status.
* Updated the templates to support the various slide types.
* Added resize and random options.
* Bug fix for fatal error when no GD library. Added gd_info check.
* Bug fix for js error on WP below 3.5 caused by the 3.5 media library object being undefined.
* Deprecated cycloneslider_thumb use cyclone_slide_image_url instead.
* Deprecated cycloneslider_settings use cyclone_settings instead.
* Deprecated cycloneslider_slide_settings use cyclone_slide_settings instead.
* Various UI fixes and code refactoring.

= 2.2.5 - 2013-02-23 = 
* Bug fix for 2.2.4

= 2.2.4 - 2013-02-22 = 
* Now compiles the template CSS and JS files instead of using template_redirect hook. This is to fix problems with some users reporting broken css and js.
* Minified CSS and JS for templates.
* Compiles needed CSS and JS only instead of loading all CSS and JS from all templates.
* Added template column to all slideshow screen.
* Updated language files

= 2.2.3 - 2013-02-14 = 
* Added option for random slide order on every page visit.
* Refactored some code.
* Added image count to all slideshow screen.

= 2.2.2 - 2013-02-05 = 
* Updated language files.
* Bug Fix. Post Type Switcher fix via jquery.
* UI Enhancement. Removed overflow for templates.
* Ignore image resize if slideshow dimension is equal to the image dimension.
* UI Enhancement. Decrease drag delay for slide sortables in editor.

= 2.2.1 - 2012-12-25 = 
* Added Cyclone Slider 2 widget. 

= 2.2.0 - 2012-12-24 = 
* Updated cycle 2 to latest version.
* Updated template selection interface to be more visual. A screenshot of each slideshow template is now shown.
* Added Tile Count and Tile Position for both slideshow and per-slide settings.
* Cleanup Quick Edit screen to hide unused user interface.
* Slide box titles can now be clicked to open and close the slide box.
* Removed drag icon from slide box title. Slide box can now be dragged by click-holding the slide title area.
* Updated template API functions.
* Updated plugin screenshot.
* Refactored various code parts.
* Added ability to add script.js in templates
* Added ability to add screenshot.jpg in templates.
* Updated templates.
* Added fix to preserved PNG transparency.
* Fix save routine to allow saving empty slides and to preserve order of slides after drag and/or deletion of slide.

= 2.1.1 - 2012-11-16 = 
* Fix for a code typo error

= 2.1.0 - 2012-11-16 = 
* Fix for slideshow not working when NextGEN 1.9.7 is active
* You can now import images from NextGEN

= 2.0.0 =
* Initial. If you are using Cyclone Slider (version 1) deactivate it first before activating Cyclone Slider 2
