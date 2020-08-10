<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';


$response=array();

$action=isset($_GET['action']) ? $_GET['action'] : NULL;

if($action=="graphic"){
    $numberOfDays=isset($_GET['numberOfDays']) ? $_GET['numberOfDays'] : NULL;
    $dateStartInput=isset($_GET['dateStart']) ? $_GET['dateStart'] : NULL;
    $dateEndInput=isset($_GET['dateEnd']) ? $_GET['dateEnd'] : NULL;
    
    
    $intervalStop="P".$numberOfDays."D";
    
    $date_start = new DateTime($dateStartInput); 
    $date_start_string=$date_start->format('Y-m-d');
    
    $date_end= new DateTime($dateEndInput);
    
    
    $date_now=new DateTime("NOW");
    $companiesContact=array();
    $companiesOffer=array();
    $companiesOfferSigned=array();
    $companiesNotInterested=array();
    $dates=array();
    while($date_start<=$date_end){
        
        $date_start_string=$date_start->format('Y-m-d');
        
        include 'connexion.php';
        $sql="SELECT COUNT(1) AS 'SUM' FROM company_actions aa WHERE DATE<='$date_start_string' AND TYPE = 'contact' AND NOT EXISTS (select 1 from company_actions bb where aa.COMPANY=bb.COMPANY AND( bb.TYPE='offre' OR bb.TYPE='offreSigned' OR bb.TYPE='delivery')) ";
        
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);    
        $resultat = mysqli_fetch_assoc($result);        
        $conn->close();
        array_push($companiesContact, $resultat['SUM']); 
        
        include 'connexion.php';
        $sql="SELECT COUNT(1) AS 'SUM' FROM company_actions aa WHERE DATE<='$date_start_string' AND TYPE = 'offre' AND NOT EXISTS (select 1 from company_actions bb where aa.COMPANY=bb.COMPANY AND( bb.TYPE='offreSigned' OR bb.TYPE='delivery')) ";
        
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);    
        $resultat = mysqli_fetch_assoc($result);        
        $conn->close();
        array_push($companiesOffer, $resultat['SUM']); 
        
        include 'connexion.php';
        $sql="SELECT COUNT(1) AS 'SUM' FROM company_actions aa, companies bb WHERE bb.INTERNAL_REFERENCE=aa.COMPANY AND DATE<='$date_start_string' AND aa.TYPE = 'offreSigned' AND NOT EXISTS (select 1 from company_actions bb where aa.COMPANY=bb.COMPANY AND bb.TYPE='delivery') ";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);    
        $resultat = mysqli_fetch_assoc($result);
        $conn->close();
        array_push($companiesOfferSigned, $resultat['SUM']); 
        
        include 'connexion.php';
        $sql="SELECT COUNT(1) AS 'SUM' FROM companies aa WHERE HEU_MAJ<='$date_start_string' AND TYPE = 'NOT INTERESTED'";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);    
        $resultat = mysqli_fetch_assoc($result);
        $conn->close();
        array_push($companiesNotInterested, $resultat['SUM']); 
        
        
        array_push($dates, $date_start_string);
        $date_start->add(new DateInterval('P10D'));
    }
    
    $response['response']="success";
    $response['dates']=$dates;
    $response['companiesContact']=$companiesContact;
    $response['companiesOffer']=$companiesOffer;
    $response['companiesOfferSigned']=$companiesOfferSigned;
    $response['companiesNotInterested']=$companiesNotInterested;
    $response['sql']=$sql;
    echo json_encode($response);
    die;
    
}else{
    include 'connexion.php';
	$response = array();
    $sql="SELECT COUNT(*) as count FROM companies WHERE TYPE='PROSPECT' OR TYPE='CLIENT'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows>0)
		$response['companiesNumberClientOrProspect'] = $result->fetch_array(MYSQLI_ASSOC)['count'];
	
    $sql="SELECT c.ID, COMPANY_NAME AS companyName, INTERNAL_REFERENCE AS internalReference, c.TYPE AS type,
(SELECT COUNT(*) FROM customer_bikes WHERE c.INTERNAL_REFERENCE = COMPANY) AS companyBikeNumber,
(SELECT CASE WHEN COUNT(*) > 0 THEN 'OK' ELSE 'KO' END FROM customer_bike_access cba WHERE BIKE_ID IN (SELECT ID FROM customer_bikes cb WHERE cb.COMPANY=c.INTERNAL_REFERENCE) AND STAANN!='D') AS bikeAccessStatus,
(SELECT CASE WHEN COUNT(*) > 0 THEN 'OK' ELSE 'KO' END FROM customer_building_access WHERE EMAIL IN (SELECT EMAIL FROM customer_referential WHERE COMPANY=c.INTERNAL_REFERENCE) AND BUILDING_CODE IN (SELECT BUILDING_REFERENCE FROM building_access WHERE COMPANY=c.INTERNAL_REFERENCE)) AS customerBuildingAccess,
(SELECT CASE WHEN MAX(ca1.HEU_MAJ) > c1.HEU_MAJ THEN MAX(ca1.HEU_MAJ) ELSE c1.HEU_MAJ END FROM company_actions ca1, companies c1 WHERE ca1.COMPANY='KAMEO') as HEU_MAJ
FROM companies c WHERE 1";

    $company=isset($_GET['company']) ? $conn->real_escape_string($_GET['company']) : "*";
    $type=isset($_GET['type']) ? $conn->real_escape_string($_GET['type']) : "*";
    $filter=isset($_GET['filter']) ? $conn->real_escape_string($_GET['filter']) : NULL;
	
    if($type!="*")
        $sql=$sql." AND TYPE='$type'";
    
    $sql=$sql." ORDER BY INTERNAL_REFERENCE";
    
    //$response['sql']=$sql;
	$result = $conn->query($sql);
	if ($result && $result->num_rows>0)
	{
		$response['company'] = $result->fetch_all(MYSQLI_ASSOC);
        $response['companiesNumber'] = $result->num_rows;
	}
    $response['response']="success";

    echo json_encode($response);
    die;
}

?>