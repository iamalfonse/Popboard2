<?php
/**
 * login.php
 * Display a login form. Also process submission of login credentials.
 **/

/* Declare the parameters for accessing the database again. */
//DATABASE PARAMS
include("config.php");
$checkUrl = isset($_GET['url']);
echo $checkUrl;
if($checkUrl){
	$url = $_GET['url'] != '' ? $_GET['url'] : '/';
}else {
	$url = '/';
	// header('Location: /');
	// exit;
}



if (isset( $_COOKIE['login_cookie'] )) {
	iu_check_user_setup(); //make sure user has finished setup

	header('Location: /posts/general');
	exit;
}


$have_error    = false;
$have_username = false;
$have_passwd   = false;
$errmsg        = "";


/* Check to see if the login form has been submitted.  We'll test 
 * to see if the 'submit' name (ie. the submit button element) is
 * present in the POST superglobal array.
 */
if (isset($_POST['login'])) {
	
	/* Now we want to use empty() instead of just isset() */
	if (!empty($_POST['username'])) { //grab username or email
	    $have_username = true;
	    $form_username = mysqli_real_escape_string($dblink, strtolower($_POST['username']));
	}
	
	if (!empty($_POST['passwd'])) {
		$have_passwd = true;
		$form_passwd = mysqli_real_escape_string($dblink, $_POST['passwd']);
	}
	
	if ($have_username && $have_passwd) {
		/* Connect to the database and try to find 
		 * a match for the username and password. 
		 */
		
		 
         if (!$dblink) {
             $errmsg = "*** Failure!  Unable to connect to database!" . mysqli_connect_errno(); 
             $have_error = true;

         } else {
            /* Connection is okay... go ahead and try to add user to db. */

            // Scramble the shit out of that password before doing mysql query
            $password = iu_scramble_password($form_passwd);

            /* Try to find a row in the user's table with both a matching username and
             * encrypted password. If we have a match then that is a successful authentication.
             */
            $results  = @mysqli_query($dblink, "SELECT user_id, username FROM users WHERE (username='$form_username' or email = '$form_username') AND passwd='$password' ");

            /* Getting the number of rows will tell us whether this was a success or not.
             * If number of rows = 0, then there were no matches, and therefore failed.
             * If we get back exactly one row, then the user gave us the right combination
             * of username and password.
             */
            $Rows = mysqli_fetch_assoc($results);
            $num_rows = mysqli_num_rows($results);


            /* Close our connection to the database -- we're done with that now.*/
           // mysqli_close($dblink);

            if ($num_rows == '1') {
	            /* Successful login! */



	            /* add random hash to make sure that 
				 * the user has rights to delete, edit,add posts,etc.
				 * if this hash matches in the database.
				 * Also store their ip address just in case. (for login auth and for authorities)
				 */
	            $user_ip = iu_get_user_ip();
				$randomHash = md5(rand(0,1000).$form_username);
	            $results2  = mysqli_query($dblink, "UPDATE users SET session_hash ='$randomHash', session_ip = '$user_ip' WHERE (username='$form_username' or email = '$form_username')");
	
				/* Set a COOKIE */
				$cookie_duration = 3600 * 24 * 365; // 1 year 
				$login_ref = $Rows['username'];
				$retval = setcookie("login_cookie", $login_ref, time() + $cookie_duration);
				$retval2 = setcookie("hsh", $randomHash, time() + $cookie_duration);
				
				
				header("Location: $url"); 
				exit;
	        } else {
		        $have_error = true;
		        $errmsg = "2";
		    }
		    
		 }
	} else {
		$have_error = true;
		$errmsg = "1";
	}

	if($have_error){
		header("Location: /?errmsg=$errmsg");
		return $errmsg;
	}
}

?>