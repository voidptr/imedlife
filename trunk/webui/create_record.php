<?php
//create_record.php - Allows a doctor to create a new medical record for a patient.
//Show the buttons for adding specific information
?>
<form class="forms" method="post" action="../server/process.php">
	<h3> New Patient Basic Info Record</h3><p> <b>Patient Name</b></p>
	<b>First:</b> <input type="text" name="firstName" />
	<b>Middle:</b> <input type="text" name="middleName" />
	<b>Last:</b> <input type="text" name="lastName" /><br/>
	<b>Address:</b> <input type="text" name="address" />
	<b>Phone Number:</b> <input type="text" name="phoneNumber" /><br/>
	<b>Date of Birth:</b> <input type="text" name="dateOfBirth" /><br/><br/>
	<b>Sex:</b> (M or F) <input type="text" name="sex" />
	<b>Hair Color:</b> <input type="text" name="hairColor" />
	<b>Eye Color:</b> <input type="text" name="eyeColor" /><br/>
	<b>Ethnicity:</b> <input type="text" name="ethnicity" />
	<b>Height:</b> <input type="text" name="height" />
	<b>Weight:</b> <input type="text" name="weight" /><br/>
	<b>Blood Type:</b> <input type="text" name="bloodType" />
	<b>Allergies:</b> <input type="text" name="allergies" />
	<b>Emergency Name:</b> <input type="text" name="emergencyName" /><br/>
	<b>Emergency Number:</b> <input type="text" name="emergencyNumber" />
	<b>Emergency Address:</b> <input type="text" name="emergencyAddress" />
	<br/>
    
    <!-- CREATE Insurance Info -->
	<h3> Insurance Company Information </h3>
	<b>Insurance Company:</b> <input type="text" name="insuranceCompany" />
	<b>Policy Number:</b> <input type="text" name="policyNumber" /><br/>
    
	<input type="hidden" name="request" value="create" /><br/>
	<input type="submit" value="Create Record and Continue" />
</form>
