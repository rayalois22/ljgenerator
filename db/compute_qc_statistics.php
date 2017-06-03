<?php 
## The Logic first####
#Obtain a selected control material
#Obtain all cmr for the selected cm
#Sum all the cmr_value for the cmr and divide by the total number of cmr to obtain the mean
#subtract every cmr_value from the mean, square the difference and sum up all the squares, divide the sum by the total number of cmr to obtain sd
#add sd to mean, add 2sd to mean, add 3sd to mean, subtract sd from mean, subtract 2sd from mean, subtract 3sd from mean
#plot limit lines corresponding to mean, plus1sd, plus2sd, plus3sd, minus 1sd, minus2sd, minus3sd
#cmr_value on the y-axis
#cmr on the x-axis
#scatter plots
#Obtain the corresponding cmr_id for all the cmr 
# Plot a scatter plot for all the cmr and their cmr_id
######Done with the logic

	require_once 'login_connect.php';
	//connecting to database
	$db = new login_connect();
	$connection = $db->connect();
	
	if( isset($_POST['ls_staffID']) && isset($_POST['cm_name']) ){ 
		#receive the post params
		$ls_staffID = $_POST['ls_staffID'];
		$cm_name = $_POST['cm_name'];
		$q1 = "SELECT cm_id FROM controlmaterial WHERE cm_name = '$cm_name' AND LaboratoryStaff_ls_staffID = '$ls_staffID';";
		$cm_id = mysqli_query($connection, $q1);
		$query = "SELECT cmr_id, cmr_value FROM controlmaterialresult WHERE ControlMaterial_cm_id = '$cm_id' AND LaboratoryStaff_ls_staffID = '$ls_staffID';";
		$result = mysqli_query($connection, $query);
		$res = array();
		while($row = mysqli_fetch_assoc($result)){
			array_push($res, array(
				$data["cmr_id"] => $row["cmr_id"],
				$data["cmr_value"] => $row["cmr_value"]
			));
		}
		echo json_encode(array("response" =>$res));
		mysqli_close($connection);
	}


?>