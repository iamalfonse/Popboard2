<?
include("config.php");

if (isset( $_COOKIE['login_cookie'] )) {
	$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
	$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);

	iu_check_user_setup(); //make sure user has finished setup

	/* GET LOGGED IN USER INFORMATION*/
	$query  = mysqli_query($dblink, "SELECT * FROM users WHERE username='$username' AND session_hash = '$session_hash'");
	$Rows = mysqli_fetch_assoc($query);
}else {
	header('Location: /?errmsg=5');
	exit;
}


$crewid = mysqli_real_escape_string($dblink, $_POST['crewid']);


//check if user has already tried submitting for an invite to a crew
$crewinviteCheckQuery = mysqli_query($dblink, "SELECT * FROM crewinvites WHERE user_id='{$Rows['user_id']}'");
$crewinviteNumRows = mysqli_num_rows($crewinviteCheckQuery);

if($crewinviteNumRows == 0){// there's no record yet, add it to crewinvites table
	$requestdate = date("Y-m-d H:i:s"); //get current datetime value. i.e. '2013-10-22 14:45:00'
	$crewinviteQuery =  mysqli_query($dblink, "INSERT INTO crewinvites (user_id,crew_id,daterequest) VALUES ('{$Rows['user_id']}','$crewid','$requestdate')");
	echo "Invite Sent";
}else {
	echo "Invite is Pending";
}









?>