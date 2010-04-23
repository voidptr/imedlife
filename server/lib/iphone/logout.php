<?php 
//logout.php - Just destroys the user's session and logs the user out.
//expects a reference to the sessionID that wants to logout

include_once("lib/connect.php"); //Connects to the database so the user can be authenticated

header("Content-Type:text/xml");
//Print out the xml header information from the file
$file = fopen("lib/top.xml", "r"); //open the file (file is relative to process.php, since it's including this file)
echo fgets($file); //print out the line to set up the xml output
fclose($file);

if (!isset($_GET['sessionID'])) { //Respond with error if we didn't get the sessionID
	echo "<response success=\"no\">";
	echo "<error1>Expecting sessionID to logout</error1>";
	echo "</response>";

}
else {
	$sessionID = $_GET['sessionID'];
	//Destroy the session information from the sessions table.
	$query = "SELECT * FROM sessions WHERE sessionID='$sessionID'"; //Just make sure that the sessionID actually exists
	$exists = mysql_query($query);
	
	if (mysql_num_rows($exists) < 1) {//Respond with error, already logged out, or never logged in.
		echo "<response success=\"no\">";
		echo "<error1>No such sessionID</error1>";
		echo "</response>";	
	}
	else { //We have a valid sessionID, now let's log them out
		$logout = "DELETE FROM sessions WHERE sessionID ='$sessionID'";
		$result = mysql_query($logout);
		
		if (!$result) { //Unsuccessful logging out
			echo "<response success=\"no\">";
			echo "<error1>Could not logout</error1>";
			echo "</response>";
	
		}
		else { //Just respond with success to acknowledge to the iPhone that the user is logged out
			echo "<response success=\"yes\">";
			echo "<action1>logged out</action1>";
			echo "</response>";
		
		}
	}
}
?>
