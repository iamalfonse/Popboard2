<?
include("config.php");

$Rows = '';
if (isset( $_COOKIE['login_cookie'] )) { //if user is logged in
	$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
	$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);

	iu_check_user_setup(); //make sure user has finished setup of avatar

	/* GET LOGGED IN USER INFORMATION*/
	$r  = mysqli_query($dblink, "SELECT user_id, email, session_hash, lvl, crew_id FROM users WHERE username='$username' AND session_hash = '$session_hash'");
	$Rows = mysqli_fetch_assoc($r);
}else {
	header("Location: /");
	exit;
}

// allow only users who are lvl 5 or higher to create a crew
// if($Rows['lvl'] < '5'){
// 	header("Location: /crews?errmsg=1");
// 	exit;
// }

// if a user is already in a crew, they can't create their own
if(isset($Rows['crew_id'])){
	header("Location: /crews?errmsg=2");
	exit;
}

//get error message if there is one
$errmsg = '';
if(isset($_GET['errmsg'])){
	$errmsg = $_GET['errmsg'];
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta content="width=device-width, user-scalable=yes" name="viewport" />
	
	<title>Import Underground | Create A Crew</title>
	<meta name="keywords" content="Import Underground, Imports, Racing, Tuner, Cars, Car Meets, Underground, Street, Pictures, Events, Meets, Crews, Car Crews">
	<meta name="description" content="Import Underground | Create your own crew ">
	<link href="/stylesheets/car.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" type="image/png" href="/images/favicon.png">
</head>

<body class='createcrew'>
	<?php include("top.php"); ?>

	<div id="content">
		<?php include("left.php"); ?>

	    <div id="right" class="createcrewsection">
	    	<? 	
	    		if($errmsg){
	    			$crewname = $_GET['crewname'];
	    			$crewdesc = $_GET['crewdesc'];
	    			$crewlocation = $_GET['crewlocation'];
	    		}
	    		if($errmsg == '1'){
	    			echo "<p class='error'>Please fill in the required fields.</p>";
	    		}
	    		if($errmsg == '2'){
	    			echo "<p class='error'>Not a valid crew name. Only letters, numbers, underscores, and spaces are allowed.</p>";
	    		}
	    	?>
	    	<div class="headertitle crewname">
	    		<h2>Create A Crew</h2>
	    	</div>
			<div class="crewinfo">
				<form method="post" enctype="multipart/form-data" action="/submitcreatecrew">
					<label>Crew Name</label>
					<p><strong id="crewnametaken">&nbsp;</strong><input class="crewName" type="text" name="crewname" value="<? if($errmsg=='1'){echo $crewname;} ?>" placeholder="What's the name of your crew?" maxlength="30"/></p>
					<label>Crew Description</label>
					<p><input class="crewDesc" type="text" name="crewdesc" value="<? if($errmsg=='1'||$errmsg=='2'){echo $crewdesc;} ?>" placeholder="Describe your crew" /></p>
					<label>Location</label>
					<p><input class="crewLoc" type="text" name="crewlocation" placeholder="Where is your crew located?" value="<? if($errmsg=='1'||$errmsg=='2'){echo $crewlocation;} ?>" maxlength='20'/></p>
					<label class="crewprivate">Is this Crew Private? </label>
					<p><input type="radio" name="private" value="1"/> Yes <input type="radio" name="private" checked value="0"/> No</p>
					<label>Invite Crew Members by email <span>seperate with commas</span></label>
					<p><input class="crewInviteEmail" type="text" name="crewEmails" placeholder="Enter Email addresses"/></p>
					<input type="submit" class="submitbtn" name="submitCreateCrew" value="Create Your Crew" />
				</form>
			</div>
			<div class="crewPreview">
				<h3>Preview</h3>
				<ul class="crewlist">
					<li>
						<div class="crewImg">
							<img src="/images/crew.jpg" />
							<div class="transparent">
								<p class="crewAbout"><? if($errmsg=='1'||$errmsg=='2'){echo $crewdesc;}else{echo "Your crew description here";} ?></p>
							</div>
						</div>
						<h3>Crew Name</h3>
						<p class="members" ><strong><i></i>1 Member</strong></p>
						<p class="location" ><? if($errmsg=='1'||$errmsg=='2'){echo $crewlocation;}else{ echo "Your Location";} ?></p>
						<? $crewDateCreated = date("Y-m-d H:i:s"); ?>
						<p class="created"><?= iu_readable_date( $crewDateCreated, 'short'); ?></p>
					</li>
				</ul>
			</div>
		</div>
	</div>
<?php include("scripts.php"); ?>
<script type="text/javascript" src="/js/createcrew.js"></script>
</body>
</html>