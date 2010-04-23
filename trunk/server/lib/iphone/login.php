<?php
//login.php - Handles loggin into the server from the iPhone
include_once("lib/connect.php"); //Connects to the database so the user can be authenticated								 //path is relative to process.php, since this file is included from there

header("Content-Type:text/xml");
//Print out the xml header information from the file
$file = fopen("lib/top.xml", "r"); //open the file (file is relative to process.php, since it's including this file)
echo fgets($file); //print out the line to set up the xml output
fclose($file);

if (!isset($_GET['username']) || !isset($_GET['password'])) {
	echo "<response success=\"no\">";
	echo "<error1>No username or no password</error1>";
	echo "</response>";
}

else {//Proceed if we have a valid request
	//set up some variables to use for authenticating the user
	$username = $_GET['username'];
	$password = $_GET['password'];
	$query = "SELECT * FROM patients WHERE username='$username'"; //Try the patient's table first
	$result = mysql_query($query);
	
	if (!$result) { //Username was not found in patient table
		echo "<response success=\"no\">";
		echo "<error1>Incorrect Username</error1>";
		echo "</response>";
	}
	
	else { //We know the username exists so now authenticate based on the password
		$row = mysql_fetch_array($result); //Get the password from the database
		$dbPassword = $row['password']; //The encrypted password stored in the database
		
		//Now compare the two hashes
		if (crypt($password, $dbPassword) == $dbPassword) {//successful authentication of the patient
			
			//Set the sessionID
			$query = "INSERT INTO sessions(username, sessionType) VALUES('$username', 'iphone')"; //Insert their username and get their session ID
			
			if(mysql_query($query)) { //Successful insert
				//Now get the sessionID, so that we can give it to the iPhone
				$query = "SELECT * FROM sessions WHERE username='$username' AND sessionType='iphone'"; //Query for the row we just created
				$res = mysql_query($query); //Run the query
				if($res) { //Make sure there is no problem selecting the row we just queried for
					$row = mysql_fetch_array($res);
					$sessionID = $row['sessionID']; //The actual sessionID
							
					//Finally, respond with success message sessionID
					echo "<response success=\"yes\">";
					echo "<sessionID>$sessionID</sessionID>";
					echo "</response>";
				}
				else { //Couldn't retrieve the new session row
					echo "<response success=\"no\">";
					echo "<error1>Couldn't get sessionID</error1>";
					echo "</response>";
				}
			}//End successful insert
			else { //Couldn't insert into the sessions table
				echo "<response success=\"no\">";
				echo "<error1>Couldn't get sessionID (couldn't start session)</error1>";
				echo "</response>";	
			}	
		}//End password validation
	
		else { //Respond with an error if password was invalid.
			echo "<response success=\"no\">";
			echo "<error1>Incorrect Password</error1>";
			echo "</response>";
		}
	}
}
?>