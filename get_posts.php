<?php
	include("config.php");

	//get username for reference
	if(isset( $_COOKIE['login_cookie'])){
		$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));

	    $r  = mysqli_query($dblink, "SELECT user_id,crew_id FROM users WHERE username='$username'");
		$Rows = mysqli_fetch_assoc($r);
	}else {
		// logged out. set $Rows['user_id'] = '';
		$Rows = array('user_id' => '' );
		$Rows = array('crew_id' => '' );
	}
	
	
	$start = mysqli_real_escape_string($dblink, $_GET['start']);
	$limit = mysqli_real_escape_string($dblink, $_GET['limit']);
	$page = '';
	$subpage = '';

	//grab category name if in a category
	$category = '';
	if(isset($_GET['category'])){
		$category = mysqli_real_escape_string($dblink, $_GET['category']);
	}

	//grab crewurl if it has a crewurl
	$crewurl = '';
	if(isset($_GET['crewurl'])){
		$crewurl = mysqli_real_escape_string($dblink, $_GET['crewurl']);
	}

	//grab myposts if in myposts section
	$myposts = '';
	if(isset($_GET['myposts'])){
		$myposts = mysqli_real_escape_string($dblink, $_GET['myposts']);
	}

	if(isset($_GET['category'])){
		//get posts from category
		$QueryResult = @mysqli_query($dblink, "SELECT username, blog_title, blog_message, user_id, id, post_date FROM users INNER JOIN posts USING (user_id) WHERE (category = '$category' AND posts.crew_id is NULL AND posts.deleted='0') ORDER BY id DESC LIMIT $start, $limit");
	}else if(isset($_GET['crewurl'])) {
		//get posts from crew
		if($crewurl != ''){
			//get crew_id of crewurl
			$crewPostQuery = @mysqli_query($dblink, "SELECT * FROM crews WHERE crew_url='$crewurl'");
			$crewPostRow = mysqli_fetch_assoc($crewPostQuery);
			$crewid = $crewPostRow['crew_id'];
			$isprivate = $crewPostRow['private'];
		}
		if($isprivate=='1' && $Rows['crew_id']!=$crewid){ //if crew is private and it's not your crew
			// This crew is Private. Only crew members can see the posts
			return;
		}else{
			$QueryResult = @mysqli_query($dblink, "SELECT username, blog_title, blog_message, user_id, id, post_date FROM users INNER JOIN posts USING (user_id) WHERE (posts.crew_id = '$crewid' AND posts.deleted = '0') ORDER BY id DESC LIMIT $start, $limit");
		}
	}else if(isset($_GET['myposts'])){

		if($myposts == 'newest' || $myposts == ''){
			$QueryResult = @mysqli_query($dblink, "SELECT username, blog_title, blog_message, user_id, id, post_date FROM users INNER JOIN posts USING (user_id) WHERE (username = '$username' AND posts.deleted='0') AND posts.crew_id IS NULL ORDER BY id DESC LIMIT $start, $limit");
			$subpage = '';
		}else if($myposts == 'oldest'){
			$QueryResult = @mysqli_query($dblink, "SELECT username, blog_title, blog_message, user_id, id, post_date FROM users INNER JOIN posts USING (user_id) WHERE (username = '$username' AND posts.deleted='0') AND posts.crew_id IS NULL ORDER BY id ASC LIMIT $start, $limit");
			$subpage = '';
		}else if($myposts == 'crewposts'){
			$QueryResult = @mysqli_query($dblink,"SELECT username, displayname, email, lvl, blog_title, blog_message, user_id, id, post_date FROM users INNER JOIN posts USING (user_id) WHERE (username = '$username' AND deleted='0') AND (posts.crew_id='{$Rows['crew_id']}' AND posts.category='general') ORDER BY id DESC LIMIT $start, $limit");
			$subpage = 'crewposts';
		}
		$page = 'myposts';
	}else { //grab all posts (used for top posts)
		$QueryResult = @mysqli_query($dblink, "SELECT username, blog_title, blog_message, user_id, id, likes, post_date FROM users INNER JOIN posts USING (user_id) WHERE (posts.crew_id is NULL AND posts.deleted = '0') ORDER BY likes DESC LIMIT  $start, $limit");
	}
	
	$Row = mysqli_fetch_assoc($QueryResult);
	$num_rows = mysqli_num_rows($QueryResult);
	
	if($num_rows == 0){
		$start = $start-6;
		return $start;
	}else {
		
		iu_get_posts($Rows, $Row, $QueryResult, '', $page, $subpage);
		
	}
?>



