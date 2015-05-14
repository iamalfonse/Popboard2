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

//check if user is already in a crew (just in case they're trying to hack to make another crew)
if(isset($Rows['crew_id'])){
	header('Location: /crews/?errmsg=2');
	exit;
}


if (isset($_POST['submitCreateCrew'])) { //if user submits form

	$crewname = mysqli_real_escape_string($dblink, $_POST['crewname']);
	$validCrewname = preg_match("/^([a-zA-Z0-9_\s-]+)$/", $_POST['crewname']);
	$crewdesc = mysqli_real_escape_string($dblink, $_POST['crewdesc']);
	$crewlocation = mysqli_real_escape_string($dblink, $_POST['crewlocation']);
	$private = mysqli_real_escape_string($dblink, $_POST['private']);
	$crew_url = iu_cleaner_url($crewname);
	$crewbannerimg = '/images/crewbanner'.mt_rand(0,5).'.jpg'; // random banner img

	// make sure user fills in all fields
	if(empty($crewname) || empty($crewdesc) || empty($crewlocation)){
		header("Location: /createcrew/?errmsg=1&crewname=$crewname&crewdesc=$crewdesc&crewlocation=$crewlocation");
		exit;
	}
	// make sure crewname only has valid characters
	if(!$validCrewname){
		header("Location: /createcrew/?errmsg=2&crewname=$crewname&crewdesc=$crewdesc&crewlocation=$crewlocation");
		exit;
	}

	// //create a crew
	$datecreated = date("Y-m-d H:i:s");
	$addCrew = mysqli_query($dblink, "INSERT INTO crews(crewname, crew_url, founder, about,num_members,location,datecreated,banner_img,private) VALUES('$crewname','$crew_url','{$Rows['username']}','$crewdesc','1','$crewlocation','$datecreated','$crewbannerimg','$private');");

	//get crew_id of the recently created crewname above
	$selectCrewQuery = mysqli_query($dblink, "SELECT crew_id FROM crews WHERE crewname = '$crewname';");
	$selectCrewRow = mysqli_fetch_assoc($selectCrewQuery);

	// update the crew_id for the user
	$updateUserQuery = mysqli_query($dblink, "UPDATE users SET crew_id='{$selectCrewRow['crew_id']}' WHERE username = '{$Rows['username']}';");

	// send user to section where they can upload a background image for their crew
	header('Location: /crewsetup?crewname=$crew_url&from=createcrew');
	exit;
}

?>