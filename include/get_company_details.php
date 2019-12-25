<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';


$company=isset($_POST['company']) ? $_POST['company'] : NULL;
$ID=isset($_POST['ID']) ? $_POST['ID'] : NULL;
$email=isset($_POST['email']) ? $_POST['email'] : NULL;
$response=array();


if($ID==NULL & $email != NULL){
    include 'connexion.php';
	$sql="SELECT * FROM customer_referential dd where EMAIL='$email'";
    
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	
    $result = mysqli_query($conn, $sql);        
    $resultat = mysqli_fetch_assoc($result);
    $conn->close();   
    $company=$resultat['COMPANY'];

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
    $ID=$resultat['ID'];
    
    
}
if($ID != NULL)
{
	
    include 'connexion.php';
	$sql="SELECT * FROM companies dd where ID='$ID'";
    
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	
    $result = mysqli_query($conn, $sql);        
    $resultat = mysqli_fetch_assoc($result);
    $conn->close();   


    $response['response']="success";
    $response['ID']=$resultat['ID'];
    $response['companyName']=$resultat['COMPANY_NAME'];
    $response['companyStreet']=$resultat['STREET'];            
    $response['companyZIPCode']=$resultat['ZIP_CODE'];  
    $response['companyTown']=$resultat['TOWN'];
    $response['companyVAT']=$resultat['VAT_NUMBER'];
    $response['emailContact']=$resultat['EMAIL_CONTACT'];
    $response['type']=$resultat['TYPE'];
    $response['firstNameContact']=$resultat['PRENOM_CONTACT'];
    $response['lastNameContact']=$resultat['NOM_CONTACT'];
    $response['phone']=$resultat['CONTACT_PHONE'];
    $response['emailContactBilling']=$resultat['EMAIL_CONTACT_BILLING'];
    $response['firstNameContactBilling']=$resultat['FIRSTNAME_CONTACT_BILLING'];
    $response['lastNameContactBilling']=$resultat['LASTNAME_CONTACT_BILLING'];
    $response['phoneContactBilling']=$resultat['PHONE_CONTACT_BILLING'];
    $response['automaticBilling']=$resultat['BILLS_SENDING'];
    $response['automaticStatistics']=$resultat['AUTOMATIC_STATISTICS'];
    $response['internalReference']=$resultat['INTERNAL_REFERENCE'];

    if($company==NULL){
        $company=$resultat['INTERNAL_REFERENCE'];
    }
    include 'connexion.php';
	$sql="SELECT * FROM conditions dd where COMPANY='$company'";
    
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	
    $result = mysqli_query($conn, $sql);        
    $resultat = mysqli_fetch_assoc($result);
    $conn->close();   
    
    $response['assistance']=$resultat['ASSISTANCE'];
    $response['locking']=$resultat['LOCKING'];

    include 'connexion.php';
	$sql="SELECT * FROM customer_bikes dd where COMPANY='$company'";
    
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
    $result = mysqli_query($conn, $sql);   
    
    $response['bikeNumber']=$result->num_rows;      
    
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
    $response['buildingNumber']=$result->num_rows;    
    $i=0;
    while($row = mysqli_fetch_array($result)){
        $response['building'][$i]['buildingReference']=$row['BUILDING_REFERENCE'];
        $response['building'][$i]['buildingFR']=$row['BUILDING_FR'];
        $response['building'][$i]['buildingNL']=$row['BUILDING_NL'];
        $response['building'][$i]['buildingEN']=$row['BUILDING_EN'];
        $response['building'][$i]['address']=$row['ADDRESS'];
        $i++;
        
    }

    ///////////////////
    
    include 'connexion.php';
    $sql="SELECT CONTRACT_START, CONTRACT_END, SUM(LEASING_PRICE) as 'PRICE', COUNT(1) as 'BIKE_NUMBER' FROM `customer_bikes` WHERE COMPANY = '$company' AND LEASING='Y' GROUP BY CONTRACT_START, CONTRACT_END";
    
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	
    $result = mysqli_query($conn, $sql);    
    $response['bikeContracts']=$result->num_rows;    
    $i=0;
    while($row = mysqli_fetch_array($result)){
        $response['offer'][$i]['id']="N/A";        
        $response['offer'][$i]['description']=$row['BIKE_NUMBER']." vélos en leasing";
        $response['offer'][$i]['probability']="signé";
        $response['offer'][$i]['type']="N/A";
        $response['offer'][$i]['amount']=$row['PRICE']." €/mois";
        $response['offer'][$i]['margin']="N/A";
        $response['offer'][$i]['start']=$row['CONTRACT_START'];
        $response['offer'][$i]['end']=$row['CONTRACT_END'];
        $i++;
    }
    
    
    ///////////////////
    
    include 'connexion.php';
	$sql="SELECT * FROM offers dd where COMPANY='$company' AND STAANN != 'D'";
    
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	
    $result = mysqli_query($conn, $sql);    
    $response['offerNumber']=$result->num_rows;    
    while($row = mysqli_fetch_array($result)){
        $response['offer'][$i]['id']=$row['ID'];
        $response['offer'][$i]['title']=$row['TITRE'];
        $response['offer'][$i]['description']=$row['DESCRIPTION'];
        $response['offer'][$i]['probability']=$row['PROBABILITY'];
        $response['offer'][$i]['type']=$row['TYPE'];
        $response['offer'][$i]['amount']=$row['AMOUNT'];
        $response['offer'][$i]['margin']=$row['MARGIN'];
        $response['offer'][$i]['date']=$row['DATE'];
        $response['offer'][$i]['start']=$row['START'];
        $response['offer'][$i]['end']=$row['START'];
        $response['offer'][$i]['status']=$row['STATUS'];
        $i++;
    }

    include 'connexion.php';
	$sql="SELECT * FROM customer_referential dd where COMPANY='$company' AND STAANN != 'D'";
    
    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	
    $result = mysqli_query($conn, $sql);    
    $i=0;
    while($row = mysqli_fetch_array($result)){
        $response['user'][$i]['name']=$row['NOM'];
        $response['user'][$i]['firstName']=$row['PRENOM'];
        $response['user'][$i]['email']=$row['EMAIL'];
        $i++;
        
    }
    $response['userNumber']=$i;

    
	echo json_encode($response);
    die;    
    
    
    
    

}
else
{
	errorMessage("ES0006");
}

?>