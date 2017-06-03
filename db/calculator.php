<?php 

	require_once 'login_connect.php';
	//connecting to database
	$db = new login_connect();
	$connection = $db->connect();
	
	if( isset($_POST['ls_staffID']) && isset($_POST['cm_name']) ){ 
		#receive the post params
		$ls_staffID = $_POST['ls_staffID'];
		$cm_name = $_POST['cm_name'];
		#$q1 = "SELECT cm_id FROM controlmaterial WHERE cm_name = '$cm_name' AND LaboratoryStaff_ls_staffID = '$ls_staffID';";
		#$r1 = mysqli_query($connection, $q1);
			function ObtainCMID($cm_name, $LaboratoryStaff_ls_staffID){
				require_once 'login_connect.php';
				//connecting to database
				$db = new login_connect();
				$connection = $db->connect();
				$statement = $connection->prepare("SELECT cm_id FROM controlmaterial WHERE cm_name = ? AND LaboratoryStaff_ls_staffID = ?");
				$statement->bind_param("ss", $cm_name, $LaboratoryStaff_ls_staffID);
				$statement->execute();
				$statement->bind_result($token1);
				while($statement->fetch()){
					$cm_id = $token1;
				}
				$statement->close();
				return $cm_id;
			} ## ending ObtainCMID()
		$cm_id = ObtainCMID($cm_name, $ls_staffID);
		$query = "SELECT cmr_id, cmr_value FROM controlmaterialresult WHERE ControlMaterial_cm_id = '$cm_id' AND LaboratoryStaff_ls_staffID = '$ls_staffID';";
		## Calculating the mean and inserting it into the database
		$values_query = "SELECT cmr_value FROM controlmaterialresult WHERE ControlMaterial_cm_id = '$cm_id' AND LaboratoryStaff_ls_staffID = '$ls_staffID';";
		####
		$result = mysqli_query($connection, $query);
		$res = array();
		while($row = mysqli_fetch_assoc($result)){
			array_push($res, array(
				"cmr_id" => $row["cmr_id"],
				"cmr_value" => $row["cmr_value"]
			));
		}
		echo json_encode(array("response" =>$res));
		mysqli_close($connection);
	}
	
	/**
			require_once 'login_info.php';
		$db = new login_info();
		
		# json response array
		$response = array("error" => FALSE);
		$data = array();
		
		if( isset($_POST['ls_staffID']) && isset($_POST['cm_name']) ){
			# Receiving the POST params
			$ls_staffID = $_POST['ls_staffID'];
			$cm_name = $_POST['cm_name'];
			$cm_id = $db->ObtainCMID($cm_name, $ls_staffID);
			$data = $db->ObtainCM_CMR($cm_id, $ls_staffID);
			echo json_encode(array("response" => $data));		
		} else {
			$response["error"] = TRUE;
			$response["error_message"] = "Failed, no control material is selected!";
			echo json_encode($response);
		}
	*/
	
	
?>