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

//Get the info from the iPhone request
$username = $_GET['username'];
$password = $_GET['password'];
$firstName = $_GET['firstName'];
$middleName = $_GET['middleName'];
$lastName = $_GET['lastName'];
$patientID = $_GET['patientID']; //Patient must provide the patient number associated with his/her medical record. 
						    //Helps us determine that they are who they say they are				    
				    
//Make sure we got all the fields
if (!isset($_GET['username']) || !isset($_GET['password']) || !isset($_GET['firstName']) || !isset($_GET['middleName']) || !isset($_GET['lastName']) || !isset($_GET['patientID'])) {
	echo "<response success=\"no\">";
	echo "<error1>Didn't get all fields</error1>";
	echo "</response>";
}	
else {//If all the fields are entered, proceed	
	//Now verify that this patient has a record in the medicalRecords table
	$testQuery = "SELECT * FROM medicalRecords WHERE firstName='$firstName' AND middleName='$middleName' AND lastName='$lastName' AND patientID='$patientID'";
	$rows = mysql_num_rows(mysql_query($testQuery));

	if ($rows != 1) {
		echo "<response success=\"no\">";
		echo "<error1>No match in database</error1>";
		echo "</response>";
	}
	else if ($rows == 1) { //Finally, if all is well, go ahead and create the user's account in the database
		//First, make sure the user account does not already exist
		$exists = mysql_num_rows(mysql_query("SELECT * FROM patients WHERE username='$username'"));
	
		if (!$exists) {
			$password = crypt($password); //encrypt the password before we store it in the database
			$query = "INSERT INTO patients VALUES('$patientID', '$username', '$password')";
			$result = mysql_query($query);

			//If we successfully created the user, respond with success.
			if ($result) {
				echo "<response success=\"yes\">";
				echo "<change1>Created Account</change1>";
				echo "</response>";
			}
			else {
				echo "<response success=\"no\">";
				echo "<error1>Could not create account</error1>";
				echo "</response>";
			}
		}//End if account doesn't already exist
		else {
			echo "<response success=\"no\">";		
			echo "<error1>This username already exists</error1>";
			echo "</response>";
		}
	}
}