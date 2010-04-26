<?php 
//view patients.php - Returns a list of all the patients in the database. The doctor can select which one he/she would like to work with
include_once("../server/lib/connect.php");//path is relative to process.php

//Set up the query
$query = "SELECT patientID, firstName, middleName, lastName FROM patientBasicInfo ORDER BY lastName ASC";
$result = mysql_query($query);
if ($result) {//Display a listing of all the patients (names) for the doctor to choose from
	echo "<div class=\"forms\">"
			."<form method=\"post\" action=\"". $_SERVER['PHP_SELF'] ."\""
			."<b>Choose a patient to add Medical History: </b>"
			."<select name=\"patientID\">";
	
	while ($row = mysql_fetch_array($result)) {//Get all the rows
		echo "<option value=\"$row[0]\"> $row[1] $row[2] $row[3]</option>";
	}
	echo "</select>";
		echo "<h3>Actions</h3>";
		echo "<br/><input type=\"submit\" name=\"viewPatientInfo\" value=\"View Patient Info\"/>";	
		echo "<input type=\"submit\" name=\"requestApproval\" value=\"Request Patient Approval\"/>";
		echo "<br/><input type=\"submit\" name=\"addHistory\" value=\"New Medical History Record\"/>";	
		echo "<input type=\"submit\" name=\"addTest\" value=\"New Test Procedures Record\"/>";	
		echo "<input type=\"hidden\" name=\"option\" value=\"viewPatients\"/>";
			
		
  	echo "</form></div>";
}
else {
echo "No patients found. Check back later.";
}

//New Medical Record Option
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
		<th>Complains Of:</th> <th>Denies:</th>
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
		Pulse Rate: <input type="text" name="pulseWeight"/><br/>
		Pulse Rhythm: <input type="text" name="pulseRhythm"/><br/>
		Blood Pressure (Systolic): <input type="text" name="bloodPressureSystolic"/><br/>
		Blood Pressure (Diastolic): <input type="text" name="bloodPressureDiastolic"/><br/>
		
		<input type="hidden" name="patientID" value="<?php echo $patientID; ?>"/>
		<input type="hidden" name="request" value="createMedHist" />
		<input type="submit" value="Done"/>
	</form>

<?php } //End New Medical History Option 
if (isset($_POST['viewPatientInfo'])) { //Option to View Patient Info

}
if (isset($_POST['RequestApproval'])) { //Option to Request Patient Approal

}
if (isset($_POST['addTest'])) { //Option to Add Tests Procedures

}


?>