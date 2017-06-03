<?php 
/**********************************
	*This script is used to
	*obtain and encode data 
	*from the server 
	*as JSON objects and arrays
	*It obtains the details of an
	*analyte from the server 
**********************************/
include "index.php";

$ls_emailAddress = mysqli_real_escape_string($con, $_POST["ls_emailAddress"]);

$query = "SELECT * FROM analyte WHERE LaboratoryStaff_ls_emailAddress = '$ls_emailAddress';";

$result = mysqli_query($con, $query);

$response = array();

	if($result == false){
		//something went wrong. It must be handled here
		echo "Oops! Something went wrong. Try again.";
	}
	else {
		while($row = mysqli_fetch_array($result)){
			array_push($response,
				array("an_name"=>$row[0], "an_units"=>$row[1],
				"ls_emailAddress"=>$row[2], "ls_staffID"=>$row[3])
			);	
		}
		echo json_encode(array("server_response"=>$response));
		mysqli_close($con);		
	}
?>