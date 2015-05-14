<? 
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
	
	<title>Import Underground | The Auto Enthusiast Community</title>
	<meta name="keywords" content="Import Underground, Imports, Racing, Tuner, Cars, Car Meets, Underground, Street, Pictures, Events, Meets, Crews, Car Crews">
	<meta name="description" content="Import Undergroud">
	<link href="/stylesheets/car.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" type="image/png" href="/images/favicon.png">
</head>
<body class="error404">

<?php include("top.php"); ?>

<div id="content" class="errorWrap">
	<div id="left">
	<?
	if(isset($_COOKIE['login_cookie'])){

	$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));

	/* Open connection to the database */
	$dblink = @mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	$leftQuery  = mysqli_query($dblink, "SELECT user_id, username, displayname, email FROM users WHERE username='$username'");
	$leftRows = mysqli_fetch_assoc($leftQuery);

?>
	<a href="/profile"><?php iu_get_avatar_pic($leftRows['email']); ?>
		<p class="leftusername"><strong><?php echo $leftRows['displayname']; ?></strong></p>
	</a>
	
	<p class="leftbuttons profile"><a href="/profile/<?= $leftRows['username']?>"><span></span>My Profile</a>
	<p class="leftbuttons createpost"><a href="/createpost"><span></span>Create Post</a></p>
	<p class="leftbuttons myposts"><a href="/myposts"><span></span>My Posts</a></p>
	<p class="leftbuttons logout"><a id="logoutbtn" href="/logout"><span></span>Logout</a></p>

<? 
} else {

	

	?>
	<form id="signinform" method="post" action="/login">
		<h3>Sign In</h3>
		<p><input class="userinput" type="text" name="username" placeholder="Username" onFocus="clearText(this)" onBlur="clearText(this)" /></p>
		<p><input class="userinput" type="password" name="passwd" placeholder="Password"/></p>
		<p><input class="submitbtn" type="submit" name="login" value="Sign In" /></p>
	</form>
<? } ?>
	</div>
	<div id="right" class="errorContent">
		<h2>Oh snaps! Error 404 bro! </h2>
		<h2>I think something broke bcuz 'Page not found'...</h2>
		<div class="content">
			<img src="/images/errorbg.jpg" alt="Import Underground Error" /> 
			<p>Why not try checking out the <a href="/categories">Categories Page</a>, <a href="/crews">Crews Page</a>, or check out some of the <a href="/topposts">Top Posts</a>.</p>
		</div>
		
	</div>
</div>
<footer class="footer">
	<div class="footerWrap">
		<div class="copyright">
			<p>&copy; 2013-<?= date("Y") ?> ImportUnderground.com | All Rights Reserved | Created by Glazed</p>
		</div>
		<div class="links">
			<p><a href="/terms">Terms and Conditions</a> | <a href="/privacypolicy">Privacy Policy</a></p>
		</div>
	</div>
</footer>
<?php include("scripts.php"); ?>
</body>
</html>