<?php

// TODO: check IP address of user and store it to see if they're logged in somewhere else.
// If they are logged in form another IP, log them out and try re-logging them back in


// Remove magic quotes if enabled
if (get_magic_quotes_gpc()) {
	$process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
	while (list($key, $val) = each($process)) {
		foreach ($val as $k => $v) {
			unset($process[$key][$k]);
			if (is_array($v)) {
				$process[$key][stripslashes($k)] = $v;
				$process[] = &$process[$key][stripslashes($k)];
			} else {
				$process[$key][stripslashes($k)] = stripslashes($v);
			}
		}
	}
	unset($process);
}

include 'dbparams.php';

//set charset for using with mysqli_real_escape_string();
mysqli_set_charset($dblink, "utf8");

function iu_get_page_url() {
	$pageURL = '';
	if (isset($_SERVER["HTTPS"]) == "on") {$pageURL .= "s";}

	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

function iu_check_user_setup(){
	include 'dbparams.php';

	$userSetupUsername = mysqli_real_escape_string($dblink, $_COOKIE['login_cookie']);
	$userSetupSession_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);
	$userSetupResults  = mysqli_query($dblink, "SELECT setup, session_hash FROM users WHERE username='$userSetupUsername'");
	$userSetupRow = mysqli_fetch_assoc($userSetupResults);

	//check first if user session matches session hash
	if($userSetupRow['session_hash']!=$userSetupSession_hash ){ // session hash doesn't match (possible because user logged in from another computer before)
		header('Location: /logout?errmsg=3'); //prompt to log back in
		exit;
	}

	//check if user has done setup yet
	if($userSetupRow['setup']=='0' || $userSetupRow['setup']==NULL || $userSetupRow['setup']==''){ //user hasn't gone through profile setup yet
		header('Location: /setup');
		exit;
	}
}


function iu_get_posts($Rows, $Row, $QueryResult, $category='', $page ='',$subpage = ''){
	include 'dbparams.php';

	if(!isset($_COOKIE['login_cookie'])){
		// logged out. set $Rows['user_id'] = '';
		$Rows = array('user_id' => '' );
	}

	$pageURL = $_SERVER["REQUEST_URI"];
	preg_match('/\/([A-Za-z]+)(?:\/)?([A-Za-z]+)?(?:\/)?/', $pageURL, $url);

	if($page==''){ //if normal posts
		if(isset($url[1])){
			$urlpage = $url[1];
		}
		if(isset($url[2])){
			$suburlpage = $url[2];
		}
	}else if($page=='myposts'){ // if in /myposts
		$suburlpage = $subpage;
		$urlpage = $page;
	}

	//need to do query for $Row before you call this function
	//example:
	// $QueryResult = @mysqli_query($dblink, "SELECT username, blog_title, blog_message, user_id, id, post_date FROM users INNER JOIN post USING (user_id) ORDER BY id DESC LIMIT 0, 3");
	// $Row = mysqli_fetch_assoc($QueryResult);
		
	do {
		$blogtitle = iu_cleaner_url($Row['blog_title']);

		//get the correct username of the post
		$getPostsQuery = @mysqli_query($dblink, "SELECT id, username, displayname, lvl, email, views FROM users INNER JOIN posts USING (user_id) WHERE id = '{$Row['id']}'");
		$getPostsRow = mysqli_fetch_assoc($getPostsQuery);
		$getPostsUsername = $getPostsRow['displayname'];

		//get the number of comments for a post
		$getPostsQuery2 = @mysqli_query($dblink, "SELECT count(posts.id), posts.crew_id, posts.category FROM posts INNER JOIN comments WHERE comments.post_id = '{$Row['id']}' AND comments.post_id = posts.id");
		$getPostsRow2 = mysqli_fetch_assoc($getPostsQuery2);

		//get the number of likes for a post
		$getPostsQuery3 = @mysqli_query($dblink, "SELECT count(post_id), user_id FROM likes WHERE post_id = '{$Row['id']}'");
		$getPostsRow3 = mysqli_fetch_assoc($getPostsQuery3);

		//used to check if user has already liked a post
		$getPostsQuery4 = @mysqli_query($dblink, "SELECT user_id FROM likes WHERE likes.user_id = '{$Rows['user_id']}' AND likes.post_id = '{$Row['id']}'");
		$getPostsRow4 = mysqli_fetch_assoc($getPostsQuery4);

		if($urlpage == 'myposts' && $suburlpage != 'crewposts'){// only do query in /myposts page
			//get the category from the post id
			$getPostsQuery5 = @mysqli_query($dblink, "SELECT posts.category, categories.cat_displayname FROM posts INNER JOIN categories WHERE posts.category = categories.category AND posts.id = '{$Row['id']}'");
			$getPostsRow5 = mysqli_fetch_assoc($getPostsQuery5);
		}else if ($urlpage == 'myposts' && $suburlpage == 'crewposts'){// only do query in /myposts/crewposts page
			//get crew name from post id
			$getPostsQuery5 = @mysqli_query($dblink, "SELECT crews.crewname, posts.crew_id FROM crews INNER JOIN posts WHERE posts.crew_id = crews.crew_id AND posts.id = '{$Row['id']}'");
			$getPostsRow5 = mysqli_fetch_assoc($getPostsQuery5);
		}
?>
		<div class='postitem'>
			<div class="postuser">
				<a href="/profile/<?= $getPostsRow['username']?>">
					<? iu_get_avatar_pic($getPostsRow['email']); ?>
				</a>
				<div class="lvl">
					<p>Lvl <?= trim($getPostsRow['lvl']); ?></p>
				</div>
			</div>
			<div class='posttop'>
				<h3 class='posttitle' id='<?= $Row['id'] ?>'><a href='/post/<?= $Row['id']?>/<?= $blogtitle?>'><?= $Row['blog_title'] ?></a></h3>
				<p class='username'>Posted by: <a href="/profile/<?= $Row['username'] ?>"><?= $getPostsUsername ?></a> 
					<?= iu_time_elapsed_string($Row['post_date']) ?>
					<?  
						if($urlpage == 'myposts' && $suburlpage != 'crewposts'){ //show normal posts in /myposts
							echo "in <a href='/posts/{$getPostsRow5['category']}'>{$getPostsRow5['cat_displayname']}</a>"; 
						}else if($urlpage == 'myposts' && $suburlpage == 'crewposts'){ //show crew posts in /myposts
							echo "in <a href='/crew/".iu_cleaner_url($getPostsRow5['crewname'])."'>{$getPostsRow5['crewname']}</a>"; 
						}
					?>
				</p>
				
				<? if($urlpage == 'myposts'){ //only show if inside of myposts section ?>
					<p class='editpostbtn'><a class="submitbtn" href='/editpost/<?= $Row['id'] ?>/<?=$blogtitle?>'>Edit Post</a></p>
					<p class='deletepostbtn'><a class="deletebtn" href='deletepost/<?= $Row['id']?>/<?=$blogtitle?>'>Delete Post</a></p>
				<? } ?>
			</div>
			<div class='postbottom'>
				<div class='blogmessage'>
					<?= iu_convert_image_links(iu_read_more( iu_convert_video_links(iu_linkusername($Row['blog_message'])), 200, '<a class="readmore" href="/post/'.$Row['id'].'/'.$blogtitle.'"> ...Read More</a>')) ?>
				</div>

				<div class='counters'>
					<? //show likes and determine if user liked the post already and user is logged in
					if($getPostsRow4['user_id'] == $Rows['user_id'] && isset($_COOKIE['login_cookie'])){
						echo "<div class='likes'><span class='liked'>Likes</span> {$getPostsRow3['count(post_id)']}</div>";
					}else{
						echo "<div class='likes'><span>Likes</span> {$getPostsRow3['count(post_id)']}</div>";
					}

					//show number of views
					$numviews = $getPostsRow['views'] != '' ? $getPostsRow['views'] : '0';
					echo "<div class='views'><span>Views</span> $numviews</div>";
					
					?>
				</div>
				<p class="viewcomments"><a class='normalbtn' href='/post/<?= $Row['id']?>/<?= $blogtitle?>'> <?= iu_plural($getPostsRow2['count(posts.id)'], 'Comment'); ?></a></p>
				
				
			</div>
		</div>
<?
			$Row = mysqli_fetch_assoc($QueryResult);
	} while ($Row);

}

function iu_get_crews($crewsRow, $crewsQuery){
		$crewsNumRows = mysqli_num_rows($crewsQuery);
		if($crewsNumRows < 1){ // no crews found
			echo "<h2>No Crews Found</h2>";
		}else{
			// show list of crews
			echo "<ul class='crewlist'>";
			do {
			?>
				<li>
					<a class="crewImg" href="/crew/<?= $crewsRow['crew_url']?>">
						<img src="<?= $crewsRow['img_url']?>" alt="<?= $crewsRow['crewname']?>"/>
						<div class="transparent">
							<p class="crewAbout"><?= $crewsRow['about']?></p>
						</div>
					</a>
					<h3><?= $crewsRow['crewname']?></h3>
					<p class="members" ><strong><i></i><?= iu_plural($crewsRow['num_members'], 'Member'); ?></strong></p>
					<!-- <p class="founder"><strong>Founder:</strong> <a href="/profile/<?= strtolower($crewsRow['founder']) ?>"><?= $crewsRow['founder']?></a></p> -->
					<!-- <p class="admins"><strong>Admins:</strong> <a href="/profile/<?= strtolower($crewsRow['leader']) ?>"><?= $crewsRow['leader']?></a></p> -->
					<p class="location" ><?= $crewsRow['location']?></p>
					<p class="created"><?= iu_readable_date($crewsRow['datecreated'], 'short'); ?></p>
					<? if($crewsRow['private'] == '1'){ echo "<div class='private'>Private</div>";} ?>
				</li>
			<?
				$crewsRow = mysqli_fetch_assoc($crewsQuery);
			} while ($crewsRow);
			echo  "</ul>";
		}
}

function iu_get_crewmembers($crewMemberRow, $crewMemberQuery){
	do {
	?>
		<li class="tooltip" data-tooltip="<?= $crewMemberRow['displayname']?>">
			<a href="/profile/<?= $crewMemberRow['username']?>">
				<?= iu_get_avatar_pic($crewMemberRow['email']); ?>
			</a>
		</li>
	<?
			$crewMemberRow = mysqli_fetch_assoc($crewMemberQuery);
		} while ($crewMemberRow);
}

function iu_cleaner_url($title){
	//get rid of spaces and special characters from the blog title to make it cleaner url
	$blogtitle = trim(strtolower($title)); //remove spaces before and after and lowercase
	$blogtitle = iconv('UTF-8', 'ASCII//TRANSLIT', $blogtitle); // replaces weird characters with normal/readable counterparts ie: à to a
	$blogtitle = preg_replace('/[^A-Za-z0-9_\s]+/', '', $blogtitle); //get rid of non-url chars
	$blogtitle = preg_replace('/\s/', '-', $blogtitle); //replace spaces with a dash -
	$blogtitle = preg_replace('/-([-]+)?/', '-', $blogtitle); // replace dashes with more than two dashes side by side into only a single dash
	return $blogtitle;
}

function iu_linkusername($string) { //create links to user profiles if mentioned in a post or comment

	// regex pattern
	$pattern = '/@([a-zA-Z0-9_]+)/';
	// convert string URLs to active links
	$replace = '<a href="/profile/$1">@$1</a>';
	$new_string = preg_replace($pattern, $replace, $string);

	return $new_string;
}

function iu_mentionusername($string, $from, $posturl){ //send notification to users if mentioned in a post
	include 'dbparams.php';
	preg_match_all('/@[a-zA-Z0-9_]+/', $string, $matches);
	// print_r($matches[1]);
	foreach ($matches as $match) {
		if(in_array($match, $matches)){
			foreach ($match as $name) {
				$matchedname = str_replace("@", "", $name);//remove '@' from username match
				$mentionQuery = @mysqli_query($dblink, "SELECT user_id FROM users WHERE username = '$matchedname'");
				$mentionRow = mysqli_fetch_assoc($mentionQuery);
				$mentionuserid = $mentionRow['user_id'];

				iu_send_notification($from, $mentionuserid, 'mentionpost', $posturl);
			}
		}
	}
}

function iu_get_avatar_pic($email = ""){
	include 'dbparams.php';

	$results3 = mysqli_query($dblink, "SELECT profileimg, displayname FROM users WHERE email = '$email'");
	$Row3 = mysqli_fetch_assoc($results3);

	if($Row3['profileimg'] != NULL || $Row3['profileimg'] != ''){
		echo '<img class="avatar" src="/'.$Row3['profileimg'].'" alt="'.$Row3['displayname'].'" />';
	}else {
		echo '<img class="avatar" src="/images/me.jpg" />';
	}

	// $image = '/images/avatars/'.strtolower($Row3['profileimg']).'.png';
	// echo '<img class="avatar" src="'.$image.'" alt="'.$username.'">';

	//find out if user has set gravatar image
	// $query3    = "SELECT * FROM users WHERE email='$email'";
	// $results3  = mysqli_query($dblink, $query3);
	// $Row3 = mysqli_fetch_assoc($results3);

	// if($Row3['gravatar'] == 1){//user has gravatar set
	// 	$email = $Row3['email'];
	// 	$default = "http://popboard.hallofhavoc.com/images/me.jpg";
	// 	$size = 90;
	// 	$grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size;

	// 	echo '<img class="avatar" src="'.$grav_url.'" alt="">';
	// }else if($Row3['gravatar'] == 0 || $Row3['gravatar'] == null){//user set to normal
	// 	echo '<img src="/images/me.jpg" class="profilePic">';
	// }
}

function iu_plural($number, $text){
	if($number==1){
		return $number." ".$text;
	}else {
		return $number." ".$text."s";
	}
}

function iu_update_views($postid){
	include 'dbparams.php';

	//Check Post and add +1 view to the table column views
	$updateViewsQuery = @mysqli_query($dblink, "SELECT views FROM posts WHERE id = $postid ");
	$updateViewsRow = mysqli_fetch_assoc($updateViewsQuery);

	if($updateViewsRow['views']== '' || $updateViewsRow == null){
		//add +1 to views if there's no value yet
		$updateViewsQuery1 = @mysqli_query($dblink, "UPDATE posts SET views = 1 WHERE id = $postid ");
	}else{
		//update and add +1 to views if it's not null
		$updateViewsQuery1 = @mysqli_query($dblink, "UPDATE posts SET views = views+1 WHERE id = $postid ");
	}
}

function iu_get_inviterequests( $inviteQuery){
	include 'dbparams.php';

	if($inviteQuery){
		$inviteQueryRows = mysqli_fetch_assoc($inviteQuery);
		$inviteQueryNumRows = mysqli_num_rows($inviteQuery);

		if($inviteQueryNumRows >= 1){
			do {
				//get username and email from user_id of the requestd invite
				$inviteQuery2 = @mysqli_query($dblink, "SELECT user_id,email, username,displayname FROM users WHERE user_id = '{$inviteQueryRows['user_id']}' ");
				$inviteQueryRows2 = mysqli_fetch_assoc($inviteQuery2);

				echo "<li data-userid='".$inviteQueryRows2['user_id']."'><a href='/profile/".$inviteQueryRows2['username']."'>";
				iu_get_avatar_pic($inviteQueryRows2['email']);
				echo "</a> <p><a href='/profile/".$inviteQueryRows2['username']."'>".$inviteQueryRows2['displayname']."</a> has asked to join your crew.</p><button class='btn acceptinvite'>Accept</button><button class='btn rejectinvite'>Reject</button></li>";

				$inviteQueryRows = mysqli_fetch_assoc($inviteQuery);
			}while($inviteQueryRows);
		}else{
			echo "<li><p>No Pending Invites</p></li>";
		}
	}else {
		echo "<li><p>No Pending Invites </p></li>";
	}
	return;
}

function iu_get_notifications($Row, $QueryResult){
	include 'dbparams.php';
	
	$gn_numrows = mysqli_num_rows($QueryResult);
	if($gn_numrows >= 1){
		do {
			//get username and email from user_id of the notification
			$QueryResult2 = @mysqli_query($dblink, "SELECT email, username FROM users INNER JOIN notifications WHERE users.user_id = {$Row['from_user_id']};");
			$Row2 = mysqli_fetch_assoc($QueryResult2);

			echo "<li><a href='/profile/{$Row2['username']}'>";
			echo iu_get_avatar_pic($Row2['email'])."</a><p>{$Row['notification']} <span>".iu_time_elapsed_string($Row['notification_date'])."</span></p><div class='notificationtype {$Row['notification_type']}'></div></li>";
			$Row = mysqli_fetch_assoc($QueryResult);
		}while($Row);
	}else{
		echo "<li><p>No notifications</p></li>";
	}
}

function iu_send_notification($from_userid, $to_userid, $type, $posturl=''){
	include 'dbparams.php';

	$QueryResult = @mysqli_query($dblink, "SELECT username, displayname FROM users WHERE user_id = $from_userid ");
	$Row = mysqli_fetch_assoc($QueryResult);

	if($type=='comment'){
		$notification = mysqli_real_escape_string($dblink,"<a href='/profile/".$Row['username']."'>{$Row['displayname']}</a> commented on your <a href='".$posturl."'>post</a>.");
	}
	if($type=='like'){
		$notification = mysqli_real_escape_string($dblink,"<a href='/profile/".$Row['username']."'>{$Row['displayname']}</a> liked your <a href='".$posturl."'>post</a>.");
	}
	if($type=='mentionpost'){
		$notification = mysqli_real_escape_string($dblink,"<a href='/profile/".$Row['username']."'>{$Row['displayname']}</a> mentioned you in a <a href='".$posturl."'>post</a>.");
	}
	if($type=='joined'){
		$notification = mysqli_real_escape_string($dblink,"<a href='/profile/".$Row['username']."'>{$Row['displayname']}</a> has joined your crew.");
	}
	if($type=='accepted'){
		$notification = mysqli_real_escape_string($dblink,"<a href='/profile/".$Row['username']."'>{$Row['displayname']}</a> has accepted your invitation to join their crew.");
	}

	$notification_date = date("Y-m-d H:i:s"); //get current datetime value. i.e. '2013-10-22 14:45:00'
	
	// Insert a new notification
	$InsertQuery = @mysqli_query($dblink, "INSERT INTO notifications(user_id, from_user_id, notification_type, notification, notification_date) VALUES('$to_userid', '$from_userid','$type', '$notification', '$notification_date');");

}

function iu_calculate_GP($user_id){
	include 'dbparams.php';

	//check if logged in
	if(!isset($_COOKIE['login_cookie'])){
		return;
	}
	$QueryResult = @mysqli_query($dblink, "SELECT user_id, total_gp FROM users WHERE user_id = '$user_id'");
	$Row = mysqli_fetch_assoc($QueryResult);
	
	$myGP = $Row['total_gp'];
	//$myGP = 32141;

	$copper = $myGP % 100;
	$myGP = ($myGP - $copper) / 100;
	$silver = $myGP % 100;
	$gold = ($myGP - $silver) / 100;
	echo "<p class='gp'><span class='gold'></span> ".$gold." <span class='silver'></span> " .$silver ." <span class='copper'></span> ".$copper."</p>";
}

function iu_get_userxp($user_id) {
	include 'dbparams.php';

	//check if logged in
	if(!isset($_COOKIE['login_cookie'])){
		return;
	}

	// get total likes, comments and posts
	$numtotal_query = @mysqli_query($dblink, "SELECT total_posts, total_comments, total_likes, lvl, total_xp FROM users USE INDEX (user_id) WHERE user_id='$user_id';");
	$numtotal_row = mysqli_fetch_assoc($numtotal_query);

	//add total XP. 1 Like = 1XP. 1 comment = 2XP. 1 post = 10XP.
	$myXP = $numtotal_row['total_likes'] + ($numtotal_row['total_comments'] * 2) + ($numtotal_row['total_posts'] * 10); //likes + (comments * 2 XP) + (posts * 10 XP);
	
	$startXP = 0; // Level 1 Start XP
	$endXP = 20;  // Level 1 End XP
	$increaseXP =40;   // Increase by extra how many per level?
	$lvlMultiplier = 40; // Multiply by how many per level? (1- easy / 100- hard)
	//max 52980 to reach lvl 100 with multiplier = 20
	//max 77480 to reach lvl 100 with multiplier = 30
	//max 101980 to reach lvl 100 with multiplier = 40
	// $myXP = 1100;

	/* Calculate Level */
	$myLevel = 0;
	$calcCount = 0;
	do {
		$calcCount = $calcCount+1;
		if ($calcCount % 2 == 0 ) { 
			$increaseXP = $increaseXP + $lvlMultiplier; 
		}
		if (($myXP < $endXP) && ($myXP >= $startXP)) { 
			$myLevel = $calcCount; $myStart = $startXP; $myEnd = $endXP; 
		}
		$startXP = $endXP;
		$endXP = $endXP + $increaseXP;
	} while ($myLevel == 0);
	$myLevel--;

	/* Calculate XP to next level */
	$myCurrentXP = $myXP - $myStart;
	$toNextLevel = $myEnd - $myStart;

	/* Calculate Percentage to Next Level */
	$myPercent = (($myXP - $myStart) / ($myEnd - $myStart)) * 100;
	$myPercent = round($myPercent);
	if ($myPercent == 0) { $myPercent = 1; }

	//return array('percent'=>$myPercent,'level'=>$myLevel);
	//echo 'percent: '.$myPercent.' level: '.$myLevel.' Progress: '.$myCurrentXP.'/'.$toNextLevel;

	//only update if there's a change (to prevent unnecessary update table overhead)
	if($myXP != $numtotal_row['total_xp']){
		//Update lvl and total_xp in users table
		$updatelevel_query = @mysqli_query($dblink, "UPDATE users SET lvl = '$myLevel' WHERE user_id='$user_id';");
		$updatexp_query = @mysqli_query($dblink, "UPDATE users SET total_xp = '$myXP' WHERE user_id='$user_id';");
	}
	
	
	?>
	<div id="xp">
		<div class='userLvl'>
			<p>Lvl <span><?= $myLevel ?></span></p>
		</div>
		<div class='xpBar'>
			<p>XP <?= $myCurrentXP ?>/<?= $toNextLevel ?></p>
			<div class='progressXP' style='width: <?= $myPercent ?>%'></div>
		</div>
		<div class='nextLvl'>
			<p>Lvl <span><?= $myLevel+1 ?></span></p>
		</div>
	</div>
	<?
}

function iu_convert_video_links($text) {
	$videoEmbed = preg_replace("/(?:\<a.+?)?\s*[a-zA-Z\/\/:\.]*vimeo.com\/(?:channels\/|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)(?:\<a.+?)?/i","<iframe width=\"640\" height=\"390\" src=\"//player.vimeo.com/video/$3\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>",$text);
    $videoEmbed = preg_replace("/(?:\<a.+?)?\s*[a-zA-Z\/\/:\.]*youtu\.?be(.com)?\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)(?:.+\>)?/i","<iframe width=\"640\" height=\"390\" src=\"//www.youtube.com/embed/$2\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>",$videoEmbed);
    
    return $videoEmbed;
}
function iu_convert_image_callback($matches){

	if($matches[2] != ''){
		$returned = "<div class='imgContainer'><img src='".$matches[1]."' /> <div class='imgSrc'><a href='".$matches[1]."' rel='external' target='_blank'>source</a></div> </div><p>".$matches[2]."</p>";
	}else {
		$returned = "<div class='imgContainer'><img src='".$matches[1]."' /> <div class='imgSrc'><a href='".$matches[1]."' rel='external' target='_blank'>source</a></div> </div>";
	}
	
	return $returned;
}
function iu_convert_image_links($text) {
	// print_r($text);die();
	$imageEmbed = preg_replace_callback("/<p>\<img(?:.+)?src\=(?:\"|\')(.+?)(?:\"|\')(?:.+?)\/?\>(.*)?<\/p>/i", 'iu_convert_image_callback', $text);

	return $imageEmbed;
}

// how to output '' days ago, '' hours ago, etc. Must use DateTime() format. ex: 2013-05-29 00:00:00
function iu_time_elapsed_string($datetime, $full = false) {
	$now = new DateTime;
	$ago = new DateTime($datetime);
	$diff = $now->diff($ago);
	//print_r($diff);
	// $diff->w = floor($diff->d / 7);
	// $diff->d -= $diff->w * 7;

	$string = array(
	'y' => 'year',
	'm' => 'month',
	//'w' => 'week',
	'd' => 'day',
	'h' => 'hour',
	'i' => 'minute',
	's' => 'second',
	);
	foreach ($string as $k => &$v) {
		if ($diff->$k) {
			$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
		} else {
			unset($string[$k]);
		}
	}

	if (!$full) $string = array_slice($string, 0, 1);
	return $string ? implode(', ', $string) . ' ago' : 'just now';
}

// output datetime to readable date (i.e. 2013-05-04 to May 4, 2013)
function iu_readable_date($datetime, $format = 'normal'){
	$date = New DateTime($datetime);
	if($format == 'normal'){
		$updatedDate = date_format($date,'F j, Y');
	}else if($format == 'short'){
		$updatedDate = date_format($date,'M j, Y');
	}else if($format == 'numbered'){
		$updatedDate = date_format($date,'n/j/y');
	}
	
	return $updatedDate;

}

// adds ...Read More to text
function iu_read_more($s, $l, $e = '&hellip;', $isHTML = true) {
	$s = trim($s);
	$e = (strlen(strip_tags($s)) > $l) ? $e : '';
	$i = 0;
	$tags = array();

	if($isHTML) {
		preg_match_all('/<[^>]+>([^<]*)/', $s, $m, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
		foreach($m as $o) {
			if($o[0][1] - $i >= $l) {
				break;
			}
			$t = substr(strtok($o[0][0], " \t\n\r\0\x0B>"), 1);
			if($t[0] != '/') {
				$tags[] = $t;
			}
			elseif(end($tags) == substr($t, 1)) {
				array_pop($tags);
			}
			$i += $o[1][1] - $o[0][1];
		}
	}
	$output = substr($s, 0, $l = min(strlen($s), $l + $i)) . (count($tags = array_reverse($tags)) ? '</' . implode('></', $tags) . '>' : '') . $e;
	return $output;
}


function iu_format_number($input){
	$suffixes = array('', 'k', 'M', 'B');
	$suffixIndex = 0;

	while(abs($input) >= 1000 && $suffixIndex < sizeof($suffixes)){
		$suffixIndex++;
		$input /= 1000;
	}
	return round(floatval($input / pow(10, 0)),1).$suffixes[$suffixIndex];
}


?>