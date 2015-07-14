<?php

include("config.php");

if (isset( $_COOKIE['login_cookie'] )) {
	iu_check_user_setup(); //make sure user has finished setup

	header('Location: /home');
	exit;
}


$errmsg = '';
if(isset($_GET['errmsg'])){
	//grab any error messages if any
	$errmsg = $_GET['errmsg'];
}
if($errmsg=='1'){
	$errmsg = 'Please provide both your username and password to login.';
}else if($errmsg=='2'){
	$errmsg = 'Incorrect username and/or password. Please try again.';
}else if($errmsg=='3'){
	$errmsg = 'Unable to authenticate. Please try logging in again.';
}else if($errmsg=='4'){
	$errmsg = 'You have been temporarily banned. Sorry bro...';
}else if($errmsg=='5'){
	$errmsg = 'You must be logged in to be able to do that.';
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
<body class="home">

<?php include("top.php"); ?>

<div id="content" class="homeWrap">
	<?php
	if ($errmsg && $errmsg != '') {
		echo "<p class='error'>$errmsg</p>\n";
	}
	?>
	<div class="homeHeading">
		<h2>Welcome to <?= $__site['name']; ?></h2>
		<p>The other front page of the internet</p>
	</div>
    <div class="signupfront">
    	<form id="signinform" method="post" action="/login">
        	<p class="signupfront-username">Username <input class="userinput" type="text" name="username" placeholder="Username or Email" onFocus="clearText(this)" onBlur="clearText(this)" /></p>
            <p class="signupfront-password">Password <input class="userinput" type="password" name="passwd" placeholder="Password"/></p>
            <p class="forgotpassword"><a href="/forgotpassword">Forgot your password?</a></p>
            <input class="submitbtn" type="submit" name="login" value="Sign In" />
        </form>
        <hr />
		<h3><span>New to <?= $__site['name']; ?>?</span> Sign up for free!</h3>
		<p class="signup"><a class="btn signupbtn" href="/register">Sign Up Now</a></p>
	</div>
	<ul id="bgslider" >
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
	</ul>
</div><!--#content-->
<!-- <div class="homeAbout">
	<div class="homeAboutWrap">
		<ul>
			<li>

			</li>
		</ul>
	</div>
</div> -->
<footer class="footer">
	<div class="footerWrap">
		<div class="copyright">
			<p>&copy; 2013-<?= date("Y") ?> <?= $__site['name'] ?> | All Rights Reserved | Created by Undr</p>
		</div>
		<div class="links">
			<p><a href="/terms">Terms and Conditions</a> | <a href="/privacypolicy">Privacy Policy</a></p>
		</div>
	</div>
</footer>
<?php include("scripts.php"); ?>
<script type="text/javascript" src="js/index.js"></script>
</body>
</html>