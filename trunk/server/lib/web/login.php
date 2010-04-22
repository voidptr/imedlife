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
		header("location: ../../../webui/patientinfo.php");
	}

	else //redirect to main page if patient login was unsuccessful
		header("location: ../../../webui/main.php");
}
?>