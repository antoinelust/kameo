<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';




$email=$_POST['email'];
$response=array();

if($email != NULL)
{

	$timestamp_now=time();
	
    include 'connexion.php';
	$sql="SELECT * FROM customer_referential dd where COMPANY=(select COMPANY from customer_referential where EMAIL='$email') ORDER BY NOM";
    
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	
    $result = mysqli_query($conn, $sql);        
    $length = $result->num_rows;
	$response['usersNumber']=$length;


    
    $i=0;
    while($row = mysqli_fetch_array($result))

    {

        $response['response']="success";
		$response['user'][$i]['name']=$row['NOM'];
		$response['user'][$i]['firstName']=$row['PRENOM'];            
		$response['user'][$i]['email']=$row['EMAIL'];  
        $response['user'][$i]['staann']=$row['STAANN'];
        $i++;

	}
	echo json_encode($response);
    die;

}
else
{
	errorMessage("ES0006");
}

?>