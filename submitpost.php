<?php
include("config.php");

/* This will tell us to display an error message */
$have_error = false;
$errmsg     = "";

//grab the location where the user posted/edited from (from normal post or from editing a post)
if(isset($_POST['from'])){
	$from = $_POST['from'];
}else {
	header("Location: /createpost?errmsg=error");
	exit;
}
if(empty($_POST['posttitle']) ){ //if no post title
	
	if($from == 'editpost'){
		$post_id = mysqli_real_escape_string($dblink, $_POST['post_id']);
		$post_url = mysqli_real_escape_string($dblink, $_POST['post_url']);
		header("Location: /editpost/$post_id/$post_url?errmsg=1");
	}else if($from == 'createpost'){
		header("Location: /createpost?errmsg=1");
	}
	exit;
}


if(isset($_COOKIE['login_cookie'])){ //if logged in
	// get user info
	$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
	$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);
	$submitPostQuery  = mysqli_query($dblink, "SELECT * FROM users WHERE username = '$username'");
	$submitPostRows = mysqli_fetch_assoc($submitPostQuery);
}else {
	header("Location: /");
	exit;
}

if(isset($_POST['crewid'])){ // if posting from crew post
	$crewid = mysqli_real_escape_string($dblink, $_POST['crewid']);

	$submitpotCrewQuery = mysqli_query($dblink, "SELECT * FROM crews WHERE crew_id = '$crewid'");
	$submitPostCrewRows = mysqli_fetch_assoc($submitpotCrewQuery);
	$crewurl = $submitPostCrewRows['crew_url'];

	//make sure user has permission to post to this crew
	if($submitPostRows['crew_id'] != $crewid || $_POST['category'] != 'general'){ // if user crew doesn't match or category not in general (trying to hack)
		
		if($from == 'editpost'){
			$post_id = mysqli_real_escape_string($dblink, $_POST['post_id']);
			$post_url = mysqli_real_escape_string($dblink, $_POST['post_url']);
			header("Location: /editpost/$post_id/$post_url?errmsg=2");
		}else if($from == 'createpost'){
			header("Location: /createpost?errmsg=2&crew=$crewid");
			exit;
		}
	}
}else {
	$crewid = 'NULL';
}


// Grab values from form in createpost/editpost.php
$category  = mysqli_real_escape_string($dblink, $_POST['category']);
$posttitle  = mysqli_real_escape_string($dblink, trim(htmlspecialchars_decode( htmlentities( $_POST['posttitle'], ENT_NOQUOTES, 'UTF-8', false ), ENT_NOQUOTES )));
$blogmessage = mysqli_real_escape_string($dblink, trim(htmlspecialchars_decode( htmlentities( $_POST['postmessage'], ENT_NOQUOTES, 'UTF-8', false ), ENT_NOQUOTES )));	
// $blogmessage = iu_linkusername($blogmessage); // replace @username replies with anchor text (doing this on output now)

//sanitize other html tags that user might have included
$posttitle = strip_tags($posttitle);
$blogmessage = strip_tags($blogmessage, '<p><a><br><ul><ol><li><img><b><i><u><blockquote><strike><hr>'); //used for CLeditor (not used in CKeditor)
// $blogmessage = iu_convert_video_links($blogmessage); //convert youtube and vimeo links to video embeds (doing this on output now)
// $blogmessage = iu_convert_image_links($blogmessage); //convert images to link to source file (doing this on output now)

if (isset($_POST['submitpost'])) { //============= IF USER SUBMITS BLOG POST ===============================

	$post_date = date("Y-m-d H:i:s"); //get current datetime value. i.e. '2013-10-22 14:45:00'

	//make sure it's the correct user posting it by checking their session_hash (security check)
	if($session_hash == $submitPostRows['session_hash']){

		// //check if user posted from a crew post
		if(!isset($_POST['crewid'])){ //if user didn't post from a crew
			$submitPostDB = mysqli_query($dblink, "INSERT INTO posts(user_id, blog_title, blog_message, category, post_date, post_updated) VALUES('$submitPostRows[user_id]','$posttitle', '$blogmessage', '$category','$post_date','$post_date')");
		}else {
			//add user and post to posts table
			$submitPostDB = mysqli_query($dblink, "INSERT INTO posts(user_id, blog_title, blog_message, category, crew_id, post_date, post_updated) VALUES('$submitPostRows[user_id]','$posttitle', '$blogmessage', '$category', '$crewid','$post_date','$post_date')");
		}

		//add +1 total posts for user
		$submitPostTotal = mysqli_query($dblink, "UPDATE users SET total_posts=total_posts+1 WHERE user_id={$submitPostRows['user_id']}");
		
		if(!isset($_POST['crewid'])){ //if user didn't post from crew
			//add +1 total posts to category
			$submitPostCatTotal = mysqli_query($dblink, "UPDATE categories SET num_posts=num_posts+1 WHERE category='$category'");
		}

		//get recently added post id and blog_title
		$submitGetPostQuery = mysqli_query($dblink, "SELECT id, blog_title FROM posts WHERE blog_title = '$posttitle'");
		$submitGetPostRows = mysqli_fetch_assoc($submitGetPostQuery);
		$newpostid = $submitGetPostRows['id'];
		$newposttitle = iu_cleaner_url($submitGetPostRows['blog_title']);

		// send notification to user(s) if you mentioned them in a post
		iu_mentionusername($blogmessage, $submitPostRows['user_id'], '/post/'.$newpostid.'/'.$newposttitle);

	}

}else if(isset($_POST['editsubmitblog'])){  //============= IF USER EDITS BLOG POST =================================	

	$updated_date = date("Y-m-d H:i:s");

	//make sure it's the correct user posting it by checking their session_hash (security check)
	if($session_hash == $submitPostRows['session_hash']){
		$post_id = mysqli_real_escape_string($dblink, $_POST['post_id']);

		//check if user put this post in a new category
		$checkCatQuery = mysqli_query($dblink, "SELECT category FROM posts WHERE id = '$post_id'");
		$r1 = mysqli_fetch_assoc($checkCatQuery);
		if($category != $r1['category']){ // if new category is not the same as the old category

			$oldcategory = $r1['category'];
			$newcategory = $category;

			//subtract -1 total posts to category that it was in before
			$subtractPostCatTotal = mysqli_query($dblink, "UPDATE categories SET num_posts=num_posts-1 WHERE category='$oldcategory'");
			//add +1 total posts to new category
			$addPostCatTotal = mysqli_query($dblink, "UPDATE categories SET num_posts=num_posts+1 WHERE category='$newcategory'");

			//grab the number of comments from the post
			$numCommentsQuery = mysqli_query($dblink, "SELECT COUNT(*) AS numcomments FROM comments USE INDEX (user_id) WHERE post_id='$post_id'");
			$numCommentsRow = mysqli_fetch_assoc($numCommentsQuery);
			$numComments = $numCommentsRow['numcomments'];

			//subtract -n total comments to category that it was in before
			$subtractPostCatTotal = mysqli_query($dblink, "UPDATE categories SET num_comments=num_comments-$numComments WHERE category='$oldcategory'");
			//add +n total comments to new category.
			$addPostCatTotal = mysqli_query($dblink, "UPDATE categories SET num_comments=num_comments+$numComments WHERE category='$newcategory'");

			//default $category back to $_POST['category']
			// $category  = mysqli_real_escape_string($dblink, $_POST['category']);
		}

		//Update the post in db
        $r2 = mysqli_query($dblink, "UPDATE posts SET blog_title='$posttitle', blog_message='$blogmessage', category='$category', post_updated='$updated_date' WHERE id = '$post_id'");
    }
}

if(isset($_POST['crewid'])){
	header("Location: /crew/$crewurl");
	exit;
}else {
	header("Location: /posts/$category");
	exit;
}

?>