<?php
	
	include("config.php");

	$x = mysqli_real_escape_string($dblink, $_GET['xvalue']);
	$y = mysqli_real_escape_string($dblink, $_GET['yvalue']);
	$orig_w = mysqli_real_escape_string($dblink, $_GET['wvalue']);
	$orig_h = mysqli_real_escape_string($dblink, $_GET['hvalue']);
	$src = mysqli_real_escape_string($dblink, $_GET['src']);
	$crop = true;
	// echo "x: ".$x." <br/> y: ".$y."<br/> w: ".$orig_w." <br/> h: ".$orig_h." <br/> src: ".$src." <br/>width: $width <br/>height: $height <br/>";exit;

	$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
	$uploadsDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . $src;
	$width = 90;
	$height = 90;
	
	// preg_match('/(\/userimages\/.+\/)([A-Za-z0-9\.\-\_]+)?/', $uploadsDirectory, $matches);
	// echo "userfolder: ".$matches[0]."<br/>";exit;
	// $dst = $matches[0];
	// $dst = $uploadsDirectory;


	if(!list($w, $h, $typefile) = getimagesize($uploadsDirectory)) return "Unsupported picture type!";

	// $type = strtolower(substr(strrchr($uploadsDirectory,"."),1));
	// if($type == 'jpeg') $type = 'jpg';
	if($typefile == '1') $type = 'gif';
	if($typefile == '2') $type = 'jpg';
	if($typefile == '3') $type = 'png';
	switch($type){
		case 'gif': $img = imagecreatefromgif($uploadsDirectory); break;
		case 'jpg': $img = imagecreatefromjpeg($uploadsDirectory); break;
		case 'png': $img = imagecreatefrompng($uploadsDirectory); break;
		default : return "Unsupported picture type!";
	}

	$new = imagecreatetruecolor($width, $height);

	// preserve transparency
	if($type == "gif" or $type == "png"){
		imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
		imagealphablending($new, false);
		imagesavealpha($new, true);
	}

	imagecopyresampled($new, $img, 0, 0, $x, $y, $width, $height, $orig_w, $orig_h);

	switch($type){
		case 'gif': imagegif($new, $uploadsDirectory); break;
		case 'jpg': imagejpeg($new, $uploadsDirectory); break;
		case 'png': imagepng($new, $uploadsDirectory); break;
	}

	echo $src;

?>