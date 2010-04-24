<?php
//edit.php - Allows edits of certain tables and fields through the webui. 
//Also records all changes into the recordChanges table for synchronization

session_start();

include_once("lib/connect.php");

if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true) {//proceed if this is a valid user. Should be since it's coming from process.php
	$patientID = $_SESSION['patientID']; //Get patientID into a variable for easy use
	
	//Get the table to modify and all the modified fields from POST
	
	//Modify the fields in the database
	
	//Record the changes in the recordChanges table
	$query = "INSERT INTO recordChanges(patientID, tableChanged, tableRecID) VALUES('$patientID', '$table', '$tableID')";
}

?>