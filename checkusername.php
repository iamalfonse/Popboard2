<?php
//DATABASE PARAMS
include("config.php");

/* Process the new user registration */
//$new_username  = $_POST['new_username'];

$check_username  = mysqli_real_escape_string($dblink, strtolower(trim($_GET['check_username'])));

if(preg_match('/^([a-zA-Z0-9\_]+)$/', $check_username) == 1){

}else {
  echo "<span class='mini'>Only letters, numbers and underscores are allowed</span>";
  return;
}

/* Open connection to the database */
//$dblink = @mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$dblink) {
     $errmsg = "*** Failure!  Unable to connect to database!" . mysqli_connect_errno();
     $have_error = true;
 } else {
      //connection is good
      /* Does this username already exist? */
      $query    = "SELECT username FROM users WHERE username = '$check_username'";
      $result  = mysqli_query($dblink, $query);
      //echo $result;

      //$num_rows = mysqli_num_rows($result);
      //echo $num_rows;

      $Row = mysqli_fetch_assoc($result);
      //echo $Row['username'];

	  if ( $Row['username'] == $check_username || $Row['displayname'] == $check_username ) {
          /* This username is already taken! */
		  echo "<span class='red'>That username is already taken.</span>";
      } else if($Row['username'] != $check_username) {
          echo "<span class='green'>*</span>";
      } else if ($check_username == ''){
		  echo "";
	  }
}


?>