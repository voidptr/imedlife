<?php
//check_login.php - Checks that we got a valid sessionID from the iPhone

$result = mysql_query("SELECT * FROM sessions WHERE sessionID=$sessionID");
$loggedIn = false;

if($result) //If the session exists, the user is still logged in.
	$loggedIn = true;
else exit(0);
?>