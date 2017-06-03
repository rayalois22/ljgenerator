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

$sql = "select ls_firstName, ls_lastName, ls_emailAddress from laboratorystaff;";

$con = mysqli_connect($host, $user, $pass, $db);

$result = mysqli_query($con, $sql);

$response = array();

while($row = mysqli_fetch_array($result)){
	array_push($response,
		array("First name"=>$row[0],
		"Last name"=>$row[1], "Email address"=>$row[2])
	);	
}
echo json_encode(array("server_response"=>$response));
mysqli_close($con);

?>