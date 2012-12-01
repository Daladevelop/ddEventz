$('aside#pluginlist div.plugin').draggable();
$('section#activeplugins').droppable({
	drop:function(event,ui){
		
		$(this).addClass('over'); 
		$(this).append($(event.srcElement).parent().clone().removeAttr('style'));
		$(event.srcElement).parent().css("left",'');
	   	$(event.srcElement).parent().css("top",''); 	
		
	}



});
