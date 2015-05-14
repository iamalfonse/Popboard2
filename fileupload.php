<?php

//DATABASE PARAMS
include("config.php");


if (isset( $_COOKIE['login_cookie'] )) { 
		
		$have_user_id = true; 
		$user_id = ucfirst($_COOKIE['login_cookie']);


		if (isset($_POST["submit"])) { 
			/* Check to see if a file has been uploaded. */ 
			if (is_uploaded_file($_FILES['newfile']['tmp_name'])) {
				$dest_filepath = "/Users/alfonsesurigao/Desktop/Websites/Popboard/images/userimages"; 
				$full_dest_path = $dest_filepath . "/" . $_FILES['newfile']['name'];
				/* This will return 1 on success. */ 
				$retval = move_uploaded_file($_FILES['newfile']['tmp_name'], $full_dest_path);

				$filename = $_FILES['newfile']['name'];
				$filetype = $_FILES['newfile']['type'];
				$filesize = $_FILES['newfile']['size'];


				$dblink = @mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
				$query = "INSERT INTO user_files(filename, mime_type, file_size) VALUES('$filename', '$filetype', '$filesize')";
		        $results  = mysqli_query($dblink, $query);

				echo "Congrats! File uploaded!";
				echo "here are your files:";


				$SQLString = "SELECT * FROM user_files";
				$QueryResult = @mysqli_query($dblink, $SQLString);
				$Row = mysqli_fetch_assoc($QueryResult);
				do {
					echo "<p> </p>";
					echo "<a href='images/userimages/{$Row['filename']}'>{$Row['filename']}</a>";
					echo "<p> </p>";
					echo "<hr />";
					$Row = mysqli_fetch_assoc($QueryResult);
				} while ($Row);

				//echo "<img src='files/$filename'>";


			}
		}

}
?>