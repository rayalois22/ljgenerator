<?php 
/*
	*This file is used to insert data into the mysql database.
	*The data to be inserted should be provided by the android application.
	*This should be data about an analyte.
*/
require "index.php";
$an_name = $_POST["an_name"];
$an_units = $_POST["an_units"];
$LaboratoryStaff_ls_emailAddress = $_POST["ls_emailAddress"];
$LaboratoryStaff_ls_staffID = $_POST["ls_staffID"];
$an_highControl = "";
$an_normalControl = "";
$an_lowControl = "";
# query variable
$query = "insert into analyte values ('$an_name', '$an_units', '$an_highControl',
 '$an_normalControl','$an_lowControl', '$LaboratoryStaff_ls_staffID', '$LaboratoryStaff_ls_emailAddress');";
# call the mysqli_query() and pass it the connection variable and the query variable in order to insert data into the database.
if(mysqli_query($con, $query)){
	 echo "<h3>Data inserted successfully!</h3>";
} else
{
	 echo "Error while inserting data...".mysqli_error($con);
}
?>