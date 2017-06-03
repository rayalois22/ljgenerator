<?php 
require_once 'login_info.php';
	$db = new login_info();
	if( isset($_POST['an_name']) && isset($_POST['an_units'])){
		# receiving the POST params
		$an_name = $_POST['an_name'];
		$an_units = $_POST['an_units'];
		$ls_staffID = $_POST['ls_staffID'];
		$ls_emailAddress = $_POST['ls_emailAddress'];
		# checking if there already exists an analyte with the same name
		if($db->CheckExistingAnalyte($an_name, $ls_staffID)){
			# analyte exists already
			$response["error"] = TRUE;
			$response["error_message"] = "Analyte created!";
			echo json_encode($response);
		} else {
			# creating the analyte
			$analyte = $db->StoreAnalyte($an_name, $an_units, $ls_staffID, $ls_emailAddress);
			# checking for successful storage
			if($analyte){
				# storage successful
				$response["error"] = FALSE;
				$response["analyte"]["an_name"] = $analyte["an_name"];
				$response["analyte"]["an_units"] = $analyte["an_units"];
				$response["analyte"]["ls_staffID"] = $analyte["ls_staffID"];
				$response["analyte"]["ls_emailAddress"] = $analyte["ls_emailAddress"];
				echo json_encode($response);
			} else {
				$response["error"] = TRUE;
				$response["error_message"] = "Unknown error occured while creating the analyte. Please try again!";
				echo json_encode($response);
			}
			
		}
	} else {
		$response["error"] = TRUE;
		$response["error_message"] = "Failed, you must fill in all the required fields!";
		echo json_encode($response);
	}
?>