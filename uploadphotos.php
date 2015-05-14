<?php 
/* Set a COOKIE */
$cookie_duration = 3600 * 24 * 7; // 1 week 
$login_ref = 'alfonse';
$retval = setcookie("login_cookie", $login_ref, time() + $cookie_duration );
?>
<html>
<body>

<form action="fileupload.php" method="post" enctype="multipart/form-data"> 
	<input type="hidden" name="MAX_FILE_SIZE" value="30000" />
Select File to upload: <input type="file" name="newfile" value="" /> <br /> 
<input type="submit" name="submit" value="Upload!" />
</form>

</body>
</html>