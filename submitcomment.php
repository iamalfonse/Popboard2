<?php
include("config.php");

$titleurl = mysqli_real_escape_string($dblink, $_POST['title']);
$comment = strip_tags(mysqli_real_escape_string($dblink, $_POST['comment']));
$comment = iu_linkusername($comment); // replace @username replies with anchor text
$postid_num = mysqli_real_escape_string($dblink, $_POST['postid_num']);

if(isset($_POST['submitcomment']) && $_POST['comment'] != ''){
	//grab user name
	$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
	$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);
    $qresult2  = mysqli_query($dblink, "SELECT user_id, session_hash FROM users WHERE username='$username'");
	$Rows2 = mysqli_fetch_assoc($qresult2);
	
	//================ PROCESS COMMENT INPUT ===============//
	if($session_hash == $Rows2['session_hash']){
		//get id of post to insert into comments
		$QueryResult4 = @mysqli_query($dblink, "SELECT id, user_id, category FROM posts WHERE id = $postid_num");
		$Row4 = mysqli_fetch_assoc($QueryResult4);
		$urlid = $Row4['id'];
		if($Row4['category']=='' || $Row4['category']==NULL){
			$category = 'general';
		}else {
			$category = $Row4['category'];
		}
		
		//get user id of logged in person to insert into comments
		$QueryResult5 = @mysqli_query($dblink, "SELECT user_id FROM users WHERE username = '$username' AND session_hash = '$session_hash'");
		$Row5 = mysqli_fetch_assoc($QueryResult5);
		$user_id = $Row5['user_id'];
		
		$comment_date = date("Y-m-d H:i:s");
		
		//Insert comment from logged in user using their Id into the comments table using the post id
		$QueryResult2 = @mysqli_query($dblink, "INSERT INTO comments(usrname, post_id, user_id, comment_post, comment_date) VALUES ('$username', '$postid_num', '$user_id', '$comment', '$comment_date')");
		$submitCommentTotal = mysqli_query($dblink, "UPDATE users SET total_comments=total_comments+1 WHERE username = '$username'");
		
		//add +1 num_comments to category
		$submitCommentCatTotal = mysqli_query($dblink, "UPDATE categories SET num_comments=num_comments+1 WHERE category = '$category'");
	
		//add +1 comments to this post in the posts table and update the post_updated date
		$submitCommentPostTotal = mysqli_query($dblink, "UPDATE posts SET comments=comments+1 WHERE id = '$postid_num'");

		// send notification to user of post
		iu_send_notification($Row5['user_id'], $Row4['user_id'], 'comment', '/post/'.$postid_num.'/'.$titleurl);
		
	}
	

	header("Location: /post/$postid_num/$titleurl");
}

?>