$(document).ready(function(){
	

$('.likes').on('click', function(){

	var thisLike = $(this); //used to reference $('.likes')
	var post_id = thisLike.parents('.postitem').find('h3').attr('id');
	$.ajax({
		url: "/likepost/"+post_id,
		datatype: "html",
		//data: {postid: post_id},
		beforeSend: function() {
			thisLike.html('wait...');
		},
		success: function(data) {
		    thisLike.html(data);
		}
	});
});

    


});//end document.ready



