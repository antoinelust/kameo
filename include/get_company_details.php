<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';




$company=$_POST['company'];
$response=array();

if($company != NULL)
{
	
    include 'connexion.php';
	$sql="SELECT * FROM companies dd where INTERNAL_REFERENCE='$company'";
    
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	
    $result = mysqli_query($conn, $sql);        
    $resultat = mysqli_fetch_assoc($result);
    $conn->close();   


    $response['response']="success";
    $response['companyName']=$resultat['COMPANY_NAME'];
    $response['companyStreet']=$resultat['STREET'];            
    $response['companyZIPCode']=$resultat['ZIP_CODE'];  
    $response['companyTown']=$resultat['TOWN'];
    $response['companyVAT']=$resultat['VAT_NUMBER'];
    $response['emailContact']=$resultat['EMAIL_CONTACT'];
    $response['firstNameContact']=$resultat['PRENOM_CONTACT'];
    $response['lastNameContact']=$resultat['NOM_CONTACT'];

    include 'connexion.php';
	$sql="SELECT * FROM customer_bikes dd where COMPANY='$company'";
    
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	
    $result = mysqli_query($conn, $sql);    
    $i=0;
    while($row = mysqli_fetch_array($result)){
        $response['bike'][$i]['frameNumber']=$row['FRAME_NUMBER'];
        $frameNumber=$row['FRAME_NUMBER'];
        $response['bike'][$i]['model']=$row['MODEL'];
        $response['bike'][$i]['facturation']=$row['LEASING'];
        $response['bike'][$i]['leasingPrice']=$row['LEASING_PRICE']; 
        
        $sql2="SELECT * FROM bike_building_access dd where BIKE_NUMBER='$frameNumber'";

        if ($conn->query($sql2) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }

        $result2 = mysqli_query($conn, $sql2);  
        $j=0;
        while($row2 = mysqli_fetch_array($result2)){
            $response['bike'][$i]['building'][$j]['buildingCode']=$row2['BUILDING_CODE'];
            $j++;
        }
        $response['bike'][$i]['buildingNumber']=$j;        
        
        $i++;
    }
    $response['bikeNumber']=$i;
    
	$sql="SELECT * FROM building_access dd where COMPANY='$company'";
    
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	
    $result = mysqli_query($conn, $sql);    
    $i=0;
    while($row = mysqli_fetch_array($result)){
        $response['building'][$i]['buildingReference']=$row['BUILDING_REFERENCE'];
        $response['building'][$i]['buildingFR']=$row['BUILDING_FR'];
        $response['building'][$i]['buildingNL']=$row['BUILDING_NL'];
        $response['building'][$i]['buildingEN']=$row['BUILDING_EN'];
        $response['building'][$i]['address']=$row['ADDRESS'];
        $i++;
        
    }
    $response['buildingNumber']=$i;

    
	echo json_encode($response);
    die;    
    
    
    
    

}
else
{
	errorMessage("ES0006");
}

?>