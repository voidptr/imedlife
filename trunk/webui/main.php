<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- #BeginTemplate "template.dwt" -->
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<link rel="stylesheet" type="text/css" href="css/default.css"/>
<link rel="shortcut icon" href="images/favicon.ico" />
<!-- #BeginEditable "doctitle" -->
<title>iMedLife Web Interface | Main</title>
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
		<h1> 	Welcome</h1>
		<p class="main"> iMedLife is an iPhone application that serves as a personal medical record, PMR, for the owner. 
						Users can view their medical records, images, etc. directly from the iPhone. Users also have the capability of 
						updating and uploading information to the server, all within the iPhone app! </p>
						
		<p class="main"> You have reached the web interface to the server. web users have all the capabilities that iPhone users have,
						but with the additional capability of being able to access their medical records from any computer, anywhere!
						Doctors may view and edit patient information from the web interfice as well. </p>
						
		<?php //display message if not logged in
			if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] != true) {
				echo "<p class=\"notice\"> Please Login or Create Account above to access your medical records!";
				echo "<img src=\"images/notice.png\" alt=\"!\"/></p>";
			}
		?>
											
		<!-- #EndEditable --> </div>
	<div id="footer"> Copyright &copy; 2010 | CSE 870 iMedLife Design Group - <a href="http://www.msu.edu" target="_blank">Mighigan State University</a></div>
</body>

<!-- #EndTemplate -->

</html>
