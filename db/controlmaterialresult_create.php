<?php 
	require_once 'login_info.php';
	$db = new login_info();
	
	# json response array
	$response = array("error" => FALSE);
	
	if( isset($_POST['cmr_value']) && isset($_POST['cm_name']) ){
		# receiving the POST params
		$cmr_value = $_POST['cmr_value'];
		$cm_name = $_POST['cm_name'];
		$cmr_instrument = $_POST['cmr_instrument'];
		$cmr_assayMethod = $_POST['cmr_assayMethod'];
		$cmr_temperature = $_POST['cmr_temperature'];
		$ls_staffID = $_POST['ls_staffID'];
		$ls_emailAddress = $_POST['ls_emailAddress'];
		$ControlMaterial_cm_id = $db->ObtainCMID($cm_name, $ls_staffID);
		$ControlMaterial_Analyte_an_name = $db->ObtainAnalyteName($cm_name, $ls_staffID);
		## creating the control material result
		$controlmaterialresult = $db->StoreControlMaterialResult($cmr_value, $cmr_instrument, $cmr_assayMethod, $cmr_temperature, $ls_staffID, $ls_emailAddress, $ControlMaterial_cm_id, $ControlMaterial_Analyte_an_name);
		if($controlmaterialresult){
			$response["error"] = FALSE;
			$response["controlmaterialresult"]["cmr_id"] = $controlmaterialresult["cmr_id"];
			$response["controlmaterialresult"]["cmr_time"] = $controlmaterialresult["cmr_time"];
			$response["controlmaterialresult"]["cmr_value"] = $controlmaterialresult["cmr_value"];
			$response["controlmaterialresult"]["ControlMaterial_cm_id"] = $controlmaterialresult["ControlMaterial_cm_id"];
			$response["controlmaterialresult"]["ControlMaterial_Analyte_an_name"] = $controlmaterialresult["ControlMaterial_Analyte_an_name"];
			echo json_encode($response);
		} else {
			$response["error"] = TRUE;
			$response["error_message"] = "Unknown error occurred while storing the result. Please try again!";
			echo json_encode($response);
		}
	} else {
		$response["error"] = TRUE;
		$response["error_message"] = "Failed, you must enter all the required parameters!";
		echo json_encode($response);
	}
?>