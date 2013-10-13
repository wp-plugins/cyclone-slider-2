(function() {
	var main = '.cycloneslider-template-thumbnails';
	
	jQuery(document).on('cycle-initialized', main+' .cycloneslider-slides', function( event, optionHash ) {
		
		jQuery(this).parent().next().find('li').eq(optionHash.currSlide).addClass('current'); /* Highlight first thumb */
		
	});
	
	jQuery(document).on('cycle-before', '.cycloneslider-template-thumbnails .cycloneslider-slides', function( event, optionHash, outgoingSlideEl, incomingSlideEl, forwardFlag ) {
		var i = optionHash.nextSlide,
			slide = jQuery( outgoingSlideEl ); /* Current slide */
		
		jQuery(this).parent().next().find('li').removeClass('current').eq(i).addClass('current');
		
		if(optionHash.dynamicHeight == "on") jQuery(this).animate({height:jQuery(incomingSlideEl).outerHeight()}, optionHash.autoHeightSpeed, optionHash.autoHeightEasing); /* Autoheight when dynamic height is on and auto height is not ratio (eg. 300:250) */
		
		if(slide.hasClass('cycloneslider-slide-youtube')) pauseYoutube( slide ); /* Pause youtube video on next */
		
		if(slide.hasClass('cycloneslider-slide-vimeo')) pauseVimeo( slide ); /* Pause vimeo video on next */
	});
	
	jQuery(document).on('cycle-initialized cycle-after', '.cycloneslider-template-thumbnails .cycloneslider-slides', function( event, optionHash, outgoingSlideEl, incomingSlideEl, forwardFlag ) {
		var index = (event.type == 'cycle-initialized') ? optionHash.currSlide : optionHash.nextSlide;
		var slide = jQuery( optionHash.slides[ index ] );
		slide.css('zIndex', parseInt(slide.css('zIndex'))+20); /* Fix for slideshow with youtube slide */
	});
	
	jQuery(document).on('click', '.cycloneslider-thumbnails li', function(){
		var i = jQuery(this).index();
		
		jQuery(this).parents('.cycloneslider-thumbnails').prev().find('.cycloneslider-slides').cycle('goto', i);
	});
	
	function pauseYoutube( slide ){
		var data = {
			"event": "command",
			"func": "pauseVideo",
			"args": [],
			"id": ""
		}
		postMessage( slide.find('iframe'), data, '*');
	}
	
	function pauseVimeo( slide ){
		postMessage( slide.find('iframe'), {method:'pause'}, slide.find('iframe').attr('src'));
	}
	
	function postMessage(iframe, data, url){
		try{
			if (iframe[0]) { // Frame exists
				iframe[0].contentWindow.postMessage(JSON.stringify(data), url);
			}
		} catch (ignore) {}
	}
})();