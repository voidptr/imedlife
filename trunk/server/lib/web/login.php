<?php
//login.php - Handles loggin into the web app

session_start();
include_once("lib/connect.php"); //Connects to the database so the user can be authenticated

//set up some variables to use for authenticating the user
$_SESSION['loggedIn'] = false; //used to see if the patient is logged in or not
$username = $_POST['username'];
$password = $_POST['password'];
$isPatient = true; //used to determine whether user is a patient or not
$query = "SELECT * FROM patients WHERE username='$username'"; //Try the patient's table first
$result = mysql_query($query);
$rows = mysql_num_rows($result); //See if we get any rows (should only be one if the user is a patient)

if ($rows == 0) { //Username was not found in patient table
	$isPatient = false; //set to false so we can check for doctor login
}

if ($isPatient == false) { 
	//Now check to see if a doctor is trying to login
	$query = "SELECT * FROM doctors WHERE username='$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	$dbPassword = $row['password']; //The encrypted password stored in the database
	
	//Now compare the two hashes
	if (crypt($password, $dbPassword) == $dbPassword) {//successful authentication of a doctor
		$_SESSION['loggedIn'] = true;
		$_SESSION['userType'] = "doctor";
		$_SESSION['firstName'] = $row['firstName'];
		$_SESSION['lastName'] = $row['lastName'];
		
		//Set the sessionID in the DB for the doctor
		$query = "INSERT INTO sessions(username, sessionType) VALUES('$username', 'webui')"; //Insert their username and get their session ID
		if(mysql_query($query)) { //Successful insert
			//Now get the sessionID, so that we can give it to the iPhone
			$query = "SELECT * FROM sessions WHERE username='$username' AND sessionType='webui'"; //Query for the row we just created
			$res = mysql_query($query); //Run the query
			
			if($res) { //Make sure there is no problem selecting the row we just queried for
				$row = mysql_fetch_array($res);
				$_SESSION['sessionID'] = $row['sessionID']; //The actual sessionID we'll keep
				mysql_free_result($res); //Free result so we can reuse the variable without problems.
				
				//Get the doctorID for use with accessing tables
				$query = "SELECT doctorID FROM doctors WHERE username='$username'";
				$res = mysql_query($query);//Run the query
				
				if ($res) {
					$row = mysql_fetch_array($res);
					$_SESSION['doctorID'] = $row[0]; //Now we have the patientID that we can use in our session
					header("location: ../webui/patientinfo.php"); //Now redirect to the patient info when successful
				}
				else echo "Couldn't get the doctorID";
			}
			else { //Couldn't retrieve the new session row
				echo "Couldn't get sessionID";
			}
		}
	}//End successful authentication of a doctor
	else //redirect to main if doctor login was unsuccessful
		header("location: ../webui/main.php");
		echo "Couldn't login doctor";

}
else { //We know the username exists and it must be a patient, so now authenticate based on the password
	$row = mysql_fetch_array($result); //Get the password from the database
	$dbPassword = $row['password']; //The encrypted password stored in the database
	
	//Now compare the two hashes
	if (crypt($password, $dbPassword) == $dbPassword) {//successful authentication of a patient
		$_SESSION['loggedIn'] = true;
		$_SESSION['userType'] = "patient";
		
		//Set the sessionID in the DB
		$query = "INSERT INTO sessions(username, sessionType) VALUES('$username', 'webui')"; //Insert their username and get their session ID
		if(mysql_query($query)) { //Successful insert
			//Now get the sessionID, so that we can give it to the iPhone
			$query = "SELECT * FROM sessions WHERE username='$username' AND sessionType='webui'"; //Query for the row we just created
			$res = mysql_query($query); //Run the query
			
			if($res) { //Make sure there is no problem selecting the row we just queried for
				$row = mysql_fetch_array($res);
				$_SESSION['sessionID'] = $row['sessionID']; //The actual sessionID we'll keep
				mysql_free_result($res); //Free result so we can reuse the variable without problems.
				
				//Get the patientID for use with accessing tables
				$query = "SELECT patientID FROM patients WHERE username='$username'";
				$res = mysql_query($query);
				$row = mysql_fetch_array($res);

				//Get the patient's name for displaying on the webui while they're logged in
				$patientID = $row[0];
				//echo $patientID;
				$getName = "SELECT * FROM medicalRecords LEFT JOIN patients ON medicalRecords.patientID=patients.patientID WHERE patients.patientID='$patientID'";
				$result = mysql_query($getName);
				$record = mysql_fetch_array($result);
				
				//Now set the session variables we want to store
				$_SESSION['firstName'] = $record['firstName'];
				$_SESSION['lastName'] = $record['lastName'];
				$_SESSION['patientID'] = $patientID;
					
				header("location: ../webui/patientinfo.php"); //Now redirect to the patient info page when successful
			}
			else { //Couldn't retrieve the new session row
				echo "Couldn't get sessionID";
			}
		}//End successful insert
		else { //Couldn't insert into the sessions table
			echo "Couldn't get sessionID (couldn't start session)";
		}	
	}
	//else //redirect to main page if patient login was unsuccessful. They will still see the notice to login.
	//	header("location: ../webui/main.php");
}
?>