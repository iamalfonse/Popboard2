<?php
include("config.php");

/* This will tell us to display an error message */
$have_error = false;
$errmsg     = "";

if (isset($_POST['submitpost'])) { //============= IF USER SUBMITS BLOG POST ===============================

	// get user info
	$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
	$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);
	$submitPostQuery  = mysqli_query($dblink, "SELECT user_id, session_hash FROM users WHERE username='$username'");
	$submitPostRows = mysqli_fetch_assoc($submitPostQuery);

    // Grab values from form in createpost.php
	$category  = mysqli_real_escape_string($dblink, $_POST['category']);
	$posttitle  = mysqli_real_escape_string($dblink, trim(htmlspecialchars_decode( htmlentities( $_POST['posttitle'], ENT_NOQUOTES, 'UTF-8', false ), ENT_NOQUOTES )));
	$blogmessage = mysqli_real_escape_string($dblink, trim(htmlspecialchars_decode( htmlentities( $_POST['postmessage'], ENT_NOQUOTES, 'UTF-8', false ), ENT_NOQUOTES )));	
	$blogmessage = iu_linkusername($blogmessage); // replace @username replies with anchor text

	if(empty($_POST['posttitle']) || empty($blogmessage)){
		$errmsg = "Title and Message must not be empty.";
		header("Location: /createpost?errmsg=$errmsg");
		exit;
	}

	//sanitize other html tags that user might have included
	$posttitle = strip_tags($posttitle);
	$blogmessage = strip_tags($blogmessage, '<p><a><br><ul><ol><li><img><b><i><u><blockquote><strike><hr>'); //used for CLeditor (not used in CKeditor)
	$blog_original = $blogmessage; //used to store original text for editing
	$blogmessage = iu_convert_video_links($blogmessage); //convert youtube and vimeo links to video embeds
	$blogmessage = iu_convert_image_links($blogmessage); //convert images to link to source file

	$post_date = date("Y-m-d H:i:s"); //get current datetime value. i.e. '2013-10-22 14:45:00'

	//make sure it's the correct user posting it by checking their session_hash (security check)
	if($session_hash == $submitPostRows['session_hash']){
		//add user and post to DB
		$submitPostDB = mysqli_query($dblink, "INSERT INTO posts(user_id, blog_title, blog_message, blog_original, category, post_date, post_updated) VALUES('$submitPostRows[user_id]','$posttitle', '$blogmessage', '$blog_original', '$category', '$post_date','$post_date')");
		$submitPostTotal = mysqli_query($dblink, "UPDATE users SET total_posts=total_posts+1 WHERE user_id={$submitPostRows['user_id']}");
	}

}else if(isset($_POST['editsubmitblog'])){  //============= IF USER EDITS BLOG POST =================================

	// get user info
    $username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
	$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);
    $submitPostQuery  = mysqli_query($dblink, "SELECT session_hash FROM users WHERE username='$username'");
	$submitPostRows = mysqli_fetch_assoc($submitPostQuery);

	//get values from editpost.php
    $category  = mysqli_real_escape_string($dblink, $_POST['category']);
    $posttitle  = mysqli_real_escape_string($dblink, trim(htmlspecialchars_decode( htmlentities( $_POST['posttitle'], ENT_NOQUOTES, 'UTF-8', false ), ENT_NOQUOTES )));
	$blogmessage = mysqli_real_escape_string($dblink, trim(htmlspecialchars_decode( htmlentities( $_POST['postmessage'], ENT_NOQUOTES, 'UTF-8', false ), ENT_NOQUOTES )));
	$blogmessage = iu_linkusername($blogmessage); // replace @username replies with anchor text
	
	if(empty($_POST['posttitle']) || empty($blogmessage)){
		$errmsg = "Title and Message must not be empty.";
		header("Location: /createpost?errmsg=$errmsg");
		exit;
	}

	$posttitle = strip_tags($posttitle);
	$blogmessage = strip_tags($blogmessage, '<p><a><br><ul><ol><li><img><b><i><u><blockquote><strike><hr>'); //used for CLeditor (not used in CKeditor)
	$blog_original = $blogmessage; //used to store original text for editing
	$blogmessage = iu_convert_video_links($blogmessage); //convert youtube and vimeo links to video embeds
	$blogmessage = iu_convert_image_links($blogmessage); //convert images to link to source file

	$updated_date = date("Y-m-d H:i:s");

	//make sure it's the correct user posting it by checking their session_hash (security check)
	if($session_hash == $submitPostRows['session_hash']){
		$post_id = mysqli_real_escape_string($dblink, $_POST['post_id']);
		//Update post in db
        $r1 = mysqli_query($dblink, "UPDATE posts SET blog_title='$posttitle', blog_message='$blogmessage', blog_original='$blog_original', category='$category', post_updated='$updated_date' WHERE id = '$post_id'");
    }


}

header("Location: /posts/$category");
exit;
	


?>