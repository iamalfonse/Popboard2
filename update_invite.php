<?php
	include("config.php");
	
	//get username for reference
	$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
	$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);

	if(isset( $_COOKIE['login_cookie'])){
	    $r  = mysqli_query($dblink, "SELECT user_id FROM users WHERE username='$username' AND session_hash = '$session_hash'");
		$Rows = mysqli_fetch_assoc($r);
		
		//grab the user who is trying to get accepted
		$requester_userid = mysqli_real_escape_string($dblink, $_GET['user_id']);
		$decision = mysqli_real_escape_string($dblink, $_GET['decision']);
		echo $requester_userid." ".$decision;

		
		//select * from group invites
		$updateGroupInviteQuery  = mysqli_query($dblink, "SELECT * FROM groupinvites WHERE user_id = '$requester_userid'");
		$updateGroupInviteRow = mysqli_fetch_assoc($updateGroupInviteQuery);

		if($decision == 'accepted'){

			$invite_date = date("Y-m-d H:i:s"); //get current datetime value. i.e. '2013-10-22 14:45:00'

			//then add user to group (add a group_id to user)
			// $updateGroupInviteQuery2  = mysqli_query($dblink, "UPDATE users SET group_id = '{$updateGroupInviteRow['group_id']}' WHERE user_id = '$requester_userid'");
			$updateGroupInviteQuery2  = mysqli_query($dblink, "INSERT INTO user_groups(user_id, group_id, joindate) VALUES('$requester_userid','{$updateGroupInviteRow['group_id']}','$invite_date')");

			//add +1 to group members in group table
			$updateGroupInviteQuery3  = mysqli_query($dblink, "UPDATE groups SET num_members = num_members+1 WHERE group_id = '{$updateGroupInviteRow['group_id']}'");

			//add notification to say that this user has joined your group
			iu_send_notification($requester_userid, $Rows['user_id'], 'joined');

			//also add a notification to the user that he has been accepted into the group
			iu_send_notification($Rows['user_id'], $requester_userid,'accepted');
		}else {
			//do nothing. invite gets deleted anyways
		}
		//delete pending = 0 to get rid of the pending invite
		$updateGroupInviteQuery  = mysqli_query($dblink, "DELETE FROM groupinvites WHERE user_id = '$requester_userid'");
	
		
	}

?>



