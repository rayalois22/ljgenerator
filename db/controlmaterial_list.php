<?php 
	require_once 'login_connect.php';
	//connecting to database
	$db = new login_connect();
	$connection = $db->connect();
	
	if ( isset($_POST['ls_staffID']) ){
		# Receive the post params
		$ls_staffID = $_POST['ls_staffID'];
		$query = "SELECT cm_name FROM controlmaterial WHERE LaboratoryStaff_ls_staffID = '$ls_staffID';";
		$result = mysqli_query($connection, $query);
		$response = array();
		while($row = mysqli_fetch_assoc($result)){
			array_push($response, array(
				"controlmaterial"=>$row["cm_name"]
			));
		}
		echo json_encode($response);
	} else {
	}
?>