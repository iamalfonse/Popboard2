<?
include("config.php");

// get error
$errmsg = '';
if(isset($_GET['errmsg'])){
	$errmsg = $_GET['errmsg'];
}

if (isset( $_COOKIE['login_cookie'] )) {
	$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
	$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);

	iu_check_user_setup(); //make sure user has finished setup

	/* GET LOGGED IN USER INFORMATION*/
	$r  = mysqli_query($dblink, "SELECT * FROM users WHERE username='$username' AND session_hash = '$session_hash'");
	$Rows = mysqli_fetch_assoc($r);
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta content="width=device-width, user-scalable=yes" name="viewport" />
	<title>Import Underground | Groups</title>
	<meta name="keywords" content="">
	<meta name="description" content="Search and join your favorite car groups in your area">
	<link href="/stylesheets/car.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" type="image/png" href="/images/favicon.png">
</head>
<body class="groups">
	<?php include("top.php"); ?>

	<div id="content">

		<?php include("left.php"); ?>

		<div id="right" class="groupsection">
			<? 
				if($errmsg == '1'){
					echo "<p class='error'>You must be at least level 1 to create your own group.</p>";
				}else if($errmsg == '2'){
					echo "<p class='error'>You're already in a group. You can't create your own. Ain't nobody got time fo' dat!</p>";
				}else if($errmsg == '3'){
					echo "<p class='error'>You don't have permission to edit that group.</p>";
				}
			?>
			<div class="groupHeader">
				<? if(isset($_COOKIE['login_cookie']) && !isset($Rows['group_id'])){ ?>
				<div class="createGroup">
					<p><a href="/creategroup" class='submitbtn creategroupbtn'>Start A Group</a></p>
				</div>
				<? } ?>
				<!-- <div class="searchGroups">
					<input id="groupSearch" class="inputGroupSearch" type="text" placeholder="Search Groups..." />
					<button class="button1 searchbtn">Search</button>
				</div> -->
			</div>

			<?
				$groupsQuery1 = @mysqli_query($dblink, "SELECT * FROM groups ORDER BY group_id ASC");
				$groupsnr = mysqli_num_rows($groupsQuery1);
				if(isset($_GET['p'])){
					$pagenum = mysqli_real_escape_string($dblink, preg_replace("/[^0-9]/i", "", $_GET['p']));
				}else {
					$pagenum = 1;
				}
				$itemsPerPage = 10; 
				$lastPage = ceil($groupsnr / $itemsPerPage);

				// Be sure URL variable $pagenum(page number) is no lower than page 1 and no higher than $lastpage
				if ($pagenum < 1) { // If it is less than 1
					$pagenum = 1; // force if to be 1
				} else if ($pagenum > $lastPage) { // if it is greater than $lastpage
					$pagenum = $lastPage; // force it to be $lastpage's value
				}
				$centerPages = "";
				$sub1 = $pagenum - 1;
				$sub2 = $pagenum - 2;
				$add1 = $pagenum + 1;
				$add2 = $pagenum + 2;
				if ($pagenum == 1) {
					$centerPages .= '<span class="pagNumActive">' . $pagenum . '</span>';
					$centerPages .= '<a href="/groups?p=' . $add1 . '">' . $add1 . '</a>';
				} else if ($pagenum == $lastPage) {
					$centerPages .= '<a href="/groups?p=' . $sub1 . '">' . $sub1 . '</a>';
					$centerPages .= '<span class="pagNumActive">' . $pagenum . '</span>';
				} else if ($pagenum > 2 && $pagenum < ($lastPage - 1)) {
					$centerPages .= '<a href="/groups?p=' . $sub2 . '">' . $sub2 . '</a>';
					$centerPages .= '<a href="/groups?p=' . $sub1 . '">' . $sub1 . '</a>';
					$centerPages .= '<span class="pagNumActive">' . $pagenum . '</span>';
					$centerPages .= '<a href="/groups?p=' . $add1 . '">' . $add1 . '</a>';
					$centerPages .= '<a href="/groups?p=' . $add2 . '">' . $add2 . '</a>';
				} else if ($pagenum > 1 && $pagenum < $lastPage) {
					$centerPages .= '<a href="/groups?p=' . $sub1 . '">' . $sub1 . '</a>';
					$centerPages .= '<span class="pagNumActive">' . $pagenum . '</span>';
					$centerPages .= '<a href="/groups?p=' . $add1 . '">' . $add1 . '</a>';
				}
				$limit = 'LIMIT ' .($pagenum - 1) * $itemsPerPage .',' .$itemsPerPage; 

				$groupQuery2 = mysqli_query($dblink, "SELECT * FROM groups ORDER BY group_id ASC $limit");
				$groupsRow1 = mysqli_fetch_assoc($groupQuery2);

				$paginationDisplay = ""; // Initialize the pagination output variable
				// This code runs only if the last page variable is ot equal to 1, if it is only 1 page we require no paginated links to display
				if ($lastPage != "1"){
					// This shows the user what page they are on, and the total number of pages
					$paginationDisplay .= '<span class="pageIndex">Page <strong>' . $pagenum . '</strong> of ' . $lastPage. '</span>';
					// If we are not on page 1 we can place the Back button
					if ($pagenum != 1) {
						$previous = $pagenum - 1;
						$paginationDisplay .=  '<a class="prev" href="/groups?p=' . $previous . '"> Back</a>';
					}
					// Lay in the clickable numbers display here between the Back and Next links
					$paginationDisplay .= '<span class="pageNumbers">' . $centerPages . '</span>';
					// If we are not on the very last page we can place the Next button
					if ($pagenum != $lastPage) {
						$nextPage = $pagenum + 1;
						$paginationDisplay .=  '<a class="next" href="/groups?p=' . $nextPage . '"> Next</a>';
					}
				}
				iu_get_groups($groupsRow1, $groupQuery2);





				// $start = 0 + (($pagenum == 1 ? 0 : $pagenum) * 6);
				// $end = 6 * ($pagenum == 0 ? 1 : $pagenum) + 6;
				// echo $start." ".$end;
				// $groupsQuery1  = @mysqli_query($dblink, "SELECT * FROM groups ORDER BY group_id ASC LIMIT $start, $end");
				// $groupsRow1 = mysqli_fetch_assoc($groupsQuery1);
	
				// iu_get_groups($groupsRow1, $groupsQuery1);
			?>
			
				
			<div class="paginate">
				<p><?php echo $paginationDisplay; ?></p>
			</div>
		</div>

	</div>
<?php include("scripts.php"); ?>
<script type="text/javascript" src="/js/groups.js"></script>
</body>
</html>