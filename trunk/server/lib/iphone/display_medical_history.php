<?php
//display_medical_rec.php - Renders the medical record to the iPhone upon the request.
include_once("lib/connect.php"); //Since this is included from process.php, files are relative to it, not this file

header("Content-Type:text/xml"); //set the type to be xml
$file = fopen("lib/top.xml", "r"); //Just stores the first line of the xml file. Avoiding the issue with escaping
echo fgets($file); //output the xml version information, etc.
fclose($file);

//setup variables to hold the GET data
$patientID = $_GET['patientID'];
$firstName = $_GET['firstName'];
$middleName= $_GET['middleName'];
$lastName = $_GET['lastName'];

echo "<response>"; //All responses to the iPhone will be enclosed in a response element

//Now get the information from the database
$check = "SELECT * FROM medicalRecords WHERE firstName='$firstName' AND middleName='$middleName' AND lastName='$lastName' AND patientID='$patientID'";

if (mysql_num_rows(mysql_query($check)) == 1) {//Always validate the user's credentials before showing them data
	$query = "SELECT * FROM medicalHistories WHERE patientID='$patientID'";
	$result = mysql_query($query); //Run the query
	$columns = mysql_query("SHOW COLUMNS FROM medicalHistories");	
	
	$fields = array(); //store all the fields here so we can reuse them
	while ($column = mysql_fetch_array($columns)) {//get all the field names without hard-coding them
		$fields[] = $column[0]; //append the field name
	}
	
	$rowNum =1; //Used to create seperate elements when we have multiple records in the table
	while($row = mysql_fetch_array($result)) {//Go through all the records we get from the table
		for ($i=0; $i < count($fields); $i++) {
			$value = $row[$fields[$i]]; //won't parse for some reason, so stick it in a variable
			if (mysql_num_rows($result) > 1)
				echo "<$fields[$i]-$rowNum>$value</$fields[$i]-$rowNum>"; //finally create an xml element for each value
			else
				echo "<$fields[$i]>$value</$fields[$i]>";
		}
		$rowNum++;
	}
	if(!result)
		echo "<error>No match in database</error>";
}
else
	echo "<error>No match in database</error>";
echo "</response>";
?>