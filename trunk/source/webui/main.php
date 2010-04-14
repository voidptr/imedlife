<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- #BeginTemplate "template.dwt" -->
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<link rel="stylesheet" type="text/css" href="css/default.css"/>
<!-- #BeginEditable "doctitle" -->
<title>iMedLife Web Interface | Main</title>
<!-- #EndEditable -->
</head>

<body>
	<div id="banner">  </div>
	<div id="logo"> <a href="http://www.cse.msu.edu/~burksarm/imedlife/webui/main.php"><img src="images/logo.png"/></a></div>
	<?php if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] != true) { //Show login form if the user has not logged in ?>
	<div id="login">
		<form action="login.php">
			Username: <input type="text" name="username" />
			Password: <input type="password" name="password" />
			<input type="submit" name="login" value="Login"/>
		</form>
	</div>
	<div id="create"> <a href="#"> Create Account </a></div>

	<?php  } ?>
	
	<div id="menu">
		<ul>
			<li> <a href="main.php"> Main </a></li>
			<li> <a href="#"> Patient Info </a></li>
			<li> <a href="#"> Medical Info </a></li>
			<li> <a href="#"> Help </a> </li>
		</ul>
	</div>
	
	<div id="content"> <!-- #BeginEditable "MainContent" -->
		<h1> Welcome</h1>
		<p class="main"> iMedLife &trade; is an iPhone application that serves as a personal medical record, PMR, for the owner. 
						Users can view their medical records, images, etc. directly from the iPhone. Users also have the capability of 
						updating and uploading information to the server, all within the iPhone app! </p>
						
		<p class="main"> You have reached the web interface to the server. web users have all the capabilities that iPhone users have,
						but with the additional capability of being able to access their medical records from any computer, anywhere!
						Doctors may view and edit patient information from the web interfice as well. </p>
						
		<?php if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] != true) //display message if not logged in
		echo "<p class=\"notice\"> Please Login or Create Account above to access your medical records!</p>";
		?>
											
		<!-- #EndEditable --> </div>
	<div id="footer"> Copyright &copy; 2010 | CSE 870 iMedLife Design Group - <a href="http://www.msu.edu" target="_blank">Mighigan State University</a></div>
</body>

<!-- #EndTemplate -->

</html>
