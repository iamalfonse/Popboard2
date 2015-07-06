<?php
	include("config.php");

	if (isset( $_COOKIE['login_cookie'] )) {
		$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
		$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);

		// check user setup
		$setupQuery  = mysqli_query($dblink, "SELECT * FROM users WHERE username='$username' AND session_hash = '$session_hash'");
		$Rows = mysqli_fetch_assoc($setupQuery);

		if($Rows['setup'] == '1'){ //check if user finished setup already
			header("Location: /");
			exit;
		}

	} else {
		header("Location: /");
		exit;
	}

	$errmsg = isset($_GET['errmsg']);
	if($errmsg=='1'){
		$errmsg = "Please enter your date of birth.";
	}else {
		$errmsg = '';
	}
	
	$max_file_size = 512000; //500kb

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta content="width=device-width, user-scalable=yes" name="viewport" />
	<title>Import Underground | Setup Your Profile</title>
	<meta name="keywords" content="">
	<meta name="description" content="">
	<link href="/stylesheets/<?= $stylesheet; ?>.css" rel="stylesheet" type="text/css" />
	<link href="/stylesheets/jcrop.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" type="image/png" href="/images/favicon.png">
</head>
<body class="setup">
<div id="top">
	<div class="logomenu">
		<h1><a href="/">Import Underground</a></h1>
	</div>
</div>
<?php //include("top.php"); ?>

<div id="content">

	<div class="selectSetup">
		<form id="setupform" enctype="multipart/form-data" method="post" action="/setupsubmit">
			<h2>Setup your profile</h2>
			<? if($errmsg!=''){echo "<p class='error'>".$errmsg."</p>";} ?>
			<input type="hidden" name="MAX_FILE_SIZE" value="<?= $max_file_size ?>">
			<ul>
				<li class="userName">
					<h3>Username</h3>
					<p><?= $Rows['displayname']; ?></p>
				</li>
				<li class="userName">
					<h3>Email</h3>
					<p><?= $Rows['email']; ?></p>
				</li>
				<li class="profilePic">
					<h3>Profile Picture</h3>
					<div class="profileImg">
						<?//= iu_get_avatar_pic($Rows['email']); ?>
						<img class="avatarImg" src="/images/me.jpg" />
						<p><span class="submitbtn uploadphoto">Upload Photo<input id="profileimg" type="file" name="profileimg" value="Upload a Photo"/></span></p>  
						<!-- <p>or</p>
						<p>Use your Gravatar Image</p> -->
					</div>
				</li>
				<li class="editBio">
					<h3>Bio</h3>
					<p><input type="text" name="bio" value="" placeholder="Tell us about yourself" maxlength="120"/></p>
				</li>
				<li class="enterBirthday">
					<h3><span class="red">*</span> Birthday</h3>
				</li>
				<li class="enterLocation">
					<h3>Location</h3>
					<p><input type="text" name="location" value="" placeholder="Where are you located?" maxlength="60"/></p>
				</li>
				<li class="enterCar">
					<h3>Car</h3>
					<p><input type="text" name="car" value="" placeholder="What car do you drive?" maxlength="30"/></p>
				</li>
			</ul>
			<input class="submitbtn" type="submit" name="submitSetup" value="Submit" />
		</form>
	</div>
</div><!--#bottom-->
<?php include("scripts.php"); ?>
<script src="/js/bday-picker.min.js" type="text/javascript"></script>
<script src="/js/ajaxfileupload.js" type="text/javascript"></script>
<script src="/js/jquery.Jcrop.js" type="text/javascript"></script>
<script src="/js/setup.js" type="text/javascript"></script>
<div class="jcropOverlay">
	<div class="jcropContainer">
		<div class="mainImg"></div>
		<div id="preview-pane">
			<div class="preview-container"></div>
		</div>
		<button class="submitbtn jcropConfirm">Use Photo</button>
		<button class="submitbtn jcropCancel">Cancel</button>
		<input type="hidden" id="x" name="x" value="0" />
		<input type="hidden" id="y" name="y" value="0" />
		<input type="hidden" id="w" name="w" value="90" />
		<input type="hidden" id="h" name="h" value="90" />
	</div>
</div>
</body>
</html>