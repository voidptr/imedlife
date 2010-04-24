<?php 
//sync_complete.php - Removes all the rows from recordChanges when the iPhone let's us know that it has finished syncing
//Sends the contents of the recordChanges table, for the rows related to the specific user

include_once("lib/connect.php"); //Since this is included from process.php, files are relative to it, not this file

header("Content-Type:text/xml"); //set the type to be xml
$file = fopen("lib/top.xml", "r"); //Just stores the first line of the xml file. Avoiding the issue with escaping
echo fgets($file); //output the xml version information, etc.
fclose($file);

//Get the sessionID, or error if we weren't supplied with one
if(isset($_GET['sessionID'])) {
	$sessionID = $_GET['sessionID'];

	//Check to see that the user is logged in
	include_once("lib/iphone/check_login.php");
	
	if($loggedIn == true) {		
		//Now get the information from the database
		
		//Get the patientID by using the sessionID and username
		$query = "SELECT patientID FROM patients LEFT JOIN sessions ON patients.username=sessions.username WHERE sessions.sessionID='$sessionID'";
		$row = mysql_fetch_array(mysql_query($query));
		$patientID = $row[0];
		
		$query = "DELETE FROM recordChanges WHERE patientID='$patientID'"; //Removes all the recordChanges rows for the patient
		$result = mysql_query($query); //Run the query
		if($result) {
			$rowsAffected = mysql_affected_rows();
			
			//Now respond with success if we actually deleted rows
			if ($rowsAffected >= 1) {
				echo "<response success=\"yes\">";
				echo "<change1>$rowsAffected rows affected.</change1>";
				echo "</response>";
			}
			
			//Respond with error saying there was nothing to delete.
			else {
				echo "<response success=\"no\">";
				echo "<error1>No new changes to delete</error1>";
				echo "</response>";
			}
		}
		else if(!$result) {//Didn't find any record changes associated with this patient.
			echo "<response success=\"no\">";
			echo "<error1>No new changes to delete</error1>";
			echo "</response>";
		}
	}//End check for loggedIn
}//End check for sessionID
else {//User is not logged in
	echo "<response success=\"no\">";
	echo "<error1>No SessionID</error1>";
	echo "</response>";
}
?>