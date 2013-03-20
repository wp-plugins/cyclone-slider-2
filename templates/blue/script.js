jQuery(document).ready(function(){
	jQuery('.cycloneslider-template-blue').each(function(i,el){
		
		var main = jQuery(el),
			prev = main.find('.cycloneslider-prev'),
			next = main.find('.cycloneslider-next');
		
		prev.fadeTo(0,0);
		next.fadeTo(0,0);
		main.on('mouseenter', function(){
			prev.fadeTo('fast',0.4);
			next.fadeTo('fast',0.4);
		}).on('mouseleave', function(){
			prev.fadeTo(0,0);
			next.fadeTo(0,0);
		});
		
		prev.on('mouseenter', function(){
			prev.fadeTo('fast',1);
		}).on('mouseleave', function(){
			prev.fadeTo('fast',0.4);
		});
		
		next.on('mouseenter', function(){
			next.fadeTo('fast',1);
		}).on('mouseleave', function(){
			next.fadeTo('fast',0.4);
		});
	});
});