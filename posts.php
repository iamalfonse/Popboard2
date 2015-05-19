<?
include("config.php");

//grab category name if in a category
$category = mysqli_real_escape_string($dblink, $_GET['category']);
$catQuery  = mysqli_query($dblink, "SELECT category,cat_displayname FROM categories WHERE category='$category'");
$catRows = mysqli_fetch_assoc($catQuery);
$catname = $catRows['category'];
$catDisplayname = $catRows['cat_displayname'];


if (isset( $_COOKIE['login_cookie'] )) { //if user is logged in
	$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
	$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);

	iu_check_user_setup(); //make sure user has finished setup of avatar

	/* GET LOGGED IN USER INFORMATION*/
	$r  = mysqli_query($dblink, "SELECT * FROM users WHERE username='$username' AND session_hash = '$session_hash'");
	$Rows = mysqli_fetch_assoc($r);
}else {
	// logged out. set $Rows['user_id'] = '';
	$Rows = array('user_id' => '' );
}

$pageurl = iu_get_page_url();
if($pageurl == '/posts/' || $pageurl == '/posts' ){
	header('Location: /posts/general');
	exit;
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta content="width=device-width, user-scalable=yes" name="viewport" />
	<title>Import Underground | <?= $catDisplayname ?></title>
	<meta name="keywords" content="Import Underground, Imports, Racing, Tuner, Cars, Car Meets, Underground, Street, Pictures, Events, Meets, Groups, Car Groups">
	<meta name="description" content="Import Underground | <?= $catDisplayname ?> ">
	<link href="/stylesheets/car.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" type="image/png" href="/images/favicon.png">
</head>

<body class='posts'>
	<input type="hidden" id="category" value="<?= $category?>">
	<?php include("top.php"); ?>

	<div id="content">
		<?php include("left.php"); ?>

	    <div id="right">

	    	<div class="headertitle">
	    		<h2><?= $catDisplayname ?></h2>
	    	</div>
			<?php
				//show posts
				$QueryResult = @mysqli_query($dblink, "SELECT username, blog_title, blog_message, user_id, id, post_date FROM users INNER JOIN posts USING (user_id) WHERE (category = '$category' AND posts.group_id is NULL AND posts.deleted = '0') ORDER BY id DESC LIMIT 0, 6");
				$Row = mysqli_fetch_assoc($QueryResult);
				$num_rows = mysqli_num_rows($QueryResult);
				
				if($num_rows == 0){
					echo "<h2>Sorry, there are no posts here.</h2>";
				}else {
					//display initial posts
					iu_get_posts($Rows, $Row, $QueryResult);
				}
			?>
		</div><!-- #right -->
		<aside id="side">
			<div class="sideWrap sidePost">
			<? if(isset($_COOKIE['login_cookie'])){ ?>
			
				<h3><?= $catDisplayname ?></h3>
				<a class="submitbtn createpostbtn" href="/createpost?c=<?= $catname ?>">Create New Post</a>
			
			<? }else { ?>
				<p>You must be logged in to post</p>
				<a class="submitbtn" href="/signin?c=<?= $catname ?>">Log in</a>
			<? } ?>
			</div>
		</aside>
		<p class='load-more'>Load More</p>
	</div><!-- #bottom -->
<? include("scripts.php"); ?>
<script type="text/javascript" src="/js/posts.js"></script>
<? include("createpost.php"); ?>
<? 
	// Only show this if it's the first time someone signed up
	if(isset($_GET['start'])){
?>
	<script type="text/javascript">
	$(document).ready(function(){
		$('.startOverlay').css({'height': $(document).height()});
		$('.startOverlay .nextstep').click(function(){
			$('.step1').hide();
			$('.step2').show();
		});
		$('.startOverlay .getstarted').click(function(){
			$('.startOverlay').fadeOut(500);
		});
	});
	</script>
	<div class="startOverlay">
		<div class="startWrap">
			<div class="step1">
				<h1>Hi <?= $Rows['displayname'] ?>!</h1>
				<h2>Welcome to Import Underground</h2>
				<p>Create and share your posts with other auto enthusiasts just like you. Check out some of the other categories, join a group or create your own, see the top posts, or look for upcoming events near you.</p>
				<button class="btn nextstep">Next</button>
			</div>
			<div class="step2 help1">
				<div class="arrow"></div>
				<p>Create your first post or view your profile.</p>
			</div>
			<div class="step2 help2">
				<div class="arrow"></div>
				<p>Check out or join some of the groups or see the top posts from others.</p>
			</div>
			<div class="step2 help3">
				<div class="arrow"></div>
				<p>Level up by creating posts and getting likes.</p>
				<ul>
					<li>Each post you create is worth 10XP</li>
					<li>Each like you get gets you 1XP</li>
					<li>Each comment you make gets you 2XP</li>
				</ul>
				<p>Unlock extra options by leveling up.</p>
			</div>
			<div class="step2 help4">
				<p>I'm Ready!</p>
				<button class="btn getstarted">Get Started</button>
			</div>
		</div>
	</div>
<?	} ?>
</body>
</html>