<?php 
	require_once 'login_connect.php';
	//connecting to database
	$db = new login_connect();
	$connection = $db->connect();
	
	if ( isset($_POST['ls_staffID']) ){
		# Receive the post params
		$ls_staffID = $_POST['ls_staffID'];
		$query = "SELECT an_name FROM analyte WHERE LaboratoryStaff_ls_staffID = '$ls_staffID';";
		$result = mysqli_query($connection, $query);
		$response = array();
		while($row = mysqli_fetch_assoc($result)){
			array_push($response, array(
				"analyte"=>$row["an_name"]
			));
		}
		echo json_encode($response);
		
	} else {
	}
?>