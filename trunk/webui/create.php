<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<link rel="stylesheet" type="text/css" href="css/default.css"/>
<link rel="shortcut icon" href="images/favicon.ico" />
<title>iMedLife Web Interface | Create Account</title>
</head>

<body>
	<div id="banner">  
		<p>
			<a href="http://www.cse.msu.edu/~burksarm/imedlife/webui/main.php"><img id="logo" src="images/logo.png" alt="iMedLife"/></a></p>		
	</div>
	<div id="menu">
		<ul>
			<li> <a href="main.php"> Main </a></li>
			<li> <a href="patientinfo.php"> Patient Info </a></li>
		</ul>
	</div>
	
	<div id="content"> 
		<form class="forms" method="post" action="../server/process.php">	
		<h3> Create a new Web Client Account</h3><br/>
			<p> Account Type: <b>Patient</b> <input type="radio" name="createType" value="patient"/>
			<b>Doctor</b> <input type="radio" name="createType" value="doctor"/></p>
			Username: <input type="text" name="username"/><br/>
			Password (at least 6 characters): 
			<input type="password" name="password" />
			Confirm Password: <input type="password" name="passwordConfirm"/><br/><br/>
			First Name: <input type="text" name="firstName" />
			Middle Name: <input type="text" name="middleName" />
			Last Name :<input type="text" name="lastName" /><br/>
			<input type="hidden" name="request" value="createAccount"/>
			<input type="submit" value="Create Account" />
		</form>

											
	</div>
	<div id="footer"> Copyright &copy; 2010 | CSE 870 iMedLife Design Group - <a href="http://www.msu.edu" target="_blank">Mighigan State University</a></div>
</body>

</html>
