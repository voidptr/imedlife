<?php 
//logout.php - Just destroys the user's session and logs the user out.
session_destroy();
header("location: ../../webui/main.php");
?>
