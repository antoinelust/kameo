<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';

$bikeID=isset($_POST['bikeID']) ? $_POST['bikeID']: NULL;
$response=array();

if($bikeID != NULL)
{

    include 'connexion.php';
	$sql="select * from factures_details where BIKE_ID = '$bikeID'";

    
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
    }
	$result = mysqli_query($conn, $sql); 
    $conn->close();

    $length = $result->num_rows;
    $response['response']="success";
	$response['billNumber']=$length;
    
    
    $i=0;
    while($row = mysqli_fetch_array($result))
    {
        $factureID=$row['FACTURE_ID'];
		$response['bill'][$i]['FACTURE_ID']=$factureID;
		$response['bill'][$i]['amountHTVA']=$row['AMOUNT_HTVA'];
        $response['bill'][$i]['sent']='Y';
        $response['bill'][$i]['paid']='Y';
        
        
        include 'connexion.php';
        $sql2="SELECT * FROM factures WHERE ID='$factureID'";
        if ($conn->query($sql2) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result2 = mysqli_query($conn, $sql2); 
        $resultat2=mysqli_fetch_assoc($result2);
        $conn->close();
        $response['bill'][$i]['date']=$resultat2['DATE'];
        $response['bill'][$i]['fileName']=$resultat2['FILE_NAME'];
        $response['bill'][$i]['sent']=$resultat2['FACTURE_SENT'];
        $response['bill'][$i]['paid']=$resultat2['FACTURE_PAID'];
        
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