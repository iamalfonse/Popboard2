$(document).ready(function(){

	$('body').on('click','.submitbtn', function(event){
		event.preventDefault();

		var email = $('.email').val();
		var key = $('.key').val();
		var password = $('.password').val();
		var passconf = $('.passconf').val();
		
		if(password.length <= 0){
			event.preventDefault();
			$('.signup-banner').after('<p class="error">Please fill in the required fields.</p>');
			
			if(password == ''){$('.password').addClass('required');}
			return false;
		}
		if(passconf.length <= 0){
			event.preventDefault();
			$('.signup-banner').after('<p class="error">Please fill in the required fields.</p>');
			
			if(passconf == ''){$('.passconf').addClass('required');}
			return false;
		}
		
		if (password != passconf) { //passwords don't match
			$('.signup-banner').after('<p class="error">Password does not match. Please recheck your password.</p>');
			return false;
		}

		$.ajax({
			url: "/resetpassword.php",
			type: "POST",
			datatype: "html",
			data: {email: email, key: key, newpassword: password, passwordconfirm: passconf},
			beforeSend: function() {
			    console.log('reset password for '+email);
			},
			success: function(data) {
				// console.log('reset password for: '+data);
				// window.location = '/';
				$('#resetform').html(data);
			}
		});

	})
	.on('keyup', '.password, .passconf', function(){
		var inputVal = $(this).val();
		$(this).removeClass('required');//if required was added after submit
		if(inputVal == ''){
			$(this).addClass('required');
		}
	})


});