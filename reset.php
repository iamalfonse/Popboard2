<?php
/**
 * reset.php
 *
 * Display reset password form
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
	<title>Import Underground | Rest Password</title>
	<meta name="keywords" content="Import Underground, Imports, Racing, Tuner, Cars, Car Meets, Underground, Street, Pictures, Events, Meets, Crews, Car Crews">
	<meta name="description" content="Sign up now to create your own Import Underground account and share and discuss with other car enthusiasts">
</head>
<body class="reset">

	<?php include("top.php"); ?>

	<div id="content">
		<div id="signup">
			<div class="signup-banner">
                <h2>Reset Your Password</h2>
            </div>
		<?
			if(isset($_GET['errmsg'])){
				echo $errmsg;
			}
			/* This is when the user clicks on the email link
			 * that they recieved for confirmation. It checks
			 * the database to see if a key is assigned
			 */

			//quick/simple validation
			if(empty($_GET['email']) || empty($_GET['key'])){
				echo '<p class="normal">Please check your email to reset your password.</p>';
			} else {

				//cleanup the variables
				$email = mysqli_real_escape_string($dblink, $_GET['email']);
				$key = mysqli_real_escape_string($dblink, $_GET['key']);

				//check if the key is in the database
				$check_key = mysqli_query($dblink,"SELECT * FROM `forgotpassword` WHERE `email` = '$email' AND `key` = '$key' LIMIT 1") or die(mysqli_error($dblink));  
				$checkNumRows = mysqli_num_rows($check_key);

				if($checkNumRows != 0){
					//get the confirm info
					$confirm_info = mysqli_fetch_assoc($check_key);

					//The password reset token and email is good
					?>
					<form id="resetform" method="post" action="/resetpassword">
						<p>Reset Password for: <strong><?= $email ?></strong></p>
						<input class="userinput password" type="password" name="newpassword" placeholder="New Password" />
						<input class="userinput passconf" type="password" name="passwordconfirm" placeholder="Confirm Password" />
						<input class="email" type="hidden" name="email" value="<?= $email ?>">
						<input class="key" type="hidden" name="key" value="<?= $key ?>">
						<input type="submit" name="resetpassword" value="Reset Password" class="submitbtn"/>
					</form>
					<?
				}else{
					echo '<p class="error">Oops! Looks like you tried to re-use a Reset Password token from before or the Reset Password token is expired. Please <a href="/forgotpassword">click here</a> to reset your password again.</p>';
				}

			}
		?>

		</div>
	</div><!--#bottom-->
<?php include("scripts.php"); ?>
<script src="/js/reset.js" type="text/javascript"></script>
</body>
</html>