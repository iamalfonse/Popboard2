$(document).ready(function(){

	$('.invitelist .acceptinvite').click(function(){
		update_invite($(this).closest('li'),'accepted');
	});
	$('.invitelist .rejectinvite').click(function(){
		update_invite($(this).closest('li'),'rejected');
	});

});//end document.ready

update_invite = function(li,choice){
	var userid = $(li).data('userid');

	$.ajax({
		url: "/update_invite",
		datatype: "html",
		data: {user_id: userid, decision: choice},
		beforeSend: function() {
		    console.log('update invite status for '+userid);
		},
		success: function(data) {
			console.log('invite status updated for: '+data);
			window.location = '/invites';
		}
	});
}