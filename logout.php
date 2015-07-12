<?php
	
	$errormsg = $_GET['errmsg'] == '' ? '' : $_GET['errmsg'];
	// die($_GET['errmsg']);
	$cookie_duration = 3600 * 24 * 366; // 1 year 
	$login_ref = $form_username;
	$retval = setcookie("login_cookie", $login_ref, time() - $cookie_duration);
	$retval2 = setcookie("hsh", $randomHash, time() - $cookie_duration);

	header("Location: /?errmsg=$errormsg"); 
	exit;


?>