<?php
		
if(substr($_SERVER['HTTP_HOST'], 0, 9)=="localhost"){
    $servername = "localhost:3307";
    $username = "root";
    $password = "";
    $dbname = "kameobiknq";
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    mysqli_set_charset($conn, "utf8");

    // Check connection

    if ($conn->connect_error) {
        $response = array ('response'=>'error', 'message'=> $conn->connect_error);
        echo json_encode($response);
        die;
    } 
    
}else{
    
    $servername = "kameobiknqdataba.mysql.db";
    $username = "kameobiknqdataba";
    $password = "2sZzk32Y";
    $dbname = "kameobiknqdataba";
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    mysqli_set_charset($conn, "utf8");

    // Check connection

    if ($conn->connect_error) {
        echo $conn->connect_error;
    } 

}

?>