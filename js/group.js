$(document).ready(function(){
	
	//init show posts
    show_posts();

	//load more posts by scrolling down
	$(window).scroll(function(){
		show_posts();
    }); 
	
	$('.load-more').click(function(){
		load_more_posts();
	});

	enable_liking();

	$('body').on('click','.joingroupbtn', function(){
		show_joinoverlay();
	})
	.on('click','.joinOverlay .closebtn, .joinOverlay .cancelBtn', function(){
		$('.joinOverlay').fadeOut(300);
	})
	.on('click','.joinOverlay .joinBtn', function(){
		join_group();
		$('.joinOverlay').fadeOut(300);
	})

});//end document.ready


//load more posts
var processing = false;
var starting = 6; //start of posts
var nomoreposts = false;
load_more_posts = function() {
	var groupurl = $('#groupurl').val();
	processing = true;

	if(!nomoreposts){ // if there's still posts to load, load them
		$.ajax({
			url: "../get_posts.php?groupurl="+groupurl,
			datatype: "html",
			data: {start: starting, limit: '6'},
			beforeSend: function() {
			    $('.load-more').html('<img src="/images/loadmorespinner.gif" />');
			},
			success: function(data) {
				if(data == 0){
					$('.load-more').addClass('none').html('No More Posts');
					nomoreposts = true;
				}else {
					$("#right").append(data);
					starting = starting + 6;
					$('.load-more').html('Load More');
					disable_liking();
					enable_liking();
				}
				processing = false;
			}
		});
	}
	
}

enable_liking = function() {
	//add likes to a post
	$('.likes').on('click', function(){

        var thisLike = $(this); //used to reference $('.likes')
		var post_id = thisLike.parents('.postitem').find('h3').attr('id');
		$.ajax({
			url: "../likepost/"+post_id,
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
}
disable_liking = function() {
	$('.likes').off();
}

show_posts = function(){
	if(processing){ 
		return;
	}

	// if at bottom of blogwall, add new content:
	var winHeight = window.innerHeight ? window.innerHeight : $(window).height();
	if ($(window).scrollTop() >= ($(document).height() - winHeight)-200 && $('body').length) {
		load_more_posts();
	}
}

show_joinoverlay = function(){
	$('.joinOverlay').fadeIn(300);

}

join_group = function() {
	
	var thisBtn = $('.joingroupbtn'); 
	var group_id = $('#groupid').val();
	$.ajax({
		url: "../joingroup",
		datatype: "html",
		data: {groupid: group_id},
		type: 'POST',
		beforeSend: function() {
			thisBtn.removeClass('btn joingroupbtn').addClass('pending').html('wait...');
		},
		success: function(data) {
		    thisBtn.html(data);
		}
	});
}
