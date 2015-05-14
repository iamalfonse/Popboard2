<?php
	include("config.php");

	//get username for reference
	if(isset( $_COOKIE['login_cookie'])){
		$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));

	    $r  = mysqli_query($dblink, "SELECT user_id,group_id FROM users WHERE username='$username'");
		$Rows = mysqli_fetch_assoc($r);
	}else {
		// logged out. set $Rows['user_id'] = '';
		$Rows = array('user_id' => '' );
		$Rows = array('group_id' => '' );
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

	//grab groupurl if it has a groupurl
	$groupurl = '';
	if(isset($_GET['groupurl'])){
		$groupurl = mysqli_real_escape_string($dblink, $_GET['groupurl']);
	}

	//grab myposts if in myposts section
	$myposts = '';
	if(isset($_GET['myposts'])){
		$myposts = mysqli_real_escape_string($dblink, $_GET['myposts']);
	}

	if(isset($_GET['category'])){
		//get posts from category
		$QueryResult = @mysqli_query($dblink, "SELECT username, blog_title, blog_message, user_id, id, post_date FROM users INNER JOIN posts USING (user_id) WHERE (category = '$category' AND posts.group_id is NULL AND posts.deleted='0') ORDER BY id DESC LIMIT $start, $limit");
	}else if(isset($_GET['groupurl'])) {
		//get posts from group
		if($groupurl != ''){
			//get group_id of groupurl
			$groupPostQuery = @mysqli_query($dblink, "SELECT * FROM groups WHERE group_url='$groupurl'");
			$groupPostRow = mysqli_fetch_assoc($groupPostQuery);
			$groupid = $groupPostRow['group_id'];
			$isprivate = $groupPostRow['private'];
		}
		if($isprivate=='1' && $Rows['group_id']!=$groupid){ //if group is private and it's not your group
			// This group is Private. Only group members can see the posts
			return;
		}else{
			$QueryResult = @mysqli_query($dblink, "SELECT username, blog_title, blog_message, user_id, id, post_date FROM users INNER JOIN posts USING (user_id) WHERE (posts.group_id = '$groupid' AND posts.deleted = '0') ORDER BY id DESC LIMIT $start, $limit");
		}
	}else if(isset($_GET['myposts'])){

		if($myposts == 'newest' || $myposts == ''){
			$QueryResult = @mysqli_query($dblink, "SELECT username, blog_title, blog_message, user_id, id, post_date FROM users INNER JOIN posts USING (user_id) WHERE (username = '$username' AND posts.deleted='0') AND posts.group_id IS NULL ORDER BY id DESC LIMIT $start, $limit");
			$subpage = '';
		}else if($myposts == 'oldest'){
			$QueryResult = @mysqli_query($dblink, "SELECT username, blog_title, blog_message, user_id, id, post_date FROM users INNER JOIN posts USING (user_id) WHERE (username = '$username' AND posts.deleted='0') AND posts.group_id IS NULL ORDER BY id ASC LIMIT $start, $limit");
			$subpage = '';
		}else if($myposts == 'groupposts'){
			$QueryResult = @mysqli_query($dblink,"SELECT username, displayname, email, lvl, blog_title, blog_message, user_id, id, post_date FROM users INNER JOIN posts USING (user_id) WHERE (username = '$username' AND deleted='0') AND (posts.group_id='{$Rows['group_id']}' AND posts.category='general') ORDER BY id DESC LIMIT $start, $limit");
			$subpage = 'groupposts';
		}
		$page = 'myposts';
	}else { //grab all posts (used for top posts)
		$QueryResult = @mysqli_query($dblink, "SELECT username, blog_title, blog_message, user_id, id, likes, post_date FROM users INNER JOIN posts USING (user_id) WHERE (posts.group_id is NULL AND posts.deleted = '0') ORDER BY likes DESC LIMIT  $start, $limit");
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



