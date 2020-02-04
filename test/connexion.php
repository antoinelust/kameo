<?php
				$servername = "kameobiknqdataba.mysql.db";
				$username = "kameobiknqdataba";
				$password = "2sZzk32Y";
				$dbname = "kameobiknqdataba";
				// Create connection
				$conn = new mysqli($servername, $username, $password, $dbname);
				// Check connection

				if ($conn->connect_error) {
					$response = array ('response'=>'error', 'message'=> $conn->connect_error);
					echo json_encode($response);
					die;
				} 
?>