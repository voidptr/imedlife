<?php
//edit.php - Allows edits of certain tables and fields through the webui. 
//Also records all changes into the recordChanges table for synchronization

session_start();

include_once("lib/connect.php");

if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true) {//proceed if this is a valid user. Should be since it's coming from process.php
	$patientID = $_SESSION['patientID']; //Get patientID into a variable for easy use
	
	//Get the table to modify and all the modified fields from POST
	$tableName = $_POST['table'];
	
	//Get the tableRecID to record in the recordChanges table
	$tableRecID = $_POST['tableRecID'];
	
	//Start building the query. We will finish building it in the loop
	$query = "UPDATE $tableName SET "; //This is an update, not a regular insertion
	
	//Now get the list of fields from that table so we can process automatically
	$fields = $_POST['fields'];
	$fields = explode("\t", $fields); //Break up into the actual fields from the tab-delimited string
			
	//Now add the fields/values to be set in the query
	for($i=0; $i<count($fields); $i++) {
		$value = $_POST[$fields[$i]]; 
		if ($value != "") {//If it is a restricted field, we better not try to update it
			$query .= ($i == count($fields)-1) ? $fields[$i] ."='" .$value."'" : $fields[$i] ."='" .$value ."', "; //Don't put comma at end when we reach the last field
		}
	}
	$query .= " WHERE patientID='$patientID'"; //Finish the building the query
	
	//Modify the record in the database
	$success = mysql_query($query);
	
	if($success) {
		//Record the changes in the recordChanges table by inserting a new transaction into it.
		$query = "INSERT INTO recordChanges(patientID, tableChanged, tableRecID) VALUES('$patientID', '$tableName', '$tableRecID')";
		$result = mysql_query($query);
		
		if($result) {//All done, redirect back to the edit page
			header("location:../webui/patientinfo.php");
		}
		else echo "DID NOT RECORD THE CHANGE!";
	}
}

?>