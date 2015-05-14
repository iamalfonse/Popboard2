<?php
//DATABASE PARAMS
include("config.php");


if (isset( $_COOKIE['login_cookie'] )) { 
	$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
	$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);

	iu_check_user_setup(); //make sure user has finished setup
	
	$r  = mysqli_query($dblink, "SELECT * FROM users WHERE username='$username' AND session_hash = '$session_hash'");
	$Rows = mysqli_fetch_assoc($r);
}else {
	$username = '';
}

$userprofile = mysqli_real_escape_string($dblink, strtolower($_GET['username']));

if($userprofile == ''){
	$userprofile = $Rows['username'];
}

//TODO: make sure user can reset password, send email verification

//TODO: Make sure user can remove themselves from a crew (also -1 to num_crew members)

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta content="width=device-width, user-scalable=yes" name="viewport" />
	<link href="/stylesheets/car.css" rel="stylesheet" type="text/css" />
	<link href="/stylesheets/jcrop.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" type="image/png" href="/images/favicon.png">
	<title>Import Underground | <?= $Rows['displayname'] ?></title>
	<meta name="keywords" content="Import Underground <?= $Rows['displayname'] ?> Profile, Import Underground Profile, Import Underground, Imports, Racing, Tuner, Cars, Car Meets, Underground, Street, Pictures, Events, Meets, Crews, Car Crews">
	<meta name="description" content="Import Underground | <?= $Rows['displayname'] ?>'s Profile">
</head>
<body class="profile">

	<?php include("top.php"); ?>

    <div id="content">
		<?php include("left.php"); ?>
	
    	<div id="right" class="profilesection">
    		<?
			
			//----------------------if you're looking at someone else's profile------------------
			if($userprofile && ($userprofile != $username )){
				
				//get user profile's info
				$userProfileQuery1  = mysqli_query($dblink, "SELECT * FROM users USE INDEX (user_id) WHERE username='$userprofile'");
				$userProfileRow1 = mysqli_fetch_assoc($userProfileQuery1);
				$userProfileNum_rows = mysqli_num_rows($userProfileQuery1);
				

				if($userProfileNum_rows == 1){ //if there's a user in the database

					//get crew info for user
					if($userProfileRow1['crew_id'] != NULL || $userProfileRow1['crew_id'] != ''){
						//get crew name of user
						$userProfileQuery5 = @mysqli_query($dblink, "SELECT crewname, crew_url FROM crews WHERE crew_id={$userProfileRow1['crew_id']}");
						$userProfileRow5 = mysqli_fetch_assoc($userProfileQuery5);
					}
			?>
			
				<div id="myprofile">
					<div class="userpic <?=$userProfileRow1['profilebg'] ?>">
						<?php iu_get_avatar_pic($userProfileRow1['email']); ?>
						<h3><?= $userProfileRow1['displayname'] ?></h3>
						<div class="lvl">
							<p>Lvl <? if($userProfileRow1['lvl'] == NULL){echo "0";}else{ echo $userProfileRow1['lvl'];} ?></p> 
						</div>
					</div>
					<div class="userinfo">
						<p class="location"><span class="icon"></span><strong>Location:</strong> <?= $userProfileRow1['location']; ?></p>
						<p class="car"><span class="icon"></span><strong>Car:</strong> <?= $userProfileRow1['car']; ?></p>
						<p class="crew"><span class="icon"></span><strong>Crew:</strong> 
							<? if($userProfileRow1['crew_id'] != NULL || $userProfileRow1['crew_id'] != ''){// if user is in a crew ?>
								<a href="/crew/<?= $userProfileRow5['crew_url'] ?>"><?= $userProfileRow5['crewname']; ?></a>
							<? } ?>
						</p>
						<p class="joindate"><span class="icon"></span><strong>Joined on:</strong> <?= iu_readable_date($userProfileRow1['joindate']); ?></p>
						<p class="bio"><strong>Bio</strong></p>
						<p class="bio"><?= stripslashes($userProfileRow1['bio']); ?></p>
						
						<div class="posts">
							<p class="postcount"><?= $userProfileRow1['total_posts']; ?></p>
							<div class="icon"></div>
							<p>Posts</p>
						</div>
						<div class="comments">
							<p class="commentcount"><?= $userProfileRow1['total_comments']; ?></p>
							<div class="icon"></div>
							<p>Comments</p>
						</div>
						<div class="likes">
							<p class="likescount"><?= $userProfileRow1['total_likes']; ?></p>
							<div class="icon"></div>
							<p>Likes</p>
						</div>
						
					</div>
				</div>
			<?	}else { //no profile  
					echo "<h2>Profile Not Found</h2>";
				}

	    	}else{ //----------------------------else show your own profile---------------------

				//check if user is in a crew
				if($Rows['crew_id'] != NULL || $Rows['crew_id'] != ''){
					//get crew name of user
					$userProfileQuery5 = @mysqli_query($dblink, "SELECT crewname, crew_url FROM crews WHERE crew_id={$Rows['crew_id']}");
					$userProfileRow5 = mysqli_fetch_assoc($userProfileQuery5);
				}
				$max_file_size = 512000; //500kb
    		?>
				
				<div id="myprofile">
					<div class="userpic <?=$Rows['profilebg'] ?>">
						<?php iu_get_avatar_pic($Rows['email']); ?>
						<h3><?= $Rows['displayname'] ?></h3>
						<div class="lvl">
							<p>Lvl <? if($Rows['lvl'] == NULL){echo "0";}else{ echo $Rows['lvl'];} ?></p>
						</div>
						<p class="showbgSelector">Update Background</p>
					</div>
					<div class="userinfo">
						<p class="location"><span class="icon"></span><strong>Location:</strong> <?= $Rows['location']; ?></p>
						<p class="car"><span class="icon"></span><strong>Car:</strong> <?= $Rows['car']; ?></p>
						<p class="crew"><span class="icon"></span><strong>Crew:</strong> 
							<? if($Rows['crew_id'] != NULL || $Rows['crew_id'] != ''){// if user is in a crew ?>
								<a href="/crew/<?= $userProfileRow5['crew_url'] ?>"><?= $userProfileRow5['crewname']; ?></a>
							<? } ?>
						</p>
						<p class="email"><span class="icon"></span><strong>E-mail:</strong> <?= $Rows['email'] ?></p>
						<p class="joindate"><span class="icon"></span><strong>Joined on:</strong> <?= iu_readable_date($Rows['joindate']); ?></p>
						<p class="bio"><strong>Bio</strong></p>
						<p class="bio"><?= $Rows['bio'];?></p>
						<div class="posts">
							<p class="postcount"><?= iu_format_number($Rows['total_posts']); ?></p>
							<div class="icon"></div>
							<p>Posts</p>
						</div>
						<div class="comments">
							<p class="commentcount"><?= iu_format_number($Rows['total_comments']); ?></p>
							<div class="icon"></div>
							<p>Comments</p>
						</div>
						<div class="likes">
							<p class="likescount"><?= iu_format_number($Rows['total_likes']); ?></p>
							<div class="icon"></div>
							<p>Likes</p>
						</div>
					</div>
					
					<div class="updateprofile">
						<form action="/updateprofile"  enctype="multipart/form-data" method="post">
							<h3>Profile Info</h3>
							<div class="update-profileimg">
								<p><strong>Update Profile Image</strong></p>
								<p class="updatephoto"><span class="submitbtn uploadphoto">Upload Photo<input id="profileimg" type="file" name="profileimg" value="Upload a Photo"/></span></p>
								<input type="hidden" name="MAX_FILE_SIZE" value="<?= $max_file_size ?>">
							</div>
							<div class="update-bio">
								<p><strong>Bio</strong></p>
								<input type="text" id="profileinfo" class="userinput" name="bio" value="<?= $Rows['bio']; ?>">
							</div>
							<div class="enterLocation">
								<p><strong>Location</strong></p>
								<p><input type="text" class="userinput" name="location" value="<?= $Rows['location']; ?>"/></p>
							</div>
							<div class="enterCar">
								<p><strong>Car</strong></p>
								<p><input type="text" class="userinput" name="car" value="<?= $Rows['car']; ?>"/></p>
							</div>
							<input type="submit" name="submitUserInfo" class="submitbtn" value="Update Profile Info">
						</form>
					</div>

					<? 
						// TODO: Create a table 'usersettings' which keeps track of personal settings
					
						// TODO: Do a query to get user's settings

					?>
					<div class="profileSettings">
						<form action="/updateprofilesettings" method="post">
							<h3>Settings</h3>

							<div class="email-notifications">
								<label>Email Notifications</label>
								<div class="suboptions">
									<p><input type="radio" name="emailnotify-mention" <? // TODO: check user settings for email notification ?>checked="checked"> Send me an email every time someone mentions me</p>
									<p><input type="radio" name="emailnotify-mention"> Do not send me an email every time someone mentions me</p>
								</div>
								<div class="suboptions">
									<p><input type="radio" name="emailnotify-commentpost" <? // TODO: check user settings for email notification ?>checked="checked"> Send me an email every time someone comments on my post</p>
									<p><input type="radio" name="emailnotify-commentpost"> Do not send me an email every time someone comments on my post</p>
								</div>
							</div>
							<div class="post-settings">
								<label>Your Categories</label>
								<? 
									// TODO: give options to show which categories are private/public
									// create a "for each" loop that checks the categories you own and sets it's privacy settings
									// example: for each categories, check if it's public or private and provide radio buttons to let the user switch it to public or private
								?>
							</div>
						<form>
					</div>
				</div>
		<?
			}
		?>
    	</div>
	</div>
	<div class="profilebgOverlay hide">
		<div class="profilebgWrap">
			<div class="closex">Close</div>
			<div class="">

			</div>
			<h3>Select a background</h3>
			<ul class="bgselect">
				<li class="default"></li>
				<li class="white"></li>
				<li class="black"></li>
				<li class="red"></li>
				<li class="blue"></li>
				<li class="green"></li>
				<li class="yellow"></li>
				<li class="pink"></li>
				<li class="purple"></li>
				<li class="orange"></li>
			</ul>
			<p>Unlocked at Lvl 10</p>
			<ul class="bgselect">
				<li class="radial-white <? if($Rows['lvl'] < '10'){echo "locked";} ?>"><span></span></li>
				<li class="radial-black <? if($Rows['lvl'] < '10'){echo "locked";} ?>"><span></span></li>
				<li class="radial-red <? if($Rows['lvl'] < '10'){echo "locked";} ?>"><span></span></li>
				<li class="radial-yellow <? if($Rows['lvl'] < '10'){echo "locked";} ?>"><span></span></li>
				<li class="radial-green <? if($Rows['lvl'] < '10'){echo "locked";} ?>"><span></span></li>
				<li class="radial-blue <? if($Rows['lvl'] < '10'){echo "locked";} ?>"><span></span></li>
				<li class="radial-orange <? if($Rows['lvl'] < '10'){echo "locked";} ?>"><span></span></li>
				<li class="radial-pink <? if($Rows['lvl'] < '10'){echo "locked";} ?>"><span></span></li>
				<li class="radial-purple <? if($Rows['lvl'] < '10'){echo "locked";} ?>"><span></span></li>
			</ul>
			<p>Unlocked at Lvl 15</p>
			<ul class="bgselect">
				<li class="bgimg01 <? if($Rows['lvl'] < '15'){echo "locked";} ?>"><span></span></li>
				<li class="bgimg02 <? if($Rows['lvl'] < '15'){echo "locked";} ?>"><span></span></li>
				<li class="bgimg03 <? if($Rows['lvl'] < '15'){echo "locked";} ?>"><span></span></li>
				<li class="bgimg04 <? if($Rows['lvl'] < '15'){echo "locked";} ?>"><span></span></li>
				<li class="bgimg05 <? if($Rows['lvl'] < '15'){echo "locked";} ?>"><span></span></li>
				<li class="bgimg06 <? if($Rows['lvl'] < '15'){echo "locked";} ?>"><span></span></li>
				<li class="bgimg07 <? if($Rows['lvl'] < '15'){echo "locked";} ?>"><span></span></li>
				<li class="bgimg08 <? if($Rows['lvl'] < '15'){echo "locked";} ?>"><span></span></li>
				<li class="bgimg09 <? if($Rows['lvl'] < '15'){echo "locked";} ?>"><span></span></li>
			</ul>
		</div>
	</div>
<?php include("scripts.php"); ?>
<script src="/js/ajaxfileupload.js" type="text/javascript"></script>
<script src="/js/jquery.Jcrop.js" type="text/javascript"></script>
<script src="/js/profile.js" type="text/javascript"></script>
<div class="jcropOverlay">
	<div class="jcropContainer">
		<p>Crop your profile picture</p>
		<div class="mainImg"></div>
		<div id="preview-pane">
			<div class="preview-container"></div>
			<p>Preview</p>
		</div>
		<button class="submitbtn jcropConfirm">Use Photo</button>
		<button class="submitbtn jcropCancel">Cancel</button>
		<input type="hidden" id="x" name="x" value="0" />
		<input type="hidden" id="y" name="y" value="0" />
		<input type="hidden" id="w" name="w" value="90" />
		<input type="hidden" id="h" name="h" value="90" />
	</div>
</div>
</body>
</html>