<?
include("config.php");

if (isset( $_COOKIE['login_cookie'] )) { //if user is logged in
	$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
	$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);

	iu_check_user_setup(); //make sure user has finished setup of avatar

	/* GET LOGGED IN USER INFORMATION*/
	$r  = mysqli_query($dblink, "SELECT * FROM users WHERE username='$username' AND session_hash = '$session_hash'");
	$Rows = mysqli_fetch_assoc($r);
}else {
	header('Location: /?errmsg=3');
	exit;
}

//check if user is already in a group (just in case they're trying to hack to make another group)
// if(isset($Rows['group_id'])){
// 	header('Location: /groups/?errmsg=2');
// 	exit;
// }


if (isset($_POST['submitGroupSetup'])) { //if user submits form
	
	// get your group info
	$selectGroupQuery = mysqli_query($dblink, "SELECT * FROM groups WHERE group_id = '{$Rows['group_id']}';");
	$selectGroupRow = mysqli_fetch_assoc($selectGroupQuery);


	$groupdesc = mysqli_real_escape_string($dblink, $_POST['groupdesc']);
	$grouplocation = mysqli_real_escape_string($dblink, $_POST['grouplocation']);
	$private = mysqli_real_escape_string($dblink, $_POST['private']);


	// make sure user fills in all fields
	if( empty($groupdesc) || empty($grouplocation)){
		header("Location: /creategroup/?errmsg=1&groupdesc=$groupdesc&grouplocation=$grouplocation");
		exit;
	}

	// update the group_id for the user
	$updateUserQuery = mysqli_query($dblink, "UPDATE groups SET about = '$groupdesc', location = '$grouplocation', private = '$private' WHERE group_id = '{$Rows['group_id']}';");

	// send user to section where they can upload a background image for their group
	header('Location: /group/'.$selectGroupRow['group_url']);
	exit;
}

?>