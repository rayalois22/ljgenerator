<?php 
require "index.php";
include "login.php";

$ls_emailAddress = $emailAddress; 
$email = $_POST["ls_emailAddress"];
$ls_staffID = 0;
$query = "select ls_staffID from laboratorystaff where ls_emailAddress = '$ls_emailAddress';";
$result = mysqli_query($con, $query);
if ($result === null){
	#echo "Oops, something is not right...";
}
else if (mysqli_num_rows($result)>0){
	$row = mysqli_fetch_assoc($result);
	$ls_staffID = $row["ls_staffID"];
	#echo "staffID is ".$ls_staffID;
}
else {
	#echo "Oops, something is not right...";
}
?>