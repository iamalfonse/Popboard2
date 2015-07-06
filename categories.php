<?
include("config.php");

if (isset( $_COOKIE['login_cookie'] )) {
	$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
	$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);

	iu_check_user_setup(); //make sure user has finished setup

	/* GET LOGGED IN USER INFORMATION*/
	$query  = mysqli_query($dblink, "SELECT * FROM users WHERE username='$username' AND session_hash = '$session_hash'");
	$Rows = mysqli_fetch_assoc($query);
}
	
	// TODO: Make 2 different stylesheets to allow users to show large categories(with cat image) vs inline text categories (default maybe?)
	
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta content="width=device-width, user-scalable=yes" name="viewport" />
	<link href="/stylesheets/<?= $stylesheet; ?>.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" type="image/png" href="/images/favicon.png">
	<title>Import Underground | Categories</title>
	<meta name="keywords" content="Import Underground, Imports, Racing, Tuner, Cars, Car Meets, Underground, Street, Pictures, Events, Meets, Groups, Car Groups">
	<meta name="description" content="Post and discuss about Import Cars">
</head>
<body class="categories">

	<?php include("top.php"); ?>

	<div id="content">

		<?php include("left.php"); ?>
	
	    <div id="right" class="categoriessection">
			<ul class="category-list">

				<?
					$catQuery = @mysqli_query($dblink, "SELECT * FROM categories ORDER BY listorder ASC");
					$catRows = mysqli_fetch_assoc($catQuery);
					do {
						if($catRows['category'] != 'general'){ //only show categories that is not general
				?>
						<li>
							<a href="/posts/<?= $catRows['category']?>">
								<!-- <div class="catimg <?= $catRows['category']?>" <? if(isset($catRows['catimg'])){ ?> style="background: url() no-repeat center center;" <? } ?>></div> -->
								<h3><?= $catRows['cat_displayname']; ?></h3>
								<p class="desc"><?= $catRows['description']; ?></p>
								<p>Posts: <?= $catRows['num_posts']; ?></p>
								<p>Comments: <?= $catRows['num_comments']; ?></p>
							</a>
						</li>
				<?
						}
						
						$catRows = mysqli_fetch_assoc($catQuery);
					} while ($catRows);

				?>


			</ul>

		</div><!--#right-->
	</div><!--#bottom-->
<?php include("scripts.php"); ?>

</body>
</html>