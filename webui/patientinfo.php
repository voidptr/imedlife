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
		<form id="login" method="post" action="../server/process.php">
			Username: <input name="username" type="text"/>
			Password: <input name="password" type="password"/>
					  <input type="hidden" name="request" value="login" />
					  <input name="login" type="submit" value="Login"/>
					  
		</form>
		<a href="create.php"> Create Account </a>
		<?php } 
		else if (isset($_SESSION['loggedIn'])) {
			echo "Logged in as: <h3>" .$_SESSION['firstName'] ." " .$_SESSION['lastName'] ." (" .$_SESSION['userType']. ")</h3>";
			echo "<form method=\"post\" action=\"../server/process.php\">";
			echo "<input type=\"submit\" name=\"request\" value=\"logout\"/>";
			echo "</form>";
		} ?>
		
	</div>
	<div id="menu">
		<ul>
			<li> <a href="main.php"> Main </a></li>
			<li> <a href="patientinfo.php"> Patient Info </a></li>
			<li> <a href="upload.php"> Upload Files </a></li>
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
			<form class="options" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				Choose an option: <select name="option">
				<?php //Only allow the options specific to the user's role (patient or doctor)
					if ($_SESSION['userType'] == "doctor") {//Options for the doctor
						echo "<option value=\"create\"> Create New Record </option>";
						echo "<option value=\"edit\"> Edit Medical Record </option>";
						echo "<option value=\"view\"> View Records </option>";
					}
					else { //Options for the patient
						echo "<option value=\"edit\"> Update Medical Record </option>";
						echo "<option value=\"view\"> View Records </option>";					
					}
				?>
				</select>  
				<input type="submit" value="Go"/>
                <br/>
			</form>
			
			<!-- View Options -->
			<form class="options" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
                <?php //Show the buttons for viewing specific information
                	if(isset($_POST['option']) && $_POST['option'] == "view") { ?>
                        <input type="submit" name="MedicalRecord" value="Medical Record"/>
		                <input type="submit" name="MedicalHistory" value="Medical History"/>
		                <input type="submit" name="HealthcareProviders" value="Healthcare Providers"/>
		                <input type="submit" name="InsuranceCompanyInformation" value="Insurance Company Information"/>
		                <input type="hidden" name="option" value="view" />
                <?php } ?>
			
			</form>
			 
            <!-- Edit Options -->
			<form class="options" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
                <?php //Show the buttons for viewing specific information
                	if(isset($_POST['option']) && $_POST['option'] == "edit") { ?>
                        <input type="submit" name="MedicalRecord" value="Medical Record"/>
		                <input type="submit" name="MedicalHistory" value="Medical History"/>
		                <input type="submit" name="HealthcareProviders" value="Healthcare Providers"/>
		                <input type="submit" name="InsuranceCompanyInformation" value="Insurance Company Information"/>
		                <input type="hidden" name="option" value="edit" />
                <?php } ?>
			
			</form>
            
            
		<?php
			if (isset($_POST['option']) && $_POST['option'] == "create") {
				include("create_record.php");
		} //END  Create Option
			
		//View Section
			if (isset($_POST['option']) && $_POST['option'] == "view") {
				include("../server/lib/web/view_records.php");
			}	
            
       //Edit Section
			if (isset($_POST['option']) && $_POST['option'] == "edit") {
				include("../server/lib/web/edit_records.php");
			}   
		}?>
											
		<!-- #EndEditable --> </div>
	<div id="footer"> Copyright &copy; 2010 | CSE 870 iMedLife Design Group - <a href="http://www.msu.edu" target="_blank">Mighigan State University</a></div>
</body>

<!-- #EndTemplate -->

</html>
