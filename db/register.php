<?php
	require_once 'login_info.php';
	$db = new login_info();
	
	# json response array
	$response = array("error" => FALSE);
	
	if((isset($_POST['ls_firstName']) && isset($_POST['ls_lastName']) &&
		isset($_POST['ls_emailAddress']) && isset($_POST['ls_laboratoryName']) && 
		isset($_POST['ls_city']) && isset($_POST['password'])) ){
		# receiving the POST params
		$ls_staffID = "";
		$ls_firstName = $_POST['ls_firstName'];
		$ls_lastName = $_POST['ls_lastName'];
		$ls_emailAddress = $_POST['ls_emailAddress'];
		$ls_laboratoryName = $_POST['ls_laboratoryName'];
		$ls_role = "manager"; # $_POST['ls_role'];
		$ls_city = $_POST['ls_city'];
		$password = $_POST['password'];
		# checking if there already exists a staff with the same email address
		if($db->CheckExistingStaff($ls_emailAddress)){
			# user exists already
			$response["error"] = TRUE;
			$response["error_message"] = "A laboratory staff already exists with " . $ls_emailAddress;
			echo json_encode($response);
		} else {
			# create a new staff
			$staff = $db->StoreLoginInfo($ls_staffID, $ls_firstName, $ls_lastName, $ls_emailAddress, $ls_laboratoryName, $ls_role, $ls_city, $password);
			if($staff){
				# staff info successfully stored
				$response["error"] = FALSE;
				$response["staff"]["ls_staffID"] = $staff["ls_staffID"];
				$response["staff"]["ls_firstName"] = $staff["ls_firstName"];
				$response["staff"]["ls_lastName"] = $staff["ls_lastName"];
				$response["staff"]["ls_emailAddress"] = $staff["ls_emailAddress"];
				echo json_encode($response);
			} else {
				# failed to store staff info
				$response["error"] = TRUE;
				$response["error_message"] = "Unknown error occured during registration. Please try again!";
				echo json_encode($response);
			}
		}
	} else{
		$response["error"] = TRUE;
		$response["error_message"] = "You must provide all the required parameters!";
		echo json_encode($response);
	}
	
?>