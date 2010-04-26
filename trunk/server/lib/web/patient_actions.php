<?php 
//view patients.php - Returns a list of all the patients in the database. The doctor can select which one he/she would like to work with
include_once("../server/lib/connect.php");//path is relative to process.php

function viewRecords($tableName, $patientID) {//Displays the patient's information from the desired table in a table form.									
	$result = mysql_query("SHOW COLUMNS FROM $tableName"); //get all the fields from the medicalRecords table
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
				$record = mysql_fetch_array($result);
				echo "<tr>"; //Create a new row in which we'll store the info.
				for($i = 0; $i < mysql_num_fields($result); $i++) { //Print each field into the table
					echo "<td> $record[$i] </td>";
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

//Form for showing all the actions the patient can choose from.
echo "<form class=\"forms\" method=\"post\" action=\"" .$_SERVER['PHP_SELF'] ."\">";
echo "<h3>Actions</h3>";
echo "<br/><input type=\"submit\" name=\"viewPatientInfo\" value=\"View Patient Basic Info\"/>";
echo "<input type=\"submit\" name=\"editPatientInfo\" value=\"Edit Patient Basic Info\"/>";	
echo "<input type=\"submit\" name=\"approveDoc\" value=\"Approve Doctor Request\"/>";
echo "<br/><input type=\"submit\" name=\"viewRecord\" value=\"View Medical Record\"/>";	
echo "<input type=\"submit\" name=\"editNote\" value=\"Edit Patient Note\"/>";
echo "<input type=\"submit\" name=\"addNote\" value=\"New Patient Note\"/>";	
echo "<input type=\"hidden\" name=\"option\" value=\"viewPatients\"/>";
echo "</form>";

//New Medical Record Option
//Now if the doctor has selected a patient, then let them make a new medical history
if (isset($_POST['viewRecord'])) {
	$patientID = $_POST['patientID'];
	viewRecords("patientBasicInfo", $patientID);
} //End New Medical History Option 

if (isset($_POST['viewPatientInfo'])) { //Option to View Patient Info
	$patientID = $_SESSION['patientID'];
		
	//If doctor has been approved by patient, show the all patient's information in View Only mode
	viewRecords("patientBasicInfo", $patientID);
	viewRecords("medicalHistories", $patientID);
	viewRecords("healthcareProviders", $patientID);
	viewRecords("tests", $patientID); //TESTS AND NOTES NEED TO BE HANDLED DIFFERENTLY
	viewRecords("notes", $patientID);
	viewRecords("userInfoCustomFields", $patientID);

}//End View Patient Info Option

if (isset($_POST['editPatientInfo'])) { //Option to View Patient Info

//Show the table with cells being the form inputs that the user can edit. Copy the code I have!!!!!

//Make the query upon submit

}

if (isset($_POST['approveDoc'])) { //Option to Approve doctor
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

echo "</div>";
?>