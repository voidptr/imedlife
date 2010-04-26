<?php 
//process.php - Serves as the interface that responds to requests for both the iPhone and the Web UI

//See if we're getting requests from the iPhone (GET requests)
if (isset($_GET['request'])) { //If we get a GET request, we know it's from the iPhone
	//Now figure out what the iPhone wants
	$request = $_GET['request'];
	
	switch($request) { //Depending on the request, perform the following actions
		case "login":
			include("lib/iphone/login.php");
			break;
		case "logout":
			include("lib/iphone/logout.php");
			break;
		case "retrieveMedRec":
			include("lib/iphone/display_medical_record.php");
			break;
		case "updateMedRec":
			include("lib/iphone/update_medical_record.php");
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
		case "retrieveUploadInfo":
			include("lib/iphone/display_uploaded_info.php");
			break;
		case "retrieveUploadContent":
			include("lib/iphone/display_uploaded_content.php");
			break;
		case "approveDoc":
			include("lib/iphone/approve_doc.php");
			break;
		case "createAccount":
			include("lib/iphone/account_create.php");
			break;
		case "uploadInfo":
			include("lib/iphone/upload.php");
			break;
		case "addCustomField":
			include("lib/iphone/custom_field.php");
			break;
		case "retrieveSync":
			include("lib/iphone/sync.php"); 
			break;
		case "syncComplete":
			include("lib/iphone/sync_complete.php"); 
			break;		
	}
}

//See if we're getting requests from the Web Interface (POST requests)
else if (isset($_POST['request'])) {
	//Now figure out what the web client is requesting
	$request = $_POST['request'];
	
	switch ($request) {//Perform the following actions depending on which request we receive
		case "createAccount": //Request to create a new webui account
			include("lib/web/account_create.php");
			break;	
		case "login":
			include("lib/web/login.php");
			break;
		case "logout":
			include("lib/web/logout.php");
			break;							
		case "create": //Request to create a new medical record
			include("lib/web/create_medical_records.php");
			break;
		case "createMedHist": //Request to create a new medical record
			include("lib/web/create_medical_history.php");
			break;	
		case "upload":
			include("lib/web/upload.php");
			break;
		case "edit":
			include("lib/web/edit.php");
			break;
		case "view":
			include("lib/web/view_records.php");
			break;						
		case "requestApproval":
			include("lib/web/request_approval.php");
			break;
		case "approveDoc":
			include("lib/web/approve_doc.php");
			break;
	}
}
?>
