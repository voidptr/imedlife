<?php
function editRecords($tableName) {//Displays the patient's information from the desired table in a table form.	
	//First build up the restricted fields (i.e. fields that are not allowed to be edited.
	$restrictedFields = array();//empty array. We will put them all in here
									
	$result = mysql_query("SHOW COLUMNS FROM $tableName"); //get all the fields from the chosen table
	if($result) {
		if (mysql_num_rows($result) > 0) {
			echo "<div class=\"viewtable\">";
			echo "<h3> Edit $tableName </h3>"; //Display which option has been chosen
			echo "<form method=\"post\" action=\"../server/lib/proess.php\"><table border=1 cellspacing=0 cellpadding=0><tr>";
	

			while ($row = mysql_fetch_array($result)) { //read every field name from the table
				echo "<th>$row[0]</th>"; //Display the row name as a table column header
			}
			
			//Now Display the information for the specific patient
			$patientID = $_SESSION['patientID'];
			$query = "SELECT * FROM $tableName WHERE patientID='$patientID'";
			$result = mysql_query($query); //Run the query and retrieve the record.

			if($result) { //If we actually got a record from the query, print it out to the table.
				$record = mysql_fetch_array($result);
				echo "<tr>"; //Create a new row in which we'll store the info.
				for($i = 0; $i < mysql_num_fields($result); $i++) { //Print each field into the table
					echo "<td><input name=\"$row[i]\" value=\"$record[$i]\"/></td>";
				}
				echo "</tr>";//End the data row
			}
			else
				echo "ERROR: Couldn't find result for patient using patientID=$patientID";						    	
		        echo "</tr></table></div>"; //Close the table
		        echo "<input type=\"hidden\" name=\"request\" value=\"edit\"/>";
		        echo "<input name=\"ApplyChanges\" type=\"submit\" value=\"Submit Changes\" />";
                echo "</form>";//Close the form
                mysql_free_result($result); //release the resource
		}
	}   
    }
//}//End viewRecords function

//If MedicalRecord Pressed then view the corresponding table 
if(isset($_POST['MedicalRecord']))
	editRecords("medicalRecords");                            

//If MedicalHistory Pressed then view the corresponding table
if(isset($_POST['MedicalHistory']))
	editRecords("medicalHistories");                            

//If HealthcareProviders Pressed then view the corresponding table
if(isset($_POST['HealthcareProviders']))
	editRecords("healthcareProviders");                            

//If InsuranceCompanyInformation Pressed then view the corresponding table
if(isset($_POST['InsuranceCompanyInformation']))
	editRecords("insuranceInfo");                            



?>