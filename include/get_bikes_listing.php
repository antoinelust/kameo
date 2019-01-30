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
	$sql="SELECT * FROM customer_bikes cc, bike_models dd where COMPANY=(select COMPANY from customer_referential aa, customer_bikes bb where aa.EMAIL='$email' and aa.FRAME_NUMBER=bb.FRAME_NUMBER and bb.COMPANY GROUP BY COMPANY) AND cc.TYPE=dd.ID";
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	$result = mysqli_query($conn, $sql);        
    $length = $result->num_rows;
	$response['bikeNumber']=$length;


    
    $i=0;
    while($row = mysqli_fetch_array($result))

    {

		$response['bike'][$i]['frameNumber']=$row['FRAME_NUMBER'];
		$response['bike'][$i]['modelFR']=$row['MODEL_FR'];            
		$response['bike'][$i]['modelEN']=$row['MODEL_EN'];            
		$response['bike'][$i]['modelNL']=$row['MODEL_NL'];
		$response['bike'][$i]['contractReference']=$row['CONTRACT_REFERENCE'];
                
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