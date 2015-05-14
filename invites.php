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
	
	<title>Import Underground | Invites Pending</title>
	<meta name="keywords" content="">
	<meta name="description" content="">
	<link href="/stylesheets/car.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" type="image/png" href="/images/favicon.png">
</head>
<body class="invites">

	<?php include("top.php"); ?>

	<div id="content">

		<?php include("left.php"); ?>

		<div id="right">

		<? 
			//only show invites if you are the founder of a crew
			$crewinfoQuery = @mysqli_query($dblink, "SELECT crew_id,crewname,crew_url,founder FROM crews WHERE founder = '{$Rows['username']}'");
			$crewinfoNumRows = mysqli_num_rows($crewinfoQuery);
			if($crewinfoNumRows > 0){ //if there is a record of you being the founder of a crew, then show invites
		?>
			<h2>Invites</h2>
			<ul class="invitelist">
				<?
					//grab pending invites
					$user_id = $Rows['user_id'];
					$inviteQuery = @mysqli_query($dblink, "SELECT * FROM crewinvites INNER JOIN crews USING (crew_id) WHERE crewinvites.crew_id = {$Rows['crew_id']} AND crewinvites.pending = '1' ORDER BY id DESC;");
					
					iu_get_inviterequests($inviteQuery);
				?>
			</ul>
		<?  } ?>

		</div>

	</div>
<?php include("scripts.php"); ?>
<script type="text/javascript" src="/js/invites.js"></script>
</body>
</html>