<?php
/**
 * resetpassword.php
 *
 * Submit reset password and update password for user
 *
 **/

/* Declare the parameters for accessing the database again. */
//DATABASE PARAMS
include("config.php");

//quick/simple validation
if(empty($_POST['email']) || empty($_POST['key'])){
	echo '<p>Please check your email to reset your password.</p>';
}else if(empty($_POST['newpassword']) || empty($_POST['passwordconfirm'])){
	echo '<p>Please fill in the password fields.</p>';
}else if($_POST['newpassword'] != $_POST['passwordconfirm']){
	echo '<p>Password does not match. Please retype your password.</p>';
}else {

	//cleanup the variables
	$email = mysqli_real_escape_string($dblink, $_POST['email']);
	$key = mysqli_real_escape_string($dblink, $_POST['key']);

	//check if the key is in the database
	$check_key = mysqli_query($dblink,"SELECT * FROM `forgotpassword` WHERE `email` = '$email' AND `key` = '$key' LIMIT 1") or die(mysqli_error($dblink));  
	$checkNumRows = mysqli_num_rows($check_key);

	if($checkNumRows != 0){
		//get the confirm info
		$confirm_info = mysqli_fetch_assoc($check_key);

		//sanitize password and encrypt
		$newpassword = mysqli_real_escape_string($dblink, $_POST['newpassword']);
		$password = sha1($newpassword);

		// //confirm the email and update the users database
		$update_users = mysqli_query($dblink,"UPDATE `users` SET `passwd` = '$password' WHERE `email` = '$confirm_info[email]'");

		if($update_users){

			// //send out a 'You reset your password' email
			$to = $confirm_info[email];
			$subject = "You've Reset Your {$__site['name']} Password";
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
								<h3>Your {$__site['name']} password has been reset.</h3>
								<p>You can now <a href='{$__site['name']}/'>log in</a> and start posting again.</p>
							</td>
						</tr>
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

			//delete the forgotpassword row
			$delete = mysqli_query($dblink,"DELETE FROM `forgotpassword` WHERE `id` = '$confirm_info[id]' ");


			echo '<p class="success">Your account password has been reset.</p>';
			echo '<p>You can now <a href="/">log back in</a>.</p>';
		}else{
			echo'<p class="error">An error occured. Please try again.</p>';
		}
	}else{
		echo '<p class="error">Oops! Looks like you tried to re-use a Reset Password token from before or the Reset Password token is expired. Please <a href="/forgotpassword">click here</a> to reset your password again.</p>';
	}

}
		?>