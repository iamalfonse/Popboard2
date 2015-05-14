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
	<meta name="keywords" content="Import Underground, Imports, Racing, Tuner, Cars, Car Meets, Underground, Street, Pictures, Events, Meets, Crews, Car Crews">
	<meta name="description" content="Sign up now to create your own Import Underground account and share and discuss with other car enthusiasts">
</head>
<body class="registration">

	<?php include("top.php"); ?>
	
    <div id="content">
		<div id="signup">
		<? 
		if(!empty($_GET['email']) || !empty($_GET['key'])){
			echo "<h2>Registration Successful!</h2>";
		}else if(isset($_GET['activate'])) {
			echo "<h2>Activate Your Account</h2>";
		}
		?>
        
        <?php 
        	if(isset($_GET['errmsg'])){
				echo $errmsg;
			}
        ?>
        <?php 
        	/* This is when the user clicks on the email link
			 * that they recieved for confirmation. It checks
			 * the database to see if a key is assigned
			 */

			//quick/simple validation  
			if(empty($_GET['email']) || empty($_GET['key'])){  
			    
			    echo '<p class="normal">Please check your email to activate your account.</p>';  
			} else {

			    //cleanup the variables  
			    $email = mysqli_real_escape_string($dblink, $_GET['email']);
			    $key = mysqli_real_escape_string($dblink, $_GET['key']);
			
			    //check if the key is in the database  
			    $check_key = mysqli_query($dblink,"SELECT * FROM `confirm` WHERE `email` = '$email' AND `key` = '$key' LIMIT 1") or die(mysqli_error());  
			  	
			  	if($check_key){
			        //get the confirm info
			        $confirm_info = mysqli_fetch_assoc($check_key);

			        //confirm the email and update the users database  
			        $update_users = mysqli_query($dblink,"UPDATE `users` SET `active` = 1 WHERE `email` = '$confirm_info[email]'");

			        //send out a welcome email
                    $to = $confirm_info[email];
                    $subject = "Thanks for joining Import Underground!";
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
                                            <td>
                                                <table width='100%' style='font-family: helvetica-neue, arial;border-collapse: collapse;height: 370px;margin: 0;padding: 0; background-image: url(http://www.importunderground.com/images/email/regsuccess_bg.jpg); background-color: #000; background-repeat: no-repeat; background-position: center top;' border='0'>
                                                    <tbody>
                                                        <tr>
                                                            <td style='vertical-align:top;padding:20px;'>
                                                                 <h1 style='color:#ffffff;text-shadow:0 1px  #333;'>Welcome to Import Underground</h1>
                                                                <h3 style='color: #ffffff;'>Thanks for joining our growing community!</h3>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style='padding:20px 20px;'>
                                                <p>We're excited that you've decided to become part of our online community of car enthusiasts. We have many more features coming in the near future and hope that you enjoy your time on the site.</p>
                                                <p></p>
                                                <p>You can log in <a href='http://www.importunderground.com'>here</a> and <a href='http://www.importunderground.com/createpost'>create your first post</a>.</p>
                                                <p>Also, don't forget to <a href='http://www.importunderground.com/profile'>update your profile</a> and <a href='http://www.importunderground.com/myposts'>manage your posts</a>.</p>
                                            </td>
                                        <tr>
                                            <td style='padding:20px 20px;'>
                                               <p>If you have any questions, comments, concerns or suggestions please email them to <a href='mailto:support@importunderground.com'>support@importunderground.com</a></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style='padding:20px 20px;'>
                                                <p>Regards,</p>
                                               <p>-Import Underground Team</p>
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

			        //delete the confirm row  
			        $delete = mysqli_query($dblink,"DELETE FROM `confirm` WHERE `id` = '$confirm_info[id]' ");  

			        if($update_users){
			  
			            echo '<p class="success">Your account has been confirmed. Thank-You!</p>';  
			  			echo '<h3>You can now sign in.</h3>';
			        }else{  
			   
			            echo'<p class="error">The user could not be updated Reason: '.mysqli_error().'</p>'; 
			  
			        }
			    }else{
			    	echo '<p class="error">Oops! That account has already been activated or is not in our system.</p>';
			    }
			  
			   
			  
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