<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';




$frameNumber=$_POST['frameNumber'];

$response=array();

if($frameNumber != NULL)
{

	
    include 'connexion.php';
	$sql="SELECT *  FROM customer_bikes WHERE FRAME_NUMBER = '$frameNumber'";
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	
    $result = mysqli_query($conn, $sql);        
    $row = mysqli_fetch_assoc($result);



    $response['response']="success";
    $response['model']=$row['MODEL'];
    $response['contractReference']=$row['CONTRACT_REFERENCE'];            
    $response['frameReference']=$row['FRAME_REFERENCE'];            
    if($row['LEASING']=="Y"){
        $response['contractType']="leasing";
        $response['contractStart']=$row['CONTRACT_START'];
        $response['contractEnd']=$row['CONTRACT_END'];
    }else{
        $response['contractType']="other";
        $response['contractStart']="N/A";
        $response['contractEnd']="N/A";
    }
    
	echo json_encode($response);
    die;

}
else
{
	errorMessage("ES0006");
}

?>