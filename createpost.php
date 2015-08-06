<?php
// include("config.php");

if (isset( $_COOKIE['login_cookie'] )) {
	$have_user_id = true;
	$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
	$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);

	iu_check_user_setup(); //make sure user has finished setup

	$r = mysqli_query($dblink, "SELECT * FROM users WHERE username='$username' AND session_hash = '$session_hash'");
	$Rows = mysqli_fetch_assoc($r);

}


if(isset($_GET['groupurl'])){ // get groupurl if posting from your group
	$cpGroupURL = mysqli_real_escape_string($dblink, strtolower($_GET['groupurl']));

	$cpGroupQuery = mysqli_query($dblink, "SELECT * FROM groups WHERE group_url='$cpGroupURL'");
	$cpGroupRow = mysqli_fetch_assoc($cpGroupQuery);
	$cpGroupName = $cpGroupRow['groupname'];
	
	$grouppermission = true;

	// TODO: Update this to check under user_groups table instead of from $Rows (users table)
	if($Rows['group_id'] != $cpGroupRow['group_id']){ //if this is not your group (i.e. you don't have permission to post)
		$grouppermission = false;
	}
}


// TODO: Make it so that there are different types of posts.
//	for example: Blog/Write post, video post (allow them to add a comment), picture post (with comment), etc.

?>
<div class="createpostOverlay">
</div>
<div id="createpost">
	
	<div class="errorContainer">
	<?php
		if (isset($_GET['errmsg']) && isset($_GET['errmsg']) != '') {
			if($_GET['errmsg'] == '1'){
				echo "<p class='error'>Title and Message must not be empty.</p>";
			}else if($_GET['errmsg'] == '2') {
				echo "<p class='error'>You don't have permission to do that. Don't try to hack bro!</p>";
			}else {
				echo "<p class='error'>An error has occured. Please try again.</p>";
			}
		}

		if(isset($_GET['groupurl']) && $grouppermission == false){
			echo "<p class='error'>You don't have permission to post in this group: ".$cpGroupName."</p>";
		}
	?>
	</div>
	<? if(isset($_GET['groupurl']) && $grouppermission == false){ 
		//dont show anything if you don't have permission to post in this group
	 }else { ?>
	<div id="postcomment">
		<p class="closebtn">Close</p>
		<form method="post" action="/submitpost">

			<? if(isset($_GET['groupurl'])){ //if posting from group ?>
				<label class='inputtitle'>Post in Group:</label>
				<p class="groupname"><?= $cpGroupName; ?></p>
				<input type="hidden" name="category" value="general">
				<input type="hidden" name="groupurl" value="<?= $_GET['groupurl']; ?>">
			<? }else { //posting normal post 

				$cat = ''; //used toautomatically select a category from dropdown select
				if(isset($_GET['category'])){
					$cat = $_GET['category'];
				}
			?>
			<label class='inputtitle'>Post in Category:</label>
				<select class="select catselect" name="category">
					<?
						$catQuery = @mysqli_query($dblink, "SELECT * FROM categories ORDER BY listorder ASC");
						$catRows = mysqli_fetch_assoc($catQuery);
						do {
					?>
							<option value="<?= $catRows['category'] ?>" <?= $cat==$catRows['category'] ? 'selected="selected"' : ''; ?> ><?= $catRows['cat_displayname'] ?></option>
					<?
							$catRows = mysqli_fetch_assoc($catQuery);
						} while ($catRows);
					?>
				</select>
			<? } ?>
			
			<input type="hidden" value="createpost" name="from">
			<label class='inputtitle' for="posttitle">Title</label>
			<input type="text" id="posttitle" name="posttitle" maxlength="50" class='inputtitle userinput' />
			<label class='inputtitle' for="postmessage">Message</label>
			<textarea id="postmessage" name="postmessage" rows="5" cols="50" class='inputmessage'></textarea>
			<div id="results"></div>
			<div id="loading_indicator">loading...</div>
			<input type="submit" name="submitpost" value="Submit Post" class="submitbtn" id="postsubmit"/>
			<p class="note">You can embed Youtube and Vimeo videos by simply copy and pasting the url</p>
		</form>
	</div>
	<? } ?>
</div>

<!-- <script type="text/javascript" src="/js/jquery.cleditor.js"></script> -->
<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="/js/createpost.js"></script>
