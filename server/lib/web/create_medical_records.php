<?php
//medical_records.php - Responds to a request to create a new medical record
include_once("lib/connect.php"); //establish initial connection to database
//Validate the data we got before we try to insert it into the database
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
		
		$errors = ""; //will keep a collection the fields that have errors in them
		
		//Check phone numbers format
		if (!strstr($phoneNumber, "-") == false || !strstr($phoneNumber, " ") == false || strlen($phoneNumber) != 10)
			$errors .= "phoneNumber";
		if (!strstr($emergencyNumber, "-") == false || !strstr($emergencyNumber, " ") == false || strlen($emergencyNumber) != 10)	
			$errors .= ",emergencyNumber";
			
		//Check the date format
		if (!strstr($dateOfBirth, "-") == false || !strstr($dateOfBirth, "/") == false || !strstr($dateOfBirth, " ") == false || strlen($dateOfBirth) != 8)
			$errors .= ",dateOfBirth";
		
		//TODO: REMOVE OR DISALLOW THE USE OF CHARACTERS THAT MUST BE ESCAPED, SUCH AS SINGLE QUOTES IN THE HEIGHT, ETC.
		if ( strlen($errors) < 1) {			
			//Now insert the data if it all looks correct
			$query = "INSERT INTO medicalRecords(firstName, middleName, lastName, address, phoneNumber,"
			 ." dateOfBirth, sex, hairColor, eyeColor, ethnicity, height, weight, bloodType, allergies,"
			 ." emergencyName, emergencyNumber, emergencyAddress) VALUES ('$firstName', '$middleName',"
			 ." '$lastName', '$address', '$phoneNumber', '$dateOfBirth', '$sex', '$hairColor',"
			 ." '$eyeColor', '$ethnicity', '$height', '$weight', '$bloodType', '$allergies', '$emergencyName', '$emergencyNumber', '$emergencyAddress')";

			$result = mysql_query($query);

			if ($result) //redirect back to the patientinfo page if all is well
				header("location: ../webui/patientinfo.php");
			else echo "ERROR, Could not create record in database.";
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