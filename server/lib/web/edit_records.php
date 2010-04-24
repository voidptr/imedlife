<?php
$tableRecID = ""; //We're going to find the specific
function restricted($restrictedFields, $field) { //Determines whether or not the given field is restricted
	$result = true;
	if (count($restrictedFields) === 0) return ($false); //Make sure we don't get an empty set of restricted fields.
	//Search throught the restricted fields to see if the field exists
	for($i=0; $i<count($restrictedFields); $i++) {
		if($restrictedFields[$i] == $field) {
			if ($field != "patientID" && strstr($field, "ID")) {//Use this to get the record ID for recording changes
				$tableRecID = $field;
			}
			break; //When we find it, stop searching.
		}
		else if ($i == count($restrictedFields)-1 && $restrictedFields[$i] != $field)
			$result = false; //If we've searched all the way through and haven't found the field, then it's not restricted
	}
	return ($result);
}//End restricted function

function editRecords($tableName) {//Displays the patient's information from the desired table in a table form.	
	//First build up the restricted fields (i.e. fields that are not allowed to be edited.
	$restrictedFields = array();//empty array. We will put them all in here
	$fieldNames = ""; //Need to store the field names to pass them when editing. So we don't have to hard-code Every table.
	$fields = array(); //We also need the fields to use for later
	
	//Now put all the restricted fields into the array. This can be done manually.
	//@TODO: LIST ALL THE RESTRICTED FIELDS HERE!!!!!
	$restrictedFields[] = "patientID";
	$restrictedFields[] = "medicalRecordID";
	$restrictedFields[] = "medicalHistoryID";
	$restrictedFields[] = "insuranceInfoID";
	$restrictedFields[] = "doctorID";
									
	$result = mysql_query("SHOW COLUMNS FROM $tableName"); //get all the fields from the chosen table
	if($result) {
		if (mysql_num_rows($result) > 0) {
			echo "<div class=\"viewtable\">";
			echo "<h3> Edit $tableName </h3>"; //Display which option has been chosen
			echo "<form method=\"post\" action=\"../server/process.php\"><table border=1 cellspacing=0 cellpadding=0><tr>";
	
			$first = true; //just used for building the fieldNames. So we can make it tab delimited
			while ($row = mysql_fetch_array($result)) { //read every field name from the table
				echo "<th>$row[0]</th>"; //Display the row name as a table column header
				$fields[] = $row[0]; //Append the fields so we can use set their names in the form
				if ($first == true) {
					$first = false;
					$fieldNames .= $row[0]; //Append the field name
				}
				else
					$fieldNames .= "\t" .$row[0]; //Append the field name with a tab delimiter in front of it
			}
			
			//Now Display the information for the specific patient
			$patientID = $_SESSION['patientID'];
			$query = "SELECT * FROM $tableName WHERE patientID='$patientID'";
			$result = mysql_query($query); //Run the query and retrieve the record.

			if($result) { //If we actually got a record from the query, print it out to the table.
				$record = mysql_fetch_array($result);
				echo "<tr>"; //Create a new row in which we'll store the info.
				
				for($i = 0; $i < mysql_num_fields($result); $i++) { //Print each field into the table						
					//Only make the field editable if it is not restricted
					if(!restricted($restrictedFields, $fields[$i]))
						echo "<td><input name=\"$fields[$i]\" value=\"$record[$i]\"/></td>";
					else {
						echo "<td><input disabled=\"disabled\" name=\"$fields[$i]\" value=\"$record[$i]\"/></td>"; //Don't allow user to edit if it's restricted
						
						//Now we need to set the tableRecID. So if it's restricted we know tableRecID has been set if we're not looking at patientID
						//Get the actualID to send now.
						if($fields[$i] == $tableRecID){ echo "FOUND:";
							$tableRecID = $record[$i]; //Assign the actual value instead of the field name
						} else echo "TABLERECID: " .$tableRecID;
					
	
					}				
				}
				echo "</tr>";//End the data row					    	
		        echo "</tr></table></div>"; //Close the table
		        echo "<input type=\"hidden\" name=\"table\" value=\"$tableName\"/>"; //Name of the table to change
		        echo "<input type=\"hidden\" name=\"tableRecID\" value=\"$tableRecID\"/>"; //ID in the table we're modifying
		        echo "<input type=\"hidden\" name=\"fields\" value=\"$fieldNames\"/>"; //Send the field names to be modified
		        echo "<input type=\"hidden\" name=\"request\" value=\"edit\"/>";
		        echo "<input name=\"applyChanges\" type=\"submit\" value=\"Submit Changes\" />";
	            echo "</form>";//Close the form
	            mysql_free_result($result); //release the resource
				
			}
			else
				echo "ERROR: Couldn't find result for patient using patientID=$patientID";	
		}
	}   
    }
//}//End editRecords function

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