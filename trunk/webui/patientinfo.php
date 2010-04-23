<?php
	session_start();
	include("../server/lib/connect.php"); //establish an initial connection to the database
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- #BeginTemplate "template.dwt" -->
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<link rel="stylesheet" type="text/css" href="css/default.css"/>
<link rel="shortcut icon" href="images/favicon.ico" />
<!-- #BeginEditable "doctitle" -->
<title>iMedLife Web Interface | Patient Info </title>
<!-- #EndEditable -->
</head>

<body>
	<div id="banner">  
		<p>
			<a href="http://www.cse.msu.edu/~burksarm/imedlife/webui/main.php"><img id="logo" src="images/logo.png" alt="iMedLife"/></a></p>
		<?php if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] == false) { ?>
		<form id="login" method="post" action="../server/lib/web/login.php">
			Username: <input name="username" type="text"/>
			Password: <input name="password" type="password"/>
					  <input name="login" type="submit" value="Login"/>
					  
		</form>
		<a href="create.php"> Create Account </a>
		<?php } 
		else if (isset($_SESSION['loggedIn']))
			echo "<a class=\"logout\" href=\"../server/lib/web/logout.php\"> Logout </a>";?>
		
	</div>
	<div id="menu">
		<ul>
			<li> <a href="main.php"> Main </a></li>
			<li> <a href="patientinfo.php"> Patient Info </a></li>
		</ul>
	</div>
	
	<div id="content"> <!-- #BeginEditable "MainContent" -->
		<h1> Patient Info </h1>
		<?php 
		if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] != true) { //display message if not logged in
			echo "<p class=\"notice\"> Please Login or Create Account above to access your medical records!";
			echo "<img src=\"images/notice.png\" alt=\"!\"/></p>";
		}
		else { //if logged in show the information ?>
			<form class="options" method="post" action="<?php echo $SERVER['PHP_SELF']; ?>">
				<select name="option">
					<option value="create"> Create New Record </option>
					<option value="edit"> Edit Medical Record </option>
					<option value="view"> View Medical Record </option>
				</select>
                
				<input type="submit" value="Go"/>
                <br/>
                <?php //Show the buttons for viewing specific information
                	if(isset($_POST['option']) && $_POST['option'] == "view") { ?>
		                <input type="submit" value="Medical Record"/>
		                <input type="submit" value="Medical History"/>
		                <input type="submit" value="Healthcare Providers"/>
		                <input type="submit" value="Insurance Company Information"/>
		         <?php } ?>
                
			</form>
			
		<?php
			if (isset($_POST['option']) && $_POST['option'] == "create") { ?>
				<form class="forms" method="post" action="../server/process.php">
					<h3> Medical Records</h3>
					<b>First Name:</b> <input type="text" name="firstName" />
					<b>Middle Name:</b> <input type="text" name="middleName" />
					<b>Last Name:</b> <input type="text" name="lastName" /><br/>
					<b>Address:</b> <input type="text" name="address" />
					<b>Phone Number:</b> (ex. 8885553256 no dashes)<input type="text" name="phoneNumber" /><br/>
					<b>Date of Birth:</b> (yyyymmdd no slashes, dashes, or spaces)<input type="text" name="dateOfBirth" /><br/><br/>
					<b>Sex:</b> (M or F) <input type="text" name="sex" />
					<b>Hair Color:</b> <input type="text" name="hairColor" />
					<b>Eye Color:</b> <input type="text" name="eyeColor" /><br/>
					<b>Ethnicity:</b> <input type="text" name="ethnicity" />
					<b>Height:</b> <input type="text" name="height" />
					<b>Weight:</b> <input type="text" name="weight" /><br/>
					<b>Blood Type:</b> <input type="text" name="bloodType" />
					<b>Allergies:</b> <input type="text" name="allergies" />
					<b>Emergency Name:</b> <input type="text" name="emergencyName" /><br/>
					<b>Emergency Number:</b> (ex. 8885553256 no dashes) <input type="text" name="emergencyNumber" />
					<b>Emergency Address:</b> <input type="text" name="emergencyAddress" />
					<br/>
		<!-- Wah -->	
					<!-- Create Medical History-->
                    <h3> Medical Histories </h3>
					<b>Medical Conditions:</b> <input type="text" name="medicalConditions" />
					<b>Medications:</b> <input type="text" name="medications" /><br/>
					<b>Procedures:</b> <input type="text" name="procedures" />
					<b>Visit Date:</b> (yyyymmdd no slashes, dashes, or spaces) <input type="text" name="visitDate" /><br/>
		<!-- Wah -->
					<!-- CREATE Healthcare Providers Info -->
                	<h3> Healthcare Providers </h3>
					<b>Name:</b> <input type="text" name="name" />
					<b>Healthcare Phone Number:</b> (ex. 8885553256 no dashes)<input type="text" name="hthcarphoneNumber" /><br/>
					<b>Referred By:</b> <input type="text" name="referredBy" />
					<br/>
        <!-- Wah -->            
                    <!-- CREATE Insurance Info -->
                	<h3> Insurance Company Information </h3>
					<b>Insurance Company:</b> <input type="text" name="insuranceCompany" />
					<b>Policy Number:</b> <input type="text" name="policyNumber" /><br/>
                    
        			<input type="hidden" name="request" value="create" /><br/>
					<input type="submit" value="Create Record" />
				</form>
			<?php } //END  Create Option
			
		//Wah View Section
			if (isset($_POST['option']) && $_POST['option'] == "view") {
/*************************************************************************************************************
				$result = mysql_query("SHOW COLUMNS FROM medicalRecords"); //get all the fields from the medicalRecords table
				if (mysql_num_rows($result) > 0) {
					echo "<form class=\"forms\" method=\"post\" action=\"../server/process.php\">";
					echo "<h3> View Medical Record </h3>";
					
					$i = 0; //just used for the display so we don't show too many text boxes on one line
			    	while ($row = mysql_fetch_array($result)) { //read every field name from the table
			    		echo "<b>$row[0]:</b>" ."<input name=\"$i\" disabled=\"disabled\" type=\"text\"/>"; 
			    			if (($i%3) ==0) echo "<br/>"; //only show three fields per row
			    		$i++;
			    	}					    	
				    echo "</form>";
				    mysql_free_result($result); //release the resource
				}
***************************************************************************************************/
			}	
		}?>
											
		<!-- #EndEditable --> </div>
	<div id="footer"> Copyright &copy; 2010 | CSE 870 iMedLife Design Group - <a href="http://www.msu.edu" target="_blank">Mighigan State University</a></div>
</body>

<!-- #EndTemplate -->

</html>
