<?php

//Wah Displays the patient's Medical Record information in a table form.
if (isset($_POST['MedicalRecord']) )										
$result = mysql_query("SHOW COLUMNS FROM medicalRecords"); //get all the fields from the medicalRecords table
if (mysql_num_rows($result) > 0) {
	echo "<div class=\"viewtable\">";
	echo "<h3> View Medical Record </h3>"; //Display which option has been chosen
	echo "<table border=1 cellspacing=0 cellpadding=5>";
	
	while ($row = mysql_fetch_array($result)) { //read every field name from the table
		echo "<th>$row[0]</th>"; //Display the row name as a header
	}
	
	//TODO: Display the actual information for the patient in the table.
						    	
    echo "</table></div>";
    mysql_free_result($result); //release the resource
}
										
//Wah Displays the patient's Medical History information in a table form.
if (isset($_POST['MedicalHistory']) )										
$result = mysql_query("SHOW COLUMNS FROM medicalhistories"); //get all the fields from the medicalRecords table
if (mysql_num_rows($result) > 0) {
	echo "<div class=\"viewtable\">";
	echo "<h3> View Medical Histories </h3>"; //Display which option has been chosen
	echo "<table border=1 cellspacing=0 cellpadding=5>";
	
	while ($row = mysql_fetch_array($result)) { //read every field name from the table
		echo "<th>$row[0]</th>"; //Display the row name as a header
	}
	
	//TODO: Display the actual information for the patient in the table.
						    	
    echo "</table></div>";
    mysql_free_result($result); //release the resource
}


//Wah Displays the patient's Healthcare Providers information in a table form.
if (isset($_POST['HealthcareProviders']) )										
$result = mysql_query("SHOW COLUMNS FROM healthcareproviders"); //get all the fields from the medicalRecords table
if (mysql_num_rows($result) > 0) {
	echo "<div class=\"viewtable\">";
	echo "<h3> Healthcare Providers </h3>"; //Display which option has been chosen
	echo "<table border=1 cellspacing=0 cellpadding=5>";
	
	while ($row = mysql_fetch_array($result)) { //read every field name from the table
		echo "<th>$row[0]</th>"; //Display the row name as a header
	}
	
	//TODO: Display the actual information for the patient in the table.
						    	
    echo "</table></div>";
    mysql_free_result($result); //release the resource
}


//Wah Displays the patient's Insurance Company information in a table form.
if (isset($_POST['InsuranceCompanyInformation']) )										
$result = mysql_query("SHOW COLUMNS FROM insuranceinfo"); //get all the fields from the medicalRecords table
if (mysql_num_rows($result) > 0) {
	echo "<div class=\"viewtable\">";
	echo "<h3> Insurance Company Information </h3>"; //Display which option has been chosen
	echo "<table border=1 cellspacing=0 cellpadding=5>";
	
	while ($row = mysql_fetch_array($result)) { //read every field name from the table
		echo "<th>$row[0]</th>"; //Display the row name as a header
	}
	
	//TODO: Display the actual information for the patient in the table.
						    	
    echo "</table></div>";
    mysql_free_result($result); //release the resource
}

                                        
?>