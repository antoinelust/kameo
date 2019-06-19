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

	
    include 'connexion.php';
	$sql="SELECT * from building_access where COMPANY = (SELECT COMPANY  FROM customer_referential WHERE EMAIL = '$email')";
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
    $result = mysqli_query($conn, $sql);        
    $response['buildingNumber'] = $result->num_rows;
    $i=0;
    $response['response']="success";
    while($row = mysqli_fetch_array($result)){
        $response['building'][$i]['code']=$row['BUILDING_REFERENCE'];
        $response['building'][$i]['descriptionFR']=$row['BUILDING_FR'];
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