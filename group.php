<?
include("config.php");

$Rows = '';
$username = '';
if (isset( $_COOKIE['login_cookie'] )) { //if user is logged in
	$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
	$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);

	iu_check_user_setup(); //make sure user has finished setup of avatar

	/* GET LOGGED IN USER INFORMATION*/
	$r  = @mysqli_query($dblink, "SELECT * FROM users WHERE username='$username' AND session_hash = '$session_hash'");
	$Rows = mysqli_fetch_assoc($r);
}else {
	// logged out. set $Rows['group_id'] = '';
	$Rows = array('group_id' => '', 'user_id' => '' );
}

//grab groupurl if in a group
$groupurl = '';
if(isset($_GET['groupurl'])){
	$groupurl = mysqli_real_escape_string($dblink, $_GET['groupurl']);
}

$pageurl = iu_get_page_url();
if($pageurl == '/group/' || $pageurl == '/group' ){
	header('Location: /groups');
	exit;
}
if($groupurl != ''){
	//get group_id of groupurl
	$groupPostQuery = @mysqli_query($dblink, "SELECT * FROM groups WHERE group_url='$groupurl'");
	$groupPostRow = mysqli_fetch_assoc($groupPostQuery);
	$grouppageid = $groupPostRow['group_id'];
	$grouppagename = $groupPostRow['groupname'];
	$groupbanner = $groupPostRow['banner_img'];
	$isprivate = $groupPostRow['private'];
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta content="width=device-width, user-scalable=yes" name="viewport" />
	<title>Import Underground | <?= $grouppagename; ?></title>
	<meta name="keywords" content="Import Underground, Imports, Racing, Tuner, Cars, Car Meets, Underground, Street, Pictures, Events, Meets, Groups, Car Groups">
	<meta name="description" content="Import Underground | <?= $grouppagename; ?> ">
	<link href="/stylesheets/car.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" type="image/png" href="/images/favicon.png">
</head>

<body class='group'>
	<input type="hidden" id="groupurl" value="<?= $groupurl?>">
	<?php include("top.php"); ?>

	<div id="content">
		<?php include("left.php"); ?>

		<div id="right">
			<? if($grouppagename){ ?>
			<div class="groupbanner" style="background: url('<?= $groupbanner ?>') no-repeat center top">
				<div class="transparent">
					<h2><?=  $grouppagename ?></h2>
				</div>
			</div>
			<? }

				//check to see if group is private
				if($isprivate=='1' && $Rows['group_id'] != $grouppageid){
					echo "<h2>Sorry, this group is Private. Only group members can see the posts.</h2>";
				}else if($grouppagename == ''){
					echo "<h2>Sorry, this group does not exist.</h2>";
				}else{
					//show posts
					$QueryResult = @mysqli_query($dblink, "SELECT username, blog_title, blog_message, user_id, id, post_date FROM users INNER JOIN posts USING (user_id) WHERE (posts.group_id = '$grouppageid' AND posts.deleted = '0') ORDER BY id DESC LIMIT 0, 6");
					$Row = mysqli_fetch_assoc($QueryResult);
					$num_rows = mysqli_num_rows($QueryResult);
					
					if($num_rows == 0){
						echo "<h2>Sorry, there are no posts here.</h2>";
					}else {
						//display initial posts
						iu_get_posts($Rows, $Row, $QueryResult);
					}
				}

				
			?>
		</div><!-- #right -->
		<? if($grouppagename){ ?>
			<aside id="side">

				<div class="sideWrap groupDesc">
					<h3>About</h3>
					<p><?= $groupPostRow['about']; ?></p>
					<? // TODO: Make the about section of the group editable for the founder to apply rules/ ?>
					<h3>Created</h3>
					<p><?= iu_readable_date($groupPostRow['datecreated'], 'short'); ?></p>
					<? 	
						// Do a query to see if user is in the group yet
						$groupCheckQuery = mysqli_query($dblink, "SELECT * FROM user_groups WHERE user_id='{$Rows['user_id']}' && group_id='$grouppageid'");
						$groupcheckRow = mysqli_fetch_assoc($groupCheckQuery);
						$groupcheckNumRows = mysqli_num_rows($groupCheckQuery);
						
						if(isset($_COOKIE['login_cookie']) && $groupcheckNumRows >= 1){ //if logged in and not in a group yet

							//check if user has asked for invite first
							$groupinviteCheckQuery = mysqli_query($dblink, "SELECT * FROM groupinvites WHERE user_id='{$Rows['user_id']}'");
							$groupinviteRow = mysqli_fetch_assoc($groupinviteCheckQuery);
							$groupinviteNumRows = mysqli_num_rows($groupinviteCheckQuery);
							if($groupinviteNumRows != 0 && $groupinviteRow['group_id'] == $grouppageid ){ // there's already a record and user is looking at the group he asked an invite for
					?>
								<div class="pending">Invite Pending</div>
						<? }else if($groupinviteNumRows == 0 ){ //not in a group and not pending an invite ?>
								<input type="hidden" id="groupid" value="<?= $grouppageid ?>">
								<div class="btn joingroupbtn">Ask to Join Group</div>
						<? 	}
						}
						if($groupPostRow['founder'] == $username){ // if user is the founder of the group
					?>
						<a class="btn groupsetupbtn" href="/groupsetup/<?= $groupurl ?>">Group Settings</a>
					<?
						}
					?>
				</div>

				<div class="sideWrap groupMembers">
					<h3><?= iu_plural($groupPostRow['num_members'],'Group Member'); ?></h3>

					<ul>
						<?
						//get group member profiles
						$groupMemberQuery  = mysqli_query($dblink, "SELECT email,username,displayname FROM users WHERE group_id = '$grouppageid' LIMIT 0, 20");
						$groupMemberRow = mysqli_fetch_assoc($groupMemberQuery);
						$groupMemberNumRows = mysqli_num_rows($groupMemberQuery);

						if($groupMemberNumRows>=1){ //if there's at least 1 group member
							iu_get_groupmembers($groupMemberRow, $groupMemberQuery);
						}
						?>
					</ul>
				</div>

				<? if(isset($_COOKIE['login_cookie']) ){ // if logged in, show post/invite options  ?>
					<div class="sideWrap sidePost">
						<? if($Rows['group_id'] == $groupPostRow['group_id']){ //if user is in the group // TODO: check user_groups table if user is in this group ?>
							<a class="submitbtn createpostbtn" href="/createpost?group=<?= $Rows['group_id'] ?>">Create New Post</a>
						<? }else if(!isset($Rows['group_id']) || $Rows['group_id'] != $groupPostRow['group_id'] ) { //if user has no group yet or this is not their group  ?>
							<p>You must be a group member to be able to post here.</p>
						<? } ?>
					</div>
				<? } ?>
			</aside>
		<? } ?>

		<p class='load-more'>Load More</p>
	</div><!-- #bottom -->

<div class="joinOverlay">
	<div class="joinWrap">
		<span class="closebtn">close x</span>
		<p>Are you sure you want to join this group?</p>
		<button class="btn cancelBtn">Cancel</button>
		<button class="btn joinBtn">Join Group</button>
	</div>
</div>

<?php include("scripts.php"); ?>
<script type="text/javascript" src="/js/group.js"></script>
<? include("createpost.php"); ?>
</body>
</html>