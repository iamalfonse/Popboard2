$(document).ready(function(){

	$('body').on('click','.submitbtn', function(event){
		event.preventDefault();

		var email = $('.email').val();
		var regEmail = new RegExp(/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/);
		
		if(email.length <= 0){
			event.preventDefault();
			$('.signup-banner').after('<p class="error">Please fill in the required fields.</p>');
			
			if(email == ''){$('.email').addClass('required');}
			return false;
		}
		
		if (!regEmail.test(email)) {
			$('.signup-banner').after('<p class="error">Please enter a valid email addreess.</p>'); 
			$('.email').focus();
			return false;
		}

		$.ajax({
			url: "/forgotpasswordsubmit.php",
			type: "POST",
			datatype: "html",
			data: {old_email: email},
			beforeSend: function() {
			    console.log('reset password for '+email);
			},
			success: function(data) {
				// console.log('reset password for: '+data);
				// window.location = '/';
				$('#forgotpasswordform').html(data);
			}
		});

	})
	.on('keyup', '.email', function(){
		var inputVal = $(this).val();
		$(this).removeClass('required');//if required was added after submit
		if(inputVal == ''){
			$(this).addClass('required');
		}
	})


});