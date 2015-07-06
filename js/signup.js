$(document).ready(function(){

	$('body').on('keyup', '.new_username',function(){
        $.ajax({
            url: "/checkusername",
            datatype: "html",
            data: {check_username: $('.new_username').val()},
            beforeSend: function() {
                
            },
            success: function(data) {
                $('.nametaken').html(data);
            }
        });
    })
    .on('click','.submitbtn', function(event){

		var username = $('.new_username').val();
		var newpass = $('.new_password').val();
		var passconf = $('.password_conf').val();
		var email = $('.email').val();
		var regEmail = new RegExp(/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/);
		
		if(username == '' || newpass == '' || passconf == '' || email ==''){
			event.preventDefault();
			$('.signup-banner').after('<p class="error">Please fill in the required fields.</p>');

			if(username == ''){$('.new_username').addClass('required');}
			if(newpass == ''){$('.new_password').addClass('required');}
			if(passconf == ''){$('.password_conf').addClass('required');}
			if(email == ''){$('.email').addClass('required');}

			return false;
		}
		if(newpass != passconf){
			$('.signup-banner').after('<p class="error">Please make sure your password matches</p>');
			return false;
		}
		if (!regEmail.test(email)) {
			$('.signup-banner').after('<p class="error">Please enter a valid email addreess.</p>'); 
			$('.email').focus();
			return false;
		}
		if(!$('input[type=checkbox]:checked').length) {
            $('.signup-banner').after('<p class="error">You must accept the Terms and Conditions</p>');
            $('.terms').addClass('checkterms');
            //stop the form from submitting
            return false;
        }
        if(!isHuman){
        	$('.signup-banner').after('<p class="error">Stop spamming bro! Hover over that circle!</p>');
        	return false;
        }
	})
	.on('keyup', '.new_username,.new_password,.password_conf,.email', function(){
		var inputVal = $(this).val();
		$(this).removeClass('required');//if required was added after submit
		if(inputVal == ''){
			$(this).addClass('required');
		}
	});

    $('input[type="checkbox"]').click(function(){
    	if($(this).closest('p').hasClass('checkterms')){
    		$(this).closest('p').removeClass('checkterms');
    	}
    });

    // Make sure user hovers over circle
	var isHuman = false;
	$(".humancheck").hover( function () {
		$(this).data('timeout', setTimeout( function () {
			isHuman = true;
			// showError('You have been hovering this element for 800ms');
			$('.humancheck').addClass('hover');
			$('.humancheck span').html('<i class="fa fa-check"></i>')
			$('#submit').fadeIn(500);
		}, 800));
	}, function () {
		clearTimeout($(this).data('timeout'));
	});
});