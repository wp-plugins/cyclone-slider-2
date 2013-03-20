jQuery(document).ready(function(){
	jQuery('.cycloneslider-template-myrtle').each(function(i,el){
		
		var main = jQuery(el),
			prev = main.find('.cycloneslider-prev'),
			next = main.find('.cycloneslider-next');
		
		main.on('mouseenter', function(){
			prev.show();
			next.show();
		}).on('mouseleave', function(){
			prev.hide();
			next.hide();
		});
	});
});