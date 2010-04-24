<?php
//display_uploaded_info.php - Renders the info for the userUploads to the iPhone upon the request.
include_once("lib/connect.php"); //Since this is included from process.php, files are relative to it, not this file

header("Content-Type:text/xml");
$file = fopen("lib/top.xml", "r"); //Just stores the first line of the xml file. Avoiding the issue with escaping
echo fgets($file); //output the xml version information, etc.
fclose($file);

//Get the sessionID, or error if we weren't supplied with one
if(isset($_GET['sessionID'])) {
	$sessionID = $_GET['sessionID'];
	
	//Check to see that the user is logged in
	include_once("lib/iphone/check_login.php");
	
	if ($loggedIn == true) {
		echo "<response>"; //All responses to the iPhone will be enclosed in a response element
		
		//Now get the information from the database
		
		//Get the patientID by using the sessionID and username
		$query = "SELECT patientID FROM patients LEFT JOIN sessions ON patients.username=sessions.username WHERE sessions.sessionID='$sessionID'";
		$row = mysql_fetch_array(mysql_query($query));
		$patientID = $row[0];
			
		//Now set everything up to display the userUpload(s)
		$query = "SELECT uploadID, patientID, noteDate, uploadType FROM userUploads WHERE patientID='$patientID'";
		$result = mysql_query($query); //Run the query
		$columns = mysql_query("SHOW COLUMNS FROM userUploads");	
		
		$fields = array(); //store all the fields here so we can reuse them
		while ($column = mysql_fetch_array($columns)) {//get all the field names without hard-coding them
			if ($column[0] != "upload") //Don't append the upload field, as it won't be sent
				$fields[] = $column[0]; //append the field name
		}
		
		$rowNum =1; //Used to create seperate elements when we have multiple records in the table
		while($row = mysql_fetch_array($result)) {//Go through all the records we get from the table
			for ($i=0; $i < count($fields); $i++) {
				$value = $row[$fields[$i]]; //won't parse for some reason, so stick it in a variable
					if (mysql_num_rows($result) > 1)
						echo "<$fields[$i]-$rowNum>$value</$fields[$i]-$rowNum>"; //finally create an xml element for each value
					else
						echo "<$fields[$i]>$value</$fields[$i]>";//Dont use the dashed format if we only have one row
			}
			$rowNum++;
		}
		echo "</response>";
	}//End if logged in
}//End check for sessionID
else {//User is not logged in
	echo "<response success=\"no\">";
	echo "<error1>No SessionID</error1>";
	echo "</response>";
}
?>