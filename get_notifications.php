<?php
	include("config.php");
	
	$start = mysqli_real_escape_string($dblink, $_GET['start']);
	$limit = mysqli_real_escape_string($dblink, $_GET['limit']);

	//get username for reference
	$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));

	if(isset( $_COOKIE['login_cookie'])){
	    $r  = mysqli_query($dblink, "SELECT user_id FROM users WHERE username='$username'");
		$Rows = mysqli_fetch_assoc($r);
	}

	$user_id = $Rows['user_id'];
	$QueryResult = @mysqli_query($dblink, "SELECT notification, notification_date, notification_type, from_user_id FROM notifications WHERE user_id = $user_id ORDER BY id DESC LIMIT $start, $limit;");
	$Row = mysqli_fetch_assoc($QueryResult);
	$num_rows = mysqli_num_rows($QueryResult);
	
	if($num_rows == 0){
		$start = $start-5;
		return $start;
	}else {
		
		iu_get_notifications($Row, $QueryResult);
		
	}
?>



