<?php
	include("config.php");
	
	//get username for reference
	$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
	$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);

	if(isset( $_COOKIE['login_cookie'])){
	    $r  = mysqli_query($dblink, "SELECT user_id FROM users WHERE username='$username' AND session_hash = '$session_hash'");
		$Rows = mysqli_fetch_assoc($r);
		
		$user_id = $Rows['user_id'];
		$QueryResult = @mysqli_query($dblink, "UPDATE notifications SET notifications.read = 1 WHERE user_id = '$user_id';");
	}

?>



