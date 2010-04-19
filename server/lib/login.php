<?php
//login.php - Handles loggin into the web app

session_start();
include_once("connect.php"); //Connects to the database so the user can be authenticated

//set up some variables to use for authenticating the user
$_SESSION['loggedIn'] = false; //used to see if the patient is logged in or not
$username = $_POST['username'];
$password = $_POST['password'];
$query = "SELECT * FROM 'patients' WHERE username='$username' AND password='$password'"; //Try the patient's table first
$result = mysql_query($query);

//Now try the doctors table if the user was not found in the patients table
if !(mysql_num_rows($result) == 1) {
	$query = "SELECT * FROM 'doctors' WHERE username='$username' AND password='password'";
	$result = mysql_query($query);
	
	if !(mysql_num_rows($result)  == 1) //redirect to the main page if neither attempt was successful
		header("location: ../../webui/main.php");
	else { //successful authentication of a doctor
		$_SESSION['loggedIn'] = true;
		$_SESSION['userType'] = "doctor";
		header("location: ../../webui/main.php");

	}
}
else { //successful authentication of a patient
	$_SESSION['loggedIn'] = true;
	$_SESSION['userType'] = "patient";
	header("location: ../../webui/main.php");
}
?>
