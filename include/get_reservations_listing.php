<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';




$email=$_POST['email'];
$dateStart=isset( $_POST['timeStampStart'] ) ? $_POST['timeStampStart'] : NULL;
$frameNumber=isset( $_POST['frameNumber'] ) ? $_POST['frameNumber'] : NULL;

$response=array();

if($email != NULL)
{

    if($dateStart==NULL){
        $dateStart=mktime(0, 0, 0, 1, 1, date("Y"));
    }
	$timestamp_now=time();
	
    include 'connexion.php';
	$sql="SELECT * FROM customer_bikes cc, reservations dd where cc.COMPANY=(select COMPANY from customer_referential aa, customer_bikes bb where aa.EMAIL='$email' and aa.FRAME_NUMBER=bb.FRAME_NUMBER GROUP BY COMPANY) AND cc.FRAME_NUMBER=dd.FRAME_NUMBER and dd.STAANN!='D' and dd.DATE_START>'$dateStart'";
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	
    $result = mysqli_query($conn, $sql);        
    $length = $result->num_rows;
	$response['bookingNumber']=$length;


    
    $i=0;
    while($row = mysqli_fetch_array($result))

    {

        $response['response']="success";
		$response['booking'][$i]['frameNumber']=$row['FRAME_NUMBER'];
		$response['booking'][$i]['dateStart']=date('d/m/Y H:i', $row['DATE_START']);            
		$response['booking'][$i]['dateEnd']=date('d/m/Y H:i',$row['DATE_END']);            
		$response['booking'][$i]['user']=$row['EMAIL'];
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