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
	// logged out. set $Rows['user_id'] = '';
	$Rows = array('user_id' => '' );
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta content="width=device-width, user-scalable=yes" name="viewport" />
	<title>Import Underground | Top Posts</title>
	<meta name="keywords" content="Import Underground, Imports, Racing, Tuner, Cars, Car Meets, Underground, Street, Pictures, Events, Meets, Crews, Car Crews, JDM">
	<meta name="description" content="Import Underground is a social network for all auto enthusiasts. Create or join a crew and share your thoughts with people like you.">
	<link href="/stylesheets/car.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" type="image/png" href="/images/favicon.png">
</head>
<body class="topposts">
	
	<?php include("top.php"); ?>
    <div id="content">
    	
		<?php include("left.php"); ?>

		<div id="right">

	    	<div class="headertitle crewname">
	    		<h2>Top Posts</h2>
	    	</div>
			<?php
				//show posts
				$QueryResult = @mysqli_query($dblink, "SELECT username, blog_title, blog_message, user_id, id, likes, post_date FROM users INNER JOIN posts USING (user_id) WHERE (posts.crew_id is NULL AND posts.deleted = '0') ORDER BY likes DESC LIMIT 0, 6");
				// $QueryResult = @mysqli_query($dblink, "SELECT username, blog_title, blog_message, user_id, id, post_date FROM users LEFT OUTER JOIN posts ON users.user_id=posts.user_id LEFT OUTER JOIN likes on likes.id=posts.id WHERE (posts.crew_id is NULL AND posts.deleted = '0') ORDER BY id DESC LIMIT 0, 3");
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
		<p class='load-more'>Load More</p>
	</div>
<?php include("scripts.php"); ?>
<script type="text/javascript" src="/js/topposts.js"></script>
</body>
</html>