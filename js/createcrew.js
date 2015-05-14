$(document).ready(function(){
	
	$('body').on('focusout', '.crewinfo .crewName', function(){
		var crewname = $(this).val();
		$('.crewlist h3').html(crewname);
		$(this).removeClass('required');//if required was added after submit
		if(crewname == ''){
			$(this).addClass('required');
		}
		//check if crewname is already taken
		$.ajax({
			url: "/checkcrewname.php",
			data:{check_crewname: crewname},
			beforeSend: function() {
			    console.log('checking if '+crewname+' exists');
			},
			success: function(data) {
				$('#crewnametaken').html(data);
			}
		});
	})
	.on('keyup', '.crewinfo .crewDesc', function(){
		var crewdesc = $(this).val();
		$('.crewPreview .crewAbout').html(crewdesc);
		$(this).removeClass('required');//if required was added after submit
		if(crewdesc == ''){
			$(this).addClass('required');
		}
	})
	.on('keyup', '.crewinfo .crewLoc', function(){
		var crewloc = $(this).val();
		$('.crewPreview .location').html(crewloc);
		$(this).removeClass('required');//if required was added after submit
		if(crewloc == ''){
			$('.crewPreview .location').html('&nbsp;');//placeholder if there's no value
			$(this).addClass('required');
		}
	})
	.on('focusout', '.crewinfo .crewDesc, .crewinfo .crewLoc', function(){
		var crewdesc = $('.crewinfo .crewDesc').val();
		var crewloc = $('.crewinfo .crewLoc').val();
		$('.crewPreview .crewAbout').html(crewdesc);
		$('.crewPreview .location').html(crewloc);
	})
	.on('click','.crewinfo input[type="radio"]', function(){
		var val = $(this).val();
		if(val == '1'){
			$('.crewprivate').append('<span>(Only crew members can view posts)</span>');
			$('.crewlist li').append('<div class="private">Private</div>');
		}else if(val == '0'){
			$('.crewprivate span, .crewlist .private').detach();
		}
	})
	.on('click','.submitbtn', function(event){
		
		var crewname = $('.crewinfo .crewName').val();
		var crewdesc = $('.crewinfo .crewDesc').val();
		var crewloc = $('.crewinfo .crewLoc').val();
		if(crewname == '' || crewdesc == '' || crewloc == ''){
			event.preventDefault();
			$('#right').prepend('<p class="error">Please fill in the required fields.</p>');

			if(crewname == ''){$('.crewinfo .crewName').addClass('required');}
			if(crewdesc == ''){$('.crewinfo .crewDesc').addClass('required');}
			if(crewloc == ''){$('.crewinfo .crewLoc').addClass('required');}

			return;
		}
	})
    

});//end document.ready
