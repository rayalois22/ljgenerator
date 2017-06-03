<?php
	/*
		****************************************************************
		****************************************************************
		THIS API COMPUTES THE MEAN AND STANDARD DEVIATION FOR 
		THE SET OF ALL CONTROL MATERIAL RESULTS OF ANY GIVEN 
		CONTROL MATERIAL
		----------------------------------------------------------------
		****************************************************************
		****************************************************************
	*/
	/*****************************************************
	## -----------------MENU----------------------------##
	##	------------------------------------------------##
	##	1. Obtain the selected control material			##
	##	------------------------------------------------##
	##	2. Obtain the id of the cm						##
	##	------------------------------------------------##
	##	3. Obtain an array of cmr for the cm			##
	##	------------------------------------------------##
	##	4. Calculate the mean of the cmr array			##
	##	------------------------------------------------##
	##	5. Calculate the SD of the cmr array			##
	##	------------------------------------------------##
	##	6. Use the SD to obtain the plus3SD,			##
	##	   plus2SD, ..., minus3SD						##
	##	------------------------------------------------##
	##	7. Insert/Update the mean,  SD, plus3SD,		##
	##		plus2SD, ..., minus3SD for the cmr 			##
	##		into the database.							##
	##	------------------------------------------------##
	##	8. Send the values to the client 				##
	##		in JSON format								##
	##	------------------------------------------------##
	*****************************************************/
	class lj_calculator_api{
		private $connection;
		//constructor
		function __construct(){
			require_once 'login_connect.php';
			//connecting to database
			$db = new login_connect();
			$this->connection = $db->connect();
		}
		//destructor
		function __destruct(){
			
		}
		// 2. 
		public function ObtainCMID($cm_name, $LaboratoryStaff_ls_staffID){
			$statement = $this->connection->prepare("SELECT cm_id FROM controlmaterial WHERE cm_name = ? AND LaboratoryStaff_ls_staffID = ?");
			$statement->bind_param("ss", $cm_name, $LaboratoryStaff_ls_staffID);
			$statement->execute();
			$statement->bind_result($token1);
			while($statement->fetch()){
				$cm_id = $token1;
			}
			$statement->close();
			return $cm_id;
		} /*--------------ENDING ObtainCMID()--------*/
		// 3.
		public function OBTAIN_CMR_ARRAY($cm_id, $LaboratoryStaff_ls_staffID){
			$cmr_array = array();
			$con = $this->connection;
			$query = "SELECT cmr_value FROM controlmaterialresult WHERE ControlMaterial_cm_id = '$cm_id' AND LaboratoryStaff_ls_staffID = '$LaboratoryStaff_ls_staffID';";
			$result = mysqli_query($con, $query);
			while( $row = mysqli_fetch_assoc($result) ){
				array_push($cmr_array, $row['cmr_value']);
			}
			return $cmr_array;
		} /*--------ENDING OBTAIN_CMR_ARRAY()---------*/
		public function OBTAIN_CMR_MEAN($cmr_arrayy){
			$sum = array_sum($cmr_arrayy);
			$N = count($cmr_arrayy);
			$mean = 0.0;
			if ($N > 1){
				$mean = $sum / $N;
				return $mean;
			} else {
				//trigger_error("Oops, not enough control material results. At least two values needed.", E_USER_WARNING);
				return $mean;
			}
		}
		public function OBTAIN_CMR_SD($cmr_array_sd, $cmr_mean){
			/**************************************
				___________________________________________________
				1. Iterate through and square each element 
				of the array.
				___________________________________________________
				2. Sum: Sum  up the squares of all elements of 
				array.
				___________________________________________________
				3. Get the quotient: sum of square of elements / 
				number of elements
				___________________________________________________
				4. Square of Mean: Square the mean of the array
				___________________________________________________
				5. SD: Get the difference: Quotient - Square of MEAN
				& find its squareroot
				____________________________________________________
			**************************************/
			$a = $cmr_array_sd;
			$x = $cmr_mean;
			$N = count($a);
			//4.
			$mean_squared = pow($x, 2);
			$squares = array();
			// 1.
			if (($a != null)&& ($N > 1)){
				// iterating through each element of the array
				for($i=0; $i < count($a); $i++){
					// using the pow() to square each element
					$a_squared = pow($a["$i"], 2);
					// pushing the square into an array
					array_push($squares, $a_squared);				
				}
				//2. & 3.
				$quotient = array_sum($squares) / $N;
				// 5. computing the variance
				$variance = $quotient - $mean_squared;
				// computing the standard deviation
				$sd = sqrt($variance);
				return $sd;
			} else {
				trigger_error("Oops, not enough control material results!", E_USER_WARNING);
			}	
		}/*-------ENDING OBTAIN_CMR_SD()-------------*/	
		public function OBTAIN_CMR_LIMITS($cmr_sd, $cmr_mean){
			$cmr_limits = array();
			if ($cmr_sd != null && $cmr_mean != null){
				$plus3SD = ( $cmr_mean + (3 * $cmr_sd) );
				$plus2SD = ( $cmr_mean + (2 * $cmr_sd) );
				$plus1SD = ( $cmr_mean +  $cmr_sd );
				$minus3SD = ( $cmr_mean - (3 * $cmr_sd) );
				$minus2SD = ( $cmr_mean - (2 * $cmr_sd) );
				$minus1SD = ( $cmr_mean - $cmr_sd );
				array_push($cmr_limits, 
					array(
						"plus3SD" => $plus3SD, "plus2SD" => $plus2SD,
						"plus1SD" => $plus1SD, "minus1SD" => $minus1SD,
						"minus2SD" => $minus2SD, "minus3SD" => $minus3SD,
						"cmr_mean" => $cmr_mean, "cmr_sd" => $cmr_sd
					)
				);
			}
			return $cmr_limits;
		}/*-----ENDING OBTAIN_CMR_LIMITS()------*/
		public function OBTAIN_X_Y($cm_id, $ls_staffID){
			$xy = array();
			$con = $this->connection;
			$query = "SELECT cmr_id, cmr_value FROM controlmaterialresult WHERE ControlMaterial_cm_id = '$cm_id' AND LaboratoryStaff_ls_staffID = '$ls_staffID';";
			$result = mysqli_query($con, $query);
			while($row = mysqli_fetch_assoc($result)){
				array_push($xy, array(
					"cmr_id" => $row["cmr_id"],
					"cmr_value" => $row["cmr_value"]
				));
			}
			mysqli_close();
			return $xy;
			//echo json_encode(array("response" =>$xy));
		}
		public function OBTAIN_Y_COORDINATES($cm_id, $ls_staffID){
			$y = array();
			$con = $this->connection;
			$query = "SELECT cmr_value FROM controlmaterialresult WHERE ControlMaterial_cm_id = '$cm_id' AND LaboratoryStaff_ls_staffID = '$ls_staffID';";
			$result = mysqli_query($con, $query);
			while($row = mysqli_fetch_assoc($result)){
				array_push($y, array(
					"cmr_value" => $row["cmr_value"]
				)); 
			}
			return $y;
		}/*-----ENDING OBTAIN_Y()-------*/
		public function OBTAIN_X_COORDINATES($cmr_array){
			/**
				_____________________________________________________
				NOW WE NEED A FUNCTION TO COMPUTE THE X-COORDINATES 
				FOR EVERY SET OF CONTROL MATERIAL RESULTS
				-----------------------------------------------------
				1. THE FUNCTION TAKES THE ARRAY OF CMR AS A PARAMETER
				2. THE FUNCTION CREATES A SERIES OF X-COORDINATES
				STARTING FROM 0, 1, 2, ....., N WHERE N = THE NUMBER 
				OF ELEMENTS IN THE ARRAY.
				-----------------------------------------------------
				______________________________________________________
				
			*/
			$N = count($cmr_array);
			$x_coordinates = array();
			for($i = 0; $i < $N; $i++){
				array_push($x_coordinates, $i);
			}
			return $x_coordinates;
		}
		public function IMPORT_CMR($ls_staffID, $cm_id){
			$cmr_delete_list = array();
			$con = $this->connection;
			$query = "SELECT cmr_value FROM controlmaterialresult WHERE LaboratoryStaff_ls_staffID = '$ls_staffID' AND ControlMaterial_cm_id = '$cm_id';";
			$result = mysqli_query($con, $query);
			//iterating through the resultset
			while ($row = mysqli_fetch_assoc($result)) {
				array_push($cmr_delete_list, array(
						"cmr_value" => $row["cmr_value"]
					));
			}
			return $cmr_delete_list;
		}
	}/*--------ENDING CLASS-----*/
?>