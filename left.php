<div id="left">
<?php



if(isset($_COOKIE['login_cookie'])){

	$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));

	/* Open connection to the database */
	$dblink = @mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	$leftQuery  = mysqli_query($dblink, "SELECT user_id, username, displayname, email FROM users WHERE username='$username'");
	$leftRows = mysqli_fetch_assoc($leftQuery);

	//user is logged in
	?>
		<a href="/profile"><?php iu_get_avatar_pic($leftRows['email']); ?>
			<p class="leftusername"><strong><?php echo $leftRows['displayname']; ?></strong></p>
		</a>
		
		<p class="leftbuttons profile"><a href="/profile/<?= $leftRows['username']?>"><span class="icon-user"></span> My Profile</a>
		<!-- <p class="leftbuttons createpost"><a href="/createpost"><span></span>Create Post</a></p> -->
		<p class="leftbuttons myposts"><a href="/myposts"><span class="icon-file-text"></span> My Posts</a></p>
		<p class="leftbuttons logout"><a id="logoutbtn" href="/logout"><span class="icon-logout"></span> Logout</a></p>
		<p class="leftbuttons singup"></p>
	<?
} else {

	$pageURL = iu_get_page_url();

	?>
	<form id="signinform" method="post" action="/login?url=<?= $pageURL ?>">
		<h3>Sign In</h3>
		<p><input class="userinput" type="text" name="username" placeholder="Username" onFocus="clearText(this)" onBlur="clearText(this)" /></p>
		<p><input class="userinput" type="password" name="passwd" placeholder="Password"/></p>
		<p><a href="/forgotpassword">Forgot your password?</a></p>
		<p><input class="submitbtn" type="submit" name="login" value="Sign In" /></p>
	</form>
<? } ?>
	<div class="closemobile"></div>
</div><!--#left-->