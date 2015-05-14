<?php
//DATABASE PARAMS
include("config.php");

/* Process the new group creation */

$check_groupname  = mysqli_real_escape_string($dblink, $_GET['check_groupname']);

if(preg_match('/^([a-zA-Z0-9\_ -]+)$/', $check_groupname) == 1){

}else {
  echo "<span class='mini'>Only letters, numbers, spaces and underscore are allowed</span>";
  return;
}

$groupurl = iu_cleaner_url($check_groupname);

/* Open connection to the database */
//$dblink = @mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$dblink) {
     $errmsg = "*** Failure!  Unable to connect to database!" . mysqli_connect_errno();
     $have_error = true;
 } else {
      //connection is good
      /* Does this groupname already exist? */
      $result  = mysqli_query($dblink, "SELECT groupname, group_url FROM groups WHERE groupname = '$check_groupname'");
      $Row = mysqli_fetch_assoc($result);

	  if ( $Row['groupname'] == $check_groupname || $Row['group_url'] == $groupurl ) {
          /* This groupname is already taken! */
		  echo "<span class='red'>Group name already taken. Please choose another.</span>";
    }
    if($Row['groupname'] != $check_groupname && $Row['group_url'] != $groupurl ) {
        echo "<span class='green'>*</span>";
    } else if ($check_groupname == ''){
		  echo "";
	  }
    // echo $groupurl;
}


?>