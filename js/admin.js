jQuery(document).ready(function($){
	(function() {

		/*** hide wordpress admin stuff ***/
		$('#minor-publishing-actions').hide();
		$('#misc-publishing-actions').hide();
		$('.inline-edit-date').prev().hide();
		
		/*** Template Chooser ***/
		$('.template-choices li').click(function(){
			$('.template-choices li').removeClass('active');
			$('.template-choices li input').removeAttr('checked');
			$(this).addClass('active').find('input').attr('checked','checked');
		});
		
		/*** show/Hide Tile Properties for slideshow ***/
		$('#cyclone-slider-properties-metabox').on('change', '#cycloneslider_settings_fx', function(){
			if($(this).val()=='tileBlind' || $(this).val()=='tileSlide'){
				$('.cycloneslider-field-tile-properties').slideDown('fast');
			} else {
				$('.cycloneslider-field-tile-properties').slideUp('fast');
			}
		});
		$("#cycloneslider_settings_fx").trigger('change');
		
		/*** show/Hide Tile Properties for slides ***/
		$('#cyclone-slides-metabox').on('change', '.cycloneslider_metas_fx', function(){
			if($(this).val()=='tileBlind' || $(this).val()=='tileSlide'){
				$(this).siblings('.cycloneslider-slide-tile-properties').slideDown('fast');
			} else {
				$(this).siblings('.cycloneslider-slide-tile-properties').slideUp('fast');
			}
		});
		$(".cycloneslider_metas_fx").trigger('change');
		
		/*** enable/disable form fields and labels ***/
		$('#cyclone-slides-metabox').on('change', '.cycloneslider_metas_enable_slide_effects', function(){
			if($(this).val()==0){
				$(this).parent().find('input,select').not(this).attr('disabled','disabled');
				$(this).parent().find('label,.note').addClass('disabled');
			} else {
				$(this).parent().find('input,select').not(this).removeAttr('disabled');
				$(this).parent().find('label,.note').removeClass('disabled');
			}
		});
		$(".cycloneslider_metas_enable_slide_effects").trigger('change');
		
		
		/*** make it sortable ***/
		$('.cycloneslider-sortable').sortable({
			handle:'.cycloneslider-box-title',
			placeholder: "cycloneslider-box-placeholder",
			forcePlaceholderSize:true,
			delay:500,
			/*** Update form field indexes when slide order changes ***/
			update: function(event, ui) {
				$('.cycloneslider-sortable .cycloneslider-box').each(function(boxIndex, box){ /*** Loop thru each box ***/
					$(box).find('input, select, textarea').each(function(i, field){ /*** Loop thru relevant form fields ***/
						var name = $(field).attr('name');
						if(name){
							name = name.replace(/\[[0-9]+\]/, '['+boxIndex+']'); /*** Replace all [index] in field_key[index][name] ***/
							$(field).attr('name',name);
						}
					});
				});
			}
		});
		
		/*** ID ***/
		$('.cycloneslider-upload-button').each(function(i){
			$(this).data('cycloneslider_id',i);
		});
		$('.cycloneslider-sortable .cycloneslider-box').each(function(i){
			$(this).data('cycloneslider_id',i);
			$(this).find('.cycloneslider-box-title-left').append((i+1));
		});
		
		
		
		/*** Add new slide box ***/
		$('input[name="cycloneslider_add_slide"]').on('click', function(e){
			var id = $('.cycloneslider-sortable .cycloneslider-box').length;
			var html = $('.cycloneslider-box-template').html();
			html = html.replace(/{id}/g, id);/*** replace all occurences of {id} to real id ***/
			
			$('.cycloneslider-sortable').append(html);
			$('.cycloneslider-sortable .cycloneslider-box:last').find('.cycloneslider-slide-thumb').hide().end().find('.cycloneslider-box-body').show();
			$('.cycloneslider-upload-button').each(function(i){
				$(this).data('cycloneslider_id',i);
			});
			$('.cycloneslider-sortable .cycloneslider-box').each(function(i){
				$(this).data('cycloneslider_id',i);
			});
			$('.cycloneslider-field-body').each(function(i){
				$(this).data('cycloneslider_id',i);
			});

			e.preventDefault();
		});
		
		/*** Toggle slide visiblity ***/
		$('#cyclone-slides-metabox').on('click',  '.cycloneslider-box-title', function(e) {
			var box = $(this).parents('.cycloneslider-box');
			var body = box.find('.cycloneslider-box-body');
			
			if(body.is(':visible')){
				body.slideUp(100);
				if($.cookie!=undefined){
					$.cookie('cycloneslider_box_'+box.data('cycloneslider_id'), null);
				}
				
			} else {
				body.slideDown(100);
				if($.cookie!=undefined){
					$.cookie('cycloneslider_box_'+box.data('cycloneslider_id'), 'open', { expires: 7});/*** remember open section ***/
				}
			}
			e.preventDefault();
		});
		
		/*** Slide Properties ***/
		$('.cycloneslider-field-body').each(function(i){
			$(this).data('cycloneslider_id',i);
		});
		
		/*** Slide Properties Toggle ***/
		$('.cycloneslider-meta-field .cycloneslider-field-title').live('click',function(e){
			var body = $(this).next();
			var id = body.data('cycloneslider_id');
			if(body.is(':visible')){
				body.slideUp(100);
				if($.cookie!=undefined){
					$.cookie('cycloneslider_slide_meta_field_'+id, null);/*** delete cookie ***/
				}
			} else {
				body.slideDown(100);
				if($.cookie!=undefined){
					$.cookie('cycloneslider_slide_meta_field_'+id, 'open', { expires: 7});/*** remember open section ***/
				}
			}
		});
		
		/*** Slide Properties Cookie ***/
		$('.cycloneslider-field-body').each(function(i){
			body = $(this);
			var id = $(this).data('cycloneslider_id');
			if($.cookie!=undefined){
				if($.cookie('cycloneslider_slide_meta_field_'+id)!='open'){/*** do not close open section ***/
					body.hide();
				}
			}
		});
		
		/*** hide all thats hidden ***/
		$('.cycloneslider-sortable .cycloneslider-box').each(function(){
			var body = $(this).find('.cycloneslider-box-body');
			var id = $(this).data('cycloneslider_id');
			if($.cookie!=undefined){
				if($.cookie('cycloneslider_box_'+id)=='open'){/*** do not close open section ***/
					body.show();
				}
			}
		});
		
		/*** Delete Slide ***/
		$('#cyclone-slides-metabox').on('click',  '.cycloneslider-box-delete', function(e) {

			var box = $(this).parents('.cycloneslider-box');
			box.fadeOut('slow', function(){ box.remove()});

			e.preventDefault();
			e.stopPropagation();
		});
	})();
	
	(function() {
		/*** Modify WP media uploader ***/
		var current_slide_box = false;/*** we use this var to determine if thickbox is being used in cycloneslider. also saves the field to be updated later. ***/
		$(document).on('click', '.cycloneslider-upload-button', function() {
			var box = $(this).parents('.cycloneslider-box');/*** get current box ***/
			
			current_slide_box = box;
			tb_show('', 'media-upload.php?referer=cycloneslider&amp;post_id=0&amp;type=image&amp;TB_iframe=true');/*** referer param needed to change button text ***/
			return false;
		});
		
		window.original_send_to_editor = window.send_to_editor;/*** backup original for other parts of admin that uses thickbox to work ***/
		window.send_to_editor = function(html) {
			if (current_slide_box) {
				var slide_thumb = current_slide_box.find('.cycloneslider-slide-thumb');/*** find the thumb ***/
				var slide_attachment_id = current_slide_box.find('.cycloneslider-slide-meta-id');/*** find the hidden field that will hold the attachment id ***/
				var slide_type = current_slide_box.find('.cycloneslider-slide-meta-type');/*** find the hidden field that will hold the type ***/
				
				var image = false;
				if(jQuery(html).get(0) != undefined){ /*** Check if its a valid html tag ***/
					if(jQuery(html).get(0).nodeName.toLowerCase()=='img'){/*** Check if html is an img tag ***/
						image = jQuery(html);
					} else { /*** If not may be it contains the img tag ***/
						if(jQuery(html).find('img').length > 0){
							image = jQuery(html).find('img');
						}
					}
				}
				if(image){
					var url = image.attr('src');
					var attachment_id = image.attr('data-id');
					if(url!=undefined && attachment_id != undefined ){
						slide_thumb.attr('src', url).show();
						slide_attachment_id.val(attachment_id);
						slide_type.val('image');
					} else {
						alert('Could not insert image. URL or attachment ID missing.');
					}
				} else {
					alert('Could not insert image.');
				}
				
				tb_remove();
				current_slide_box = false;
			} else {
				window.original_send_to_editor(html);
			}
		};
	})();
});