<?php
//create_medical_history.php - Creates a new record in the medicalHistories table
include_once("lib/connect.php");
session_start();

//Get all the MANY values from POST
$patientID = $_POST['patientID'];
$visitDate = $_POST['visitDate'];
$complains_headache = $_POST['complains_headache'];
$complains_chestPain = $_POST['complains_chestPain'];
$complains_palpitations = $_POST['complains_palpitations'];
$complains_dyspneaWithExertion = $_POST['complains_dyspneaWithExertion'];
$complains_orthopnea = $_POST['complains_orthopnea'];
$complains_PND = $_POST['complains_PND'];
$complains_peripheralEdema = $_POST['complains_peripheralEdema'];
$complains_visualSymptoms = $_POST['complains_visualSymptoms'];
$complains_neurologicProblems = $_POST['complains_neurologicProblems'];
$complains_syncope = $_POST['complains_syncope'];
$complains_sideEffectsFromTreatment = $_POST['complains_sideEffectsFromTreatment'];
$denies_headache = $_POST['denies_headache'];
$denies_chestPain = $_POST['denies_chestPain'];
$denies_palpitations = $_POST['denies_palpitations'];
$denies_dyspneaWithExertion = $_POST['denies_dyspneaWithExertion'];
$denies_orthopnea = $_POST['denies_orthopnea'];
$denies_PND = $_POST['denies_PND'];
$denies_peripheralEdema = $_POST['denies_peripheralEdema'];
$denies_visualSymptoms = $_POST['denies_visualSymptoms'];
$denies_neurologicProblems = $_POST['denies_neurologicProblems'];
$denies_syncope = $_POST['denies_syncope'];
$denies_sideEffectsFromTreatment = $_POST['denies_sideEffectsFromTreatment'];
$CHFEducationProvidedTo = $_POST['CHFEducationProvidedTo'];
$patientCheckingWeight = $_POST['patientCheckingWeight'];
$understandsSodiumIssuesManagement = $_POST['understandsSodiumIssuesManagement'];
$followingSodiumIssuesManagement = $_POST['followingSodiumIssuesManagement'];
$understandsMeds = $_POST['understandsMeds'];
$compliantWithMeds = $_POST['compliantWithMeds'];
$ableToDoADLs = $_POST['ableToDoADLs'];
$CHFEdProgram = $_POST['CHFEdProgram'];
$medicationSideEffects = $_POST['medicationSideEffects'];
$currentWeight = $_POST['currentWeight'];
$lastWeight = $_POST['lastWeight'];
$dryWeight = $_POST['dryWeight'];
$respirations = $_POST['respirations'];
$height = $_POST['height'];
$pulseRate = $_POST['pulseRate'];
$pulseRhythm = $_POST['pulseRhythm'];
$bloodPressureSystolic = $_POST['bloodPressureSystolic'];
$bloodPressureDiastolic= $_POST['bloodPressureDiastolic'];

//Put doctorID in a variable that's easier to use in the query
$doctorID = $_SESSION['doctorID'];


//Now get the healthcare provider information

//Create the query to execute
$query = "INSERT INTO medicalHistories(patientID, doctorID, visitDate, complains_headache, complains_chestPain, "
		  ."complains_palpitations, complains_dyspneaWithExertion, complains_orthopnea, complains_PND, complains_peripheralEdema, "
		  ."complains_visualSymptoms, complains_neurologicProblems, complains_syncope, complains_sideEffectsFromTreatment, "
		  ."denies_headache, denies_chestPain, denies_palpitations, denies_dyspneaWithExertion, denies_orthopnea, denies_PND, "
		  ."denies_peripheralEdema, denies_visualSymptoms, denies_neurologicProblems, denies_syncope, denies_sideEffectsFromTreatment, "
		  ."CHFEducationProvidedTo, patientCheckingWeight, understandsSodiumIssuesManagement, followingSodiumIssuesManagement, "
		  ."understandsMeds, compliantWithMeds, ableToDoADLs, CHFEdProgram, medicationSideEffects, currentWeight, lastWeight, "
		  ."dryWeight, respirations, height, pulseRate, pulseRhythm, bloodPressureSystolic, bloodPressureDiastolic) "

		  ."VALUES('$patientID', '$doctorID', '$visitDate', '$complains_headache', '$complains_chestPain', "
		  ."'$complains_palpitations', '$complains_dyspneaWithExertion', '$complains_orthopnea', '$complains_PND', '$complains_peripheralEdema', "
		  ."'$complains_visualSymptoms', '$complains_neurologicProblems', '$complains_syncope', '$complains_sideEffectsFromTreatment', "
		  ."'$denies_headache', '$denies_chestPain', '$denies_palpitations', '$denies_dyspneaWithExertion', '$denies_orthopnea', '$denies_PND', "
		  ."'$denies_peripheralEdema', '$denies_visualSymptoms', '$denies_neurologicProblems', '$denies_syncope', '$denies_sideEffectsFromTreatment', "
		  ."'$CHFEducationProvidedTo', '$patientCheckingWeight', '$understandsSodiumIssuesManagement', '$followingSodiumIssuesManagement', "
		  ."'$understandsMeds', '$compliantWithMeds', '$ableToDoADLs', '$CHFEdProgram', '$medicationSideEffects', '$currentWeight', '$lastWeight', "
		  ."'$dryWeight', '$respirations', '$height', '$pulseRate', '$pulseRhythm', '$bloodPressureSystolic', '$bloodPressureDiastolic')";
		  
//Now run the darn thing
$result = mysql_query($query);

if($result) { //We were successful inserting the new row
	//Now just insert the healthcare provider information
	$healthcareName = $_POST['healthcareName'];
	$healthcareAddress = $_POST['healthcareAddress'];
	$healthcareNumber = $_POST['healthcareNumber'];
	$referredBy = "Dr. " .$_SESSION['firstName'] ." " .$_SESSION['middleName'] ." " .$_SESSION['lastName'];

	//First make sure this doctor is not already listed before we insert a duplicate
	$check = mysql_query("SELECT * FROM healthcareProviders WHERE patientID='$patientID' AND doctorID='$doctorID'");
	if ($check) { //Query executed successfully
		$numRows = mysql_num_rows($check);
		if($numRows == 0) {//The doctor isn't already listed, so go ahead and insert new record
			$res = mysql_query("INSERT INTO healthcareProviders(patientID, doctorID, name, address, phoneNumber, referredBy) VALUES('$patientID', '$doctorID', '$healthcareName', '$healthcareAddress', '$healthcareNumber', '$referredBy')");
			if ($res) { //If we're successful, redirect back to page
				$_SESSION['option'] = "view";
				header("location:../webui/patientinfo.php");
			}
			else { //Not successful, just redirect them
				$_SESSION['option'] = "view";
				header("location:../webui/patientinfo.php");
			}
		}
		else { //The doctor is already listed. Nothing left to do but redirect.
			$_SESSION['option'] = "view";
			header("location:../webui/patientinfo.php");
		}
	}
}
else {
	echo "Please use your browser's back button and ensure that you provided valid information.";
}

?>