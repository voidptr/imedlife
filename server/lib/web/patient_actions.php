<?php 
//view patients.php - Returns a list of all the patients in the database. The doctor can select which one he/she would like to work with
include_once("../server/lib/connect.php");//path is relative to process.php
session_start();

function viewRecords($tableName, $patientID) {//Displays the patient's information from the desired table in a table form.									
	$result = mysql_query("SHOW COLUMNS FROM $tableName"); //get all the fields from the table
	if($result) {
		if (mysql_num_rows($result) > 0) {		
			echo "<div class=\"viewtable\">";
			echo "<h3>$tableName </h3>"; //Display which option has been chosen
			echo "<table border=1 cellspacing=0 cellpadding=2>";
			
			while ($row = mysql_fetch_array($result)) { //read every field name from the table
				echo "<th>$row[0]</th>"; //Display the row name as a header
			}
			
			//Now Display the information for the specific patient
			$query = "SELECT * FROM $tableName WHERE patientID='$patientID'";
			$result = mysql_query($query); //Run the query and retrieve the record.

			if($result) { //If we actually got a record from the query, print it out to the table.
				while ($record = mysql_fetch_array($result)) { //Get all records
					echo "<tr>"; //Create a new row in which we'll store the info.
					for($i = 0; $i < mysql_num_fields($result); $i++) { //Print each field into the table
						echo "<td> $record[$i] </td>";
					}
					echo "</tr>";//End the row
				}
				
			}
			else
				echo "ERROR: Couldn't find result for patient using patientID=$patientID";
								    	
		    echo "</table></div>";
		    mysql_free_result($result); //release the resource
		}
	}   
}//End viewRecords function
function editBasic($tableName) {//Displays the patient's information from the desired table in a table form.	
	//First build up the restricted fields (i.e. fields that are not allowed to be edited.
	$fieldNames = ""; //Need to store the field names to pass them when editing. So we don't have to hard-code Every table.
	$fields = array(); //We also need the fields to use for later
	//Put all the restricted fields into the array. This can be done manually.
	//@TODO: LIST ALL THE RESTRICTED FIELDS HERE!!!!!									
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
					if($fields[$i] != "patientID" && $fields[$i] != "insuranceInfoID")
						echo "<td><input name=\"$fields[$i]\" value=\"$record[$i]\"/></td>";
					else
						echo "<td><input disabled=\"disabled\" name=\"$fields[$i]\" value=\"$record[$i]\"/></td>"; //Don't allow user to edit if it's restricted								
				}
				//Get the tableRecID to log to the recordChanges table
				$tableRecID = $patientID;
				
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
}//End editRecords


//DISPLAY ALL THE OPTIONS
echo "<form class=\"forms\" method=\"post\" action=\"" .$_SERVER['PHP_SELF'] ."\">";
echo "<h3>Actions</h3>";
echo "<br/><input type=\"submit\" name=\"viewPatientInfo\" value=\"View Patient Basic Info\"/>";
echo "<input type=\"submit\" name=\"editPatientInfo\" value=\"Edit Patient Basic Info\"/>";	
echo "<input type=\"submit\" name=\"approveDoc\" value=\"Approve Doctor Request\"/>";
echo "<br/><input type=\"submit\" name=\"viewRecord\" value=\"View Medical Record\"/>";	
echo "<input type=\"submit\" name=\"customField\" value=\"Add Custom Information\"/>";
echo "<input type=\"submit\" name=\"editNote\" value=\"Edit Patient Note\"/>";
echo "<input type=\"submit\" name=\"addNote\" value=\"New Patient Note\"/>";	
echo "<input type=\"hidden\" name=\"option\" value=\"viewPatients\"/>";
echo "</form>";

//New Medical Record Option
//Show the patient's entire medical record
if (isset($_POST['viewRecord'])) {
	$patientID = $_SESSION['patientID'];
	
	viewRecords("patientBasicInfo", $patientID);
	viewRecords("medicalHistories", $patientID);
	viewRecords("healthcareProviders", $patientID);
	viewRecords("tests", $patientID); //TESTS AND NOTES NEED TO BE HANDLED DIFFERENTLY
	viewRecords("notes", $patientID);
	viewRecords("userInfoCustomFields", $patientID);

	
} //End New Medical History Option 

//Option to View Basic Patient Info
if (isset($_POST['viewPatientInfo'])) { 
	$patientID = $_SESSION['patientID'];
		
	//First see if they have created any basic info
	$basic = mysql_query("SELECT * FROM patientBasicInfo WHERE patientID='$patientID'");
	if ($basic) {//Query was successful
		if (mysql_num_rows($basic) > 0) {
			//Display the Patient's information if they have already entered it
			viewRecords("patientBasicInfo", $patientID);
			viewRecords("insuranceInfo", $patientID);

		}
		else { //User has not created any basic information yet so make them ?>
			<form class="forms" method="post" action="../server/process.php">
				<h3> New Patient Basic Info Record</h3><p> <b>Patient Name</b></p>
				<b>First:</b> <input type="text" name="firstName" />
				<b>Middle:</b> <input type="text" name="middleName" />
				<b>Last:</b> <input type="text" name="lastName" /><br/>
				<b>Address:</b> <input type="text" name="address" />
				<b>Phone Number:</b> <input type="text" name="phoneNumber" /><br/>
				<b>Date of Birth:</b> <input type="text" name="dateOfBirth" /><br/><br/>
				<b>Sex:</b> (M or F) <input type="text" name="sex" />
				<b>Hair Color:</b> <input type="text" name="hairColor" />
				<b>Eye Color:</b> <input type="text" name="eyeColor" /><br/>
				<b>Ethnicity:</b> <input type="text" name="ethnicity" />
				<b>Height:</b> <input type="text" name="height" />
				<b>Weight:</b> <input type="text" name="weight" /><br/>
				<b>Blood Type:</b> <input type="text" name="bloodType" />
				<b>Allergies:</b> <input type="text" name="allergies" />
				<b>Emergency Name:</b> <input type="text" name="emergencyName" /><br/>
				<b>Emergency Number:</b> <input type="text" name="emergencyNumber" />
				<b>Emergency Address:</b> <input type="text" name="emergencyAddress" />
				<br/>
			    
			    <!-- CREATE Insurance Info -->
				<h3> Insurance Company Information </h3>
				<b>Insurance Company:</b> <input type="text" name="insuranceCompany" />
				<b>Policy Number:</b> <input type="text" name="policyNumber" /><br/>
			    
				<input type="hidden" name="request" value="create" /><br/>
				<input type="submit" value="Create Record and Continue" />
			</form>
<?php			
		}
	}
	
}//End View Patient Info Option

//Option to Edit Patient Info
if (isset($_POST['editPatientInfo'])) {
	$patientID = $_SESSION['patientID'];
		
	//First see if they have created any basic info
	$basic = mysql_query("SELECT * FROM patientBasicInfo WHERE patientID='$patientID'");
	if ($basic) {//Query was successful
		if (mysql_num_rows($basic) > 0) {
			//Display the Patient's Basic information to edit
			editBasic("patientBasicInfo");
			editBasic("insuranceInfo");
		}
		else { //User has not created any basic information yet so make them ?>
			<form class="forms" method="post" action="../server/process.php">
				<h3> New Patient Basic Info Record</h3><p> <b>Patient Name</b></p>
				<b>First:</b> <input type="text" name="firstName" />
				<b>Middle:</b> <input type="text" name="middleName" />
				<b>Last:</b> <input type="text" name="lastName" /><br/>
				<b>Address:</b> <input type="text" name="address" />
				<b>Phone Number:</b> <input type="text" name="phoneNumber" /><br/>
				<b>Date of Birth:</b> <input type="text" name="dateOfBirth" /><br/><br/>
				<b>Sex:</b> (M or F) <input type="text" name="sex" />
				<b>Hair Color:</b> <input type="text" name="hairColor" />
				<b>Eye Color:</b> <input type="text" name="eyeColor" /><br/>
				<b>Ethnicity:</b> <input type="text" name="ethnicity" />
				<b>Height:</b> <input type="text" name="height" />
				<b>Weight:</b> <input type="text" name="weight" /><br/>
				<b>Blood Type:</b> <input type="text" name="bloodType" />
				<b>Allergies:</b> <input type="text" name="allergies" />
				<b>Emergency Name:</b> <input type="text" name="emergencyName" /><br/>
				<b>Emergency Number:</b> <input type="text" name="emergencyNumber" />
				<b>Emergency Address:</b> <input type="text" name="emergencyAddress" />
				<br/>
			    
			    <!-- CREATE Insurance Info -->
				<h3> Insurance Company Information </h3>
				<b>Insurance Company:</b> <input type="text" name="insuranceCompany" />
				<b>Policy Number:</b> <input type="text" name="policyNumber" /><br/>
			    
				<input type="hidden" name="request" value="create" /><br/>
				<input type="submit" value="Create Record and Continue" />
			</form>
<?php			
		}
	}
	

}

//Option to Approve doctor
if (isset($_POST['approveDoc'])) {
	$patientID = $_SESSION['patientID'];
	//Show a (potential) list of doctors awaiting approval
	$result = mysql_query("SELECT * FROM approvedDoctors WHERE patientID='$patientID' AND approved='0'");
	
	if ($result) {//Query executed successfully.
		
		echo "<div class=\"forms\"><table cellpadding=5 cellspacing=0 border=1>";
		echo "<tr><td border=0><b> Pending Requests </b></td><td><b>Action</b></td></tr>";
		while ($row = mysql_fetch_array($result)) { //Get all the pending requests
			$doctorID = $row[1];
			
			//Now get the doctor's name, so we can get his/her name to show the patient
			$query = "SELECT firstName, middleName, lastName FROM doctors LEFT JOIN approvedDoctors ON doctors.doctorID=approvedDoctors.doctorID WHERE approvedDoctors.doctorID='$doctorID'";
			$res = mysql_query($query);
			
			if ($res) {
				$record = mysql_fetch_array($res);
				echo "<form class=\"forms\" method=\"post\" action=\"" .$_SERVER['PHP_SELF'] ."\">";
				echo "<input type=\"hidden\" name=\"doctorID\" value=\"$doctorID\"";
				echo "<tr><td>$record[0] $record[1] $record[2]</option></td><td> <input name=\"approve\" type=\"submit\" value=\"Approve\"/></td></tr>"; //Display the doctor's name as an option to select
				echo "<input type=\"hidden\" name=\"approveDoc\" value=\"approveDoc\"/>";
				echo "</form>";
			}
		}
		echo "</table></div>";

	}//Didn't get a valid result
	else echo "No doctors have requested your approval at this time."; //No requests were found
		
	//Now approve the doctor when the form is submitted
	if (isset($_POST['approve']) && isset($_POST['doctorID'])) {
		$docID = $_POST['doctorID'];//Create new variables just so they don't conflict
		$patID = $_SESSION['patientID'];
		$approve = mysql_query("UPDATE approvedDoctors SET approved='1' WHERE patientID='$patID' AND doctorID='$docID'");
		
		//See if the query was successful
		if ($query) {
			echo "Doctor Approved.";
		}
	}
}//End Request Approval Option

if (isset($_POST['addNote'])) { //Option to Add Notes/Image and voice?>
	<form class="forms" enctype="multipart/form-data" action="../server/lib/web/upload.php" method="post">
		<h3>Upload New Patient Note</h3>
		Note Date: <input type="text" name="testDate" /><br/>
		Note:<br/><textarea name="comment" cols="50" rows="10">Enter Comment(s)...</textarea><br/>
	    <input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
	    <b>Image or Voice Recording (optional): </b><input name="userFile[]" type="file" />
	    <input type="submit" value="Submit" />
	</form>
<?php
}//End Add Tests Procedures Option

//Option to add custom information
if (isset($_POST['customField'])) {
	echo "<div class=\"forms\">";
	
	//Show the user a form to add the field?>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		Custom Field Name: <input type="text" name="fieldName" />
		Value: <input type="text" name="fieldValue" />
		<input type="hidden" name="customField" value="customField" />
		<input type="submit" name="addCustom" value="Submit" />
	</form>
	<?php
	echo "</div>";
	
	//Now add the custom field once the user has submitted it.
	if(isset($_POST['addCustom'])) {
		//Get info for insertinfg
		$patientID = $_SESSION['patientID'];
		$fieldName = $_POST['fieldName'];
		$fieldValue = $_POST['fieldValue'];
		
		//Try to insert the new record
		$result= mysql_query("INSERT INTO userInfoCustomFields(patientID, fieldName, value) VALUES('$patientID', '$fieldName', '$fieldValue')");
		
		//See if we were successful
		if ($result) {
			echo "<div class=\"forms\">";
			echo "<p><b>Custom Information Added Successfully</b></p>";
			echo "</div>";
		}
		else {
			echo "<div class=\"forms\">";
			echo "<p><b>Could not add custom information at this time. Please try again later.</b></p>";
			echo "</div>";
		
		}
	}
}

echo "</div>";
?>