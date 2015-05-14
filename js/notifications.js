$(document).ready(function(){
	
	
	$('.load-notifications').click(function(){
		load_more_notifications();
	});

});//end document.ready


//load more notifications
var processing2 = false;
var starting2 = 5; //start of posts
load_more_notifications = function() {

	processing2 = true;
	$.ajax({
		url: "../get_notifications.php",
		datatype: "html",
		data: {start: starting2, limit: '5'},
		beforeSend: function() {
		    $('.load-more').html('<img src="/images/loadmorespinner.gif" />');
		},
		success: function(data) {
			if(data == 0){
				$('.load-notifications').replaceWith('<p>No More Notifications</p>');
			}else {
				$(".notificationlist").append(data);
				starting2 = starting2 + 5;
				$('.load-notifications').html('Load More');
				disable_liking();
				enable_liking();
			}
			processing2 = false;
		}
	});
}