<?php 
//logout.php - Just destroys the user's session and logs the user out.
include_once("lib/connect.php");
session_start();

if (isset($_SESSION['sessionID'])) {
	$sessionID = $_SESSION['sessionID']; //Put in another variable just to avoid problem with quotes and mysql
	$removed = mysql_query("DELETE FROM sessions WHERE sessionID='$sessionID'");
	
	if($removed) { //Kill the session and go to homepage
		session_destroy();
		header("location: ../webui/main.php");
	}
	else echo "Error destroying session";
}
else { echo "SESSION ID IS NOT SET!";
	session_destroy();
	header("location: ../webui/main.php");
}

?>
