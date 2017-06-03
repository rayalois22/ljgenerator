<?php 
	class login_connect {
		private $connection;
		
		#connecting to the database
		public function connect(){
			require_once 'login_config.php';
			#connecting to mysql database
			$this->connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
			#return database object
			return $this->connection;
		}
	}
?>