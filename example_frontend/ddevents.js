var theFeed = 0; 
var pointer = -1;
var cardslist;
$(document).ready(function() {

	$.getJSON('../index.php?callback=?&eventId=2', function(data) {
		var content = '<ul class="cardslist">';
		for (var i in data) {
			content += '<li class="hidden cards">';
			content += '<h2>' + data[i].metadata.service + '</h2>';
			content += '<p>' + data[i].content.text + '</p>';

			if (data[i].metadata.service == 'instagram') {
				content += '<figure><img src="' + data[i].content.media[0].hires_url + '" /></figure>';	
			}

			content += '<p class="byline">' + data[i].metadata.handle + '</p>';
			content += '</li>';
		}
		content += '</ul>';

		$('#content').append(content);
		
		cardslist = document.getElementsByClassName('cards');
		turn();
		interval = window.setInterval('turn()', 10000);
		window.setInterval('reverse()', 5000);
	});

});

function turn() {
	//cardslist[pointer].
	pointer = pointer + 1;
	cardslist[pointer].setAttribute('class', 'cards');

	pointer = pointer + 1;
	cardslist[pointer].setAttribute('class', 'cards');
}

function reverse() {
	if ($('.cardslist').hasClass('reversed')) {
		$('.cardslist').removeClass('reversed');	
	} else {
		$('.cardslist').addClass('reversed');	
	}
}


function getContent(feedItem)
{
	console.log(feedItem);
	var content = '';

	if(feedItem.metadata.service == 'instagram')
	{
		content ='<h2>'+feedItem.content.text+'</h2>';
		content+='<img src="'+feedItem.content.media[0].hires_url+'"/>';
	}
	else if(feedItem.metadata.service == 'twitter')
	{
		content = '<img src="http://twitter.com/api/users/profile_image/'+feedItem.metadata.handle+'" class="avatar"/>';
		content+= '<p>' + feedItem.content.text + '</p>';
		console.log(feedItem.metadata.handle); 
		

	}

	else if(feedItem.metadata.service == 'RSS')
	{
		content = "<h2>RSS</h2>";
		content += feedItem.content;

	}

	else if (feedItem.metadata.service == 'app.net') {
		content = '<h2>App.Net</h2>';
		content += '<p>' + feedItem.content.text + '<br /> – ' + feedItem.metadata.handle + '</p>';
	}



	return content; 
}
