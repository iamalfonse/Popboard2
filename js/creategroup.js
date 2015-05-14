$(document).ready(function(){
	
	$('body').on('focusout', '.groupinfo .groupName', function(){
		var groupname = $(this).val();
		$('.grouplist h3').html(groupname);
		$(this).removeClass('required');//if required was added after submit
		if(groupname == ''){
			$(this).addClass('required');
		}
		//check if groupname is already taken
		$.ajax({
			url: "/checkgroupname.php",
			data:{check_groupname: groupname},
			beforeSend: function() {
			    console.log('checking if '+groupname+' exists');
			},
			success: function(data) {
				$('#groupnametaken').html(data);
			}
		});
	})
	.on('keyup', '.groupinfo .groupDesc', function(){
		var groupdesc = $(this).val();
		$('.groupPreview .groupAbout').html(groupdesc);
		$(this).removeClass('required');//if required was added after submit
		if(groupdesc == ''){
			$(this).addClass('required');
		}
	})
	.on('keyup', '.groupinfo .groupLoc', function(){
		var grouploc = $(this).val();
		$('.groupPreview .location').html(grouploc);
		$(this).removeClass('required');//if required was added after submit
		if(grouploc == ''){
			$('.groupPreview .location').html('&nbsp;');//placeholder if there's no value
			$(this).addClass('required');
		}
	})
	.on('focusout', '.groupinfo .groupDesc, .groupinfo .groupLoc', function(){
		var groupdesc = $('.groupinfo .groupDesc').val();
		var grouploc = $('.groupinfo .groupLoc').val();
		$('.groupPreview .groupAbout').html(groupdesc);
		$('.groupPreview .location').html(grouploc);
	})
	.on('click','.groupinfo input[type="radio"]', function(){
		var val = $(this).val();
		if(val == '1'){
			$('.groupprivate').append('<span>(Only group members can view posts)</span>');
			$('.grouplist li').append('<div class="private">Private</div>');
		}else if(val == '0'){
			$('.groupprivate span, .grouplist .private').detach();
		}
	})
	.on('click','.submitbtn', function(event){
		
		var groupname = $('.groupinfo .groupName').val();
		var groupdesc = $('.groupinfo .groupDesc').val();
		var grouploc = $('.groupinfo .groupLoc').val();
		if(groupname == '' || groupdesc == '' || grouploc == ''){
			event.preventDefault();
			$('#right').prepend('<p class="error">Please fill in the required fields.</p>');

			if(groupname == ''){$('.groupinfo .groupName').addClass('required');}
			if(groupdesc == ''){$('.groupinfo .groupDesc').addClass('required');}
			if(grouploc == ''){$('.groupinfo .groupLoc').addClass('required');}

			return;
		}
	})
    

});//end document.ready
