<?
include("config.php");
if (isset( $_COOKIE['login_cookie'] )) { //if user is logged in
	$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
	$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);

	iu_check_user_setup(); //make sure user has finished setup of avatar

	/* GET LOGGED IN USER INFORMATION*/
	$r  = mysqli_query($dblink, "SELECT * FROM users WHERE username='$username' AND session_hash = '$session_hash'");
	$Rows = mysqli_fetch_assoc($r);
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta content="width=device-width, user-scalable=yes" name="viewport" />
	<title>Events | <?= $__site['name']; ?></title>
	<meta name="keywords" content="<?= $__site['keywords']; ?>">
	<meta name="description" content="<?= $__site['description']; ?>">
	<link href="/stylesheets/<?= $stylesheet; ?>.css?<?= $__site['filedate']; ?>" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" type="image/png" href="/images/favicon.png?<?= $__site['filedate']; ?>">
</head>
<body class="events">
	<? include("top.php"); ?>
    <div id="content">

		<? include("left.php"); ?>

		<div id="right">
			
			<div class="headertitle eventname">
				<h2>Events Coming Soon</h2>
			</div>
		</div>

	</div>
<? include("scripts.php"); ?>
</body>
</html>