include("config.php");

if (isset( $_COOKIE['login_cookie'] )) { //if user is logged in
	$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
	$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);

	// iu_check_user_setup(); //make sure user has finished setup of avatar

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
	
	<title><?= $__site['name']; ?> | The Other Front Page of the Internet</title>
	<meta name="keywords" content="<?= $__site['keywords']; ?>">
	<meta name="description" content="<?= $__site['description']; ?>">
	<link href="/stylesheets/<?= $stylesheet; ?>.css?<?= $__site['filedate']; ?>" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" type="image/png" href="/images/favicon.png?<?= $__site['filedate']; ?>">
</head>
<body class="error404">

<?php include("top.php"); ?>

<div id="content" class="errorWrap">
	<?php include("left.php"); ?>
	<div id="right" class="errorContent">
		<h2>Oh snaps! Error 404 bro! </h2>
		<h2>I think something broke bcuz 'Page not found'...</h2>
		<div class="content">
			<img src="/images/errorbg.jpg" alt="<?= $__site['name']; ?> Error" /> 
			<p>Why not try checking out the <a href="/categories">Categories Page</a>, <a href="/groups">Groups Page</a>, or check out some of the <a href="/topposts">Top Posts</a>.</p>
		</div>
		
	</div>
</div>
<footer class="footer">
	<div class="footerWrap">
		<div class="copyright">
			<p>&copy; 2013-<?= date("Y") ?> <?= $__site['name']; ?> | All Rights Reserved | Created by Glazed</p>
		</div>
		<div class="links">
			<p><a href="/terms">Terms and Conditions</a> | <a href="/privacypolicy">Privacy Policy</a></p>
		</div>
	</div>
</footer>
<?php include("scripts.php"); ?>
</body>
</html>