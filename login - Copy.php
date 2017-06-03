
<?php 
require "index.php";
$emailAddress = "";
$user_email = mysqli_real_escape_string($con, $_POST["ls_emailAddress"]);
$user_password = mysqli_real_escape_string($con, $_POST["ls_password"]);
$query = "SELECT ls_staffID, ls_firstName, ls_emailAddress FROM `laboratorystaff` WHERE ls_emailAddress = '$user_email' AND ls_password = '$user_password';";
$result = mysqli_query($con, $query);

	if($result === false){
		//something went wrong. It must be handled here
		echo "Oops! Something went wrong. Try again.";
	}
	else if(mysqli_num_rows($result)>0){
		$row = mysqli_fetch_assoc($result);
		$ls_staffID = $row["ls_staffID"];
		$firstName = $row["ls_firstName"];
		$emailAddress = $row["ls_emailAddress"];
		# echo "success";
		echo "Login success: ".$ls_staffID." ";
		} else 
		{
			echo "Wrong password and/or email address. Try again.";
		}
	
?>

