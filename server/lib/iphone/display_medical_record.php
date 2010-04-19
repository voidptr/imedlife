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

//Now get the information from the database
$query = "SELECT * FROM medicalRecords WHERE firstName='$firstName' AND middleName='$middleName' AND lastName='$lastName' AND patientID='$patientID'";
$result = mysql_query($query); //Run the query

echo "<response>"; //All responses to the iPhone will be enclosed in a response element
if ($result) {
	$columns = mysql_query("SHOW COLUMNS FROM medicalRecords");
	
	$i =0; //used to loop through the fields
	while ($row = mysql_fetch_array($result)) {
		while ($column = mysql_fetch_array($columns)) {//get all the field names without hard-coding them
			echo "<$column[0]>$row[$i]</$column[0]>";//and print the values out in xml
			$i++;
		}
	}
}
else
	echo "<error>No match in database</error>";
echo "</response>";
?>