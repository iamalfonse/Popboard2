<?php
/**
 * reg_success.php
 *
 * Display a login form. Also process submission of login credentials.
 *
 **/

/* Declare the parameters for accessing the database again. */
//DATABASE PARAMS
include("config.php");

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta content="width=device-width, user-scalable=yes" name="viewport" />
	<link href="/stylesheets/car.css" rel="stylesheet" type="text/css" />
	<link rel="icon" type="image/png" href="/images/favicon.png">
	<title>Import Underground | Register</title>
	<meta name="keywords" content="Import Underground, Imports, Racing, Tuner, Cars, Car Meets, Underground, Street, Pictures, Events, Meets, Groups, Car Groups">
	<meta name="description" content="Sign up now to create your own Import Underground account and share and discuss with other car enthusiasts">
</head>
<body class="signin">

	<?php include("top.php"); ?>
	
    <div id="content" class="signinsection">
		<div id="signin">
        <h2>Sign In</h2>
        <?php 
        	if(isset($_GET['errmsg'])){
				echo $errmsg;
			}
        	
        ?>
		<form id="signinform" method="post" action="/login">
        	<input class="userinput" type="text" name="username" placeholder="Username" />
            <input class="userinput" type="password" name="passwd" placeholder="Password" />
            <input type="submit" name="login" value="Sign In" class="submitbtn"/>
        </form>
        </div>
    </div><!--#bottom-->
<?php include("scripts.php"); ?>
</body>
</html>