<?php
//echo "awesome";

include("config.php");

	
	if(isset( $_COOKIE['login_cookie'])){ // logged in
		//get username for reference
		$likesUsername = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
		$likesUserhash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);
	}else { // not logged in
		$likesUsername = '';
		$likesUserhash = '';
	}
	
	//grab the post id from the blog post
	$likesPostid = mysqli_real_escape_string($dblink, $_GET['postid']);

	//get the user id and session_hash to cross check if the user has liked the post before
	$likesQuery = @mysqli_query($dblink, "SELECT user_id, session_hash FROM users WHERE username = '$likesUsername'");
	$likesRow = mysqli_fetch_assoc($likesQuery);

	//get the correct post to like
	$likesQuery2 = @mysqli_query($dblink, "SELECT * FROM likes WHERE user_id='{$likesRow['user_id']}' AND likes.post_id = '$likesPostid';");
	$likesRow2 = mysqli_fetch_assoc($likesQuery2);
	$likesNumrows2 = mysqli_num_rows($likesQuery2);

	//get the correct user of post (for notifications)
	$likesQuery5 = @mysqli_query($dblink, "SELECT id, user_id, blog_title FROM posts WHERE posts.id='$likesPostid';");
	$likesRow5 = mysqli_fetch_assoc($likesQuery5);

	//if there's no record and session_hash matches (security check)
	if(!$likesRow2['user_id'] && ($likesUserhash == $likesRow['session_hash']) && isset($_COOKIE['login_cookie'])){
		
		//if no record, then:
		//add like to likes table
		$likesQuery3 = @mysqli_query($dblink, "INSERT INTO likes(user_id,post_id, post_user_id,liked) VALUES ('{$likesRow['user_id']}','$likesPostid','{$likesRow5['user_id']}','1')");	

		//add +1 like to posts table
		$likesQuery6 = @mysqli_query($dblink, "UPDATE posts SET likes=likes+1 WHERE posts.id = '$likesPostid'");

		//add +1 like to post_user_id
		$likesQuery5 = @mysqli_query($dblink, "UPDATE users SET total_likes=total_likes+1 WHERE users.user_id = {$likesRow5['user_id']}");

		//get num of likes
		$likesQuery4 = @mysqli_query($dblink, "SELECT likes FROM posts WHERE id = '$likesPostid'");
		$likesNumrows4 = mysqli_fetch_assoc($likesQuery4);

		if($likesRow['user_id'] != $likesRow5['user_id']){ // only send notification if it's not your own post
			// send notification to user of post
			$posturl = iu_cleaner_url($likesRow5['blog_title']);
			iu_send_notification($likesRow['user_id'], $likesRow5['user_id'], 'like', '/post/'.$likesPostid.'/'.$posturl);
		}

		echo "<span class='liked'>Likes</span> ".$likesNumrows4['likes'];
	}else {

		if(isset($_COOKIE['login_cookie'])){ //if loggeed in
			$liked = false;
			// check to see if you liked the post before
			if($likesNumrows2 > 0 && $likesRow2['liked'] == 0){
				//update table to like your post again
				$likesQuery3 = @mysqli_query($dblink, "UPDATE likes SET liked=liked+1 WHERE user_id='{$likesRow['user_id']}' AND likes.post_id = '$likesPostid';");
			
				//add +1 like to posts table again
				$likesQuery6 = @mysqli_query($dblink, "UPDATE posts SET likes=likes+1 WHERE posts.id = '$likesPostid'");

				//add +1 like to post_user_id again
				$likesQuery5 = @mysqli_query($dblink, "UPDATE users SET total_likes=total_likes+1 WHERE users.user_id = {$likesRow5['user_id']}");
				$liked = true;
			}else{
				//you already liked a post so unlike your like
				$likesQuery3 = @mysqli_query($dblink, "UPDATE likes SET liked=liked-1 WHERE user_id='{$likesRow['user_id']}' AND likes.post_id = '$likesPostid';");
			
				//subtract -1 like from posts table
				$likesQuery6 = @mysqli_query($dblink, "UPDATE posts SET likes=likes-1 WHERE posts.id = '$likesPostid'");

				//subtract -1 like from post_user_id
				$likesQuery5 = @mysqli_query($dblink, "UPDATE users SET total_likes=total_likes-1 WHERE users.user_id = {$likesRow5['user_id']}");
				
				$liked = false;
			}

		}
		// show num of likes
		$likesQuery4 = @mysqli_query($dblink, "SELECT likes FROM posts WHERE id = '$likesPostid'");
		$likesNumrows4 = mysqli_fetch_assoc($likesQuery4);
		if($liked == true){
			echo "<span class='liked'>Likes</span> ".$likesNumrows4['likes'];
		}else {
			echo "<span class=''>Likes</span> ".$likesNumrows4['likes'];
		}
		
		
	}


?>