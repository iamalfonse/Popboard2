<?php
//DATABASE PARAMS
include("config.php");

/* Process the new crew creation */

$check_crewname  = mysqli_real_escape_string($dblink, $_GET['check_crewname']);

if(preg_match('/^([a-zA-Z0-9\_ -]+)$/', $check_crewname) == 1){

}else {
  echo "<span class='mini'>Only letters, numbers, spaces and underscore are allowed</span>";
  return;
}

$crewurl = iu_cleaner_url($check_crewname);

/* Open connection to the database */
//$dblink = @mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$dblink) {
     $errmsg = "*** Failure!  Unable to connect to database!" . mysqli_connect_errno();
     $have_error = true;
 } else {
      //connection is good
      /* Does this crewname already exist? */
      $result  = mysqli_query($dblink, "SELECT crewname, crew_url FROM crews WHERE crewname = '$check_crewname'");
      $Row = mysqli_fetch_assoc($result);

	  if ( $Row['crewname'] == $check_crewname || $Row['crew_url'] == $crewurl ) {
          /* This crewname is already taken! */
		  echo "<span class='red'>Crew name already taken. Please choose another.</span>";
    }
    if($Row['crewname'] != $check_crewname && $Row['crew_url'] != $crewurl ) {
        echo "<span class='green'>*</span>";
    } else if ($check_crewname == ''){
		  echo "";
	  }
    // echo $crewurl;
}


?>