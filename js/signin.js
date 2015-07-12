$(document).ready(function(){

	$('body').on('click','.submitbtn', function(event){

		var username = $('.username').val();
		var password = $('.password').val();
		
		if(username == '' || password == ''){
			event.preventDefault();
			$('.signup-banner').after('<p class="error">Please fill in the required fields.</p>');

			if(username == ''){$('.username').addClass('required');}
			if(password == ''){$('.password').addClass('required');}

			return false;
		}
		// TODO: Do client side validation and show error
		
		
	});
	

});