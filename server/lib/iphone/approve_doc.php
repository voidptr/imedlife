<?php 
//approve_doc.php - Takes a doctorID and sets the approved? field to true
//Assumes that the iPhone has already sync'ed and knows the doctorID of the doctor the user has chosen to approve.

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
		if (!isset($_GET['doctorID'])) { 
			echo "<response success=\"no\">";
			echo "<error1>Didn't get all required parameters</error1>";
			echo "</response>";
		}	
		else { //We got all the required parameters
			//Make sure that the doctorID is actually provided
			if ($_GET['doctorID'] != "") {
				//Get the info from the iPhone request
				$doctorID = $_GET['doctorID'];
				
				//Get the patientID to insert into the custom fields table
				$query = "SELECT patientID FROM patients LEFT JOIN sessions ON patients.username=sessions.username WHERE sessions.sessionID='$sessionID'";
				$row = mysql_fetch_array(mysql_query($query));
				$patientID = $row[0];
				
				//See if there is a doctor/patient pair before we go through the trouble of trying to update
				$check = "SELECT * FROM approvedDoctors WHERE patientID='$patientID' AND doctorID='$doctorID'";
				$result = mysql_query($check); //Check to see if anything was even modified
				
				if(mysql_num_rows($result) > 0) {
					//Now set up the approval query
					$query = "UPDATE approvedDoctors SET approved='1' WHERE patientID='$patientID' AND doctorID='$doctorID'";				
					
					//Now run the query and make the update(s)		
					$approve = mysql_query($query);
					
					//If we successfully created the user, respond with success.
					if ($approve) {
						echo "<response success=\"yes\">";
						echo "<change1>Doctor Approved</change1>";
						echo "</response>";
					}
				}//If there's no association, then display error
				else {
					echo "<response success=\"no\">";
					echo "<error1>No doctorID associated with this patient</error1>";
					echo "</response>";
				}
			}//End check for non-empty list of changes
			else {//The changes list was empty. Respond with error
					echo "<response success=\"no\">";
					echo "<error1>No doctorID provided</error1>";
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