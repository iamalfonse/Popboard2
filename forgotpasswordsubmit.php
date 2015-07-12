<?php
/*

forgotpassword.php

 CREATE TABLE IF NOT EXISTS `forgotpassword` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

*/

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

if (isset($_POST['old_email'])) {

	/* Process the new user registration */
	
	$old_email     = mysqli_real_escape_string($dblink, $_POST['old_email']);
	$validEmail    = preg_match("/^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/", $old_email);
	$joindate      = date("Y-m-d H:i:s");
	if(!$validEmail){
	    //email not valid
	    $have_error = true;
        $errmsg = "The email you have entered is invalid. Please check it again.";
    } else {
        //valid username, password, password conf, and email
        // echo "valid email".$old_email;
        
        /* Open connection to the database */
        $dblink = @mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
        if (!$dblink) {
            $errmsg = "<p class='error'>*** Failure!  Unable to connect to database!" . mysqli_connect_errno()."</p>"; 
            $have_error = true;

        } else {
            /* Connection is okay... go ahead and try to add user to db. */
       
            /* Does this email already exist? */
            $results  = mysqli_query($dblink, "SELECT email FROM users WHERE email='$old_email'");
            $num_rows = mysqli_num_rows($results);

            if ($num_rows == 0) {
                /* No email found */
                $have_error = true;
                $errmsg = "That email is not in our system. Please make sure you entered it correctly.";
            } else {

                
                //print_r('WTF Crap!');die;
                //create a random key
                $key = $old_email . $joindate;
                $key = md5($key);

                //Chec to see if there's already another token inside of forgotpassword table.
                $checkforgotpassword = mysqli_query($dblink, "SELECT * FROM `forgotpassword` WHERE `email` = '$old_email' LIMIT 1");
                $checkNumRows = mysqli_num_rows($checkforgotpassword);

                if($checkNumRows == 1){ //forgot password already found in table from before, then update the key
                    //update key
                    $forgot = mysqli_query($dblink, "UPDATE `forgotpassword` SET `key` = '$key' WHERE `email` = '$old_email'"); 
                }else {
                    //add forgotpassword row
                    $forgot = mysqli_query($dblink, "INSERT INTO `forgotpassword` VALUES(NULL,'$key','$old_email')"); 
                }


                // //let's send the email
                $to = $old_email;
                $subject = "Reset your {$__site['name']} Password";
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
                                           <img src='{$__site['url']}/images/email/header_bg.jpg' alt='{$__site['name']}' />
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style='padding:20px 20px;'>
                                            <h3>Reset your login password</h3>
                                            <p>Please click on the link below to reset your account password.</p>
                                            <p><a href='{$__site['url']}/reset?email=".$old_email."&key=".$key."' >Reset Password</a></p>
                                        </td>
                                    <tr>
                                        <td style='padding:20px 20px;'>
                                            <p>If the link above does not work, copy and paste this URL into your browser window.</p>
                                            <p><a href='{$__site['url']}/reset?email=".$old_email."&key=".$key."' >{$__site['url']}/reset?email=".$old_email."&key=".$key."</a></p>
                                        </td>
                                    <tr>
                                        <td style='padding:20px 20px;'>
                                            <p style='background-color:#F2F3F6;padding: 20px;'>Sent by {$__site['name']}. Did you recieve this in error? If so, please ignore it.</p>
                                        </td>
                                    </tr>
                                    <tr style='background:#262626;'>
                                        <td style='padding:20px 20px; '>
                                           <p style='color: #cccccc;font-size: 12px;text-align:center;'> &copy;2014 {$__site['name']} | <a href='{$__site['url']}/privacypolicy' style='color:#ccc'>Privacy Policy</a> | <a href='{$__site['url']}/terms' style='color:#ccc'>Terms and Conditions</a> | <a href='{$__site['url']}/unsubscribe?email=$to' style='color:#ccc'>Unsubscribe</a></p>
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
                $headers .= "From: {$__site['name']} <{$__site['email']}>";
                mail($to, $subject, $message, $headers);

                /* Successful reset password!  Now tell user to check email. */
                // header("Location: /reset?checkemail=1");
                // exit;
                echo "<p><strong>Email sent</strong></p>\n<p>Please check your email to reset your password.</p>";
                return;
                
                
            }

            /* Close our connection to the database */
            mysqli_close($dblink);
            // return;
        } 

    	
    }
 //    header("Location: /forgotpassword?errmsg=".$errmsg); 
	// exit;
    echo "<p class='error'>".$errmsg."</p>";
    ?>
        <label>Enter your email</label>
        <p><input class="userinput email" type="text" name="old_email" size="50" /></p>
        <p class='note'>Note: We'll send you an email to reset your password.</p>
        <input class="submitbtn" type="submit" name="submit" value="Reset Password" />
    <?
}/* end if (isset(...) */

?>