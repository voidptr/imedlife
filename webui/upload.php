<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- #BeginTemplate "template.dwt" -->
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<link rel="stylesheet" type="text/css" href="css/default.css"/>
<link rel="shortcut icon" href="images/favicon.ico" />
<!-- #BeginEditable "doctitle" -->
<title>imedLife Web Interface | Upload</title>
<!-- #EndEditable -->
</head>

<body>
	<div id="banner">  
		<p>
			<a href="http://www.cse.msu.edu/~cse870/Input/SS2010/iMedLife/Source/webui/main.php"><img id="logo" src="images/logo.png" alt="iMedLife"/></a></p>
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
		</ul>
	</div>
	
	<div id="content"> <!-- #BeginEditable "MainContent" -->
<html xmlns="http://www.w3.org/1999/xhtml">

<body>
		<?php
		if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] != true) { //display message if not logged in
			echo "<p class=\"notice\"> Please Login or Create Account above to access your medical records!";
			echo "<img src=\"images/notice.png\" alt=\"!\"/></p>";
		}
	  	else{
	      if(isset($_SESSION['uploaded'])) { echo "<p class=\"uploadsuccess\">File Uploaded Successfully.</p>"; } ?>
		<h3>Please Choose a File and Upload Type and click Submit</h3>
        <form enctype="multipart/form-data" action="../server/lib/web/upload.php" method="post">
            <input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
            File: <input name="userFile[]" type="file" />
            <input type="submit" value="Upload" />
        </form>
        <?php } ?>
    </body>
</html> <!-- #EndEditable --> </div>
	<div id="footer"> Copyright &copy; 2010 | CSE 870 iMedLife Design Group - <a href="http://www.msu.edu" target="_blank">Mighigan State University</a></div>
</body>

<!-- #EndTemplate -->

</html>
