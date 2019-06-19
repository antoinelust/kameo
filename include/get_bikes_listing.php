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
	$sql="SELECT * FROM customer_bikes cc, bike_models dd where COMPANY=(select COMPANY from customer_referential where EMAIL='$email') AND cc.TYPE=dd.ID";
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

        $response['response']="success";
		$response['bike'][$i]['frameNumber']=$row['FRAME_NUMBER'];
		$response['bike'][$i]['model']=$row['MODEL'];            
		$response['bike'][$i]['contractReference']=$row['CONTRACT_REFERENCE'];
        if($row['LEASING']=="Y"){
            $response['bike'][$i]['contractType']="leasing";
            $response['bike'][$i]['contractDates']=$row['CONTRACT_START'].'-'.$row['CONTRACT_END'];
        }else{
            $response['bike'][$i]['contractType']="other";
            $response['bike'][$i]['contractDates']="N/A";
        }
        $response['bike'][$i]['status']=$row['STATUS'];
                
        $i++;

	}
    
    include 'connexion.php';
    $timestamp=mktime(0, 0, 0, 1, 1, date("Y"));
    $sql2="SELECT count(1) FROM customer_bikes cc, reservations dd where COMPANY=(select COMPANY from customer_referential where EMAIL='$email') and cc.FRAME_NUMBER=dd.FRAME_NUMBER and dd.STAANN!='D' and dd.DATE_START>'$timestamp'";
    if ($conn->query($sql2) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
    $result2 = mysqli_query($conn, $sql2);        
    $resultat2 = mysqli_fetch_assoc($result2);

    $response['numberOfBookings']=$resultat2['count(1)'];

	echo json_encode($response);
    die;

}
else
{
	errorMessage("ES0006");
}

?>