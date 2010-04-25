<?php
//create_record.php - Allows a doctor to create a new medical record for a patient.
//Show the buttons for adding specific information
if(isset($_POST['option']) && $_POST['option'] == "view") { ?>
    <input type="submit" name="MedicalRecord" value="Medical Record"/>
    <input type="submit" name="MedicalHistory" value="Medical History"/>
    <input type="submit" name="HealthcareProviders" value="Healthcare Providers"/>
    <input type="submit" name="InsuranceCompanyInformation" value="Insurance Company Information"/>
    <input type="hidden" name="option" value="edit" />
<?php } ?>

<form class="forms" method="post" action="../server/process.php">
	<h3> New Medical Record</h3><p> <b>Patient Name</b></p>
	<b>First:</b> <input type="text" name="firstName" />
	<b>Middle:</b> <input type="text" name="middleName" />
	<b>Last:</b> <input type="text" name="lastName" /><br/>
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

	<!-- CREATE Healthcare Providers Info -->
	<h3> Healthcare Provider </h3>
	<b>Healthcare Facility Name:</b> <input type="text" name="facilityName" />
	<b>Healthcare Facility Address:</b> <input type="text" name="facilityAddress" /><br/>
	<b>Phone Number:</b> (ex. 8885553256 no dashes)<input type="text" name="healthcarePhoneNumber" /><br/>
	<b>Referred By:</b> <input type="text" disabled="disabled" name="referredBy" value="<?php echo $_SESSION['firstName'] . " " .$_SESSION['lastName'];?>"/>
	<br/>
    
    <!-- CREATE Insurance Info -->
	<h3> Insurance Company Information </h3>
	<b>Insurance Company:</b> <input type="text" name="insuranceCompany" />
	<b>Policy Number:</b> <input type="text" name="policyNumber" /><br/>
    
	<input type="hidden" name="request0" value="create" /><br/>
	<input type="submit" value="Create Record and Continue" />
</form>
