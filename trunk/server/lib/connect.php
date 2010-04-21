<?php
//connect.php - Sets up the initial connection to the database

//Set up the variables for connecting to the database
$mysqlServer = 'localhost';
$mysqlUser = 'root';
$mysqlPassword = '';
$database = 'burksarm';

//$connect = mysql_connect("$mysqlServer", "$mysqlUser", "$mysqlPassword") or die("couldn't connect to database"); //log in to the server
//mysql_select_db($database); //connect to the database

$connect = mysql_connect("$mysqlServer", "$mysqlUser") or die("couldn't connect to database"); //log in to the server
mysql_select_db($database); //connect to the database

?>
