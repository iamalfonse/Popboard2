<?

include("config.php");

if (isset( $_COOKIE['login_cookie'] )) { //if user is logged in
	$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
	$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);

	iu_check_user_setup(); //make sure user has finished setup of avatar

	/* GET LOGGED IN USER INFORMATION*/
	$r  = mysqli_query($dblink, "SELECT * FROM users WHERE username='$username' AND session_hash = '$session_hash'");
	$Rows = mysqli_fetch_assoc($r);
}else {
	header("Location: /");
	exit;
}

//get crew info
$crew_url = '';
if(isset($_GET['crewname'])){
	$crew_url = $_GET['crewname'];
}
$crewInfoQuery  = mysqli_query($dblink, "SELECT * FROM crews WHERE crew_url ='$crew_url'");
$crewInfoRow = mysqli_fetch_assoc($crewInfoQuery);

// allow only users who created the crew to edit settings
if($Rows['username'] != $crewInfoRow['founder']){
	header("Location: /crews?errmsg=1");
	exit;
}

// allow only setup of the crew you made
if($Rows['crew_id'] != $crewInfoRow['crew_id'] && $Rows['username'] || $crew_url == ''){
	header("Location: /crews?errmsg=3");
	exit;
}

//get error message if there is one
$errmsg = '';
if(isset($_GET['errmsg'])){
	$errmsg = $_GET['errmsg'];
}


//TODO: Make sure the crew admin can remove crew members if need be, and also -1 from num_members in 'crews' table


//TODO: Allow crew admin to invite other users to their crew (make sure to check if the user they're trying to invite is not already in a crew)


?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta content="width=device-width, user-scalable=yes" name="viewport" />
	<title>Import Underground | Crew Setup</title>
	<meta name="keywords" content="Import Underground, Imports, Racing, Tuner, Cars, Car Meets, Underground, Street, Pictures, Events, Meets, Crews, Car Crews">
	<meta name="description" content="Import Underground | Create your own crew ">
	<link href="/stylesheets/car.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" type="image/png" href="/images/favicon.png">
</head>

<body class='crewsetup'>
	<?php include("top.php"); ?>

	<div id="content">
		<?php include("left.php"); ?>

	    <div id="right" class="crewsetupsection">
	    	
	    	<div class="headertitle crewname">
	    		<? if(isset($_GET['from']) == 'createcrew'){ ?>
	    			<h2>Congrats! Your crew: <?= $crewInfoRow['crewname'] ?> has been created</h2>
	    		<? }else{ ?>
	    			<h2><?= $crewInfoRow['crewname'] ?> Setup</h2>
	    		<? } ?>
	    	</div>
	    	
			<div class="crewinfo">
				<form method="post" enctype="multipart/form-data" action="/submitcrewsetup">
					<label>Crew Name</label>
					<p><?= $crewInfoRow['crewname']; ?></p>
					<label>Crew Description</label>
					<p><input class="crewDesc" type="text" name="crewdesc" value="<?= $crewInfoRow['about']; ?>" placeholder="Describe your crew" /></p>
					<label>Add Background Image <span>(285x285 pixel .jpg/.png)</span></label>
					<p><input id="crewimg" type="file" name="crewimg"/></p>
					<label>Location</label>
					<p><input class="crewLoc" type="text" name="crewlocation" placeholder="Where is your crew located?" value="<?= $crewInfoRow['location']; ?>" maxlength='20'/></p>
					<label class="crewprivate">Is this Crew Private? </label>
					<p><input type="radio" name="private" value="1"/> Yes <input type="radio" name="private" checked value="0"/> No</p>
					<input type="submit" class="submitbtn" name="submitCrewSetup" value="Update Crew Info" />
				</form>
			</div>
			<div class="crewPreview">
				<ul class="crewlist">
					<li>
						<div class="crewImg">
							<img src="<?= $crewInfoRow['img_url']; ?>" />
							<div class="transparent">
								<p class="crewAbout"><?= $crewInfoRow['about']; ?></p>
							</div>
						</div>
						<h3><?= $crewInfoRow['crewname']; ?></h3>
						<p class="members" ><strong><i></i><?= iu_plural($crewInfoRow['num_members'], 'Member'); ?></strong></p>
						<p class="location" ><?= $crewInfoRow['location']; ?></p>
						<p class="created"><?= iu_readable_date( $crewInfoRow['datecreated'], 'short'); ?></p>
					</li>
				</ul>
			</div>
			<div class="crewMemberList">
				<h3><?= iu_plural($crewInfoRow['num_members'],'Crew Member'); ?></h3>

				<ul>
					<?
					//get crew member profiles
					$crewMemberQuery  = mysqli_query($dblink, "SELECT email,username,displayname FROM users WHERE crew_id = '{$crewInfoRow['crew_id']}' LIMIT 0, 20");
					$crewMemberRow = mysqli_fetch_assoc($crewMemberQuery);
					$crewMemberNumRows = mysqli_num_rows($crewMemberQuery);

					if($crewMemberNumRows>=1){ //if there's at least 1 crew member
						iu_get_crewmembers($crewMemberRow, $crewMemberQuery);
					}
					?>
				</ul>
			</div>
		</div>
	</div>
<?php include("scripts.php"); ?>
<script src="/js/ajaxfileupload.js" type="text/javascript"></script>
<script src="/js/jquery.Jcrop.js" type="text/javascript"></script>
<script type="text/javascript" src="/js/crewsetup.js"></script>
<div class="jcropOverlay">
	<div class="jcropContainer">
		<div class="mainImg"></div>
		<div id="preview-pane">
			<div class="preview-container"></div>
		</div>
		<button class="submitbtn jcropCancel">Cancel</button>
		<button class="submitbtn jcropConfirm">Use Photo</button>
		<input type="hidden" id="x" name="x" />
		<input type="hidden" id="y" name="y" />
		<input type="hidden" id="w" name="w" />
		<input type="hidden" id="h" name="h" />
	</div>
</div>
</body>
</html>