<?
include("config.php");

$Rows = '';
if (isset( $_COOKIE['login_cookie'] )) { //if user is logged in
	$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
	$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);

	iu_check_user_setup(); //make sure user has finished setup of avatar

	/* GET LOGGED IN USER INFORMATION*/
	$r  = mysqli_query($dblink, "SELECT user_id, email, session_hash, lvl, group_id FROM users WHERE username='$username' AND session_hash = '$session_hash'");
	$Rows = mysqli_fetch_assoc($r);
}else {
	header("Location: /");
	exit;
}

// allow only users who are lvl 5 or higher to create a group
// if($Rows['lvl'] < '5'){
// 	header("Location: /groups?errmsg=1");
// 	exit;
// }

// if a user is already in a group, they can't create their own
// if(isset($Rows['group_id'])){
// 	header("Location: /groups?errmsg=2");
// 	exit;
// }

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
	<title>Create A Group | <?= $__site['name']; ?></title>
	<meta name="keywords" content="<?= $__site['keywords']; ?>">
	<meta name="description" content="Create Your Own Group | <?= $__site['name']; ?>">
	<link href="/stylesheets/<?= $stylesheet; ?>.css?<?= $__site['filedate']; ?>" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" type="image/png" href="/images/favicon.png?<?= $__site['filedate']; ?>">
</head>

<body class='creategroup'>
	<?php include("top.php"); ?>

	<div id="content">
		<?php include("left.php"); ?>

	    <div id="right" class="creategroupsection">
	    	<? 	
	    		if($errmsg){
	    			$groupname = $_GET['groupname'];
	    			$groupdesc = $_GET['groupdesc'];
	    			$grouplocation = $_GET['grouplocation'];
	    		}
	    		if($errmsg == '1'){
	    			echo "<p class='error'>Please fill in the required fields.</p>";
	    		}
	    		if($errmsg == '2'){
	    			echo "<p class='error'>Not a valid group name. Only letters, numbers, underscores, and spaces are allowed.</p>";
	    		}
	    	?>
	    	<div class="headertitle groupname">
	    		<h2>Create A Group</h2>
	    	</div>
			<div class="groupinfo">
				<form method="post" enctype="multipart/form-data" action="/submitcreategroup">
					<label>Group Name</label>
					<p><strong class="groupnametaken">&nbsp;</strong><input class="groupName" type="text" name="groupname" value="<? if($errmsg=='1'){echo $groupname;} ?>" placeholder="What's the name of your group?" maxlength="30"/></p>
					<label>Group Description</label>
					<p><input class="groupDesc" type="text" name="groupdesc" value="<? if($errmsg=='1'||$errmsg=='2'){echo $groupdesc;} ?>" placeholder="Describe your group" /></p>
					<label>Location</label>
					<p><input class="groupLoc" type="text" name="grouplocation" placeholder="Where is your group located?" value="<? if($errmsg=='1'||$errmsg=='2'){echo $grouplocation;} ?>" maxlength='20'/></p>
					<label class="groupprivate">Is this Group Private? </label>
					<p><input type="radio" name="private" value="1"/> Yes <input type="radio" name="private" checked value="0"/> No</p>
					<label>Invite Group Members by email <span>seperate with commas</span></label>
					<p><input class="groupInviteEmail" type="text" name="groupEmails" placeholder="Enter Email addresses"/></p>
					<input type="submit" class="submitbtn" name="submitCreateGroup" value="Create Your Group" />
				</form>
			</div>
			<div class="groupPreview">
				<h3>Preview</h3>
				<ul class="grouplist">
					<li>
						<div class="groupImg">
							<img src="/images/group.jpg" />
							<div class="transparent">
								<p class="groupAbout"><? if($errmsg=='1'||$errmsg=='2'){echo $groupdesc;}else{echo "Your group description here";} ?></p>
							</div>
						</div>
						<h3>Group Name</h3>
						<p class="members" ><strong><i></i>1 Member</strong></p>
						<p class="location" ><? if($errmsg=='1'||$errmsg=='2'){echo $grouplocation;}else{ echo "Your Location";} ?></p>
						<? $groupDateCreated = date("Y-m-d H:i:s"); ?>
						<p class="created"><?= iu_readable_date( $groupDateCreated, 'short'); ?></p>
					</li>
				</ul>
			</div>
		</div>
	</div>
<?php include("scripts.php"); ?>
<script type="text/javascript" src="/js/creategroup.js"></script>
</body>
</html>