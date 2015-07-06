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

//get group info
$group_url = '';
if(isset($_GET['groupname'])){
	$group_url = $_GET['groupname'];
}
$groupInfoQuery  = mysqli_query($dblink, "SELECT * FROM groups WHERE group_url ='$group_url'");
$groupInfoRow = mysqli_fetch_assoc($groupInfoQuery);

// allow only users who created the group to edit settings
if($Rows['username'] != $groupInfoRow['founder']){
	header("Location: /groups?errmsg=1");
	exit;
}

// allow only setup of the group you made
if($Rows['group_id'] != $groupInfoRow['group_id'] && $Rows['username'] || $group_url == ''){
	header("Location: /groups?errmsg=3");
	exit;
}

//get error message if there is one
$errmsg = '';
if(isset($_GET['errmsg'])){
	$errmsg = $_GET['errmsg'];
}


//TODO: Make sure the group admin can remove group members if need be, and also -1 from num_members in 'groups' table


//TODO: Allow group admin to invite other users to their group (make sure to check if the user they're trying to invite is not already in a group)


?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta content="width=device-width, user-scalable=yes" name="viewport" />
	<title>Import Underground | Group Setup</title>
	<meta name="keywords" content="Import Underground, Imports, Racing, Tuner, Cars, Car Meets, Underground, Street, Pictures, Events, Meets, Groups, Car Groups">
	<meta name="description" content="Import Underground | Create your own group ">
	<link href="/stylesheets/<?= $stylesheet; ?>.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" type="image/png" href="/images/favicon.png">
</head>

<body class='groupsetup'>
	<?php include("top.php"); ?>

	<div id="content">
		<?php include("left.php"); ?>

	    <div id="right" class="groupsetupsection">
	    	
	    	<div class="headertitle groupname">
	    		<? if(isset($_GET['from']) == 'creategroup'){ ?>
	    			<h2>Congrats! Your group: <?= $groupInfoRow['groupname'] ?> has been created</h2>
	    		<? }else{ ?>
	    			<h2><?= $groupInfoRow['groupname'] ?> Setup</h2>
	    		<? } ?>
	    	</div>
	    	
			<div class="groupinfo">
				<form method="post" enctype="multipart/form-data" action="/submitgroupsetup">
					<label>Group Name</label>
					<p><?= $groupInfoRow['groupname']; ?></p>
					<label>Group Description</label>
					<p><input class="groupDesc" type="text" name="groupdesc" value="<?= $groupInfoRow['about']; ?>" placeholder="Describe your group" /></p>
					<label>Add Background Image <span>(285x285 pixel .jpg/.png)</span></label>
					<p><input id="groupimg" type="file" name="groupimg"/></p>
					<label>Location</label>
					<p><input class="groupLoc" type="text" name="grouplocation" placeholder="Where is your group located?" value="<?= $groupInfoRow['location']; ?>" maxlength='20'/></p>
					<label class="groupprivate">Is this Group Private? </label>
					<p><input type="radio" name="private" value="1"/> Yes <input type="radio" name="private" checked value="0"/> No</p>
					<input type="submit" class="submitbtn" name="submitGroupSetup" value="Update Group Info" />
				</form>
			</div>
			<div class="groupPreview">
				<ul class="grouplist">
					<li>
						<div class="groupImg">
							<img src="<?= $groupInfoRow['img_url']; ?>" />
							<div class="transparent">
								<p class="groupAbout"><?= $groupInfoRow['about']; ?></p>
							</div>
						</div>
						<h3><?= $groupInfoRow['groupname']; ?></h3>
						<p class="members" ><strong><i></i><?= iu_plural($groupInfoRow['num_members'], 'Member'); ?></strong></p>
						<p class="location" ><?= $groupInfoRow['location']; ?></p>
						<p class="created"><?= iu_readable_date( $groupInfoRow['datecreated'], 'short'); ?></p>
					</li>
				</ul>
			</div>
			<div class="groupMemberList">
				<h3><?= iu_plural($groupInfoRow['num_members'],'Group Member'); ?></h3>
				<ul>
					<?
					//get group member profiles
					$groupMemberQuery  = mysqli_query($dblink, "SELECT email,username,displayname FROM users INNER JOIN user_groups USING (group_id) WHERE (users.user_id = user_groups.user_id && user_groups.group_id = '{$groupInfoRow['group_id']}') LIMIT 0, 20");
					$groupMemberRow = mysqli_fetch_assoc($groupMemberQuery);
					$groupMemberNumRows = mysqli_num_rows($groupMemberQuery);

					if($groupMemberNumRows>=1){ //if there's at least 1 group member
						iu_get_groupmembers($groupMemberRow, $groupMemberQuery);
					}
					?>
				</ul>
			</div>
		</div>
	</div>
<?php include("scripts.php"); ?>
<script src="/js/ajaxfileupload.js" type="text/javascript"></script>
<script src="/js/jquery.Jcrop.js" type="text/javascript"></script>
<script type="text/javascript" src="/js/groupsetup.js"></script>
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