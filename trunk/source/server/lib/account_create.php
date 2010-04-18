<?php 
//account_create.php - Creates a new patient or doctor account in the database

include_once("connect.php"); //establish the initial connection to the database

//First set up the patient
if (isset($_POST['createType']) && $_POST['createType'] == "patient") {
	//Get the info from the create form
	$username = $_POST['username'];
	$password = $_POST['password'];
	$passwordConfirm = $_POST['passwordConfirm'];
	$firstName = $_POST['firstName'];
	$middleName = $_POST['middleName'];
	$lastName = $_POST['lastName'];
	$patientID = $_POST['patientID']; //Patient must provide the patient number associated with his/her medical record. 
							    //Helps us determine that they are who they say they are
	//Make sure we got all the fields
	if (!isset($_POST['createType']) || !isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['passwordConfirm']) || !isset($_POST['firstName']) || !isset($_POST['middleName']) || !isset($_POST['lastName']) || !isset($_POST['patientID']))
		echo "Please fill in all the fields.";
		
	//Make sure all the data entered is correct
	else if (strlen($password) < 6 || $password != $passwordConfirm) //don't proceed if password is not correct
		echo "Please use your browser's back button and check that your password is valid and that the passoword matches.";
	
	//TODO Should also make sure none of the fields are too long before we try to create account to avoid overflow errors
	
	else {//If all the fields are entered and valid, proceed	
		//Now verify that this patient has a record in the medicalRecords table
		$testQuery = "SELECT * FROM medicalRecords WHERE firstName='$firstName' AND middleName='$middleName' AND lastName='$lastName' AND patientID='$patientID'";
		$rows = mysql_num_rows(mysql_query($testQuery));
	
		if ($rows != 1)
			echo "Error creating account. Please use your browser's back button and verify that all information was correctly entered.";
		
		else if ($rows == 1) { //Finally, if all is well, go ahead and create the user's account in the database
	
			//First, make sure the user account does not already exist
			$exists = mysql_num_rows(mysql_query("SELECT * FROM patients WHERE username='$username'"));
		
			if (!$exists) {
				$password = crypt($password); //encrypt the password before we store it in the database
				$query = "INSERT INTO patients VALUES('$patientID', '$username', '$password')";
				$result = mysql_query($query);
	
				//If we successfully created the user, return them back to the main.php page so they can Login
				if ($result) 
					header("location: ../../webui/main.php");
				else
					echo "Error. Could not create account.";
			}
			else
				echo "This username already exists. Please use your browser's back button and select another username.";
		}
	}
}
//Handle creating the doctor account
else if (isset($_POST['createType']) && $_POST['createType'] == "doctor") {
//TODO: add the functionality to create the doctor account

}

