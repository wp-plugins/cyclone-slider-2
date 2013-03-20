jQuery(document).ready(function(){jQuery(".cycloneslider-template-black").each(function(b,d){var a=jQuery(d),e=a.find(".cycloneslider-prev"),c=a.find(".cycloneslider-next");e.fadeTo(0,0);c.fadeTo(0,0);a.on("mouseenter",function(){e.fadeTo("fast",0.4);c.fadeTo("fast",0.4)}).on("mouseleave",function(){e.fadeTo(0,0);c.fadeTo(0,0)});e.on("mouseenter",function(){e.fadeTo("fast",1)}).on("mouseleave",function(){e.fadeTo("fast",0.4)});c.on("mouseenter",function(){c.fadeTo("fast",1)}).on("mouseleave",function(){c.fadeTo("fast",0.4)})})});
jQuery(document).ready(function(){jQuery(".cycloneslider-template-blue").each(function(b,d){var a=jQuery(d),e=a.find(".cycloneslider-prev"),c=a.find(".cycloneslider-next");e.fadeTo(0,0);c.fadeTo(0,0);a.on("mouseenter",function(){e.fadeTo("fast",0.4);c.fadeTo("fast",0.4)}).on("mouseleave",function(){e.fadeTo(0,0);c.fadeTo(0,0)});e.on("mouseenter",function(){e.fadeTo("fast",1)}).on("mouseleave",function(){e.fadeTo("fast",0.4)});c.on("mouseenter",function(){c.fadeTo("fast",1)}).on("mouseleave",function(){c.fadeTo("fast",0.4)})})});
jQuery(document).ready(function(){jQuery(".cycloneslider-template-default").each(function(b,d){var a=jQuery(d),e=a.find(".cycloneslider-prev"),c=a.find(".cycloneslider-next");e.fadeTo(0,0);c.fadeTo(0,0);a.on("mouseenter",function(){e.fadeTo("fast",0.4);c.fadeTo("fast",0.4)}).on("mouseleave",function(){e.fadeTo(0,0);c.fadeTo(0,0)});e.on("mouseenter",function(){e.fadeTo("fast",1)}).on("mouseleave",function(){e.fadeTo("fast",0.4)});c.on("mouseenter",function(){c.fadeTo("fast",1)}).on("mouseleave",function(){c.fadeTo("fast",0.4)})})});
jQuery(document).ready(function(){jQuery(".cycloneslider-template-myrtle").each(function(b,d){var a=jQuery(d),e=a.find(".cycloneslider-prev"),c=a.find(".cycloneslider-next");a.on("mouseenter",function(){e.show();c.show()}).on("mouseleave",function(){e.hide();c.hide()})})});
jQuery(document).ready(function(){jQuery(".cycloneslider-template-thumbnails").each(function(c,d){var a=jQuery(d),b=a.children(".cycloneslider-slides"),e=a.next();e.find("li:first").addClass("current");b.on("cycle-before",function(g,h){var f=h.nextSlide;e.find("li").removeClass("current").eq(f).addClass("current")});e.on("click","li",function(){var f=jQuery(this).index();b.cycle("goto",f)})})});
jQuery(document).ready(function(){jQuery(".cycloneslider-template-controls").each(function(d,e){var a=jQuery(e),c=a.children(".cycloneslider-slides"),b=a.find(".cycloneslider-controls"),f;c.on("cycle-before",function(){c.find(".cycloneslider-caption").animate({opacity:0,left:0},100)});c.on("cycle-after",function(){var g="10%";if(jQuery(window).width()<=480){g=0}c.find(".cycloneslider-caption").animate({opacity:1,left:g},250)});a.find(".cycloneslider-play-pause").click(function(){if(jQuery(this).hasClass("play")){jQuery(this).removeClass("play");c.cycle("resume")}else{jQuery(this).addClass("play");c.cycle("pause")}});if(jQuery.browser.msie&&parseInt(jQuery.browser.version,10)===7){f=0;b.children().children().each(function(g){f+=jQuery(this).outerWidth(true)});b.children().width(f)}})});
jQuery(document).ready(function(){
	jQuery('.cycloneslider-template-dos').each(function(i,el){
		
		var main = jQuery(el);
		var slideshow = main.children('.cycloneslider-slides');
		var carousel = main.find('.thumbnails-carousel');
		var controls = main.find('.cycloneslider-controls');
		var slide_titles = controls.data('titles').split(",");
		var slide_descs = controls.data('descriptions').split(",");
		
		slideshow.on( 'cycle-initialized ', function( event, optionHash ) {
			controls.find('.cycloneslider-counter').html('1 / '+optionHash.slideCount);
			controls.find('.cycloneslider-caption-title').html(slide_titles[optionHash.currSlide]);
			controls.find('.cycloneslider-caption-description').html(slide_descs[optionHash.currSlide]);
		});
		
		slideshow.on( 'cycle-before', function( event, optionHash, outgoingSlideEl, incomingSlideEl, forwardFlag ) {
			controls.find('.cycloneslider-counter').html(optionHash.slideNum+' / '+optionHash.slideCount);
			controls.find('.cycloneslider-caption-title').html(slide_titles[optionHash.nextSlide]);
			controls.find('.cycloneslider-caption-description').html(slide_descs[optionHash.nextSlide]);
			carousel.find('img').removeClass('current').eq(optionHash.nextSlide).addClass('current');
		});
		
		/*** Play Pause ***/
		controls.find('.cycloneslider-autoplay').click(function(){
			if(jQuery(this).hasClass('pause')){/*** pause icon showing, autoplay is on ***/
				jQuery(this).removeClass('pause');
				slideshow.cycle('pause');
			} else {
				jQuery(this).addClass('pause');
				slideshow.cycle('resume');
			}
		});
		
		/*** Toggle Thumbnails ***/
		controls.find('.cycloneslider-thumbs').click(function(){
			if(jQuery(this).hasClass('shown')){
				jQuery(this).removeClass('shown');
				main.find('.cycloneslider-thumbnails').animate({bottom:'-100px'},200);
			} else {
				jQuery(this).addClass('shown');
				main.find('.cycloneslider-thumbnails').animate({bottom:'30px'},200);
			}
		});
		
		slideshow.cycle();
		
		
		carousel.find('.cycle-slide').click(function(){
			var index = carousel.data('cycle.API').getSlideIndex(this);
			slideshow.cycle('goto', index);
		});
		
	});
});
jQuery(document).ready(function(){jQuery(".cycloneslider-template-galleria").each(function(e,f){var a=jQuery(f),d=a.children(".cycloneslider-slides"),g=a.find(".thumbnails-carousel"),c=a.find(".cycloneslider-controls"),b=c.data("titles").split(","),h=c.data("descriptions").split(",");d.on("cycle-initialized ",function(i,j){c.find(".cycloneslider-counter").html("1 / "+j.slideCount);c.find(".cycloneslider-caption-title").html(b[j.currSlide]);c.find(".cycloneslider-caption-description").html(h[j.currSlide])});d.on("cycle-before",function(k,l,m,j,i){c.find(".cycloneslider-counter").html(l.slideNum+" / "+l.slideCount);c.find(".cycloneslider-caption-title").html(b[l.nextSlide]);c.find(".cycloneslider-caption-description").html(h[l.nextSlide]);g.find("img").removeClass("current").eq(l.nextSlide).addClass("current")});c.find(".cycloneslider-autoplay").click(function(){if(jQuery(this).hasClass("pause")){jQuery(this).removeClass("pause");d.cycle("pause")}else{jQuery(this).addClass("pause");d.cycle("resume")}});c.find(".cycloneslider-thumbs").click(function(){if(jQuery(this).hasClass("shown")){jQuery(this).removeClass("shown");a.find(".cycloneslider-thumbnails").animate({bottom:"-100px"},200)}else{jQuery(this).addClass("shown");a.find(".cycloneslider-thumbnails").animate({bottom:"30px"},200)}});d.cycle();g.find(".cycle-slide").click(function(){var i=g.data("cycle.API").getSlideIndex(this);d.cycle("goto",i)})})});
jQuery(document).ready(function(){jQuery(".cycloneslider-template-lea").each(function(c,e){var a=jQuery(e),b=a.children(".cycloneslider-slides"),g=a.find(".cycloneslider-nav"),f=g.find(".cycloneslider-nav-carousel"),d=f.find(".cycle-carousel-wrap"),h=0;jQuery(window).resize(function(){b.find(".cycloneslider-caption").height(b.height())});f.find("img:first").addClass("current");b.on("cycle-before",function(k,l){var j=l.nextSlide;f.find("img").removeClass("current").eq(j).addClass("current")});f.on("click","img",function(){var j=jQuery(this).index();b.cycle("goto",j)});f.cycle();f.find("img").each(function(){h+=jQuery(this).outerWidth(true)});d.width(h)})});
jQuery(document).ready(function(){jQuery(".cycloneslider-template-uno").each(function(c,d){var a=jQuery(d),b=a.children(".cycloneslider-slides"),e=a.find(".cycloneslider-pager");jQuery(window).resize(function(){b.find(".cycloneslider-caption").height(b.height())});e.css("marginLeft","-"+(e.width()/2)+"px")})});
