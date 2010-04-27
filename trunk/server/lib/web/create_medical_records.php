<?php
//medical_records.php - Responds to a request to create a new medical record
include_once("lib/connect.php"); //establish initial connection to database
session_start();
//Validate the ALL data we got before we try to insert it into the database

//patientBasicInfo data
$firstName = $_POST['firstName'];
$middleName = $_POST['middleName'];
$lastName = $_POST['lastName'];
$address = $_POST['address'];
$phoneNumber = $_POST['phoneNumber'];
$dateOfBirth = $_POST['dateOfBirth'];
$sex = $_POST['sex'];
$hairColor = $_POST['hairColor'];
$eyeColor = $_POST['eyeColor'];
$ethnicity = $_POST['ethnicity'];
$height = $_POST['height'];
$weight = $_POST['weight'];
$bloodType = $_POST['bloodType'];
$allergies = $_POST['allergies'];
$emergencyName = $_POST['emergencyName'];
$emergencyNumber = $_POST['emergencyNumber'];
$emergencyAddress = $_POST['emergencyAddress'];

//healthcareProviders data
$facilityName = $_POST['facilityName'];
$facilityAddress = $_POST['facilityAddress'];
$HealthcarePhoneNumber = $_POST['healthcarePhoneNumber'];

//insuranceInfo data
$insuranceCompany = $_POST['insuranceCompany'];
$policyNumber = $_POST['policyNumber'];

//Get a hold of the doctorID to insert into certain tables
$patientID = $_SESSION['patientID'];
//echo $patientID;
$errors = ""; //will keep a collection the fields that have errors in them

//Check phone numbers format
if (!strstr($phoneNumber, "-") == false || !strstr($phoneNumber, " ") == false || strlen($phoneNumber) != 10)
	$errors .= "phoneNumber";
if (!strstr($emergencyNumber, "-") == false || !strstr($emergencyNumber, " ") == false || strlen($emergencyNumber) != 10)	
	$errors .= ",emergencyNumber";
if (strstr($height, "-")) {//Escape the quotes in the height if necessary
	$height = addslashes($height);
}			
if ( strlen($errors) < 1) {//If there were no errors in the format of the entered data, proceed.			
	//Now insert the data into patientBasicInfo
	$query = "INSERT INTO patientBasicInfo(patientID, firstName, middleName, lastName, address, phoneNumber,"
	 ." dateOfBirth, sex, hairColor, eyeColor, ethnicity, height, weight, bloodType, allergies,"
	 ." emergencyName, emergencyNumber, emergencyAddress) VALUES ('$patientID', '$firstName', '$middleName',"
	 ." '$lastName', '$address', '$phoneNumber', '$dateOfBirth', '$sex', '$hairColor',"
	 ." '$eyeColor', '$ethnicity', '$height', '$weight', '$bloodType', '$allergies', '$emergencyName', '$emergencyNumber', '$emergencyAddress')";

	$result = mysql_query($query);		
}//End no errors found
//Display the errors the user has and force the user to fix them before continuing
else { 
	$error = explode(",", $errors);
	echo "<b>ERROR in processing the following field(s):</b> <br/>";
	for ($i=0; $i<= count($error); $i++)
		echo "<i>$error[$i] </i><br/>";
	echo "<b>Please use your browser's back button and correct this problem.</b>";
}//End display errors	
                    
		//TODO: REMOVE OR DISALLOW THE USE OF CHARACTERS THAT MUST BE ESCAPED, SUCH AS SINGLE QUOTES IN THE HEIGHT, ETC.
		if ( strlen($errors) < 1) {			
	
			//Now insert the insurance information
//			$query = "INSERT INTO insuranceinfo(patientID, insuranceCompany, policyNumber) VALUES ('$patientID', '$insuranceCompany', '$policyNumber')";

//			$result = mysql_query($query);

//			if ($result) //redirect back to the patientinfo page if all is well
				header("location: ../webui/patientinfo.php");
//			else echo "ERROR, Could not create record in database.";
	}
		//Display the errors the user has and force the user to fix them before continuing
		else { 
			$error = explode(",", $errors);
			echo "<b>ERROR in processing the following field(s):</b> <br/>";
			for ($i=0; $i<= count($error); $i++)
				echo "<i>$error[$i] </i><br/>";
			echo "<b>Please use your browser's back button and correct this problem.</b>";
		}
?>