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
// if(isset($Rows['crew_id'])){
// 	header('Location: /crews/?errmsg=2');
// 	exit;
// }


if (isset($_POST['submitCrewSetup'])) { //if user submits form
	
	// get your crew info
	$selectCrewQuery = mysqli_query($dblink, "SELECT * FROM crews WHERE crew_id = '{$Rows['crew_id']}';");
	$selectCrewRow = mysqli_fetch_assoc($selectCrewQuery);


	$crewdesc = mysqli_real_escape_string($dblink, $_POST['crewdesc']);
	$crewlocation = mysqli_real_escape_string($dblink, $_POST['crewlocation']);
	$private = mysqli_real_escape_string($dblink, $_POST['private']);


	// make sure user fills in all fields
	if( empty($crewdesc) || empty($crewlocation)){
		header("Location: /createcrew/?errmsg=1&crewdesc=$crewdesc&crewlocation=$crewlocation");
		exit;
	}

	// update the crew_id for the user
	$updateUserQuery = mysqli_query($dblink, "UPDATE crews SET about = '$crewdesc', location = '$crewlocation', private = '$private' WHERE crew_id = '{$Rows['crew_id']}';");

	// send user to section where they can upload a background image for their crew
	header('Location: /crew/'.$selectCrewRow['crew_url']);
	exit;
}

?>