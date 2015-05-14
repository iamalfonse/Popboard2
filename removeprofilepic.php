<?php
	
	include 'config.php';

	if (isset( $_COOKIE['login_cookie'] )) { 
		$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
		$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);

		// check user setup
		$removeProfilePicQuery  = mysqli_query($dblink, "SELECT setup, username, profileimg, session_hash FROM users WHERE username='$username' AND session_hash = '$session_hash'");
		$removeProfilePicRow = mysqli_fetch_assoc($removeProfilePicQuery);

	}else {
		header("Location: /");
	}


	$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
	//if user has uploaded a profile image before delete it
	if($removeProfilePicRow['profileimg'] != '' || $removeProfilePicRow['profileimg'] != NULL ){
		$oldImgLocation = $_SERVER['DOCUMENT_ROOT'] . $directory_self .$removeProfilePicRow['profileimg'];

		//delete old file
		unlink($oldImgLocation);

		// update database for profileimg to be null
		$updateProfileImgQuery = mysqli_query($dblink, "UPDATE users SET profileimg=NULL WHERE username = '$username'");
	}

	
	echo "/images/me.jpg";

?>