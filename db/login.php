<?php
	require_once 'login_info.php';
	$db = new login_info();
	
	# json array format response
	$response = array("error" => FALSE);
	
	if (isset($_POST['ls_emailAddress']) && isset($_POST['password'])){
		# receiving the POST params
		$ls_emailAddress = $_POST['ls_emailAddress'];
		$password = $_POST['password'];
		
		# finding the staff by their email address and password
		$staff = $db->VerifyLoginInfo($ls_emailAddress, $password);
			
		if ($staff != false){
			#staff found
			$response["error"] = FALSE;
			$response["staff"]["ls_staffID"] = $staff["ls_staffID"];
			$response["staff"]["ls_firstName"] = $staff["ls_firstName"];
			$response["staff"]["ls_lastName"] = $staff["ls_lastName"];
			$response["staff"]["ls_emailAddress"] = $staff["ls_emailAddress"];
			echo json_encode($response);
		} else {
			# staff not found
			$response["error"] = TRUE;
			$response["error_message"] = "Wrong email address and/or password. Please try again!";
			echo json_encode($response);
		}
	} else {
		# Required POST params is missing
		$response["error"] = TRUE;
		$response["error_message"] = "Required parameters email address and/or password is missing!";
		echo json_encode($response);
	}
?>