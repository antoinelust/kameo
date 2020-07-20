<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';

if(isset($_POST["widget-addActionBike-form-action"])){
    $action = isset($_POST["widget-addActionBike-form-action"]) ? $_POST["widget-addActionBike-form-action"] : NULL;
    $bikeID = isset($_POST["bikeID"]) ? $_POST["bikeID"] : NULL;
    $date = isset($_POST["widget-addActionBike-form-date"]) ? date($_POST["widget-addActionBike-form-date"]) : NULL;
    $description = $_POST["widget-addActionBike-form-description"];
    $public = isset($_POST["widget-addActionBike-form-public"]) ? "1" : "0";
    $user = isset($_POST["widget-addActionBike-form-user"]) ? $_POST["widget-addActionBike-form-user"] : NULL;
} else if(isset($_POST["readActionBike-action"])){
    $action = isset($_POST["readActionBike-action"]) ? $_POST["readActionBike-action"] : NULL;
    $bikeID = isset($_POST["bikeID"]) ? $_POST["bikeID"] : NULL;
    $user = isset($_POST["readActionBike-user"]) ? $_POST["readActionBike-user"] : NULL;
}


if($action == "add" && $user != NULL){
     
    include 'connexion.php';
     
	$sql = "INSERT INTO action_log (USR_MAJ, BIKE_ID, DATE, DESCRIPTION, PUBLIC) VALUES ('$user', '$bikeID', '$date', '$description', '$public')";
    
	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	$conn->close();
    successMessage("SM0003");
    
} else if($action == "read" && $user != NULL && $bikeID != NULL){ 
    
    include 'connexion.php';
    $sql = "select * from action_log WHERE BIKE_ID='$bikeID' ORDER BY DATE DESC";
	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
    $result = mysqli_query($conn, $sql);        
    $length = $result->num_rows;
    $response['actionNumber']=$length;
    $i=0;
    $response['response']="success";
    while($row = mysqli_fetch_array($result))
    {
        $response['action'][$i]['date']=$row['DATE'];
        $response['action'][$i]['description']=$row['DESCRIPTION'];
        $response['action'][$i]['public']=$row['PUBLIC'];
        $response['action'][$i]['bikeID']=$row['BIKE_ID'];
        $i++;
    }
                                                               
   $conn->close();
    
    echo json_encode($response);
    die;
    

}
else
{
	errorMessage("ES0012");
}
?>
