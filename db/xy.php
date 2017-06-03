<?php 
	/*
	_______________________________________________________________________
	_______________________________________________________________________
	THIS FILE OBTAINS THE X- AND Y-COORDINATES AND SENDS THEM TO THE CLIENT
	_______________________________________________________________________
	_______________________________________________________________________
	*/
	require_once 'lj_calculator_api.php';
	$db = new lj_calculator_api();
	
	$response = array();
	
	if( isset($_POST['ls_staffID']) && isset($_POST['cm_name']) ){ 
		#receive the post params
		$ls_staffID = $_POST['ls_staffID'];
		$cm_name = $_POST['cm_name'];
		$cm_id = $db->ObtainCMID($cm_name, $ls_staffID);
		$response = $db->OBTAIN_X_Y($cm_id, $ls_staffID);
		echo json_encode(array("response" => $response);
	}
?>