<?php
include("config.php");
header('Content-Type: text/html; charset=UTF-8');

if (isset( $_COOKIE['login_cookie'] )) {
	$username = mysqli_real_escape_string($dblink, $_COOKIE['login_cookie']);
	$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);
	$post_id = mysqli_real_escape_string($dblink, $_GET['post_id']);

	iu_check_user_setup(); //make sure user has finished setup

	$r  = mysqli_query($dblink, "SELECT user_id, email, session_hash FROM users WHERE username='$username' AND session_hash = '$session_hash'");
	$Rows = mysqli_fetch_assoc($r);

} else {
	header("Location: /");
	exit;
}
$editpostQuery = @mysqli_query($dblink, "SELECT username, blog_title, blog_message, category, posts.crew_id FROM users INNER JOIN posts USING (user_id) WHERE posts.id = '$post_id'");
$editpostRows = mysqli_fetch_assoc($editpostQuery);

$category = $editpostRows['category'];

if(isset($editpostRows['crew_id'])){
	$editpostCrewQuery = @mysqli_query($dblink, "SELECT crewname FROM crews WHERE crew_id = '{$editpostRows['crew_id']}'");
	$editpostCrewRows = mysqli_fetch_assoc($editpostCrewQuery);
}


?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta content="width=device-width, user-scalable=yes" name="viewport" />
	<title>Import Underground | Edit Post</title>
	<link href="/stylesheets/cleditor.css" rel="stylesheet" type="text/css" />
	<link href="/stylesheets/car.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" type="image/png" href="/images/favicon.png">
</head>
<body class="editpost">

	<?php include("top.php"); ?>

	<div id="content">
		<?php include("left.php"); ?>

		<div id="right">
			<div class="right-content">
	        	<h3 class="sectiontitle">Edit Post</h3>
				<div class="clear"></div>
			</div>
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
			?>
			</div>
			<div id="postcomment">
				<?php
					// check to make sure that this user is the user who posted this post (security check)
					if($username == $editpostRows['username']){
				?>
					<form method="post" action="/submitpost" >
						<? if(isset($editpostRows['crew_id'])){ //show only if its a crew post ?>
							<h4>Posted In <?= $editpostCrewRows['crewname']; ?></h4>
							<input type="hidden" name="category" value="general">
							<input type="hidden" name="crewid" value="<?= $editpostRows['crew_id']; ?>">
						<? }else{ ?>
							<label class='inputtitle'>Post in Category:</label>
							<select class="select catselect" name="category">
								<option value="general" <? if($category=='general'){echo "selected='selected'";} ?> >General Talk</option>
								<option value="domestic" <? if($category=='domestic'){echo "selected='selected'";} ?> >Domestic Cars</option>
								<option value="euro" <? if($category=='euro'){echo "selected='selected'";} ?> >Euro Cars</option>
								<option value="import" <? if($category=='import'){echo "selected='selected'";} ?> >Import Cars</option>
								<option value="classic" <? if($category=='classic'){echo "selected='selected'";} ?> >Classic Cars</option>
								<option value="wheels" <? if($category=='wheels'){echo "selected='selected'";} ?> >Wheels/Suspension</option>
								<option value="tuning" <? if($category=='tuning'){echo "selected='selected'";} ?> >Tuning</option>
								<option value="drag" <? if($category=='drag'){echo "selected='selected'";} ?> >Drag</option>
								<option value="track" <? if($category=='track'){echo "selected='selected'";} ?> >Track</option>
								<option value="rally" <? if($category=='rally'){echo "selected='selected'";} ?> >Rally</option>
							</select>
						<? } ?>
						<input type="hidden" value="editpost" name="from">
						<input type="hidden" name="post_id" value="<?= $post_id ?>">
						<input type="hidden" name="post_url" value="<?= iu_cleaner_url($editpostRows['blog_title']) ?>">
						<label class='inputtitle'>Title</label>
						<input type="text" name="posttitle" maxlength="50" class="inputtitle" value="<?= $editpostRows['blog_title']; ?>" />
						<label class='inputtitle'>Message</label>
						<textarea id='postmessage' name='postmessage' rows='5' cols='50' class='inputmessage'> 
							<?= $editpostRows['blog_message'] ?>
			 			</textarea>
						<input type="submit" class="submitbtn" id="postsubmit" name="editsubmitblog" value="Update Post"/> 
						<p class="note">You can embed Youtube and Vimeo videos by simply copy and pasting the url</p>
					</form>
				<?php
					}else{
						echo "<h2>You do not have permission to edit this post.</h2>";
					}
				?>
			</div>
		</div>
	</div>

<?php include("scripts.php"); ?>
<!-- <script type="text/javascript" src="/js/jquery.cleditor.js"></script> -->
<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="/js/createpost.js"></script>

</body>
</html>