<?php 

// add 20 GP to each person every day
$QueryResult = @mysqli_query($dblink, "SELECT total_gp FROM users");
$Row = mysqli_fetch_assoc($QueryResult);

$newgp = $Row['total_gp'] + 20;

$cron1 = mysqli_query($dblink, "UPDATE users SET total_gp ='$newgp'");


// Update User lvl and XP


?>