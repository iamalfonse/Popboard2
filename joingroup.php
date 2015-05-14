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


$groupid = mysqli_real_escape_string($dblink, $_POST['groupid']);


//check if user has already tried submitting for an invite to a group
$groupinviteCheckQuery = mysqli_query($dblink, "SELECT * FROM groupinvites WHERE user_id='{$Rows['user_id']}'");
$groupinviteNumRows = mysqli_num_rows($groupinviteCheckQuery);

if($groupinviteNumRows == 0){// there's no record yet, add it to groupinvites table
	$requestdate = date("Y-m-d H:i:s"); //get current datetime value. i.e. '2013-10-22 14:45:00'
	$groupinviteQuery =  mysqli_query($dblink, "INSERT INTO groupinvites (user_id,group_id,daterequest) VALUES ('{$Rows['user_id']}','$groupid','$requestdate')");
	echo "Invite Sent";
}else {
	echo "Invite is Pending";
}









?>