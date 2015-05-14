<?php
		include("config.php");

		$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
		$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);

		$postid_num = mysqli_real_escape_string($dblink, $_GET['post_id']);
		
		//================ Get user session hash for security check ===============//
	    $r  = mysqli_query($dblink, "SELECT session_hash, user_id FROM users WHERE username='$username'");
		$Rows = mysqli_fetch_assoc($r);
		
		if($session_hash == $Rows['session_hash']){ //make sure user is logged in

			//Make sure that this is the user who made the post
			$deletePostQuery = mysqli_query($dblink, "SELECT user_id FROM posts WHERE username='$username'");
			$deletePostRows = mysqli_fetch_assoc($deletePostQuery);

			if($deletePostRows['user_id'] == $Rows['user_id']){ // if logged in user wrote the post
				//-------DELETE THE POST WITH PROPER ID ----------------
				// $QueryResult = @mysqli_query($dblink, "DELETE FROM posts WHERE id = '$postid_num'");
				$QueryResult = @mysqli_query($dblink, "UPDATE posts SET deleted='1' WHERE id = '$postid_num'");
				
				//------- DELETE THE COMMENTS ASSOCIATED WITH THAT POST USING POST_ID ----------//
				// $QueryResult = @mysqli_query($dblink, "DELETE FROM comments WHERE post_id = '$postid_num'");

				//------- DELETE THE LIKES ASSOCIATED WITH THAT POST USING POST_ID ----------//
				// $QueryResult = @mysqli_query($dblink, "DELETE FROM likes WHERE post_id = '$postid_num'");
			
			}else { // some hacker is trying to delete other people's posts
				header("Location: /myposts?errmsg=1");
			}

			
		}	
		
		header("Location: /myposts"); 
	
?>
