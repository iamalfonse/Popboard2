<?php
	include("config.php");
	
	//get username for reference
	$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
	$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);

	if(isset( $_COOKIE['login_cookie'])){
	    $r  = mysqli_query($dblink, "SELECT user_id FROM users WHERE username='$username' AND session_hash = '$session_hash'");
		$Rows = mysqli_fetch_assoc($r);
		
		//grab the user who is trying to get accepted
		$requester_userid = $_GET['user_id'];
		$decision = $_GET['decision'];
		echo $requester_userid." ".$decision;

		
		//select * from crew invites
		$updateCrewInviteQuery  = mysqli_query($dblink, "SELECT * FROM crewinvites WHERE user_id = '$requester_userid'");
		$updateCrewInviteRow = mysqli_fetch_assoc($updateCrewInviteQuery);

		if($decision == 'accepted'){

			//then add user to crew (add a crew_id to user)
			$updateCrewInviteQuery2  = mysqli_query($dblink, "UPDATE users SET crew_id = '{$updateCrewInviteRow['crew_id']}' WHERE user_id = '$requester_userid'");

			//add +1 to crew members in crew table
			$updateCrewInviteQuery3  = mysqli_query($dblink, "UPDATE crews SET num_members = num_members+1 WHERE crew_id = '{$updateCrewInviteRow['crew_id']}'");

			//add notification to say that this user has joined your crew
			iu_send_notification($requester_userid, $Rows['user_id'], 'joined');

			//also add a notification to the user that he has been accepted into the crew
			iu_send_notification($Rows['user_id'], $requester_userid,'accepted');
		}else {
			//do nothing. invite gets deleted anyways
		}
		//delete pending = 0 to get rid of the pending invite
		$updateCrewInviteQuery  = mysqli_query($dblink, "DELETE FROM crewinvites WHERE user_id = '$requester_userid'");
	
		
	}

?>



