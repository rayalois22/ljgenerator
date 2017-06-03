<?php
	/*
	______________________________________________________________
		COMPUTES AND SENDS THE MEAN, SD, AND CONTROL LIMITS FOR
		THE SELECTED CONTROL MATERIAL TO THE CLIENT.
	______________________________________________________________
	*/
	require_once 'lj_calculator_api.php';
	$db = new lj_calculator_api();
	
	//JSON encoded response
	$response = array("error"=>FALSE);
	$limits = array();
	$x_coordinates = array();
	$y_coordinates = array();
	$lj_components = array();
	
	if( isset($_POST['ls_staffID']) && isset($_POST['cm_name']) ){ 
		#receive the post params
		$ls_staffID = $_POST['ls_staffID'];
		$cm_name = $_POST['cm_name'];
		$cm_id = $db->ObtainCMID($cm_name, $ls_staffID);
		$cmr_array = array();
		$cmr_array = $db->OBTAIN_CMR_ARRAY($cm_id, $ls_staffID);
		$cmr_mean = $db->OBTAIN_CMR_MEAN($cmr_array);
		$cmr_sd = $db->OBTAIN_CMR_SD($cmr_array, $cmr_mean);
		$limits = $db->OBTAIN_CMR_LIMITS($cmr_sd, $cmr_mean);
		$x_coordinates = $db->OBTAIN_X_COORDINATES($cmr_array);
		$y_coordinates = $db->OBTAIN_Y_COORDINATES($cm_id, $ls_staffID);
		if( count($x_coordinates) == count($y_coordinates) ){
			array_push($lj_components, $cmr_array, $limits, $x_coordinates );
			echo json_encode (array("lj_components" => $lj_components));
		}
	} else {
		$response["error"] = TRUE;
		$response["error_message"] = "Failed. No control material selected...";
		echo json_encode($response);
	}
?>