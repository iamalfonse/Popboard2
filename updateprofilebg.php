<?php
include("config.php");

if (isset( $_COOKIE['login_cookie'] )) { //if user is logged in
	$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
	$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);

	iu_check_user_setup(); //make sure user has finished setup of avatar

	/* GET LOGGED IN USER INFORMATION*/
	$r  = mysqli_query($dblink, "SELECT * FROM users WHERE username='$username' AND session_hash = '$session_hash'");
	$Rows = mysqli_fetch_assoc($r);
}else {
	header('Location: /');
	exit;
}

if(isset($_GET['profilebg']) && isset($_GET['userprofile'])){
	$profilebg = mysqli_real_escape_string($dblink, strtolower($_GET['profilebg']));
	$userprofile = mysqli_real_escape_string($dblink, strtolower($_GET['userprofile']));
}

//get user lvl
$userlvl = $Rows['lvl'];

// make sure this user has permission to change the background
if($username == $userprofile){

	// make sure user lvl is high enough to use the background
	if(($userlvl < 10) && (strpos($profilebg, 'radial-') !== false)){
		echo "0";
		return;
	}else if(($userlvl < 15) && (strpos($profilebg, 'bgimg') !== false)){
		echo "0";
		return;
	}else{
		// User meets requirements, go ahead and change bg
		// update background color/img
		$updateProfileBGQuery  = mysqli_query($dblink, "UPDATE users SET profilebg='$profilebg' WHERE username='$username' AND session_hash = '$session_hash'");
		echo "1";
	}

}else {
	echo "2";
}






	




?>