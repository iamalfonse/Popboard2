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
	<title>Import Underground | Crews</title>
	<meta name="keywords" content="">
	<meta name="description" content="Search and join your favorite car crews in your area">
	<link href="/stylesheets/car.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" type="image/png" href="/images/favicon.png">
</head>
<body class="crews">
	<?php include("top.php"); ?>

	<div id="content">

		<?php include("left.php"); ?>

		<div id="right" class="crewsection">
			<? 
				if($errmsg == '1'){
					echo "<p class='error'>You must be at least level 1 to create your own crew.</p>";
				}else if($errmsg == '2'){
					echo "<p class='error'>You're already in a crew. You can't create your own. Ain't nobody got time fo' dat!</p>";
				}else if($errmsg == '3'){
					echo "<p class='error'>You don't have permission to edit that crew.</p>";
				}
			?>
			<div class="crewHeader">
				<? if(isset($_COOKIE['login_cookie']) && !isset($Rows['crew_id'])){ ?>
				<div class="createCrew">
					<p><a href="/createcrew" class='submitbtn createcrewbtn'>Start A Crew</a></p>
				</div>
				<? } ?>
				<!-- <div class="searchCrews">
					<input id="crewSearch" class="inputCrewSearch" type="text" placeholder="Search Crews..." />
					<button class="button1 searchbtn">Search</button>
				</div> -->
			</div>

			<?
				$crewsQuery1 = @mysqli_query($dblink, "SELECT * FROM crews ORDER BY crew_id ASC");
				$crewsnr = mysqli_num_rows($crewsQuery1);
				if(isset($_GET['p'])){
					$pagenum = mysqli_real_escape_string($dblink, preg_replace("/[^0-9]/i", "", $_GET['p']));
				}else {
					$pagenum = 1;
				}
				$itemsPerPage = 10; 
				$lastPage = ceil($crewsnr / $itemsPerPage);

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
					$centerPages .= '<a href="/crews?p=' . $add1 . '">' . $add1 . '</a>';
				} else if ($pagenum == $lastPage) {
					$centerPages .= '<a href="/crews?p=' . $sub1 . '">' . $sub1 . '</a>';
					$centerPages .= '<span class="pagNumActive">' . $pagenum . '</span>';
				} else if ($pagenum > 2 && $pagenum < ($lastPage - 1)) {
					$centerPages .= '<a href="/crews?p=' . $sub2 . '">' . $sub2 . '</a>';
					$centerPages .= '<a href="/crews?p=' . $sub1 . '">' . $sub1 . '</a>';
					$centerPages .= '<span class="pagNumActive">' . $pagenum . '</span>';
					$centerPages .= '<a href="/crews?p=' . $add1 . '">' . $add1 . '</a>';
					$centerPages .= '<a href="/crews?p=' . $add2 . '">' . $add2 . '</a>';
				} else if ($pagenum > 1 && $pagenum < $lastPage) {
					$centerPages .= '<a href="/crews?p=' . $sub1 . '">' . $sub1 . '</a>';
					$centerPages .= '<span class="pagNumActive">' . $pagenum . '</span>';
					$centerPages .= '<a href="/crews?p=' . $add1 . '">' . $add1 . '</a>';
				}
				$limit = 'LIMIT ' .($pagenum - 1) * $itemsPerPage .',' .$itemsPerPage; 

				$crewQuery2 = mysqli_query($dblink, "SELECT * FROM crews ORDER BY crew_id ASC $limit");
				$crewsRow1 = mysqli_fetch_assoc($crewQuery2);

				$paginationDisplay = ""; // Initialize the pagination output variable
				// This code runs only if the last page variable is ot equal to 1, if it is only 1 page we require no paginated links to display
				if ($lastPage != "1"){
					// This shows the user what page they are on, and the total number of pages
					$paginationDisplay .= '<span class="pageIndex">Page <strong>' . $pagenum . '</strong> of ' . $lastPage. '</span>';
					// If we are not on page 1 we can place the Back button
					if ($pagenum != 1) {
						$previous = $pagenum - 1;
						$paginationDisplay .=  '<a class="prev" href="/crews?p=' . $previous . '"> Back</a>';
					}
					// Lay in the clickable numbers display here between the Back and Next links
					$paginationDisplay .= '<span class="pageNumbers">' . $centerPages . '</span>';
					// If we are not on the very last page we can place the Next button
					if ($pagenum != $lastPage) {
						$nextPage = $pagenum + 1;
						$paginationDisplay .=  '<a class="next" href="/crews?p=' . $nextPage . '"> Next</a>';
					}
				}
				iu_get_crews($crewsRow1, $crewQuery2);





				// $start = 0 + (($pagenum == 1 ? 0 : $pagenum) * 6);
				// $end = 6 * ($pagenum == 0 ? 1 : $pagenum) + 6;
				// echo $start." ".$end;
				// $crewsQuery1  = @mysqli_query($dblink, "SELECT * FROM crews ORDER BY crew_id ASC LIMIT $start, $end");
				// $crewsRow1 = mysqli_fetch_assoc($crewsQuery1);
	
				// iu_get_crews($crewsRow1, $crewsQuery1);
			?>
			
				
			<div class="paginate">
				<p><?php echo $paginationDisplay; ?></p>
			</div>
		</div>

	</div>
<?php include("scripts.php"); ?>
<script type="text/javascript" src="/js/crews.js"></script>
</body>
</html>