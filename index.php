<?php
/**
 * index.php
 * Display a login form. Also process submission of login credentials.
 **/

/* Declare the parameters for accessing the database again. */
//DATABASE PARAMS
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
	
	<title>Import Underground | The Auto Enthusiast Community</title>
	<meta name="keywords" content="Import Underground, Imports, Racing, Tuner, Cars, Car Meets, Underground, Street, Pictures, Events, Meets, Groups, Car Groups, JDM">
	<meta name="description" content="Import Underground is a social network for all auto enthusiasts. Create or join a group and share your thoughts with people like you.">
	<link href="/stylesheets/<?= $stylesheet; ?>.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" type="image/png" href="/images/favicon.png">
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
		<h2>Welcome to Import Underground</h2>
		<p>An online community for all Import Car Enthusiasts</p>
	</div>
    <div class="signupfront">
    	<form id="signinform" method="post" action="/login">
        	<p class="signupfront-username">Username <input class="userinput" type="text" name="username" placeholder="Username or Email" onFocus="clearText(this)" onBlur="clearText(this)" /></p>
            <p class="signupfront-password">Password <input class="userinput" type="password" name="passwd" placeholder="Password"/></p>
            <p class="forgotpassword"><a href="/forgotpassword">Forgot your password?</a></p>
            <input class="submitbtn" type="submit" name="login" value="Sign In" />
        </form>
        <hr />
		<h3><span>New to Import Underground?</span> Sign up for free!</h3>
		<p class="signup"><a class="btn" href="/register">Sign Up Now</a></p>
	</div>
	<div id="bgslider" >
		<div class="active image slide1" /><p class="photocred">Photo by: <a href="http://instagram.com/deejsfc">David Frias</a></p></div>
		<div class="image slide2" /><p class="photocred">Photo by: <a href="http://instagram.com/deejsfc">David Frias</a></p></div>
		<div class="image slide3" /><p class="photocred">Photo by: <a href="http://instagram.com/deejsfc">David Frias</a></p></div>
		<div class="image slide4" /><p class="photocred">Photo by: <a href="http://instagram.com/deejsfc">David Frias</a></p></div>
		<div class="image slide5" /><p class="photocred">Photo by: <a href="http://instagram.com/deejsfc">David Frias</a></p></div>
		<div class="image slide6" /><p class="photocred">Photo by: <a href="http://instagram.com/deejsfc">David Frias</a></p></div>
		<div class="image slide7" /><p class="photocred">Photo by: <a href="http://instagram.com/deejsfc">David Frias</a></p></div>
	</div>
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
			<p>&copy; 2013-<?= date("Y") ?> ImportUnderground.com | All Rights Reserved | Created by Undr</p>
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