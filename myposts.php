<?php
	
include("config.php");

if (isset( $_COOKIE['login_cookie'] )) {
	$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
	$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);

	iu_check_user_setup(); //make sure user has finished setup

	/* GET LOGGED IN USER INFORMATION*/
	$r  = mysqli_query($dblink, "SELECT * FROM users WHERE username='$username' AND session_hash = '$session_hash'");
	$Rows = mysqli_fetch_assoc($r);
}else { //else if NOT LOGGED IN
	header('Location: /');
	exit;
}
$errmsg = '';
if(isset($_GET['errmsg'])){
	$errmsg = mysqli_real_escape_string($dblink, $_GET['errmsg']);
}

//get sort by
$sortby = mysqli_real_escape_string($dblink, $_GET['sortby']);
if($sortby=='newest' || $sortby==''){
	$sort = 'Most Recent Posts';
}else if($sortby=='oldest'){
	$sort = 'Oldest Posts';
}else if($sortby=='crewposts'){
	$sort = 'Crew Posts';
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta content="width=device-width, user-scalable=yes" name="viewport" />
	<title>Import Underground | My Posts</title>
	<link href="/stylesheets/car.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" type="image/png" href="/images/favicon.png">
</head>
<body class="mywall">
	<input type="hidden" id="sortby" value="<?= $sortby?>" />

	<?php include("top.php"); ?>

	<div id="content">

		<?php include("left.php"); ?>
		
		<div id="right">
			<?
				if($errmsg == '1'){
					echo "<p class='error'>You don't have permission to delete that post. Ain't nobody got time fo dat!</p>";
				}
			?>
			<div class="right-content">
				<h3 class="sectiontitle"><?= $sort ?></h3>
			</div>
<?php

	if($sortby == 'newest'|| $sortby == '') { //sort by newest
		$SQLString = "SELECT username, displayname, email, lvl, blog_title, blog_message, user_id, id, post_date FROM users INNER JOIN posts USING (user_id) WHERE (username = '$username' AND deleted='0') AND posts.crew_id IS NULL ORDER BY id DESC LIMIT 0, 6";
		$subpage = '';
	}else if($sortby == 'oldest'){ //sort by oldest
		$SQLString = "SELECT username, displayname, email, lvl, blog_title, blog_message, user_id, id, post_date FROM users INNER JOIN posts USING (user_id) WHERE (username = '$username' AND deleted='0') AND posts.crew_id IS NULL ORDER BY id ASC LIMIT 0, 6";
		$subpage = '';
	}else if($sortby == 'crewposts'){
		$SQLString = "SELECT username, displayname, email, lvl, blog_title, blog_message, user_id, id, post_date FROM users INNER JOIN posts USING (user_id) WHERE (username = '$username' AND deleted='0') AND (posts.crew_id='{$Rows['crew_id']}' AND posts.category='general') ORDER BY id DESC LIMIT 0, 6";
		$subpage = 'crewposts';
	}
	$page = 'myposts';
	
	$QueryResult = @mysqli_query($dblink, $SQLString);
	$Row = mysqli_fetch_assoc($QueryResult);
	$num_rows = mysqli_num_rows($QueryResult);
				
	if($num_rows == 0){
		echo "<h2>You haven't created any posts yet.</h2>";
	}else {
		//display initial posts
		iu_get_posts($Rows, $Row, $QueryResult, '', $page, $subpage);
	}
?>

		</div><!-- #right -->
		<aside id="side">
			<div class="sideWrap sortby">
				<p>Sort by: </p>
				<p><a href="/myposts/newest">Most Recent</a></p>
				<p><a href="/myposts/oldest">Oldest</a></p>
				<? if(isset($Rows['crew_id'])){ //only show option if user is in a crew ?>
				<p><a href="/myposts/crewposts">Crew Posts</a></p>
				<? } ?>
			</div>
		</aside>
		<p class='load-more'>Load More</p>
	</div><!--#bottom-->

<?php include("scripts.php"); ?>
<script type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/js/myposts.js"></script>
</body>
</html>