$(document).ready(function(){
	
	//init show posts
    show_posts();

	//load more posts by scrolling down
	$(window).scroll(function(){
		var sidepost = $('.sidePost').offset();
		var scrollTop = $(document).scrollTop();
		// console.log(scrollTop);
		$('.sidePost').css({'top':scrollTop});

		show_posts();

    }); 
	
	$('.load-more').click(function(){
		load_more_posts();
	});

	enable_liking();
    
	

});//end document.ready


//load more posts
var processing = false;
var starting = 6; //start of posts
var limitofposts = 6; //limit of posts
var nomoreposts = false;
load_more_posts = function() {
	var category = $('#category').val();
	processing = true;

	if(!nomoreposts){ // if there's still posts to load, load them
		$.ajax({
			url: "../get_posts.php?category="+category,
			datatype: "html",
			data: {start: starting, limit: limitofposts},
			beforeSend: function() {
			    $('.load-more').html('<img src="/images/loadmorespinner.gif" />');
			},
			success: function(data) {
				if(data == 0){
					$('.load-more').addClass('none').html('No More Posts');
					nomoreposts = true;
				}else {
					$("#right").append(data);
					starting = starting + limitofposts;
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

var frombottom = 400; //num of pixels from the bottom of the page
show_posts = function(){
	if(processing){ 
		return;
	}

	// if at bottom of blogwall, add new content:
	var winHeight = window.innerHeight ? window.innerHeight : $(window).height();
	if ($(window).scrollTop() >= ($(document).height() - winHeight)-frombottom && $('body').length) {
		load_more_posts();
	}
}
