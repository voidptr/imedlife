<?php 
//process.php - Serves as the interface that responds to requests for both the iPhone and the Web UI

//See if we're getting requests from the iPhone (GET requests)
if (isset($_GET['request'])) { //If we get a GET requestType, we know it's from the iPhone
	//Now figure out what the iPhone wants
	$request = $_GET['request'];
	
	switch($request) { //Depending on the request, perform the following actions
		case "retrieveMedRec":
			include("lib/iphone/display_medical_record.php");
			break;
		case "retrieveMedHist":
			include("lib/iphone/display_medical_history.php");
			break;
		case "retrieveInsurance":
			include("lib/iphone/display_insurance.php");
			break;
		case "retrieveHealthcare":
			include("lib/iphone/display_healthcare.php");
			break;
		case "retrieveUploaded":
			include("lib/iphone/display_uploaded.php");
			break;
		case "approveDoc":
			include("lib/iphone/approve.php");
			break;
		case "uploadInfo":
			include("lib/iphone/upload.php");
			break;
		case "sync":
			include("lib/iphone/sync.php"); 
			break;
	}
}

//See if we're getting requests from the Web Interface (POST requests)
else if (isset($_POST['request'])) {
	//Now figure out what the web client is requesting
	$request = $_POST['request'];
	if ($request == "create") { //Request to create a new medical record
		include("lib/web/create_medical_records.php");
	}
}
?>
