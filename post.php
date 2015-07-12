<?php 
include("config.php"); 

if (isset( $_COOKIE['login_cookie'] )) {
	$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
	$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);

	iu_check_user_setup(); //make sure user has finished setup

	/* GET LOGGED IN USER INFORMATION*/
	$query  = mysqli_query($dblink, "SELECT * FROM users WHERE username='$username' AND session_hash = '$session_hash'");
	$Rows = mysqli_fetch_assoc($query);
}else {
	// logged out. set 
	$Rows['user_id'] = '';
}


/* This will tell us to display an error message */
$have_error = false;
$errmsg     = "";

$postid = mysqli_real_escape_string($dblink, $_GET['post_id']);
$titleurl = mysqli_real_escape_string($dblink, $_GET['title']);
//update the view count. +1 to views
iu_update_views($postid);

//================ SHOW POST ===============//
$QueryResult = @mysqli_query($dblink, "SELECT username, email, displayname, lvl, blog_title, blog_message, category, user_id, id, post_date,views, deleted FROM users INNER JOIN posts USING (user_id) WHERE id = '$postid'");
$Row = mysqli_fetch_assoc($QueryResult);
$postusername = $Row['displayname'];
$blogtitle = iu_cleaner_url($Row['blog_title']);

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta content="width=device-width, user-scalable=yes" name="viewport" />
	<link href="/stylesheets/<?= $stylesheet; ?>.css?<?= $__site['filedate']; ?>" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" type="image/png" href="/images/favicon.png?<?= $__site['filedate']; ?>">
	<link rel="canonical" href="<?= $__site['url']; ?>/post/<?= $Row['id']; ?>/<?= $blogtitle; ?>" />

	<title> <?= $Row['blog_title']; ?> | <?= $__site['name']; ?></title>
	<meta name="keywords" content="<?= $__site['keywords']; ?>">
	<meta name="description" content="<?= $__site['name']; ?> | <?= $Row['blog_title']; ?> ">


	<!-- facebook -->
	<meta property="og:title" content="<?= $Row['blog_title']; ?> | <?= $__site['name']; ?>" />
	<meta property="og:url" content="<?= $__site['url']; ?>/post/<?= $Row['id']; ?>/<?= $blogtitle; ?>" />
	<meta property="og:site_name" content="<?= $__site['name']; ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:image" content="<?= $__site['url']; ?>/images/og-image.gif" />
	<meta property="og:image:url" content="<?= $__site['url']; ?>/images/og-image.gif" />
	<meta property="og:image:secure_url" content="<?= $__site['url']; ?>/images/og-image.gif" />
	<meta property="og:image:type" content="image/gif" />
	<meta property="og:image:width" content="1500" />
	<meta property="og:image:height" content="1500" />
	<meta property="og:description" content="<?= iu_convert_image_links(iu_read_more($Row['blog_message'], 200, '...Read More</a>')); ?>" />

	<!-- twitter -->
	<meta name="twitter:account_id" content="<?= $__site['twitter-url']; ?>" />
	<meta name="twitter:card" content="summary" />
	<meta name="twitter:site" content="@<?= strtolower($__site['name']); ?>" />
	<meta name="twitter:title" content="<?= $Row['blog_title']; ?> | <?= $__site['name']; ?>" />
	<meta name="twitter:description" content="<?= iu_convert_image_links(iu_read_more($Row['blog_message'], 200, '...Read More</a>')); ?>" />
	<meta name="twitter:image:src" content="https://recurly.com/img/og-image.gif" />
	<meta name="twitter:image:width" content="1500" />
	<meta name="twitter:image:height" content="1500" />
</head>
<body class='comments'>

	<?php include("top.php"); ?>

	<div id="content">

		<?php include("left.php"); ?>

		<div id='right'>

		<?	
		// If this post has been deleted by the post user
		if($Row['deleted']=='1'){ //
			echo "<h2>Uh oh! This post has been deleted.</h2>";
		}else{ 
		?>
			<div class='postitem'>
				<div class="postuser">
					<a href="/profile/<?= $Row['username'] ?>"><? iu_get_avatar_pic($Row['email']); ?></a>
					<div class="lvl">
						<p>Lvl <?= trim($Row['lvl']); ?> </p>
					</div>
				</div>
				<div class="postcontent-wrap">
					<div class='posttop'>
						<h3 class='posttitle' id="<?= $Row['id']; ?>"><?= $Row['blog_title']; ?></h3>
						<p class='username'>Posted by: <a href="/profile/<?= $Row['username']; ?>"><?= $postusername; ?></a> <?= iu_time_elapsed_string($Row['post_date']); ?></p>
					</div>
					<div class='postbottom'>
						<div class='blogmessage'><?= iu_convert_image_links(iu_convert_video_links(iu_linkusername($Row['blog_message']))) ?></div>
						<div class='counters'>
							<? 
							//get the number of likes for a post
							$getPostsQuery3 = @mysqli_query($dblink, "SELECT likes FROM posts WHERE id = '{$Row['id']}'");
							$getPostsRow3 = mysqli_fetch_assoc($getPostsQuery3);
							
							//used to check if user has already liked a post
							$getPostsQuery4 = @mysqli_query($dblink, "SELECT user_id,liked FROM likes WHERE likes.user_id = '{$Rows['user_id']}' AND likes.post_id = '{$Row['id']}'");
							$getPostsRow4 = mysqli_fetch_assoc($getPostsQuery4);

							//show likes and determine if user liked the post already and user is logged in
							if($getPostsRow4['user_id'] == $Rows['user_id'] && isset($_COOKIE['login_cookie']) && $getPostsRow4['liked'] == 1 ){
								echo "<div class='likes'><span class='liked icon-favorite'></span> {$getPostsRow3['likes']}</div>";
							}else{
								echo "<div class='likes'><span class='icon-favorite'></span> {$getPostsRow3['likes']}</div>";
							}

							
							?>
						</div>
					</div>
				</div>
				
				<div class="sharepost"> 
					<p>Share This Post  <a href="https://twitter.com/share" class="twitter-share-button" data-lang="en">Tweet</a></p>
					<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
				</div>
			</div>

	<?
		//================ OUTPUT COMMENTS ===============//
		$QueryResult3 = @mysqli_query($dblink, "SELECT * FROM comments WHERE post_id = '$postid'");
		$Row3 = mysqli_fetch_assoc($QueryResult3);
		$numrows = mysqli_num_rows($QueryResult3);
		
		//if there's at least one comment, display comments
		if($numrows >= 1){
			//echo "<div id='commentstop'><h3>$numrows Comment". ($numrows == 1 ? '' : 's') ."</h3></div>";
			echo "<div id='commentstop'><h3>". iu_plural($numrows, 'Comment') ."</h3></div>";
			echo "<div id='commentsarea'>";
			
			do {
				$QueryResult2 = @mysqli_query($dblink, "SELECT id, username, lvl, displayname, email FROM users INNER JOIN comments USING (user_id) WHERE id = '{$Row3['id']}'");
				$Row2 = mysqli_fetch_assoc($QueryResult2);
				$commentusername2 = $Row2['displayname'];
				?>
				<div class='comment' id="comment<?= $Row2['id'] ?>">
					<span class='commentpic'>
						<a href="/profile/<?= $Row2['username']; ?>"><? iu_get_avatar_pic($Row2['email']); ?></a>
						<div class="lvl">
							<p>Lvl <?= trim($Row2['lvl']); ?></p>
						</div>
					</span>
					<div class='usercomment-top'>
						<h4><a href="/profile/<?= $Row2['username']; ?>"><?= $commentusername2 ?></a> <span><?= iu_time_elapsed_string($Row3['comment_date']); ?></span></h4>
						<?if(isset($_COOKIE['login_cookie'])){ //only show reply if logged in ?>
							<p class="reply tooltip" data-tooltip="Reply" data-username="<?= $Row2['displayname']; ?>"></p>
						<?}?>
					</div>
					<div class='usercomment'>
						<?= trim($Row3['comment_post']); ?>
					</div>
				</div>
				<?
				$Row3 = mysqli_fetch_assoc($QueryResult3);
			} while ($Row3);
			echo "</div>";
			
		}else { // 0 comments
			
			echo "<div id='commentstop'><h3>". iu_plural($numrows, 'Comment') ."</h3></div>";
			
		}

		// show comment box
		if (isset( $_COOKIE['login_cookie'] )) { 
			$have_user_id = true; 
			$user_idname = $_COOKIE['login_cookie'];	

			//if user submits but there is no comment, output error
			if(isset($_POST['submitcomment']) && $_POST['comment'] == ''){
				echo "<p><span class='red'>* required</span></p>";
			}

			echo "<div id='addcomment'>
				  	<form method='post' action='/submitcomment/$postid/$blogtitle'>
						<p class='addcommentp'>Add Comment:</p>
						<textarea class='commentinput' name='comment' placeholder='Your comment here...'></textarea>
						<input type='hidden' name='postid_num' value='$postid' >
						<input type='hidden' name='title' value='$titleurl' >
						<input type='submit' name='submitcomment' class='submitbtn' value='Submit Comment'/>
				  	</form>
				 </div>";
		} else {
			echo "<p class='logintocomment' >To post comments, please <a class='btn' href='/signin'>Sign In</a>.</p>";
		}
	} ?>
		</div><!-- #right -->
	</div><!-- #content -->

<?php include("scripts.php"); ?>
<script type="text/javascript" src="/js/post.js"></script>
</body>
</html>

