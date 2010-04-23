<?php 
//upload.php - Handles uploading of data (images, notes, voice) to the database from the webui
session_start();
include_once("../connect.php");

//The Upload Function ...
function upload(){

	//Unset the $_SESSION['uploaded'] so we can make sure there are no errors
	unset($SESSION['uploaded']);
	
	if(is_uploaded_file($_FILES['userFile']['tmp_name'][0])) { 	    //make sure we actually got a file
	    $maxSize = $_POST['MAX_FILE_SIZE'];
	    if($_FILES['userFile']['size'][0] < $maxSize) { //Make sure the filesize < maximum file size
			$patientID = $_SESSION['sessionID']; //Get the patientID
			$size = getimagesize($_FILES['userFile']['tmp_name'][0]);
			$uploadType = $size['mime'];
			
			$imageData =addslashes (file_get_contents($_FILES['userFile']['tmp_name'][0])); //Raw data to insert
			$date = Date("l F d, Y");//Get the date to insert
			
		    //Get ready to insert
		    $query = "INSERT INTO userUploads(patientID, noteDate, upload, uploadType) VALUES ('$patientID', '$date', '$imageData', '$uploadType')";
		
		    // insert the image
		    if(!mysql_query($query)) {
		        echo "Unable to upload file";
		    }    
	    }
    	else { // if the file is >= than the maximum allowed, don't proceed
	     echo "File exceeds the Maximum File limit<br/>";
	     echo "Maximum File limit is $maxsize bytes.<br/>";
     	}

	}
}//END upload function

// Make sure a file was submitted and the uploadType was set
if(!isset($_FILES['userFile'])) {
    	echo "Please use your browser's back button and select a file";
}
else { //Go ahead since we got everything we needed
    try {
        upload(); //upload the file
        $_SESSION['uploaded'] = "uploaded";		
        header("location: ../../../webui/upload.php");
    }
    catch(Exception $e) { //Handle errors in the upload
        echo $e->getMessage();
        echo 'Sorry, could not upload file';
    }
}
?>