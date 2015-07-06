<?php
include("config.php");

if (isset( $_COOKIE['login_cookie'] )) {
	$have_user_id = true;
	$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
	$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);

	iu_check_user_setup(); //make sure user has finished setup

	/* GET LOGGED IN USER INFORMATION*/
	$r = mysqli_query($dblink, "SELECT * FROM users WHERE username='$username' AND session_hash = '$session_hash'");
	$Rows = mysqli_fetch_assoc($r);

} else {
	header("Location: /");
	exit;
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta content="width=device-width, user-scalable=yes" name="viewport" />
	<title>Import Underground | Create Category</title>
	<link href="/stylesheets/<?= $stylesheet; ?>.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" type="image/png" href="/images/favicon.png">
</head>
<body class="createcategory">

	<?php include("top.php"); ?>

	<div id="content">
		<?php include("left.php"); ?>

		<div id="right">
			<div class="right-content">
				<h3 class="sectiontitle">Create New Category</h3>
			</div>
			<div class="errorContainer">
			<?php
				if (isset($_GET['errmsg']) && isset($_GET['errmsg']) != '') {
					if($_GET['errmsg'] == '1'){
						echo "<p class='error'>Category name and description must not be empty.</p>";
					} else if($_GET['errmsg'] == '2') {
						echo "<p class='error'>You don't have permission to do that. Don't try to hack bro!</p>";
					} else {
						echo "<p class='error'>An error has occured. Please try again.</p>";
					}
				}
			?>
			</div>

			<div id="createcategory">
				<form method="post" action="/submitcreatecategory">
					<label class='inputtitle' for="categorytitle">Category Name</label>
					<input type="text" id="categorytitle" name="categorytitle" maxlength="50" class='inputtitle userinput' />
					<label class='inputtitle' for="categorydescription">Description</label>
					<input type="text" id="categorydescription" name="categorydescription" maxlength="50" class='inputtitle userinput' />
					<input type="submit" name="categorysubmit" value="Add Category" class="submitbtn" id="categorysubmit"/>
				</form>
			</div>
		</div>

	</div>
<?php include("scripts.php"); ?>
<script type="text/javascript" src="/js/createcategory.js"></script>
</body>
</html>