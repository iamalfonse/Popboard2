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
	<link href="/stylesheets/<?= $stylesheet; ?>.css?<?= $__site['filedate']; ?>" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" type="image/png" href="/images/favicon.png?<?= $__site['filedate']; ?>">
	<title>Register | <?= $__site['name']; ?></title>
	<meta name="keywords" content="<?= $__site['keywords']; ?>">
	<meta name="description" content="<?= $__site['description']; ?>">
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
        	<input class="userinput username" type="text" name="username" placeholder="Username" />
            <input class="userinput password" type="password" name="passwd" placeholder="Password" />
            <input type="submit" name="login" value="Sign In" class="submitbtn"/>
        </form>
        </div>
    </div><!--#bottom-->
<?php include("scripts.php"); ?>
<script type="text/javascript" src="js/signin.js"></script>
</body>
</html>