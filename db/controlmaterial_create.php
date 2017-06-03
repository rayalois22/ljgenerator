<?php 
	require_once 'login_info.php';
	$db = new login_info();
	
	# json response array
	$response = array("error" => FALSE);
	
	if ( isset($_POST['cm_name']) && isset($_POST['cm_level']) ){
		# Receiving the POST params
		$cm_name = $_POST['cm_name'];
		$Analyte_an_name = $_POST['an_name'];
		$cm_units = $db->ObtainUnits($Analyte_an_name);
		$cm_level = $_POST['cm_level'];
		$cm_lotNumber = $_POST['cm_lotNumber'];
		$cm_mean = $_POST['cm_mean'];
		$cm_sd = $_POST['cm_sd'];
		## $plus3SD = ""; $plus2SD = ""; $plus1SD = ""; $minus1SD = ""; $minus2SD = ""; $minus3SD = ""; $cm_status = ""; $cm_chart = "";
		$LaboratoryStaff_ls_staffID = $_POST['ls_staffID'];
		$LaboratoryStaff_ls_emailAddress = $_POST['ls_emailAddress'];
		$Analyte_LaboratoryStaff_ls_staffID = $_POST['ls_staffID'];
		$Analyte_LaboratoryStaff_ls_emailAddress = $_POST['ls_emailAddress'];
		# check if the controlmaterial already exists
		if ( $db->CheckExistingControlMaterial($cm_name, $cm_level, $LaboratoryStaff_ls_staffID, $Analyte_an_name) ){
			# the controlmaterial exists already
			$response["error"] = TRUE;
			$response["error_message"] = "Control material already created!";
			echo json_encode($response);
		} else {
			# create the controlmaterial
			$controlmaterial = $db->StoreControlMaterial($cm_name, $cm_units, $cm_level, $cm_lotNumber, $cm_mean, $cm_sd, $LaboratoryStaff_ls_staffID, $LaboratoryStaff_ls_emailAddress, $Analyte_an_name, $Analyte_LaboratoryStaff_ls_staffID, $Analyte_LaboratoryStaff_ls_emailAddress);
			if($controlmaterial){
				$analytelevel = $db->AsignControlLevel($cm_name, $cm_level, $Analyte_an_name);
				$response["error"] = FALSE;
				$response["controlmaterial"]["cm_id"] = $controlmaterial["cm_id"];
				$response["controlmaterial"]["cm_name"] = $controlmaterial["cm_name"];
				$response["controlmaterial"]["cm_units"] = $controlmaterial["cm_units"];
				$response["controlmaterial"]["cm_level"] = $controlmaterial["cm_level"];
				$response["controlmaterial"]["cm_lotNumber"] = $controlmaterial["cm_lotNumber"];
				echo json_encode($response);
			} else {
				$response["error"] = TRUE;
				$response["error_message"] = "Unknown error occurred while creating the control material. Please try again!";
				echo json_encode($response);
			}
		}
	} else {
		$response["error"] = TRUE;
		$response["error_message"] = "Failed, you must enter all the required parameters!";
		echo json_encode($response);
	}
?>