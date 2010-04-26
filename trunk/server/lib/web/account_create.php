<?php 
//account_create.php - Creates a new patient or doctor account in the database

include_once("lib/connect.php"); //establish the initial connection to the database

//First set up the patient
if (isset($_POST['createType']) && $_POST['createType'] == "patient") {
	//Get the info from the create form
	$username = $_POST['username'];
	$password = $_POST['password'];
	$passwordConfirm = $_POST['passwordConfirm'];
	$firstName = $_POST['firstName'];
	$middleName = $_POST['middleName'];
	$lastName = $_POST['lastName'];
							    //Helps us determine that they are who they say they are
	//Make sure we got all the fields
	if (!isset($_POST['createType']) || !isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['passwordConfirm']) || !isset($_POST['firstName']) || !isset($_POST['middleName']) || !isset($_POST['lastName']))
		echo "Please fill in all the fields.";
		
	//Make sure all the data entered is correct
	else if (strlen($password) < 6 || $password != $passwordConfirm) //don't proceed if password is not correct
		echo "Please use your browser's back button and check that your password is valid and that the passoword matches.";
		
	else {//If all the fields are entered and valid, proceed	
		//Now verify that this patient has a record in the patientBasicInfo table
		$testQuery = "SELECT * FROM patientBasicInfo WHERE firstName='$firstName' AND middleName='$middleName' AND lastName='$lastName'";
		$res= (mysql_query($testQuery));
		
		if($res) {
			$rows = mysql_num_rows($res);
	
			if ($rows == 1)
				echo "Error creating account. A record already exists for patient $firstName $lastName.";
			
			else if ($rows == 0) { //Finally, if all is well, go ahead and create the user's account in the database
		
				//First, make sure the user account does not already exist
				$exists = mysql_num_rows(mysql_query("SELECT * FROM patients WHERE username='$username'"));
			
				if (!$exists) {
					$password = crypt($password); //encrypt the password before we store it in the database
					$query = "INSERT INTO patients VALUES('', '$username', '$password')";
					$result = mysql_query($query);
		
					//If we successfully created the user, return them back to the main.php page so they can Login
					if ($result) 
						header("location: ../webui/main.php");
					else
						echo "Error. Could not create account.";
				}
				else
					echo "This username already exists. Please use your browser's back button and select another username.";
			}
		}
	
	}
}

//Handle creating the doctor account
else if (isset($_POST['createType']) && $_POST['createType'] == "doctor") {
	//Get the info from the create form
	$username = $_POST['username'];
	$password = $_POST['password'];
	$passwordConfirm = $_POST['passwordConfirm'];
	$firstName = $_POST['firstName'];
	$middleName = $_POST['middleName'];
	$lastName = $_POST['lastName'];
	
	//Make sure we got all the fields
	if (!isset($_POST['createType']) || !isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['passwordConfirm']) || !isset($_POST['firstName']) || !isset($_POST['middleName']) || !isset($_POST['lastName']))
		echo "Please use your browser's back button and  fill in all the fields.";
		
	//Make sure all the data entered is correct
	else if (strlen($password) < 6 || $password != $passwordConfirm) //don't proceed if password is not correct
		echo "Please use your browser's back button and check that your password is valid and that the passoword matches.";
		
	else {//If all the fields are entered and valid, proceed		
			//First, make sure the username does not already exist in patients or doctors tables
			$exists = mysql_num_rows(mysql_query("SELECT * FROM patients WHERE username='$username'"));//Check patients
		
			if (!$exists) {//Patient doesn't already have this username. Now check doctors
				$exists = mysql_num_rows(mysql_query("SELECT * FROM doctors WHERE username='$username'"));
				if(!$exists) {//The username is not taken. Proceed
					$password = crypt($password); //encrypt the password before we store it in the database
					$query = "INSERT INTO doctors VALUES('', '$firstName', '$middleName', '$lastName', '$username', '$password')";
					$result = mysql_query($query);
		
					//If we successfully created the user, return them back to the main.php page so they can Login
					if ($result) 
						header("location:../webui/main.php");
					else
						echo "Error. Could not create account.";
				}//End check for doctor username
				else
					echo "This username already exists. Please use your browser's back button and select another username.";
			}//End check for patient username
			else
				echo "This username already exists. Please use your browser's back button and select another username.";
	}

}

