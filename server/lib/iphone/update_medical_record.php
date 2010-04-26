<?php 
//update_medical_record.php - Takes a list of requested changes and updates the medical record to reflect them
//Assumes that the iPhone will already restrict certain fields from being updated and will send a list of fields the patient has priviledges to edit

include_once("lib/connect.php"); //establish the initial connection to the database. 
								//(file is relative to process.php, since it's including this file)

header("Content-Type:text/xml");
//Print out the xml header information from the file
$file = fopen("lib/top.xml", "r"); //open the file (file is relative to process.php, since it's including this file)
echo fgets($file); //print out the line to set up the xml output
fclose($file);			    

//Make sure we are provided a sessionID
if(!isset($_GET['sessionID'])) {
		echo "<response success=\"no\">";
		echo "<error1>Didn't get a valid sessionID</error1>";
		echo "</response>";
}
else { //We were provided with a sessionID
	$sessionID = $_GET['sessionID'];

	//Then check to make sure it's valid
	include_once("lib/iphone/check_login.php"); //Path is relative to process.php, since this file is included from there
	
	if ($loggedIn == true) {				    
		//Make sure we got all the required parameters
		if (!isset($_GET['changes'])) { 
			echo "<response success=\"no\">";
			echo "<error1>Didn't get all required parameters</error1>";
			echo "</response>";
		}	
		else { //We got all the required parameters
			//Make sure that the list of changes is not empty!
			if ($_GET['changes'] != "") {
				//Get the info from the iPhone request
				$changes = $_GET['changes'];
				
				//Get the patientID to insert into the custom fields table
				$query = "SELECT patientID FROM patients LEFT JOIN sessions ON patients.username=sessions.username WHERE sessions.sessionID='$sessionID'";
				$row = mysql_fetch_array(mysql_query($query));
				$patientID = $row[0];

				//Now set the query up for building
				$query = "UPDATE patientBasicInfo SET "; //This is an update query, not a regular insert
				//Now parse the list of changes so that we can build the query.
				$changeList = explode(";", $changes); //Break up the string into an actual list (array)
				
				//Now get each individidual change
				for($i=0; $i<count($changeList); $i++) {
					//Now break up the field:value pair and add to the query string.
					$change = explode(":", $changeList[$i]);
					$query .= ($i == count($changeList)-1) ? $change[0] ."='$change[1]'" : $change[0] ."='$change[1]', "; //Don't put comma at the end of query
				}
				$query .= " WHERE patientID='$patientID'"; //Finish building the query.
				
				//Now run the query and make the update(s)		
				$result = mysql_query($query);
	
				//If we successfully created the user, respond with success.
				if ($result) {
					echo "<response success=\"yes\">";
					echo "<change1>Updated medical record</change1>";
					echo "</response>";
				}
				else {
					echo "<response success=\"no\">";
					echo "<error1>Could not updatre medical record</error1>";
					echo "</response>";
				}
			}//End check for non-empty list of changes
			else {//The changes list was empty. Respond with error
					echo "<response success=\"no\">";
					echo "<error1>No changes were requested</error1>";
					echo "</response>";			
			}
		}//End check for valid parameters
	}//End check for valid sessionID
	else {
			echo "<response success=\"no\">";
			echo "<error1>Not a valid sessionID</error1>";
			echo "</response>";
	}
}