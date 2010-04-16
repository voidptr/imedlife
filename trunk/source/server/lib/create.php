<?php 
//create.php - Creates a new patient or doctor account in the database

include_once("connect.php"); //establish the initial connection to the database

//First set up the patient
if (isset($_POST['createType']) && $_POST['createType'] == "patient") {
	//Get the info from the create form
	$username = $_POST['username'];
	$password = $_POST['password']; //password is already encrypted when we get it from post
	$firstName = $_POST['firstName'];
	$middleName = $_POST['middleName'];
	$lastName = $_POST['lastName'];
	$address = $_POST['address'];
	$phoneNumber = $_POST['phoneNumber'];
	$patientNumber = $_POST['patientNumber']; //Use this to find the patient before we give them a webui account?

	//Now verify that this patient has a record in the medicalRecords table
	$table = "medicalRecords";
}

