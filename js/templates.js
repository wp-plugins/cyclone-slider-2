jQuery(document).ready(function(){
	jQuery('.cycloneslider-template-thumbnails').each(function(i,el){
		
		var main = jQuery(el);
		var slideshow = main.children('.cycloneslider-slides');
		var thumbnails = main.next();
		
		thumbnails.find('li:first').addClass('current');
		slideshow.on( 'cycle-before', function( event, optionHash ) {
			var i = optionHash.nextSlide;
			thumbnails.find('li').removeClass('current').eq(i).addClass('current');
		});
		thumbnails.on('click', 'li', function(){
			var i = jQuery(this).index();
			slideshow.cycle('goto', i);
		});
	});
});
