<?
include("config.php");

if (isset( $_COOKIE['login_cookie'] )) { 
	$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
	$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);

	iu_check_user_setup(); //make sure user has finished setup
	
    $updateProfileQuery  = mysqli_query($dblink, "SELECT * FROM users WHERE username='$username' AND session_hash = '$session_hash'");
	$updateProfileRow = mysqli_fetch_assoc($updateProfileQuery);

	include 'dbparams.php';

	//--------------------------------------
	$have_error    = false;
	$have_gravatar = false;
	$login_success = false;
	$errmsg        = "";

	//if user hits submit on the gravatar form---------------------------------
	if (isset($_POST['submitUserInfo'])) {
		
		// /* Now we want to use empty() instead of just isset() */
		// if (!empty($_POST['bgcolor'])) {
		//     $have_gravatar = true;
		// }
		
		$bioValue = mysqli_real_escape_string($dblink, $_POST['bio']);
		$location = mysqli_real_escape_string($dblink, $_POST['location']);
		$car = mysqli_real_escape_string($dblink, $_POST['car']);

		if($session_hash == $updateProfileRow['session_hash'] ){
			$session_good = true;
		}	
		
		if ($session_good) {
			
			/* Connect to the database */
			if (!$dblink) {
				$errmsg = "*** Failure!  Unable to connect to database!" . mysqli_connect_errno(); 
				$have_error = true;
				echo $errmsg;
			} else {
				/* Connection is okay... go ahead and try to add location to db. */
				//get email value to cross check who the user is
				$email = $updateProfileRow['email'];

				if($location != ''){//if user set a location
					$updateProfileQuery2  = mysqli_query($dblink, "UPDATE users SET bio='$bioValue', location='$location', car='$car' WHERE email='".$email."';");
				}
			}
		} else {
			
			$have_error = true;
			$errmsg = "Please choose an option for your user image!";
		}//end gravatar update
	}



	

	header('Location: /profile/'.$username);
}	


?>