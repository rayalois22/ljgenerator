<?php 
/*
	*This file is used to insert data into the mysql database.
	*The data to be inserted should be provided by the android application.
	*This should be data fields in the register screen.
*/
require "index.php";

# this information will be obtained from the android application.
# we have included it here only for testing purposes.
$ls_staffID = $_POST["ls_staffID"];
$ls_firstName = $_POST["ls_firstName"];
$ls_lastName = $_POST["ls_lastName"];
$ls_email = $_POST["ls_email"];
$ls_lab_name = $_POST["ls_lab_name"];
$ls_role = $_POST["ls_role"];
$ls_city = $_POST["ls_city"];
$ls_password = $_POST["ls_password"];
$ls_managerID = $_POST["ls_managerID"];

$query = "insert into laboratorystaff values ('$ls_staffID', '$ls_firstName', '$ls_lastName', '$ls_email',
 '$ls_lab_name', '$ls_role', '$ls_city', '$ls_password', '$ls_managerID');";
# call the mysqli_query() and pass it the connection variable and the query variable in order to insert data into the database.
if(mysqli_query($con, $query)){
	# echo "<h3>Data inserted successfully!</h3>";
} else
{
	//echo "Error while inserting data...".mysqli_error($con);
}
?>