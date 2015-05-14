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
	// logged out. set $Rows['crew_id'] = '';
	$Rows = array('crew_id' => '', 'user_id' => '' );
}

//grab crewurl if in a crew
$crewurl = '';
if(isset($_GET['crewurl'])){
	$crewurl = mysqli_real_escape_string($dblink, $_GET['crewurl']);
}

$pageurl = iu_get_page_url();
if($pageurl == '/crew/' || $pageurl == '/crew' ){
	header('Location: /crews');
	exit;
}
if($crewurl != ''){
	//get crew_id of crewurl
	$crewPostQuery = @mysqli_query($dblink, "SELECT * FROM crews WHERE crew_url='$crewurl'");
	$crewPostRow = mysqli_fetch_assoc($crewPostQuery);
	$crewpageid = $crewPostRow['crew_id'];
	$crewpagename = $crewPostRow['crewname'];
	$crewbanner = $crewPostRow['banner_img'];
	$isprivate = $crewPostRow['private'];
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta content="width=device-width, user-scalable=yes" name="viewport" />
	<title>Import Underground | <?= $crewpagename; ?></title>
	<meta name="keywords" content="Import Underground, Imports, Racing, Tuner, Cars, Car Meets, Underground, Street, Pictures, Events, Meets, Crews, Car Crews">
	<meta name="description" content="Import Underground | <?= $crewpagename; ?> ">
	<link href="/stylesheets/car.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" type="image/png" href="/images/favicon.png">
</head>

<body class='crew'>
	<input type="hidden" id="crewurl" value="<?= $crewurl?>">
	<?php include("top.php"); ?>

	<div id="content">
		<?php include("left.php"); ?>

		<div id="right">
			<? if($crewpagename){ ?>
			<div class="crewbanner" style="background: url('<?= $crewbanner ?>') no-repeat center top">
				<div class="transparent">
					<h2><?=  $crewpagename ?></h2>
				</div>
			</div>
			<? }

				//check to see if crew is private
				if($isprivate=='1' && $Rows['crew_id'] != $crewpageid){
					echo "<h2>Sorry, this crew is Private. Only crew members can see the posts.</h2>";
				}else if($crewpagename == ''){
					echo "<h2>Sorry, this crew does not exist.</h2>";
				}else{
					//show posts
					$QueryResult = @mysqli_query($dblink, "SELECT username, blog_title, blog_message, user_id, id, post_date FROM users INNER JOIN posts USING (user_id) WHERE (posts.crew_id = '$crewpageid' AND posts.deleted = '0') ORDER BY id DESC LIMIT 0, 6");
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
		<? if($crewpagename){ ?>
			<aside id="side">

				<div class="sideWrap crewDesc">
					<h3>About</h3>
					<p><?= $crewPostRow['about']; ?></p>
					<h3>Created</h3>
					<p><?= iu_readable_date($crewPostRow['datecreated'], 'short'); ?></p>
					<? 	if(isset($_COOKIE['login_cookie']) && !isset($Rows['crew_id'])){ //if logged in and not in a crew yet 

							//check if user has asked for invite first
							$crewinviteCheckQuery = mysqli_query($dblink, "SELECT * FROM crewinvites WHERE user_id='{$Rows['user_id']}'");
							$crewinviteRow = mysqli_fetch_assoc($crewinviteCheckQuery);
							$crewinviteNumRows = mysqli_num_rows($crewinviteCheckQuery);
							if($crewinviteNumRows != 0 && $crewinviteRow['crew_id'] == $crewpageid ){ // there's already a record and user is looking at the crew he asked an invite for
					?>
								<div class="pending">Invite Pending</div>
						<? }else if($crewinviteNumRows == 0 ){ //not in a crew and not pending an invite ?>
								<input type="hidden" id="crewid" value="<?= $crewpageid ?>">
								<div class="btn joincrewbtn">Ask to Join Crew</div>
						<? 	}
						}
						if($crewPostRow['founder'] == $username){ // if user is the founder of the crew
					?>
						<a class="btn crewsetupbtn" href="/crewsetup/<?= $crewurl ?>">Crew Settings</a>
					<?
						}
					?>
				</div>

				<div class="sideWrap crewMembers">
					<h3><?= iu_plural($crewPostRow['num_members'],'Crew Member'); ?></h3>

					<ul>
						<?
						//get crew member profiles
						$crewMemberQuery  = mysqli_query($dblink, "SELECT email,username,displayname FROM users WHERE crew_id = '$crewpageid' LIMIT 0, 20");
						$crewMemberRow = mysqli_fetch_assoc($crewMemberQuery);
						$crewMemberNumRows = mysqli_num_rows($crewMemberQuery);

						if($crewMemberNumRows>=1){ //if there's at least 1 crew member
							iu_get_crewmembers($crewMemberRow, $crewMemberQuery);
						}
						?>
					</ul>
				</div>

				<? if(isset($_COOKIE['login_cookie']) ){ // if logged in, show post/invite options  ?>
					<div class="sideWrap sidePost">
						<? if($Rows['crew_id'] == $crewPostRow['crew_id']){ //if user is in the crew ?>
							<a class="submitbtn createpostbtn" href="/createpost?crew=<?= $Rows['crew_id'] ?>">Create New Post</a>
						<? }else if(!isset($Rows['crew_id']) || $Rows['crew_id'] != $crewPostRow['crew_id'] ) { //if user has no crew yet or this is not their crew  ?>
							<p>You must be a crew member to be able to post here.</p>
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
		<p>You may only join one crew at a time.</p>
		<p>Are you sure you want to join this crew?</p>
		<button class="btn cancelBtn">Cancel</button>
		<button class="btn joinBtn">Join Crew</button>
	</div>
</div>

<?php include("scripts.php"); ?>
<script type="text/javascript" src="/js/crew.js"></script>
</body>
</html>