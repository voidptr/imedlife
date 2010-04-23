<?php 
//upload.php - Handles uploading of data (images, notes, voice) to the database from the webui
session_start();
include_once("../connect.php");

//The Upload Function ...
function upload(){
	if(is_uploaded_file($_FILES['userfile']['tmp_name'])) {
	
	    //Make sure the filesize < maximum file size
	    if($_FILES['userfile']['size'] < $maxsize) {
				
			//Get the patientID
			$patientID = $_SESSION['sessionID'];
			//Get the date to insert
			
		    //Get ready to insert
		    $query = "INSERT INTO userUploads(patientID, noteDate, upload, uploadType) VALUES ({$_FILES['userfile']['name']}')";
		
		    // insert the image
		    if(!mysql_query($query)) {
		        echo "Unable to upload file";
		    }
	    }
    	else { // if the file is >= than the maximum allowed, don't proceed
	     echo
	      '<div>File exceeds the Maximum File limit</div>
	      <div>Maximum File limit is '.$maxsize.'</div>
	      <div>File '.$_FILES['userfile']['name'].' is '.$_FILES['userfile']['size'].' bytes</div>
	      <hr />';
     	}

	}
}//END upload function

// Make sure a file was submitted and the uploadType was set
if(!isset($_FILES['userFile']) || !isset($_POST['uploadType']) || $_POST['uploadType'] == "Select Type") {
	if (!isset($_FILES['userFile'])
    	echo "<p>Please use your browser's back button and select a file</p>";
    if (!isset($_POST['uploadType']))
    	echo "<p>Please use your browser's back button and select an upload type</p>";
}
else { //Go ahead since we got everything we needed
    try {
        upload(); //upload the file
    }
    catch(Exception $e) { //Handle errors in the upload
        echo $e->getMessage();
        echo 'Sorry, could not upload file';
    }
}

?>