<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');


if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

include 'globalfunctions.php';

$userFrameNumber=$_POST['userFrameNumber'];

if( $userFrameNumber!=NULL ) {
    include 'connexion.php';
    $sql= "select * from building_access where BUILDING_REFERENCE like '$userFrameNumber%'";

   if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}

    $result = mysqli_query($conn, $sql);     
    $length = $result->num_rows;

    if($length == "0")
    {
        //message d'erreur à créer
        errorMessage("ES0015");
    }
    else{
        $i=0;
        $response['buildingNumber']=$length;
        while($row = mysqli_fetch_array($result)){
            $i++;
            $response['building'][$i]['building_code']=$row['BUILDING_CODE'];
            $response['building'][$i]['fr']=$row['BUILDING_FR'];
            $response['building'][$i]['en']=$row['BUILDING_EN'];
            $response['building'][$i]['nl']=$row['BUILDING_NL'];
        }
        echo json_encode($response);
        die;
    }
}
else{
//message d'erreur à créer
    errorMessage("ES0015");
}

?>