$('aside#pluginlist div.plugin').draggable();
$('section#activeplugins').droppable({
	drop:function(event,ui){
		$(this).addClass('over'); 
		$(this).append($(event.srcElement).clone().removeAttr('style'));
		$(event.srcElement).css("left",'');
	   	$(event.srcElement).css("top",''); 	
		
	}



});
