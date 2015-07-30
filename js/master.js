$(document).ready(function(){

	//for signup area
	// if($('#signupform').length){
	// 	$('#signupform').validate();
	// }

	//error window handling
	$('body').on('click', '.btn.confirm', function(){
		var linkurl = $(this).attr('href');
		window.location = linkurl;
	})
	.on('click', 'p.error', function(){
		$(this).hide(300);
		setTimeout(function(){
			$(this).detach();
		},400);
	})

    //delete post handler
    .on('click', '.deletebtn a', function(event){
    	event.preventDefault();
    	//show error box
    	var linkurl = $(this).attr('href');
    	show_delete_dialogue(linkurl);
    })

    //click to show menu on mobile
    .on('click','.mobilemenu', function(){
    	$('#left').addClass('open');
    })
    .on('click','.closemobile', function(){
    	$('#left').removeClass('open');
    })

    //Create post btn
	.on('click','.createpostbtn', function(e){
		e.preventDefault();
		$('body').addClass('openOverlay');
		var overlay = $('.createpostOverlay');
		overlay.height($(document).height() + 100);
	})
	.on('click', '#createpost .closebtn, .createpostOverlay', function(){
		$('body').removeClass('openOverlay');
	});


	// reply to comments
	$('.reply').click(function(){
		var targetUsername = $(this).data('username');
		var textarea = $('#addcomment textarea');
		var oldVal = textarea.val();
		textarea.focus().val(oldVal+'@'+targetUsername);
	});

	// tooltips
	$('.tooltip').hover(function(){
		var elem = $(this);
		var tooltip = elem.data('tooltip');
		var elemWidth = elem.width();
		console.log('elemWidth: ', elemWidth);
		elem.append('<span class="tooltip-note">'+tooltip+'</span>');
		var tooltipWidth = elem.find('.tooltip-note').width();
		console.log(tooltipWidth/2);
		elem.find('.tooltip-note').css({'margin' : '0 0 0 -'+Math.floor((tooltipWidth/2)+(elemWidth/4))+'px'});
	}, function(){
		var elem = $(this);
		elem.find('.tooltip-note').detach();
	});

	// notifications
	$('.top-notifications').click(function(){
		$('.top-invites').removeClass('open'); //close invites if open
		if($(this).hasClass('open')){
			$(this).removeClass('open');
		}else {
			$(this).addClass('open');
		}
		// remove unread count
		$(this).find('.unread').detach();
		// mark all notifications as read
		update_notifications();
	});

	// invites
	$('.top-invites').click(function(){
		$('.top-notifications').removeClass('open'); //close notifications if open
		if($(this).hasClass('open')){
			$(this).removeClass('open');
		}else {
			$(this).addClass('open');
		}
	});

});//end document.ready

clearText = function(field){
    if (field.defaultValue == field.value) field.value = '';
    else if (field.value == '') field.value = field.defaultValue;
}

update_notifications = function(){
	var username = $.cookie('login_cookie');
	$.ajax({
		url: "/update_notifications",
		beforeSend: function() {
		    // console.log('update notifications for '+username);
		},
		success: function(data) {
			// console.log('notifications updated');
		}
	});
}
showError = function(error){
	var body = $('body');
	body.append('<div class="error-overlay"></div><div class="error-container"><p>'+error+'</p><button class="btn cancel">Close</button></div>');
	
	setTimeout(function(){
		body.addClass('openOverlay').on('click', '.btn.cancel', function(){
			body.removeClass('openOverlay');
			setTimeout(function(){
				$('.error-overlay, .error-container').remove();
			},1000);
			
		})
	},50);
	return false;
};	
show_delete_dialogue = function(linkurl){
	$('body').append('<div class="error-overlay"><div class="error-container"><p>Are you sure you want to do delete your post?</p><button class="btn cancel">Cancel</button><button class="btn confirm" href="'+linkurl+'">Confirm</button></div></div>');
};

