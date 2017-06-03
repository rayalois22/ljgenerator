<?php 
/**********************************
	*This script is used to
	*obtain and encode data 
	*from the server 
	*as JSON objects and arrays
**********************************/
$host = "localhost";
$user = "root";
$pass = "root";
$db = "ljgenerator";

$sql = "select ls_staffID, ls_firstName, ls_lastName, ls_emailAddress, ls_laboratoryName, ls_role, ls_city, ls_managerID from laboratorystaff;";

$con = mysqli_connect($host, $user, $pass, $db);

$result = mysqli_query($con, $sql);

$response = array();

while($row = mysqli_fetch_array($result)){
	array_push($response,
		array("Staff ID"=>$row[0], "First name"=>$row[1],
		"Last name"=>$row[2], "Email address"=>$row[3],
		"Laboratory name"=>$row[4], "Role"=>$row[5], "City"=>$row[6], "Manager ID"=>$row[7])
	);	
}
echo json_encode(array("server_response"=>$response));
mysqli_close($con);

?>