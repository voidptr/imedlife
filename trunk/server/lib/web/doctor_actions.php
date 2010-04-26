<?php 
//view patients.php - Returns a list of all the patients in the database. The doctor can select which one he/she would like to work with
include_once("../server/lib/connect.php");//path is relative to process.php
session_start();
function viewRecords($tableName, $patientID) {//Displays the patient's information from the desired table in a table form.									
	$result = mysql_query("SHOW COLUMNS FROM $tableName"); //get all the fields from the patientBasicInfo table
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
					
						//Show yes/no instead of 1/0 where appropriate
						if ($tableName == "medicalHistories") {
							if ($record[$i] == "0" && $i >2) //Don't format the IDs
								echo "<td> <form><input type=\"checkbox\" disabled=\"disabled\"/></form> </td>";
							else if ($record[$i] == "1" && $i>2)
								echo "<td> <input type=\"checkbox\" checked=\"yes\" disabled=\"disabled\"/></form> </td>";
							else echo "<td> $record[$i] </td>";
						}
						else echo "<td> $record[$i] </td>";
					}
				}
				echo "</tr>";//End the row
				
			}
			else
				echo "ERROR: Couldn't find result for patient using patientID=$patientID";
								    	
		    echo "</table></div>";
		    mysql_free_result($result); //release the resource
		}
	}   
}//End viewRecords function

//Show the list of patients to the doctor
$query = "SELECT patientID, firstName, middleName, lastName FROM patientBasicInfo ORDER BY lastName ASC";
$result = mysql_query($query);
if ($result) {//Display a listing of all the patients (names) for the doctor to choose from
	echo "<div class=\"forms\">"
			."<form method=\"post\" action=\"". $_SERVER['PHP_SELF'] ."\""
			."<b>Choose a patient to add Medical History: </b>"
			."<select name=\"patientID\">";
	
	while ($row = mysql_fetch_array($result)) {//Get all the rows
		if (isset($_POST['patientID']) && $row[0] == $_POST['patientID']) //Keep the current patient selected unless doctor changes it.
			echo "<option value=\"$row[0]\" selected=\"selected\"> $row[1] $row[2] $row[3]</option>";
		else 
			echo "<option value=\"$row[0]\"> $row[1] $row[2] $row[3]</option>";
	}
	//Doctor's Options
	echo "</select>";
		echo "<div class=\"forms\">";
		echo "<h3>Actions</h3>";
		echo "<br/><input type=\"submit\" name=\"viewPatientInfo\" value=\"View Patient Info\"/>";	
		echo "<input type=\"submit\" name=\"requestApproval\" value=\"Request Patient Approval\"/>";
		echo "<br/><input type=\"submit\" name=\"addHistory\" value=\"New Medical History Record\"/>";
		//TODO: ADD EDIT CAPABILITY echo "<input type=\"submit\" name=\"editHistory\" value=\"Edit Medical History Record\"/>";
		//TODO: ADD DELETE CAPABILITY echo "<input type=\"submit\" name=\"deleteHistory\" value=\"Delete Medical History Record\"/>";	
		echo "<input type=\"submit\" name=\"addTest\" value=\"New Test Procedures Record\"/>";	
		echo "<input type=\"hidden\" name=\"option\" value=\"viewPatients\"/>";	
  	echo "</div></form>";
}
else {
echo "No patients found. Check back later.";
}

//New Medical History Option
//Now if the doctor has selected a patient, then let them make a new medical history
if (isset($_POST['addHistory'])) {
	$patientID = $_POST['patientID'];
	$getName = mysql_fetch_array(mysql_query("SELECT firstName, middleName, lastName FROM patientBasicInfo WHERE patientID='$patientID'"));
	$firstName = $getName[0]; $middleName = $getName[1]; $lastName = $getName[2];
	
	//Now build the (huge) form in HTML
?>

	<form class="forms" method="post" action="../server/process.php">
		<?php echo "<p><b><u> New Medical History Record for patient:</u></b> $firstName $middleName $lastName</p><br/>" ?>
		Visit Date: <input type="text" name="visitDate"/>
		<table border="1" cellspacing="0">
		<th>Complains of:</th> <th>Denies:</th>
		<tr><td>Headache<input type="checkbox" name="complains_headache" value="1"/></td><td>Headache<input type="checkbox" name="denies_headache" value="1"/></td></tr>
		<tr><td>Chest Pain <input type="checkbox" name="complains_chestPain" value="1"/></td><td>Chest Pain <input type="checkbox" name="denies_chestPain" value="1"/></td></tr>
		<tr><td>Palpitations <input type="checkbox" name="complains_palpitations" value="1"/></td><td>Palpitations <input type="checkbox" name="denies_palpitations" value="1"/></td></tr>
		<tr><td>Dyspnea with Exertion<input type="checkbox" name="complains_dyspneaWithExertion" value="1"/></td><td>Dyspnea with Exertion<input type="checkbox" name="denies_dyspneaWithExertion" value="1"/></td></tr>
		<tr><td>Orthopnea<input type="checkbox" name="complains_orthopnea" value="1"/></td><td>Orthopnea<input type="checkbox" name="denies_orthopnea" value="1"/></td></tr>
		<tr><td>PND <input type="checkbox" name="complains_PND" value="1"/></td><td>PND <input type="checkbox" name="denies_PND" value="1"/></td></tr>
		<tr><td>Peripheral Edema <input type="checkbox" name="complains_peripheralEdema" value="1"/></td><td>Peripheral Edema <input type="checkbox" name="denies_peripheralEdema" value="1"/></td></tr>
		<tr><td>Visual Symptoms <input type="checkbox" name="complains_visualSymptoms" value="1"/></td><td>Visual Symptoms <input type="checkbox" name="denies_visualSymptoms" value="1"/></td></tr>
		<tr><td>Neurologic Problems <input type="checkbox" name="complains_neurologicProblems" value="1"/></td><td>Neurologic Problems <input type="checkbox" name="denies_neurologicProblems" value="1"/></td></tr>
		<tr><td>Syncope <input type="checkbox" name="complains_syncope" value="1"/></td><td>Syncope <input type="checkbox" name="denies_syncope" value="1"/></td></tr>
		<tr><td>Side Effects from Treatment <input type="checkbox" name="complains_sideEffectsFromTreatment" value="1"/></td><td>Side Effects from Treatment <input type="checkbox" name="denies_sideEffectsFromTreatment" value="1"/></td></tr>
		</table>
		<br/>
		CHFE Education Provided To: <input type="text" name="CHFEducationProvidedTo"/><br/>
		Patient Checking Weight?<input type="checkbox" name="patientCheckingWeight" value="1"/><br/>
		Patient understands Sodium Issues Management?<input type="checkbox" name="understandsSodiumIssuesManagement" value="1"/><br/>
		Patient following Sodium Issues Management? <input type="checkbox" name="followingSodiumIssuesManagement" value="1"/><br/>
		Patient understands medications and RX plan? <input type="checkbox" name="understandsMeds" value="1"/><br/>
		Compliant with medications and RX plan? <input type="checkbox" name="compliantWithMeds" value="1"/><br/>
		Able to do ADLs? <input type="checkbox" name="ableToDoADLs" value="1"/><br/>
		Enrolled in or completed CHF Ed Program? <input type="checkbox" name="CHFEdProgram" value="1"/><br/>
		Side Effects to any Medications? <input type="checkbox" name="medicationSideEffects" value="1"/>
		
		<h3>Vital Signs</h3> 
		Current Weight: <input type="text" name="currentWeight"/><br/>
		Last Weight: <input type="text" name="lastWeight"/><br/>
		Dry Weight: <input type="text" name="dryWeight"/><br/>
		Respirations: <input type="text" name="respirations"/><br/>
		Height: <input type="text" name="height"/><br/>
		Pulse Rate: <input type="text" name="pulseRate"/><br/>
		Pulse Rhythm: <input type="text" name="pulseRhythm"/><br/>
		Blood Pressure (Systolic): <input type="text" name="bloodPressureSystolic"/><br/>
		Blood Pressure (Diastolic): <input type="text" name="bloodPressureDiastolic"/><br/>
		
		<h3>Healthcare Provider Information</h3>
		Healthcare Facility Name: <input type="text" name="healthcareName"/><br/>
		Address: <input type="text" name="healthcareAddress" /> <br/>
		Healthcare Facility Phone Number: <input type="text" name="healthcareNumber"/>
		
		<input type="hidden" name="patientID" value="<?php echo $patientID; ?>"/>
		<input type="hidden" name="request" value="createMedHist" />
		<input type="submit" value="Done"/>
	</form>

<?php } //End New Medical History Option 

//Option to edit Medical History
/* TODO: ADD EDIT CAPABILITY
if (isset($_POST['editHistory'])) { 
	$patientID = $_POST['patientID'];
	$doctorID = $_SESSION['doctorID'];
	//Get all the records pertaining to that doctor
	$result = mysql_query("SELECT * FROM medicalHistories WHERE patientID='$patientID' AND doctorID='$doctorID'");
	
	if ($result) {//Query executed successfully.
		//Show the list of visitDates and patient names for the doctor to select from
			$columns = mysql_query("SHOW COLUMNS FROM medicalHistories"); //get all the field names from the table
			$fields = array(); //Create array to store fields in
			if($columns) {
				if (mysql_num_rows($result) > 0) {		
					echo "<div class=\"viewtable\">";
					echo "<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">";
					echo "<h3>Please select a medicalHistoriesID you wish to Edit.</h3><br/>";
					echo "<table border=1 cellspacing=0 cellpadding=2>";
				
					while ($row = mysql_fetch_array($columns)) { //read every field name from the table
						echo "<th>$row[0]</th>"; //Display the row name as a header
						$fields[] = $row[0];
					}
	
					while ($row = mysql_fetch_array($result)) {//Show the fields (all rows)
			 			echo "<tr>";
			 			for ($i=0; $i<mysql_num_fields($result); $i++) {
							if ($row[$i] == "0" && $i >2) //Don't format the IDs
								echo "<td><input type=\"checkbox\" disabled=\"disabled\" /></td>";
							else if ($row[$i] == "1" && $i>2)
								echo "<td><input type=\"checkbox\" disabled=\"disabled\" checked=\"yes\"/></td>";
							else if ($i > 2)
								echo "<td>$row[$i]</td>";
							else if ($i==0)  {//Make the medicalHistoriesID clickable to select the record for editing
								echo "<input type=\"hidden\" name=\"medicalHistoriesID-$i\" value=\"$row[$i]\"/>";
								echo "<input type=\"hidden\" name=\"editHistory\" value=\"editHistory\"/>";
								echo "<td><input type=\"submit\" value=\"$row[$i] Edit this Record\"/> </td>";
							}
							else
								echo "<td>$row[$i]</td>";	
			 			}
			 			echo "</tr>";
			 		}
			 		echo "</form></table></div>"; //End the table for editing the results
			 	}
			}
	}//End check for successful query execution
	if (isset($_POST['medicalHistoriesID'])) {
		$medicalHistoriesID = $_POST['medicalHistoriesID']; echo $medicalHistoriesID;
		$result = mysql_query("SELECT * FROM medicalHistories WHERE medicalHistoriesID='$medicalHistoriesID'");
		
		
		if ($result) {
			$columns = mysql_query("SHOW COLUMNS FROM medicalHistories"); //get all the field names from the table
			$fields = array(); //Create array to store fields in

			if($columns) {
				if (mysql_num_rows($result) > 0) {	
					echo "<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">";
					echo "<div class=\"viewtable\">";
					echo "<h3>Edit Patient History</h3><br/>";
					echo "<table border=1 cellspacing=0 cellpadding=2>";
				
					while ($row = mysql_fetch_array($columns)) { //read every field name from the table
						echo "<th>$row[0]</th>"; //Display the row name as a header
						$fields[] = $row[0];
					}
		
					while ($row = mysql_fetch_array($result)) {//Show the fields (all rows)
			 			echo "<tr>";
			 			for ($i=0; $i<mysql_num_fields($result); $i++) {
							if ($row[$i] == "0" && $i >2) //Don't format the IDs
								echo "<td><input type=\"checkbox\" /></td>";
							else if ($row[$i] == "1" && $i>2)
								echo "<td><input type=\"checkbox\" checked=\"yes\"/></td>";
							else if ($i > 2)
								echo "<td><input type=\"text\" name=\"fields[$i]\" value=\"$row[$i]\"/></td>";
							else
								echo "<td><input type=\"text\" name=$fields[$i] value=\"$row[$i]\" disabled=\"disabled\" /></td>";	
			 			}
			 			echo "</tr>";
			 		}
			 	}
			 		echo "</form></table></div>";
			 	    echo "<input type=\"submit\" name=\"makeEdit\" value=\"Submit Changes\"/>";
			} 
		}
		else echo "Error Processing Request.";
		
	}
}//End edit histories option*/


//Option to View Patient Info
if (isset($_POST['viewPatientInfo'])) { 
	$patientID = $_POST['patientID'];
	$doctorID = $_SESSION['doctorID'];
	
	//First Find out whether or not the doctor has been approved by the patient
	$approved = mysql_query("SELECT * FROM approvedDoctors WHERE patientID='$patientID' AND doctorID='$doctorID' AND approved='1'");
	
	if ($approved) {
		if (mysql_num_rows($approved) > 0) {
			//If doctor has been approved by patient, show the all patient's information in View Only mode
			viewRecords("patientBasicInfo", $patientID);
			viewRecords("medicalHistories", $patientID);
			viewRecords("healthcareProviders", $patientID);
			viewRecords("tests", $patientID);
			viewRecords("notes", $patientID);
			viewRecords("userInfoCustomFields", $patientID);
		}
		else {//Doctor has not been approved
			echo "<div class=\"forms\">";
			echo "<p><b>Patient has not approved you to view this record. Please Request Approval or await pending request.</b></p>";
			echo "</div>";
 		}
 	}
 }//End View Patient Info Option

if (isset($_POST['requestApproval'])) { //Option to Request Patient Approal
	$patientID = $_POST['patientID'];
	$doctorID = $_SESSION['doctorID'];
	
	//Make sure the doctor is not already approved
	$approved = mysql_query("SELECT * FROM approvedDoctors WHERE patientID='$patientID' AND doctorID='$doctorID' AND approved='1'");
	if ($approved) {
		if(mysql_num_rows($approved) == 0) {//Doctor is not approved
			//Now make sure there's not already a pending request
			$pending = mysql_query("SELECT * FROM approvedDoctors WHERE patientID='$patientID' AND doctorID='$doctorID' AND approved='0'");
			if ($pending) {
				if(mysql_num_rows($pending) == 0) { //No pending requests. Go ahead and make one
					$request = mysql_query("INSERT INTO approvedDoctors(patientID, doctorID, approved) VALUES ('$patientID', '$doctorID', '0')");
			
					if($request) {//See if we were successful at inserting
						//Now make a log in the recordChanges table so the patient's iPhone will see it.	
						$recordChange = mysql_query("INSERT INTO recordChanges(patientID, tableChanged, tableRecID) VALUES('$patientID', 'approvedDoctors', '$doctorID')");
						if($recordChange) {
							echo "<div class=\"forms\">";
							echo "<p><b> Request for patient's approval was made. </b></p>";
							echo "</div>";
						}
						else { //Couldn't insert the request.
							echo "<div class=\"forms\">";
							echo "<p><b> Could not make request at this time. </b></p>";
							echo "</div>";		
						}
						
					}
				}
				else { //There is already a pending request. Don't make a new one.
						echo "<div class=\"forms\">";
						echo "<p><b> There is already a pending request for this patient's approval. </b></p>";
						echo "</div>";					
				}
			}//End Pending Check
		}//End Doctor already approved
		else {
		echo "<div class=\"forms\">";
		echo "<p><b> You have already been approved by this patient. Please select another action. </b></p>";
		echo "</div>";
		}
	}
}//End Request Approval Option

//Option to Add Tests Procedures
if (isset($_POST['addTest'])) { 
	$patientID = $_POST['patientID'];?>

	<form class="forms" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<h3>New Test Procedures Record</h3>
		Test Date: <input type="text" name="testDate" /><br/>
		Comment:<br/><textarea name="comment" cols="50" rows="10">Enter Comment(s)...</textarea><br/>
		<input type="hidden" name="patientID" value="<?php echo $patientID; ?>"/>
		<input type="hidden" name="addTest" value="addTest"/>
	    <input type="submit" name="test" value="Submit" />
	</form>
	
<?php	//Now process the test once the doctor submits
	if(isset($_POST['test'])) {
		//Insert the new test information
		$patientID = $_POST['patientID'];
		$testDate = $_POST['testDate'];
		$comment = $_POST['comment'];
		if (strstr($comment, "'") || strstr($comment, "\"")) $comment = addslashes($comment); //Escape quotes if necessary so we don't have problems with the query.
		
		$query = mysql_query("INSERT INTO tests(patientID, testDate, comment) VALUES('$patientID', '$testDate', '$comment')");
		
		if($query) {
			$numRows = mysql_affected_rows();
		
			if($numRows > 0)
				echo "<p><b>Test Added</b></p>";
				//@TODO: RECORD CHANGES MADE HERE INTO THE recordChanges TABLE SO IPHONE CAN SYNC
			else
				echo "<p><b>Couldn't add test. Please Try again later.</b></p>";
		}
	}
}//End Add Tests Procedures Option

echo "</div>";
?>