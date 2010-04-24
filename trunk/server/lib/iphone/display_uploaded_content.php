<?php
//display_uploaded_content.php - Renders the uploaded file to the iPhone upon the request.
include_once("lib/connect.php"); //Since this is included from process.php, files are relative to it, not this file

//Get the sessionID, or error if we weren't supplied with one
if(isset($_GET['sessionID']) && isset($_GET['uploadID'])) {
	$sessionID = $_GET['sessionID'];
	$uploadID = $_GET['uploadID']; //We will expect to get a request for one uploadID at a time
	
	//Check to see that the user is logged in
	include_once("lib/iphone/check_login.php");
	
	if ($loggedIn == true) {		
		//Now get the information from the database
		
		//Get the patientID by using the sessionID and username
		$query = "SELECT patientID FROM patients LEFT JOIN sessions ON patients.username=sessions.username WHERE sessions.sessionID='$sessionID'";
		$row = mysql_fetch_array(mysql_query($query));
		$patientID = $row[0];
			
		//Now set everything up to display the userUpload(s)
		$query = "SELECT upload, uploadType FROM userUploads WHERE patientID='$patientID' AND uploadID='$uploadID'";
		$result = mysql_query($query); //Run the query
		$columns = mysql_query("SHOW COLUMNS FROM userUploads");	
		
		$fields = array(); //store all the fields here so we can reuse them
		while ($column = mysql_fetch_array($columns)) {//get all the field names without hard-coding them
			$fields[] = $column[0]; //append the field name
		}
		//Now get the file ready to send
		$row= mysql_fetch_array($result);
		$type = $row[1];
		$file = $row[0]; //display the file
		header("Content-Type:$type");//Responds with the image
		echo $file;
		
		if(!result)
			echo "<error1>No match in database</error1>";
		echo "</response>";
	}//End if logged in
}//End check for sessionID
else {//User is not logged in
	header("Content-Type:text/xml");
	echo "<response success=\"no\">";
	echo "<error1>Didn't get both SessionID and uploadID</error1>";
	echo "</response>";
}
?>