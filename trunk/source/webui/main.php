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
	
	<!-- #BeginEditable "Login" -->
	<?php //if($_SESSION['loggedin'] != true) { //Show login form if the user has not logged in?>
	<div id="login">
		<form action="login.php">
			Username: <input type="text" name="username" />
			Password: <input type="password" name="password" />
			<input type="submit" name="login" value="Login"/>
		</form>
	</div>
	<div id="create"> <a href="#"> Create Account </a></div>

	<?php // } ?>
	<!-- #EndEditable -->
	
	<div id="menu">
		<ul>
			<li> <a href="main.php"> Main </a></li>
			<li> <a href="#"> Patient Info </a></li>
			<li> <a href="#"> Medical Info </a></li>
			<li> <a href="#"> Help </a> </li>
		</ul>
	</div>
	
	<div id="content"> <!-- #BeginEditable "MainContent" -->Content <!-- #EndEditable --> </div>
</body>

<!-- #EndTemplate -->

</html>
