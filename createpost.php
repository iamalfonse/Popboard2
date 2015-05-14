<?php
include("config.php");

if (isset( $_COOKIE['login_cookie'] )) {
	$have_user_id = true;
	$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
	$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);

	iu_check_user_setup(); //make sure user has finished setup

	$r = mysqli_query($dblink, "SELECT * FROM users WHERE username='$username' AND session_hash = '$session_hash'");
	$Rows = mysqli_fetch_assoc($r);

} else {
	header("Location: /");
	exit;
}


if(isset($_GET['crew'])){ // get crewid if posting from your crew
	$cpCrewID = mysqli_real_escape_string($dblink, strtolower($_GET['crew']));

	$cpCrewQuery = mysqli_query($dblink, "SELECT * FROM crews WHERE crew_id='$cpCrewID'");
	$cpCrewRow = mysqli_fetch_assoc($cpCrewQuery);
	$cpCrewName = $cpCrewRow['crewname'];
	
	$crewpermission = true;
	if($Rows['crew_id'] != $cpCrewRow['crew_id']){ //if this is not your crew (i.e. you don't have permission to post)
		$crewpermission = false;
	}
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta content="width=device-width, user-scalable=yes" name="viewport" />
	<title>Import Underground | Create Post</title>
	<link href="/stylesheets/cleditor.css" rel="stylesheet" type="text/css" />
	<link href="/stylesheets/car.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" type="image/png" href="/images/favicon.png">
</head>
<body class="createpost">
	
	<?php include("top.php"); ?>

	<div id="content">
		<?php include("left.php"); ?>
	
    	<div id="right">
    		<div class="right-content">
	        	<h3 class="sectiontitle">Create Post</h3>
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

				if(isset($_GET['crew']) && $crewpermission == false){
					echo "<p class='error'>You don't have permission to post in this crew: ".$cpCrewName."</p>";
				}
			?>
			</div>
			<? if(isset($_GET['crew']) && $crewpermission == false){ 
				//dont show anything if you don't have permission to post in this crew
			 }else { ?>
        	<div id="postcomment">
				<form method="post" action="/submitpost">

					<? if(isset($_GET['crew'])){ //if posting from crew ?>
						<label class='inputtitle'>Post in Crew:</label>
						<p class="crewname"><?= $cpCrewName; ?></p>
						<input type="hidden" name="category" value="general">
						<input type="hidden" name="crewid" value="<?= $_GET['crew']; ?>">
					<? }else { //posting normal post 

						$cat = ''; //used toautomatically select a category from dropdown select
						if(isset($_GET['c'])){
							$cat = $_GET['c'];
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
					<label>Or</label>
					<p><a href="/createcategory">Create Your Own Category</a></p>
					<input type="hidden" value="createpost" name="from">
					<label class='inputtitle' for="posttitle">Title</label>
					<input type="text" id="posttitle" name="posttitle" maxlength="50" class='inputtitle userinput' />
					<label class='inputtitle' for="postmessage">Message</label>
					<textarea id="postmessage" name="postmessage" rows="5" cols="50" class='inputmessage'></textarea>
					<input type="submit" name="submitpost" value="Submit Post" class="submitbtn" id="postsubmit"/>
					<p class="note">You can embed Youtube and Vimeo videos by simply copy and pasting the url</p>
				</form>
			</div>
			<? } ?>
    	</div>

    </div>
<?php include("scripts.php"); ?>
<!-- <script type="text/javascript" src="/js/jquery.cleditor.js"></script> -->
<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="/js/createpost.js"></script>
</body>
</html>