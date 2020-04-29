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
    $sql="SELECT * from companies WHERE 1 ";
    $company=isset($_POST['company']) ? $_POST['company'] : "*";
    $type=isset($_POST['type']) ? $_POST['type'] : NULL;    
    $filter=isset($_POST['filter']) ? $_POST['filter'] : NULL;    
    
    
    
    if($type!="*" && $type != NULL){
        $sql=$sql." AND TYPE='$type'";
    }

    $sql=$sql." ORDER BY INTERNAL_REFERENCE";
    
    $response['sql']=$sql;
    

    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);        
    $response['companiesNumber'] = $result->num_rows;
    $i=0;
    $response['response']="success";




    while($row = mysqli_fetch_array($result)){
        $response['company'][$i]['ID']=$row['ID'];
        $response['company'][$i]['companyName']=$row['COMPANY_NAME'];
        $currentCompany=$row['INTERNAL_REFERENCE'];
        $response['company'][$i]['internalReference']=$row['INTERNAL_REFERENCE'];
        $response['company'][$i]['type']=$row['TYPE'];
        $internalReference=$row['INTERNAL_REFERENCE'];
        $HEU_MAJ=$row['HEU_MAJ'];
        
        $sql2="SELECT * FROM customer_bikes WHERE COMPANY='$internalReference'";
        if ($conn->query($sql2) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result2 = mysqli_query($conn, $sql2);        
        $response['company'][$i]['companyBikeNumber'] = $result2->num_rows;
        $bikeAccessStatus="OK";
        $customerBuildingStatus="OK";

        if($response['company'][$i]['companyBikeNumber']==0){
            $bikeAccessStatus="OK";
        }
        while($row2 = mysqli_fetch_array($result2)){
            $bikeReference=$row2['FRAME_NUMBER'];
            $sql3="SELECT * from customer_bike_access where BIKE_NUMBER='$bikeReference' and STAANN!='D'";
            if ($conn->query($sql3) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result3 = mysqli_query($conn, $sql3);     
            if($result3->num_rows=='0'){
                $bikeAccessStatus="KO";
            }
        }

        $sql3="SELECT * from customer_building_access where EMAIL in (select EMAIL from customer_referential where COMPANY='$internalReference') and BUILDING_CODE in (select BUILDING_REFERENCE FROM building_access where COMPANY='$internalReference')";
        if ($conn->query($sql3) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result3 = mysqli_query($conn, $sql3);     
        if($result3->num_rows=='0'){
            $customerBuildingStatus="OK";
        }else{
            $sql4="SELECT * from building_access where COMPANY='$internalReference'";
            if ($conn->query($sql4) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                echo json_encode($response);
                die;
            }
            $result4 = mysqli_query($conn, $sql4);     
            while($row4 = mysqli_fetch_array($result4)){
                $buildingReference=$row4['BUILDING_REFERENCE'];
                $sql5="SELECT * from customer_building_access where BUILDING_CODE='$buildingReference' and STAANN!='D'";
                if ($conn->query($sql5) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
                $result5 = mysqli_query($conn, $sql5);     
                if($result5->num_rows=='0'){
                    $customerBuildingStatus="KO";
                }
            }            
        }
        
        $response['company'][$i]['bikeAccessStatus'] = $bikeAccessStatus;
        $response['company'][$i]['customerBuildingAccess'] = $customerBuildingStatus;
        
        
        $sql6="SELECT MAX(HEU_MAJ) as HEU_MAJ from company_actions where COMPANY='$currentCompany'";
        if ($conn->query($sql6) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
    
        $result6 = mysqli_query($conn, $sql6);     
        $resultat6=mysqli_fetch_array($result6);
        
        if($resultat6['HEU_MAJ'] > $HEU_MAJ){
            $HEU_MAJ=$resultat6['HEU_MAJ'];
        }
        $response['company'][$i]['HEU_MAJ'] = $HEU_MAJ;
        $i++;
    }



    include 'connexion.php';
    $sql="SELECT SUM(LEASING_PRICE) as 'PRICE' FROM customer_bikes WHERE CONTRACT_START<CURRENT_TIMESTAMP AND CONTRACT_END>CURRENT_TIMESTAMP";

    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);        
    $resultat = mysqli_fetch_assoc($result);
    $conn->close();  

    $response['sumContractsCurrent']=$resultat['PRICE'];

    include 'connexion.php';
    $sql="SELECT SUM(AMOUNT) as 'PRICE' FROM boxes WHERE START<CURRENT_TIMESTAMP AND END>CURRENT_TIMESTAMP AND STAANN != 'D' and COMPANY != 'KAMEO' and COMPANY!='KAMEO VELOS TEST'";
    if($company!="*"){
        $sql=$sql." AND COMPANY='$company'";
    }

    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);        
    $resultat = mysqli_fetch_assoc($result);
    $conn->close();  

    $response['sumContractsCurrent']=$response['sumContractsCurrent']+$resultat['PRICE'];

    echo json_encode($response);
    die;
}







?>