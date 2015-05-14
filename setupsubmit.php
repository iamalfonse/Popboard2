<?php

include("config.php");

if (isset( $_COOKIE['login_cookie'] )) { 
	$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
	$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);

	// check user setup
    $query0  = mysqli_query($dblink, "SELECT setup FROM users WHERE username='$username' AND session_hash = '$session_hash'");
	$Row0 = mysqli_fetch_assoc($query0);

	if($Row0['setup'] == '1'){ //check if user finished setup already
		header("Location: /");
		exit;
	}

	if (isset($_POST['submitSetup'])) { // if user submits setup

		//make sure user selected birthday
		if (!empty($_POST['birthdate'])) {
			$have_birthdate = true;
			$birthdate = mysqli_real_escape_string($dblink, $_POST['birthdate']);
		}
		// $profileimg = mysqli_real_escape_string($dblink, $_POST['profileimg']);
		$bio = mysqli_real_escape_string($dblink, $_POST['bio']);
		$location = mysqli_real_escape_string($dblink, $_POST['location']);
		$car = mysqli_real_escape_string($dblink, $_POST['car']);

		if($have_birthdate){

			if (!$dblink) {
	            $errmsg = "*** Failure!  Unable to connect to database!" . mysqli_connect_errno(); 
	            $have_error = true;

	        } else {
		        /* Connection is okay... go ahead and try to add setup to db. */

	        	// get user hash
	            $query  = mysqli_query($dblink, "SELECT session_hash FROM users WHERE username='$username'");
				$Row = mysqli_fetch_assoc($query);

				//make sure it's the correct user posting it by checking their session_hash (security check)
				if($session_hash == $Row['session_hash']){

					//add user setup to DB
					$r2 = mysqli_query($dblink, "UPDATE users SET bio='$bio', birthdate='$birthdate', location='$location', car='$car', setup='1'  WHERE username='$username'");
					header("Location: /posts/general&start");
					exit;
				}
			}
		}else {
			$have_error = true;
			$error = "1";
			header("Location: /setup?errmsg=$error");
			exit;
		}
	}

	
}
header("Location: /");
exit;

?>