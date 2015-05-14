<?php
$error = "";

// fieldname used within the file <input> of the HTML form 
$fileElementName = 'profileimg';

if(!empty($_FILES[$fileElementName]['error'])){
	switch($_FILES[$fileElementName]['error']){

		case '1':
			$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
			break;
		case '2':
			$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
			break;
		case '3':
			$error = 'The uploaded file was only partially uploaded';
			break;
		case '4':
			$error = 'No file was uploaded.';
			break;
		case '6':
			$error = 'Missing a temporary folder';
			break;
		case '7':
			$error = 'Failed to write file to disk';
			break;
		case '8':
			$error = 'File upload stopped by extension';
			break;
		case '999':
		default:
			$error = 'No error code avaiable';
	}
}elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none'){
	$error = 'No file was uploaded..';
}else {
	$filename = $_FILES[$fileElementName]['name'];
	$filesize = @filesize($_FILES[$fileElementName]['tmp_name']);


	include("config.php");

	if (isset( $_COOKIE['login_cookie'] )) { 
		$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
		$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);

		// check user setup
		$setupUploadQuery  = mysqli_query($dblink, "SELECT * FROM users WHERE username='$username' AND session_hash = '$session_hash'");
		$setupUploadRow = mysqli_fetch_assoc($setupUploadQuery);

		// if($setupUploadRow['setup'] == '1'){ //check if user finished setup already
		// 	header("Location: /");
		// }

	}
	// first let's set some variables 

	// make a note of the current working directory, relative to root. 
	$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);

	// create a user directory to upload this image
	$userDirectory = substr(md5($setupUploadRow['username']), 2, 8); // create an 8 string directory for the user
	if (!file_exists('userimages/'.$userDirectory)) {
		mkdir('userimages/'.$userDirectory, 0755, true);
	}

	// make a note of the directory that will recieve the uploaded file 
	$uploadsDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . 'userimages/'.$userDirectory.'/';

	// check that the file we are working on really was the subject of an HTTP upload 
	@is_uploaded_file($_FILES[$fileElementName]['tmp_name'])
	    or $error = 'Not an HTTP upload: '.$_FILES[$fileElementName]['tmp_name'];
	     
	// validation... since this is an image upload script we should run a check
	// to make sure the uploaded file is in fact an image. Here is a simple check: 
	// getimagesize() returns false if the file tested is not an image. 
	@getimagesize($_FILES[$fileElementName]['tmp_name'])
	    or $error = 'Only image uploads are allowed.';
	     
	$imageSize = getimagesize($_FILES[$fileElementName]['tmp_name']);

	if($error == 'Only image uploads are allowed.'){
		// skip file upload and show error
	}else {

		//do query to check if user has uploaded a profile image before
		$checkUploadImg = mysqli_query($dblink, "SELECT profileimg FROM users WHERE username = '$username'");
		$RowUpload = mysqli_fetch_assoc($checkUploadImg);

		if($RowUpload['profileimg'] != '' || $RowUpload['profileimg'] != NULL ){
			$oldImgLocation = $_SERVER['DOCUMENT_ROOT'] . $directory_self .$RowUpload['profileimg'];

			// $error = 'already uploaded: '.$oldImgLocation;
			// echo "{";
			// echo	"uploaderror: '" . $error . "',\n";
			// echo "}";exit();
			//delete old file
			unlink($oldImgLocation);

		}


		// 	preg_match('/(\/userimages\/.+\/)([A-Za-z0-9\.\-\_]+)?/', $uploadFileUrl, $match);
		// 	$imageUrl = $match[0];
		// 	$imagename = $match[2];
		// }else{
			// make a unique filename for the uploaded file and check it is not already 
			// taken... if it is already taken keep trying until we find a vacant one 
			// sample filename: 1140732936c4e.jpg

			// $ext = pathinfo($_FILES[$fileElementName]['name'], PATHINFO_EXTENSION);
			$ext = ($ext == 'jpeg' ? 'jpg' : pathinfo($_FILES[$fileElementName]['name'], PATHINFO_EXTENSION));
			$now = time();
			while(file_exists($uploadFileUrl = $uploadsDirectory.$now.substr(md5($_FILES[$fileElementName]['name']), 2, 3).'.'.$ext)){
			    $now++;
			}

			$imageUrl = 'userimages/'.$userDirectory.'/'.$now.substr(md5($_FILES[$fileElementName]['name']), 2, 3).'.'.$ext;
			$imagename = $now.substr(md5($_FILES[$fileElementName]['name']), 2, 3).'.'.$ext;
		// }
		
		// now let's move the file to its final location and allocate the new filename to it 
		@move_uploaded_file($_FILES[$fileElementName]['tmp_name'], $uploadFileUrl) 
		    or $error = 'Receiving directory insuffiecient permission';

		//now lets remember the file location to store in the database
		if($session_hash == $setupUploadRow['session_hash']){
			//Post imageUrl into database
			$updateProfileImgQuery = mysqli_query($dblink, "UPDATE users SET profileimg='$imageUrl' WHERE username = '$username'");
		}

	
		//------------------------------- FINISHED UPLOADING IMAGE FILE ---------------------
		

		//Now resize image to fit cropping area
		list($w, $h, $typefile) = getimagesize($uploadFileUrl);

		
		// $type = strtolower(substr(strrchr($uploadFileUrl,"."),1));
		// $type = $typefile);
		// if($type == 'jpeg') $type = 'jpg';
		if($typefile == '1') $type = 'gif';
		if($typefile == '2') $type = 'jpg';
		if($typefile == '3') $type = 'png';
		switch($type){
			case 'gif': $img = imagecreatefromgif($uploadFileUrl); break;
			case 'jpg': $img = imagecreatefromjpeg($uploadFileUrl); break;
			case 'png': $img = imagecreatefrompng($uploadFileUrl); break;
			default : return "Unsupported picture type!";
		}
		$width = $w;
		$height = $h;
		if($w >= 600){ 
			
			$ratio = $w/$h; // width/height
			if( $ratio > 1) {
			    $width = 600;
			    $height = 600/$ratio;
			}
			else {
			    $width = 600*$ratio;
			    $height = 600;
			}
		}
		$new = imagecreatetruecolor($width, $height);
		
		if($type == "gif" or $type == "png"){
			imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
			imagealphablending($new, false);
			imagesavealpha($new, true);
		}
		imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);

		switch($type){
			case 'gif': imagegif($new, $uploadFileUrl); break;
			case 'jpg': imagejpeg($new, $uploadFileUrl); break;
			case 'png': imagepng($new, $uploadFileUrl); break;
		}
	}

	//for security reason, we force to remove all uploaded file
	@unlink($_FILES[$fileElementName]);
}
echo "{";
echo	"uploaderror: '" . $error . "',\n";
echo	"filename: '" . $filename . "',\n";
echo	"filesize: '" . $filesize . "',\n";
echo	"imagesize_w: '" . $imageSize[0] . "',\n";
echo	"imagesize_h: '" . $imageSize[1] . "',\n";
echo	"imagetype: '" . $typefile . "',\n";
echo	"imagename: '" . $imagename . "',\n";
echo	"filedir: '" . $imageUrl . "'\n";
// echo				"filedir: '" . $uploadFileUrl . "'\n";
echo "}";
?>