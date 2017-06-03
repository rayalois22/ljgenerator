<?php 
class login_info{
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
	public function StoreLoginInfo($ls_staffID, $ls_firstName,
	$ls_lastName, $ls_emailAddress, $ls_laboratoryName,
	$ls_role, $ls_city, $password){
		
		$hash = $this->hashFunction($password);
		$ls_password = $hash["encrypted"]; //encrypted password
		$ls_managerID = $hash["ls_managerID"]; //salt
		
		$statement = $this->connection->prepare("INSERT INTO laboratorystaff(ls_staffID, ls_firstName, ls_lastName, ls_emailAddress, ls_laboratoryName, ls_role, ls_city, ls_password, ls_managerID) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$statement -> bind_param("sssssssss", $ls_staffID, $ls_firstName, $ls_lastName, $ls_emailAddress, $ls_laboratoryName, $ls_role, $ls_city, $ls_password, $ls_managerID);
		$result = $statement->execute();
		$statement->close(); 
		
		#checking for successful storage
		if($result){
			$statement = $this->connection->prepare("SELECT ls_staffID, ls_firstName, ls_lastName, ls_emailAddress, ls_laboratoryName, ls_role, ls_city, ls_password, ls_managerID FROM laboratorystaff WHERE ls_emailAddress = ?");
		
			$statement->bind_param("s", $ls_emailAddress);
			$statement->execute();
			$statement->bind_result($token1, $token2, $token3, $token4, $token5, $token6, $token7, $token8, $token9);
			
				while ($statement->fetch()){
					$staff["ls_staffID"] = $token1;
					$staff["ls_firstName"] = $token2;
					$staff["ls_lastName"] = $token3;
					$staff["ls_emailAddress"] = $token4;
				}
				$statement->close();
				return $staff;
		} else {
			return false;
		}
	} ####ending StoreLoginInfo()	
	public function hashFunction($password){
		$ls_managerID = sha1(rand());
		$ls_managerID = substr($ls_managerID, 0, 10);
		$encrypted = base64_encode(sha1($password.$ls_managerID, true).$ls_managerID);
		$hash = array("ls_managerID" => $ls_managerID, "encrypted" => $encrypted);
		return $hash;
	} ####ending hashFunction()	
	// verifying login authentication
	public function VerifyLoginInfo($ls_emailAddress, $password){
		$statement = $this->connection->prepare("SELECT ls_staffID, ls_firstName, ls_lastName, ls_emailAddress, ls_laboratoryName, ls_role, ls_city, ls_password, ls_managerID FROM laboratorystaff WHERE ls_emailAddress = ?");
		
		$statement ->bind_param("s", $ls_emailAddress);
			
		if ($statement->execute()){
			$statement->bind_result($token1, $token2, $token3, $token4, $token5, $token6, $token7, $token8, $token9);
			while ($statement->fetch()){
				$staff["ls_staffID"] = $token1;
				$staff["ls_firstName"] = $token2;
				$staff["ls_lastName"] = $token3;
				$staff["ls_emailAddress"] = $token4;
				$staff["ls_laboratoryName"] = $token5;
				$staff["ls_role"] = $token6;
				$staff["ls_city"] = $token7;
				$staff["ls_password"] = $token8;
				$staff["ls_managerID"] = $token9;
			}
			$statement->close();
			
			# verifying staff password
			$ls_managerID = $token9;
			$ls_password = $token8;
			$hash = $this->checkHashFunction($ls_managerID, $password);
			#check for password equality
			if ($ls_password == $hash){
				# valid staff credentials found and authentication is successful
				return $staff;
			}
		} else {
			return NULL;
		}
	} ####ending VerifyLoginInfo()
	public function checkHashFunction($ls_managerID, $password){
		$hash = base64_encode(sha1($password.$ls_managerID, true).$ls_managerID);
		return $hash;
	} ###ending checkHashFunction()	
	public function CheckExistingStaff($ls_emailAddress){
		$statement = $this->connection->prepare("SELECT ls_emailAddress FROM laboratorystaff WHERE ls_emailAddress = ?");
		$statement->bind_param("s", $ls_emailAddress);
		$statement->execute();
		$statement->store_result();
		if($statement->num_rows > 0){
			# staff already exists.
			$statement->close();
			return true;
		} else {
			# staff does not exist
			$statement->close();
			return false;
		}
	} ####ending CheckExistingStaff()	
	public function StoreAnalyte($an_name, $an_units, $ls_staffID, $ls_emailAddress){
		$an_lowControl = "";
		$an_normalControl = "";
		$an_highControl = "";
		$statement = $this->connection->prepare("INSERT INTO analyte(an_name, an_units, an_lowControl, an_normalControl, an_highControl, LaboratoryStaff_ls_staffID, LaboratoryStaff_ls_emailAddress) VALUES(?, ?, ?, ?, ?, ?, ?)");
		$statement->bind_param("sssssss", $an_name, $an_units, $an_lowControl, $an_normalControl, $an_highControl, $ls_staffID, $ls_emailAddress);
		$result = $statement->execute();
		$statement->close();
		# checking for successful storage
		if($result){
			$statement = $this->connection->prepare("SELECT an_name, an_units, ls_staffID, ls_emailAddress WHERE an_name = ?");
			$statement->bind_param("s", $an_name);
			$statement->execute();
			$statement->bind_result($token1, $token2, $token3, $token4);
			while($statement->fetch()){
				$analyte["an_name"] = $token1;
				$analyte["an_units"] = $token2;
				$analyte["ls_staffID"] = $token3;
				$analyte["ls_emailAddress"] = $token4;
			}
			$statement->close();
			return $analyte;
		} else {
			return false;
		}
	} #####ending StoreAnalyte()	
	public function CheckExistingAnalyte($an_name, $ls_staffID){
		$statement = $this->connection->prepare("SELECT an_name FROM analyte WHERE an_name = ? AND LaboratoryStaff_ls_staffID = ?");
		$statement->bind_param("ss", $an_name, $ls_staffID);
		$statement->execute();
		$statement->store_result();
		if($statement->num_rows > 0){
			# analyte already exists
			$statement->close();
			return true;
		} else {
			# analyte does not exist yet
			$statement->close();
			return false;
		}
	} ###ending CheckExistingAnalyte()	
	public function StoreControlMaterial($cm_name, $cm_units, $cm_level, $cm_lotNumber, $cm_mean, $cm_sd, $LaboratoryStaff_ls_staffID, $LaboratoryStaff_ls_emailAddress, $Analyte_an_name, $Analyte_LaboratoryStaff_ls_staffID, $Analyte_LaboratoryStaff_ls_emailAddress){
		$cm_id = "";
		$plus3SD = "";
		$plus2SD = "";
		$plus1SD = "";
		$minus1SD = "";
		$minus2SD = "";
		$minus3SD = "";
		$cm_status = "";
		$cm_chart = "";
		$statement = $this->connection->prepare("INSERT INTO controlmaterial(cm_id, cm_name, cm_units, cm_level, cm_lotNumber, cm_mean, cm_sd, plus3SD, plus2SD, plus1SD, minus1SD, minus2SD, minus3SD, cm_status, cm_chart, LaboratoryStaff_ls_staffID, LaboratoryStaff_ls_emailAddress, Analyte_an_name, Analyte_LaboratoryStaff_ls_staffID, Analyte_LaboratoryStaff_ls_emailAddress) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$statement->bind_param("ssssssssssssssssssss", $cm_id, $cm_name, $cm_units, $cm_level, $cm_lotNumber, $cm_mean, $cm_sd, $plus3SD, $plus2SD, $plus1SD, $minus1SD, $minus2SD, $minus3SD, $cm_status, $cm_chart, $LaboratoryStaff_ls_staffID, $LaboratoryStaff_ls_emailAddress, $Analyte_an_name, $Analyte_LaboratoryStaff_ls_staffID, $Analyte_LaboratoryStaff_ls_emailAddress);
		$result = $statement->execute();
		$statement->close();
		# checking for successful storage
		if($result){
			$statement = $this->connection->prepare("SELECT cm_id, cm_name, cm_units, cm_level, cm_lotNumber, LaboratoryStaff_ls_staffID, LaboratoryStaff_ls_emailAddress, Analyte_an_name FROM controlmaterial WHERE cm_name = ? AND cm_level = ? AND LaboratoryStaff_ls_staffID = ?");
			$statement->bind_param("sss", $cm_name, $cm_level, $LaboratoryStaff_ls_staffID);
			$statement->execute();
			$statement->bind_result($token1, $token2, $token3, $token4, $token5, $token6, $token7, $token8);
			while($statement->fetch()){
				$controlmaterial["cm_id"] = $token1;
				$controlmaterial["cm_name"] = $token2;
				$controlmaterial["cm_units"] = $token3;
				$controlmaterial["cm_level"] = $token4;
				$controlmaterial["cm_lotNumber"] = $token5;
				$controlmaterial["LaboratoryStaff_ls_staffID"] = $token6;
				$controlmaterial["LaboratoryStaff_ls_emailAddress"] = $token7;
				$controlmaterial["Analyte_an_name"] = $token8;
			}
			$statement->close();
			return $controlmaterial;
		} else {
			return false;
		}
	} ####ending StoreControlMaterial()
	public function CheckExistingControlMaterial($cm_name, $cm_level, $LaboratoryStaff_ls_staffID, $Analyte_an_name){
		$statement = $this->connection->prepare("SELECT cm_name FROM controlmaterial WHERE cm_name = ? AND cm_level = ? AND LaboratoryStaff_ls_staffID = ? AND Analyte_an_name = ?");
		$statement->bind_param("ssss", $cm_name, $cm_level, $LaboratoryStaff_ls_staffID, $Analyte_an_name);
		$statement->execute();
		$statement->store_result();
		if($statement->num_rows > 0){
			# controlmaterial already exists
			$statement->close();
			return true;
		} else {
			# controlmaterial does not exist yet
			$statement->close();
			return false;
		}
	} ####ending CheckExistingControlMaterial()	
	public function ObtainUnits($an_name){
		$statement = $this->connection->prepare("SELECT an_units FROM analyte WHERE an_name = ?");
		$statement->bind_param("s", $an_name);
		$statement->execute();
		$statement->bind_result($token1);
		while($statement->fetch()){
			$an_units = $token1;
		}
		$statement->close();
		return $an_units;
	} #### ending ObtainUnits()	
	// This function assigns every newly created control material to every newly created 
	public function AsignControlLevel($cm_name, $cm_level, $an_name){
		if($cm_level == "Normal"){
			$statement = $this->connection->prepare("UPDATE analyte SET an_normalControl = ? WHERE an_name = ?");
			$statement->bind_param("ss", $cm_name, $an_name);
			$result = $statement->execute();
			$statement->close();
			if ($result){
				# normal control material assigned to the analyte
				$statement = $this->connection->prepare("SELECT an_name, an_units, an_normalControl FROM analyte WHERE an_normalControl = ?");
				$statement->bind_param("s", $cm_name);
				$statement->execute();
				$statement->bind_result($token1, $token2, $token3);
				while($statement->fetch()){
					$analyte["an_name"] = $token1;
					$analyte["an_units"] = $token2;
					$analyte["an_normalControl"] = $token3;
				}
				$statement->close();
				return $analyte;
			} else {
				return false;
			}
		} else if($cm_level == "High"){
			$statement = $this->connection->prepare("UPDATE analyte SET an_highControl = ? WHERE an_name = ?");
			$statement->bind_param("ss", $cm_name, $an_name);
			$result = $statement->execute();
			$statement->close();
			if($result){
				# high control material assigned to the analyte
				$statement = $this->connection->prepare("SELECT an_name, an_units, an_highControl FROM analyte WHERE an_highControl = ?");
				$statement->bind_param("s", $cm_name);
				$statement->execute();
				$statement->bind_result($token1, $token2, $token3);
				while($statement->fetch()){
					$analyte["an_name"] = $token1;
					$analyte["an_units"] = $token2;
					$analyte["an_highControl"] = $token3;
				}
				$statement->close();
				return $analyte;
			} else {
				return false;
			}
		} else if($cm_level == "Low"){
			$statement = $this->connection->prepare("UPDATE analyte SET an_lowControl = ? WHERE an_name = ?");
			$statement->bind_param("ss", $cm_name, $an_name);
			$result = $statement->execute();
			$statement->close();
			if($result){
				# low control material assigned to the analyte
				$statement = $this->connection->prepare("SELECT an_name, an_units, an_lowControl FROM analyte WHERE an_lowControl = ?");
				$statement->bind_param("s", $cm_name);
				$statement->execute();
				$statement->bind_result($token1, $token2, $token3);
				while($statement->fetch()){
					$analyte["an_name"] = $token1;
					$analyte["an_units"] = $token2;
					$analyte["an_lowControl"] = $token3;
				}
				$statement->close();
				return $analyte;
			} else {
				return false;
			}
		}
	} #######ending AssignControlLevel()
	public function StoreControlMaterialResult($cmr_value, $cmr_instrument, $cmr_assayMethod, $cmr_temperature, $ls_staffID, $ls_emailAddress, $ControlMaterial_cm_id, $ControlMaterial_Analyte_an_name){
		$cmr_id = "";
		$cmr_time = date("Y.m.l.d.h.i.s"); 
		$ControlMaterial_LaboratoryStaff_ls_staffID = $LaboratoryStaff_ls_staffID = $ControlMaterial_LaboratoryStaff_ls_staffID = $ControlMaterial_Analyte_LaboratoryStaff_ls_staffID = $ls_staffID;
		$ControlMaterial_LaboratoryStaff_ls_emailAddress = $LaboratoryStaff_ls_emailAddress = $ControlMaterial_Analyte_LaboratoryStaff_ls_emailAddress = $ls_emailAddress;
		$statement = $this->connection->prepare("INSERT INTO controlmaterialresult(cmr_id, cmr_time, cmr_value, cmr_instrument, cmr_assayMethod, cmr_temperature, LaboratoryStaff_ls_staffID, LaboratoryStaff_ls_emailAddress, ControlMaterial_cm_id, ControlMaterial_LaboratoryStaff_ls_staffID, ControlMaterial_LaboratoryStaff_ls_emailAddress, ControlMaterial_Analyte_an_name, ControlMaterial_Analyte_LaboratoryStaff_ls_staffID, ControlMaterial_Analyte_LaboratoryStaff_ls_emailAddress) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$statement->bind_param("ssdssdssssssss", $cmr_id, $cmr_time, $cmr_value, $cmr_instrument, $cmr_assayMethod, $cmr_temperature, $LaboratoryStaff_ls_staffID, $LaboratoryStaff_ls_emailAddress, $ControlMaterial_cm_id, $ControlMaterial_LaboratoryStaff_ls_staffID, $ControlMaterial_LaboratoryStaff_ls_emailAddress, $ControlMaterial_Analyte_an_name, $ControlMaterial_Analyte_LaboratoryStaff_ls_staffID, $ControlMaterial_Analyte_LaboratoryStaff_ls_emailAddress);
		$result = $statement->execute();
		$statement->close();
		# Checking for successful storage
		if($result){
			$statement = $this->connection->prepare("SELECT cmr_id, cmr_time, cmr_value, $ControlMaterial_cm_id, ControlMaterial_Analyte_an_name FROM controlmaterialresult WHERE cmr_time = ? AND cmr_value = ? AND LaboratoryStaff_ls_staffID = ?");
			$statement->bind_param("sss", $cmr_time, $cmr_value, $ls_staffID);
			$statement->execute();
			$statement->bind_result($token1, $token2, $token3, $token4, $token5);
			while($statement->fetch()){
				$controlmaterialresult["cmr_id"] = $token1;
				$controlmaterialresult["cmr_time"] = $token2;
				$controlmaterialresult["cmr_value"] = $token3;
				$controlmaterialresult["ControlMaterial_cm_id"] = $token4;
				$controlmaterialresult["ControlMaterial_Analyte_an_name"] = $token5;
			}
			$statement->close();
			return $controlmaterialresult;
		} else {
			return false;
		}	
	} ###ending StoreControlMaterialResult()
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
	} ## ending ObtainCMID()
	public function ObtainAnalyteName($cm_name, $LaboratoryStaff_ls_staffID){
		$statement = $this->connection->prepare("SELECT Analyte_an_name FROM controlmaterial WHERE cm_name = ? AND LaboratoryStaff_ls_staffID = ?");
		$statement->bind_param("ss", $cm_name, $LaboratoryStaff_ls_staffID);
		$statement->execute();
		$statement->bind_result($token1);
		while($statement->fetch()){
			$Analyte_an_name = $token1;
		}
		$statement->close();
		return $Analyte_an_name;
	} ## ending ObtainAnalyteName()
	public function ObtainCM_CMR($cm_id, $ls_staffID){
		$statement = $this->connection->prepare("SELECT cmr_id, cmr_value FROM controlmaterialresult WHERE ControlMaterial_cm_id = ? AND LaboratoryStaff_ls_staffID = ?");
		$statement->bind_param("ss", $cm_id, $ls_staffID);
		$result = $statement->execute();
		$res = array("error" => FALSE);
		while($row = mysqli_fetch_assoc($result)){
			array_push($res, array(
				"cmr_id" => $row[0],
				"cmr_value" => $row[1]
			));
		}
		return $res;		
	}
} ##ending class	
?>