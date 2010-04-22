<?php 
//logout.php - Just destroys the user's session and logs the user out.
session_start();
session_destroy();
header("location: ../../../webui/main.php");
?>
