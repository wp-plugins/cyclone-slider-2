(function() {
	jQuery(document).on('cycle-before', '.cycloneslider-template-default .cycloneslider-slides', function( event, optionHash, outgoingSlideEl, incomingSlideEl, forwardFlag ) {
		var slide = jQuery( outgoingSlideEl ); /* Current slide */
		
		if(optionHash.dynamicHeight == "on") jQuery(this).animate({height:jQuery(incomingSlideEl).outerHeight()}, optionHash.autoHeightSpeed, optionHash.autoHeightEasing);
		
		if(slide.hasClass('cycloneslider-slide-youtube')) pauseYoutube( slide ); /* Pause youtube video on next */
		
		if(slide.hasClass('cycloneslider-slide-vimeo')) pauseVimeo( slide ); /* Pause vimeo video on next */
	});
	
    jQuery(document).on('cycle-initialized cycle-after', '.cycloneslider-template-default .cycloneslider-slides', function( event, optionHash, outgoingSlideEl, incomingSlideEl, forwardFlag ) {
		var index = (event.type == 'cycle-initialized') ? optionHash.currSlide : optionHash.nextSlide;
		var slide = jQuery( optionHash.slides[ index ] );
		slide.css('zIndex', parseInt(slide.css('zIndex'))+20); /* Fix for slideshow with youtube slide */
	});
	
	function pauseYoutube( slide ){
		callYoutubeAPI( slide.find('iframe'), 'pauseVideo');
	}
	
	function pauseVimeo( slide ){
		callVimeoAPI( slide.find('iframe'), slide.find('iframe').attr('src') );
	}
	
	function callYoutubeAPI(iframe, func, args) {
		try{
			if (iframe[0]) {
				// Frame exists, 
				iframe[0].contentWindow.postMessage(JSON.stringify({
					"event": "command",
					"func": func,
					"args": args || [],
					"id": ''
				}), "*");
			}
		} catch (ignore) {}
	}
	
	function callVimeoAPI(iframe, url) {
		try{
			if (iframe[0]) {
				// Frame exists, 
				iframe[0].contentWindow.postMessage(JSON.stringify({
					method: "pause"
				}), url);
			}
		} catch (ignore) {}
	}
})();