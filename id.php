<?php
	$mysql_server = "localhost";
	$mysql_user = "root";
	$mysql_pass = "root";
	$mysql_db = "ljgenerator";
	# receive the email address from the android application
	$email = $_POST["ls_emailAddress"];
	# query variable
	$sql = "select ls_staffID from laboratorystaff where ls_emailAddress = '$email';";
	# connect to the database.
	$con = mysqli_connect($mysql_server, $mysql_user, $mysql_pass, $mysql_db);
	# capture the results of the query in a result variable	
	$result = mysqli_query($con, $sql);
	# initialize an array to store the results
	$response = array();
	while($row = mysqli_fetch_array($result)){
		array_push($response,
			array(
				"id" =>$row[0]
			)
		);
	}
	# encode the data in json format and send it back to the client.
	echo json_encode(array("server_response"=>$response));
	# close the connection to the database
	mysqli_close($con);
?>