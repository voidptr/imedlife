<?php
//login.php - Handles loggin into the web app

session_start();
include_once("../connect.php"); //Connects to the database so the user can be authenticated

//set up some variables to use for authenticating the user
$_SESSION['loggedIn'] = false; //used to see if the patient is logged in or not
$username = $_POST['username'];
$password = $_POST['password'];
$isPatient = true; //used to determine whether user is a patient or not
$query = "SELECT * FROM patients WHERE username='$username'"; //Try the patient's table first
$result = mysql_query($query);

if (!$result) { //Username was not found in patient table
	$isPatient = false; //set to false so we can check for doctor login
}

if ($isPatient == false) { //Now check to see if a doctor is trying to login
	$query = "SELECT password FROM doctors WHERE username='$username'";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	$dbPassword = $row['password']; //The encrypted password stored in the database
	
	//Now compare the two hashes
	if (crypt($password, $dbPassword) == $dbPassword) {//successful authentication of a doctor
		$_SESSION['loggedIn'] = true;
		$_SESSION['userType'] = "doctor";
		header("location: ../../../webui/patientinfo.php");
	}
	else //redirect to main if doctor login was unsuccessful
		header("location: ../../../webui/main.php");

}
else { //We know the username exists so now authenticate based on the password
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
				
				if ($res) {
					$row = mysql_fetch_array($res);
					$_SESSION['patientID'] = $row[0]; //Now we have the patientID that we can use in our session
					header("location: ../../../webui/patientinfo.php"); //Now redirect to the main page when successful
				}
				else echo "Couldn't get the patientID";
			}
			else { //Couldn't retrieve the new session row
				echo "Couldn't get sessionID";
			}
		}//End successful insert
		else { //Couldn't insert into the sessions table
			echo "Couldn't get sessionID (couldn't start session)";
		}	
	}
	else //redirect to main page if patient login was unsuccessful
		header("location: ../../../webui/main.php");
}
?>