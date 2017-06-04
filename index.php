<?php 
# declare and initialize the needed variables: 
# database name, user name for mysql database, 
# password for mysql database
# name of the server
$server_name = "localhost";
$mysql_user = "root";
$mysql_pass = "root";
$db_name = "ljgenerator"; 

# calling the mysqli_connect() function to create a connection to the database.
$con = mysqli_connect($server_name, $mysql_user, $mysql_pass, $db_name);

if(!$con){
	# mysqli_connect_error() function will print any error during connection
	//echo "Connection Error...".mysqli_connect_error();
} else 
{
	//echo "<h3>Database connection success!</h3>";	
}


?>
<?php
	echo '<center><br /><br /><h3>';
	echo '<em>The LJ Generator app website is coming soon.</em>';
	echo '</h3></center>';
?>
