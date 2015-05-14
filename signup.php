<?php
/**
 * signup.php
 *
 * Simple example of a user registration page.  Note that this version is leaving out
 * some important checks for SQL Insertion!
 *
 *CREATE TABLE users (
 * user_id  int unsigned NOT NULL AUTO_INCREMENT,
 * username varchar(30) NOT NULL,
 * displayname varchar(30) NOT NULL,
 * email    varchar(50) NOT NULL,
 * passwd   char(40) NOT NULL,
 * PRIMARY KEY (user_id),
 * UNIQUE KEY username (username)
 *)
 *
 *
 **/

/* Database params */
include("config.php");


if (isset( $_COOKIE['login_cookie'] )) { //if user is logged in
    header("Location: /categories"); 
    exit;
}

/* This will tell us to display an error message */
$have_error = false;
$errmsg     = "";

if(isset($_GET['errmsg'])){
	$errmsg = $_GET['errmsg'];
	$have_error = true;
}

if (isset($_POST['submit'])) {

	/* Process the new user registration */
	$new_username  = strtolower(mysqli_real_escape_string($dblink, $_POST['new_username']));
	$validUsername = preg_match("/^([a-zA-Z0-9_]+)*$/", $_POST['new_username']);
	$display_username = mysqli_real_escape_string($dblink, $_POST['new_username']);
	$new_pass      = mysqli_real_escape_string($dblink, $_POST['new_pass']);
	$new_pass_conf = mysqli_real_escape_string($dblink, $_POST['new_pass_conf']);
	$new_email     = mysqli_real_escape_string($dblink, $_POST['new_email']);
	$validEmail    = preg_match("/^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/", $new_email);
	$joindate      = date("Y-m-d H:i:s");
	if($new_username == "" || $new_pass == "" || $new_pass_conf == "" || $new_email == "" ){
		$have_error = true;
	    $errmsg = "Please fill in the required fields.";
		
	}else if(!$validUsername){
	    //username not valid
	    $have_error = true;
	    $errmsg = "The username you entered has invalid characters. Please enter only letters, numbers, or underscores.";
	}else if(!$validEmail){
	    //email not valid
	    $have_error = true;
        $errmsg = "The email you have entered is invalid. Please check it again.";
    }else if(!isset($_POST['terms'])){
        $have_error = true;
        $errmsg = "You must agree to the Terms and Conditions.";
    }else {
        //valid username, password, password conf, and email

        if ($new_pass != $new_pass_conf) {
            $have_error = true;
            $errmsg = "Your password and password confirmation did not match!";
        } else {
            /* Open connection to the database */
            $dblink = @mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
            if (!$dblink) {
                $errmsg = "<p class='error'>*** Failure!  Unable to connect to database!" . mysqli_connect_errno()."</p>"; 
                $have_error = true;
            } else {
                /* Connection is okay... go ahead and try to add user to db. */
           
                /* Does this username already exist? */
                $results  = mysqli_query($dblink, "SELECT * FROM users WHERE username='$new_username'");
                $num_rows = mysqli_num_rows($results);

                if ($num_rows != 0) {
                    /* This username is already taken! */
                    $have_error = true;
                    $errmsg = "That username is already taken.  Please select another.";   
                } else { 


                    /* Is this email already taken? */
                    $results  = mysqli_query($dblink, "SELECT * FROM users WHERE email='$new_email'");
                    $num_rows = mysqli_num_rows($results);

                    if($num_rows != 0){
                        /* This email is already taken! */
                        $have_error = true;
                        $errmsg = "That email is already in use.  Please select another.";   
                    }else{
                        /* Insert new row... */
                        $r2 = mysqli_query($dblink, "INSERT INTO users(username, displayname, passwd, email, active, joindate) VALUES('$new_username', '$display_username', sha1('$new_pass'), '$new_email', '0', '$joindate')");
                         
                        //user was added to database
                        //get the new user id  
                        $userid = mysqli_insert_id($dblink);
                        //print_r($userid.'WTF Crap!');die; 
                        //create a random key used for email activiation
                        $key = $new_username . $new_email . date('mY');
                        $key = md5($key);

                        //add confirm row  
                        $confirm = mysqli_query($dblink, "INSERT INTO `confirm` VALUES(NULL,'$userid','$key','$new_email')"); 
 
                        //let's send the email
                        $to = $new_email;
                        $subject = "Activate your Import Underground account";
                        $message = "
                                    <html>
                                    <head>
                                    </head>
                                    <body style='margin:0;padding:0;background:#e3e3e3;'>
                                    <style type='text/css'>
                                    * {margin:0; padding:0; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box;}
                                    img {border: 0 none;}
                                    h1, h2, h3, h4, h5, h6 {margin:0;}
                                    p {font-size: 1em;margin: 0 auto 10px;}
                                    </style>
                                    <table width='100%' style='font-family: helvetica-neue, arial;border-collapse: collapse;margin:0 auto;padding:0;' border='0'>
                                        <thead>
                                            <tr style='background:#E9E9E9;'>
                                                <td style='padding:10px 10px;'>
                                                   <img src='http://www.importunderground.com/images/email/header_bg.jpg' alt='Import Underground' />
                                                </td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style='padding:20px 20px;'>
                                                    <h3>Please Confirm Your Email</h3>
                                                    <p>Before you can post on Import Underground, we need to confirm your email.</p>
                                                    <p>Please click on the link below to activate your account.</p>
                                                    <p><a href='http://importunderground.com/success?email=".$new_email."&key=".$key."' >Confirm Email</a></p>
                                                </td>
                                            <tr>
                                                <td style='padding:20px 20px;'>
                                                    <p>If the link above does not work, copy and paste this URL into your browser window.</p>
                                                    <p><a href='http://importunderground.com/success?email=".$new_email."&key=".$key."' >http://importunderground.com/success?email=".$new_email."&key=".$key."</a></p>
                                                </td>
                                            <tr>
                                                <td style='padding:20px 20px;'>
                                                    <p style='background-color:#F2F3F6;padding: 20px;'>Sent by ImportUnderground.com. Did you recieve this in error? If so, please ignore it.</p>
                                                </td>
                                            </tr>
                                            <tr style='background:#262626;'>
                                                <td style='padding:20px 20px; '>
                                                   <p style='color: #cccccc;font-size: 12px;text-align:center;'> &copy;2014 Import Underground | <a href='http://www.importunderground.com/privacypolicy' style='color:#ccc'>Privacy Policy</a> | <a href='http://www.importunderground.com/terms' style='color:#ccc'>Terms and Conditions</a> | <a href='http://www.importunderground.com/unsubscribe?email=$to' style='color:#ccc'>Unsubscribe</a></p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    </body>
                                    </html>
                                    ";
                        
                        // To send HTML mail, the Content-type header must be set
                        $headers ="MIME-Version: 1.0" . "\r\n";
                        $headers .= "X-Mailer: PHP/" .phpversion() ."\n";
                        $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
                        $headers .= "From: Import Underground <support@importunderground.com>";
                        mail($to, $subject, $message, $headers);

                        // send another mail to support@importunderground.com to tell who registered
                        $headers2 ="MIME-Version: 1.0" . "\r\n";
                        $headers2 .= "X-Mailer: PHP/" .phpversion() ."\n";
                        $headers2 .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
                        $headers2 .= "From: User Registered <noreply@importunderground.com>";
                        mail('support@importunderground.com',$display_username.' Registered for Import Underground','New User Registered',$headers2);

                        /* Successful registration!  Now redirect the user to the success page. */
                        header("Location: /success?activate=1"); 
                        exit;
                        
                    }
                    
                }

                /* Close our connection to the database */
                mysqli_close($dblink);
            } 

    	}
    }
    header("Location: /register?errmsg=".$errmsg); 
	exit;

}/* end if (isset(...) */
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta content="width=device-width, user-scalable=yes" name="viewport" />
    <title>Import Underground | Sign Up</title>
    <meta name="keywords" content="Import Underground, Imports, Racing, Tuner, Cars, Car Meets, Underground, Street, Pictures, Events, Meets, Crews, Car Crews, JDM">
    <meta name="description" content="Import Underground is a social network for all auto enthusiasts. Create or join a crew and share your thoughts with people like you.">
    <link href="/stylesheets/car.css" rel="stylesheet" type="text/css" />
    <link rel="icon" type="image/png" href="/images/favicon.png">
</head>
<body>

    <?php include("top.php"); ?>

    <div id="content">
        
        <div id="signup">
            <div class="signup-banner"><h2>Sign Up</h2></div>
            <?php
                if ($have_error) {
                    echo "<p class='error'>$errmsg</p>\n";
                }
            ?>
            <form action="signup.php" method="post" id="signupform">
                <label>Username</label>
                <p><input class="userinput new_username" type="text" name="new_username" size="20" />
                    <i id="nametaken"></i>
                </p>
                <p>Password<br /><input class="userinput new_password" type="password" name="new_pass" size="20" /></p>
                <p>Password Confirmation<br /><input class="userinput password_conf" type="password" name="new_pass_conf" size="20" /></p>
                <p>Email<br /><input class="userinput email" type="text" name="new_email" size="50" /></p>
                <p class='note'>Note: A verification email will be sent to this email address.</p>
                <p class='note terms'><input type="checkbox" name="terms" value="1"> I agree to the <a href="/terms">Terms and Conditions</a> and have read the <a href="/privacypolicy">Privacy Policy</a>.</p>
                <div class="check">
                    <div class="humancheck"><span></span></div><p>Hover over this to make sure you're human</p>
                </div>
                <input class="submitbtn" type="submit" name="submit" value="Register" />
            </form>
        
        </div><!--#signup-->
    </div><!--#bottom-->

<?php include("scripts.php"); ?>
<script src="/js/signup.js" type="text/javascript"></script>
<!-- <script src="/js/jquery.validate.min.js" type="text/javascript"></script> -->
</body>
</html>