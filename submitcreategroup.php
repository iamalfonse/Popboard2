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


if (isset($_POST['submitCreateGroup'])) { //if user submits form

	$groupname = mysqli_real_escape_string($dblink, $_POST['groupname']);
	$validGroupname = preg_match("/^([a-zA-Z0-9_\s-]+)$/", $groupname);
	$groupdesc = mysqli_real_escape_string($dblink, $_POST['groupdesc']);
	$grouplocation = mysqli_real_escape_string($dblink, $_POST['grouplocation']);
	$private = mysqli_real_escape_string($dblink, $_POST['private']);
	$group_url = iu_cleaner_url($groupname);
	$groupbannerimg = '/images/groupbanner'.mt_rand(0,5).'.jpg'; // random banner img

	// make sure user fills in all fields
	if(empty($groupname) || empty($groupdesc) || empty($grouplocation)){
		header("Location: /creategroup/?errmsg=1&groupname=$groupname&groupdesc=$groupdesc&grouplocation=$grouplocation");
		exit;
	}
	// make sure groupname only has valid characters
	if(!$validGroupname){
		header("Location: /creategroup/?errmsg=2&groupname=$groupname&groupdesc=$groupdesc&grouplocation=$grouplocation");
		exit;
	}

	// //create a group
	$datecreated = date("Y-m-d H:i:s");
	$addGroup = mysqli_query($dblink, "INSERT INTO groups(groupname, group_url, founder, about,num_members,location,datecreated,banner_img,private) VALUES('$groupname','$group_url','{$Rows['username']}','$groupdesc','1','$grouplocation','$datecreated','$groupbannerimg','$private');");

	//get group_id of the recently created groupname above
	$selectGroupQuery = mysqli_query($dblink, "SELECT group_id FROM groups WHERE groupname = '$groupname';");
	$selectGroupRow = mysqli_fetch_assoc($selectGroupQuery);

	// insert record into user_groups for this user
	$creategroupdate = date("Y-m-d H:i:s"); //get current datetime value. i.e. '2013-10-22 14:45:00'
	$addUserGroupQuery  = mysqli_query($dblink, "INSERT INTO user_groups(user_id, group_id, joindate) VALUES('{$Rows['user_id']}','{$selectGroupRow['group_id']}','$creategroupdate')");

	// send user to section where they can upload a background image for their group
	header("Location: /groupsetup?groupname=$group_url&from=creategroup");
	exit;
}

?>