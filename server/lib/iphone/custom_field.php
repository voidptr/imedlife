<?php 
//account_create.php - Creates a new patient account in the database from the iPhone
//Assumes that the verification of valid fields such as password length and password confirm have already been done by the iPhone before sending the request.

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
		if (!isset($_GET['fieldName']) || !isset($_GET['value'])) { 
			echo "<response success=\"no\">";
			echo "<error1>Didn't get all required parameters</error1>";
			echo "</response>";
		}	
		else { //We got all the required parameters
			//Get the info from the iPhone request
			$fieldName = $_GET['fieldName'];
			$value = $_GET['value'];	
			
			//Get the patientID to insert into the custom fields table
			$query = "SELECT patientID FROM patients LEFT JOIN sessions ON patients.username=sessions.username WHERE sessions.sessionID='$sessionID'";
			$row = mysql_fetch_array(mysql_query($query));
			$patientID = $row[0];

			//Now insert the custom field and it's corresponding value		
			$query = "INSERT INTO userInfoCustomFields(patientID, fieldName, value) VALUES('$patientID', '$fieldName', '$value')";
			$result = mysql_query($query);

			//If we successfully created the user, respond with success.
			if ($result) {
				echo "<response success=\"yes\">";
				echo "<change1>Created custom field</change1>";
				echo "</response>";
			}
			else {
				echo "<response success=\"no\">";
				echo "<error1>Could not create custom Field</error1>";
				echo "</response>";
			}
		}
	}//End check for valid sessionID
	else {
			echo "<response success=\"no\">";
			echo "<error1>Not a valid sessionID</error1>";
			echo "</response>";
	}
}