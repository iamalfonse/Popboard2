<?
include("config.php");

if (isset( $_COOKIE['login_cookie'] )) {
	$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
	$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);

	iu_check_user_setup(); //make sure user has finished setup

	/* GET LOGGED IN USER INFORMATION*/
	$r  = mysqli_query($dblink, "SELECT * FROM users WHERE username='$username' AND session_hash = '$session_hash'");
	$Rows = mysqli_fetch_assoc($r);
}else { //else if NOT LOGGED IN
	header('Location: /');
	exit;
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta content="width=device-width, user-scalable=yes" name="viewport" />
	<title>Notifications | <?= $__site['name']; ?></title>
	<meta name="keywords" content="<?= $__site['keywords']; ?>">
	<meta name="description" content="<?= $__site['description']; ?>">
	<link href="/stylesheets/<?= $stylesheet; ?>.css?<?= $__site['filedate']; ?>" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" type="image/png" href="/images/favicon.png?<?= $__site['filedate']; ?>">
</head>
<body class="notifications">

	<?php include("top.php"); ?>

	<div id="content">

		<?php include("left.php"); ?>

		<div id="right">

			<h2>Notifications</h2>
			<ul class="notificationlist">
				<?	
					$user_id = $Rows['user_id'];
					$QueryResult = @mysqli_query($dblink, "SELECT notification, notification_date, notification_type, from_user_id FROM notifications WHERE user_id = $user_id ORDER BY id DESC LIMIT 0, 5;");
					$Row = mysqli_fetch_assoc($QueryResult);

					iu_get_notifications($Row, $QueryResult);
				?>
			</ul>
			
			<button class="submitbtn load-notifications">Load More</button>
		</div>

	</div>
<?php include("scripts.php"); ?>
<script type="text/javascript" src="/js/notifications.js"></script>
</body>
</html>