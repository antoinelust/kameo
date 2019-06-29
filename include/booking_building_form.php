<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');


if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

include 'globalfunctions.php';

$email=$_POST['email'];

if( $email!=NULL ) {
    include 'connexion.php';
    $sql= "select * from customer_building_access where EMAIL = '$email' and STAANN != 'D'";
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
    $result = mysqli_query($conn, $sql);     
    $length = $result->num_rows;
    $conn->close();   

    if($length == "0")
    {
        //message d'erreur à créer
        errorMessage("ES0026");
    }
    else{
        $i=0;
        $response['response']="success";
        $response['buildingNumber']=$length;
        while($row = mysqli_fetch_array($result)){
            $i++;
            $response['building'][$i]['building_code']=$row['BUILDING_CODE'];
            $buildingReference=$row['BUILDING_CODE'];

            include 'connexion.php';
            $sql2= "select * from building_access where BUILDING_REFERENCE = '$buildingReference'";
            if ($conn->query($sql2) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result2 = mysqli_query($conn, $sql2);   
            $resultat = mysqli_fetch_assoc($result2);
            $conn->close();
            $response['building'][$i]['fr']=$resultat['BUILDING_FR'];
            $response['building'][$i]['en']=$resultat['BUILDING_EN'];
            $response['building'][$i]['nl']=$resultat['BUILDING_NL'];
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