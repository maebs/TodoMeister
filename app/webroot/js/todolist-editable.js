$(function() {
	$( ".sortable" ).sortable({
	 	placeholder: "sortablePlaceholder",
	   	connectWith: ".sortable",
	 	start: function(event, ui) { 
			$('body').addClass('editing');
		},
	

	   	update: function(event, ui) {
			resizeColorBlocks();
		
			// update the time in the item
			ui.item.find("#time").html("(0 minutes ago)");
			ui.item.find("#user").html(user_name);
			
			$('.column').each(function(index) {
				var totalNumberTodos = $(this).find('li').length;
				more = $(this).find('div.more');
				if(more.length > 0){
					$(this).find('.numberOfTodos').html(totalNumberTodos+'+');
				}else{
					$(this).find('.numberOfTodos').html(totalNumberTodos);
				}
			});

			

			data = '';
			$('ul.sortable').each(function(index) {
			    data += '&column'+ (index+1) + '=' + $(this).sortable( "toArray" );
			});
			$.ajax({
				type: "POST",
				url: order_update_url,
				data: "item="+ui.item.attr('id') + data,
				success: function(html){
					$('body').removeClass('editing');
					$('body').addClass('dontReload');				
				}
			});
		}
	});
	
	$('.edit_button').click(function() {
		editElement($(this).parent().parent().parent());
	});
	
	// edit a todoitem when a user clicks the text
	$('.editable').dblclick(function() {
		editElement($(this));
	});
	
	function editElement(item){
		
		$('body').addClass('editing');
			
		if(item.hasClass('editable')){
			item.removeClass('editable');
			id = item.attr('id');
			originaltext = item.find('.original').html();
			
			replacetext = '<div class="form">';
			replacetext += '<form method="post" action="'+text_update_url+'">';
			replacetext += '<textarea name="todo" type="text">'+originaltext+'</textarea>';
			replacetext += '<input name="id" type="hidden" value="'+id+'">';
			replacetext += '<div class="twinForm">';
			replacetext += '<input style="width: 50%; left: 0%;" type="button" class="cancel" value="cancel">';
			replacetext += '<input style="width: 50%;" type="submit" value="save">';
			replacetext += '</div>';
			replacetext += '</form>';
			replacetext += '</div>';
			item.children('p.text').before(replacetext);
			
			item.find('.cancel').click(function() {
				item = $(this).parent().parent().parent().parent();
				item.children('p.text').show();
				item.children('.actions').show();
				item.children('.footer').show();
				item.children('div.form').remove();
				item.addClass('editable');
				$('body').removeClass('editing');
				resizeColorBlocks();
			});
			
			item.children('p.text').hide();
			item.children('.actions').hide();
			item.children('.footer').hide();
			resizeColorBlocks();
		};
	}
	
	$.fn.colorPicker.defaultColors =  ['BE7CBA','6460AF','83AFDC','3A804A','E9EB4A','F4AE48','EB3D36','A8773E','000000','FF00FF','CCCCCC'];

	$('.color_picker').colorPicker();
	
	resizeColorBlocks();
	
	// handle color changes
	$('.color_picker').change(function() {
		var id =  $(this).attr('name');
		var color =  $(this).attr('value');
		
		$.ajax({
		   type: "POST",
		   url: color_update_url+'/'+id,
		   data: "color="+color,
		   success: function(html){
				$('body').addClass('dontReload');
			}
		 });
	});
	
	
	// the url can contain a # and a status number, this gives input to right field
	urlparts = window.location.href.split('#');
	urlparts.shift();
	if(urlparts.length > 0){
		selector = '.column.status'+urlparts[0]+' input:text.todo';
		$(selector).focus();
	}
});


