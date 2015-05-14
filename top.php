<?php
	
	$pageURL = $_SERVER["REQUEST_URI"];
	$url1 = '';
	$url2 = '';
	preg_match('/\/([A-Za-z]+)(?:\/)?([A-Za-z-]+)?(\/)?/', $pageURL, $url);
	if(isset($url[1])){
		$url1 = $url[1];
		$url2 = isset($url[2]) ? $url[2] : ''; //used to grab groupname
	}

	
	if (isset( $_COOKIE['login_cookie'] )) { //if user is logged in
		$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
		$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);

		iu_check_user_setup(); //make sure user has finished setup of avatar

		/* GET LOGGED IN USER INFORMATION*/
		$r  = mysqli_query($dblink, "SELECT * FROM users WHERE username='$username' AND session_hash = '$session_hash'");
		$Rows = mysqli_fetch_assoc($r);
		$topgroupid = $Rows['group_id'];
	}

	// print_r($url);

	//get group url
	if(!isset($topgroupid)){
		$topgroupid = '';
	}
	$topgroupurl ='';
	if($topgroupid){
		$nav_results  = @mysqli_query($dblink, "SELECT group_url FROM groups WHERE group_id = '$topgroupid'");
		$nav_rows = mysqli_fetch_assoc($nav_results);
		$topgroupurl = $nav_rows['group_url'];
	}
	

?>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-15746792-8', 'importunderground.com');
  ga('send', 'pageview');

</script>

<div id="top">
	<div class="logomenu">
		<div class="mobilemenu"></div>
		<h1><a href="/">Import Underground</a></h1>
		<? 
		if(isset( $_COOKIE['login_cookie']) && $url1 != 'setup' ){ // show notifications and XP if logged in and not in setup
			iu_get_userxp($Rows['user_id']);
				
		?>
			<div class="top-notifications">
				<?
					// get logged in user id
					$notify_userid = $Rows['user_id'];

					// get all notifications
					$notify_results2 = @mysqli_query($dblink, "SELECT notification,notification_date,notification_type, from_user_id FROM notifications WHERE user_id = '$notify_userid' ORDER BY id DESC LIMIT 0, 10;");
					$notify_rows2 = mysqli_fetch_assoc($notify_results2);

					// get number of unread notifications
					$notify_results3 = @mysqli_query($dblink, "SELECT COUNT(*) AS 'unreadcount' FROM notifications USE INDEX (id) WHERE user_id = '$notify_userid' AND notifications.read = '0';");
					$notify_rows3 = mysqli_fetch_assoc($notify_results3);
					$notify_count = $notify_rows3['unreadcount'];

					if($notify_count >= 1 && $notify_count < 11){ //show number of unread notifications
						echo "<span class='unread'>$notify_count</span>";
					}
					if($notify_count > 10){
						echo "<span class='unread'>10+</span>";
					}
				?>
				<ul>
					<? 
						
						iu_get_notifications($notify_rows2, $notify_results2); 
						
					?>
					<li><a class='viewbtn' href='/notifications'>View All Notifications</a></li>
				</ul>
			</div>
		<? 
			//check if user is a founder/creator of a group
			$groupfounderQuery = @mysqli_query($dblink, "SELECT group_id FROM groups WHERE founder = '{$Rows['username']}'");
			if(mysqli_num_rows($groupfounderQuery)){ // if there's a match, show invite count

				$groupfounderRows = mysqli_fetch_assoc($groupfounderQuery);
				//grab number of invites
				$getInvitesQuery = @mysqli_query($dblink, "SELECT COUNT(*) AS 'invitecount' FROM groupinvites WHERE group_id = '{$groupfounderRows['group_id']}' AND groupinvites.pending = '1'");
				$getInvitesRows = mysqli_fetch_assoc($getInvitesQuery);
				$invite_count = $getInvitesRows['invitecount'];
		?>
				<div class="top-invites">

					<? if($invite_count >= 1 && $invite_count < 11){ //show number of invites pending ?>
					<span class="invitecount"><?= $invite_count ?></span>
					<? }
					if($invite_count > 10){ ?>
					<span class="invitecount">10+</span>
					<? } ?>
					<ul>
						<? 
							//grab pending invites
							$user_id = $Rows['user_id'];
							$inviteQuery = @mysqli_query($dblink, "SELECT * FROM groupinvites INNER JOIN groups USING (group_id) WHERE groupinvites.group_id = {$Rows['group_id']} AND groupinvites.pending = '1' ORDER BY id DESC;");
							
							iu_get_inviterequests($inviteQuery);
						?>
						<li><a class='viewbtn' href='/invites'>View All Pending Invites</a></li>
					</ul>
				</div>
		<?		
			} 
		}// end of if(isset$_COOKIE['login_cookie'])
		//iu_calculate_GP($Rows['user_id']); ?>
	</div>
</div>

<? 
	if($url1 != 'setup'){ //don't show menu in setup
?>
<nav class="mainNav">
	<ul>
		<li class="nav-home <? if($url1 == 'home'){ echo 'active'; } ?>"><a href="/home"><i></i><span>Home</span></a></li>
		<li class="nav-categories <? if($url1 == 'categories' || $url1 == 'posts'){ echo 'active'; } ?>"><a href="/categories"><i></i><span>Categories</span></a></li>
		<li class="nav-groups <? if($url1 == 'groups' || ($url1== 'group' && $url2 != $topgroupurl)){ echo 'active'; } ?>"><a href="/groups"><i></i><span>Groups</span></a></li>
		<li class="nav-topposts <? if($url1 == 'topposts'){ echo 'active'; } ?>"><a href="/topposts"><i></i><span>Top Posts</span></a></li>
		<? if( isset($_COOKIE['login_cookie']) && isset($Rows['group_id']) ){ ?>
			<li class="nav-mygroup <? if($url2 == $topgroupurl){ echo 'active'; } ?>"><a href="/group/<?= $topgroupurl ?>"><i></i><span>My Group</span></a></li>
		<? } ?>
		<li class="nav-events <? if($url1 == 'events'){ echo 'active'; } ?>"><a href="/events"><i></i><span>Events</span></a></li>
	</ul>
</nav>
<? }

?>
