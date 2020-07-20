<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';

$email=$_POST['email'];

include 'connexion.php';
$sql="SELECT COMPANY  FROM customer_referential WHERE EMAIL = '$email'";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);
if($result->num_rows=='0'){
    errorMessage("ES0039");
}
$resultat = mysqli_fetch_assoc($result);
$company=$resultat['COMPANY'];
$conn->close();

$response=array();

$response['company']=$company;


// number of bikes for the client, to be done for all companies with fleet manager access

include 'connexion.php';
$sql="SELECT 1 FROM customer_bikes where COMPANY='$company' AND STAANN != 'D'";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);
$length=$result->num_rows;
$response['bikeNumberClient']=$length;
$conn->close();


// number of bookings for the client, to be done for all companies with fleet manager access

$dateEnd=new DateTime();


if($dateEnd->format('m')==1){
    $monthBefore=12;
    $yearBefore=(($dateEnd->format('Y'))-1);
}else{
    $monthBefore=(($dateEnd->format('m'))-1);
    $yearBefore=$dateEnd->format('Y');
}
$dayBefore=$dateEnd->format('d');

if(strlen($monthBefore)==1){
    $monthBefore='0'.$monthBefore;
}
if(strlen($dayBefore)==1){
    $dayBefore='0'.$dayBefore;
}

$dateStart=new DateTime($yearBefore.'-'.$monthBefore.'-'.$dayBefore);

$dateStartString=$dateStart->format('Y-m-d');
$dateEndString=$dateEnd->format('Y-m-d');


include 'connexion.php';
$sql="SELECT 1 FROM customer_bikes cc, reservations dd where cc.COMPANY='$company' AND cc.ID=dd.BIKE_ID and dd.STAANN!='D' and dd.DATE_START_2>'$dateStartString' and dd.DATE_END_2<='$dateEndString'";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);
$response['bookingNumber']=$result->num_rows;

$conn->close();



// number of users for the client, to be done for all companies with fleet manager access

include 'connexion.php';
$sql="SELECT * FROM customer_referential dd where COMPANY='$company' ORDER BY NOM";

if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}

$result = mysqli_query($conn, $sql);        
$length = $result->num_rows;
$response['usersNumber']=$length;
$conn->close();


if($company=='KAMEO'){

    include 'connexion.php';
    $sql = "select 1 from customer_bikes where STAANN != 'D' AND (CONTRACT_TYPE='stock' OR CONTRACT_TYPE = 'test' OR CONTRACT_TYPE='leasing' OR CONTRACT_TYPE='renting')";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }

    $result = mysqli_query($conn, $sql);
    $length=$result->num_rows;

    $response['bikeNumber']=$length;
    $conn->close();
    
    include 'connexion.php';
    $sql = "select 1 from client_orders";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }

    $result = mysqli_query($conn, $sql);
    $length=$result->num_rows;

    $response['ordersNumber']=$length;
    $conn->close();

    include 'connexion.php';
    $sql = "select 1 from bike_catalog where STAANN != 'D'";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }

    $result = mysqli_query($conn, $sql);
    $length=$result->num_rows;

    $response['bikeNumberPortfolio']=$length;
    $conn->close();


    include 'connexion.php';
    $sql="SELECT * from companies WHERE TYPE='PROSPECT' OR TYPE='CLIENT' AND STAANN != 'D'";    
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);        
    $response['companiesNumberClientOrProspect'] = $result->num_rows;
    $conn->close();

    include 'connexion.php';
    $sql="SELECT SUM(LEASING_PRICE) as 'SOMME' from customer_bikes WHERE CONTRACT_START < CURRENT_TIMESTAMP AND (CONTRACT_END > CURRENT_TIMESTAMP OR CONTRACT_END is NULL)";    
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);        
    $resultat = mysqli_fetch_assoc($result);
    $response['sumContractsCurrent'] = $resultat['SOMME'];
    $conn->close();


    include 'connexion.php';
    $sql="SELECT SUM(AMOUNT) as 'PRICE' FROM boxes WHERE START<CURRENT_TIMESTAMP AND STAANN != 'D' and COMPANY != 'KAMEO' and COMPANY!='KAMEO VELOS TEST'";    
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);        
    $resultat = mysqli_fetch_assoc($result);
    $response['sumContractsCurrent'] += $resultat['PRICE'];
    $conn->close();

    include 'connexion.php';
    $sql="SELECT SUM(AMOUNT) as 'PRICE' FROM costs WHERE START<CURRENT_TIMESTAMP AND (END > CURRENT_TIMESTAMP OR END is NULL) AND STAANN != 'D'";

    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);
    $resultat = mysqli_fetch_assoc($result);
    $conn->close();

    $response['sumContractsCurrent']-=$resultat['PRICE'];                


    include 'connexion.php';
    $sql="SELECT 1 from company_actions WHERE OWNER = '$email' AND STATUS = 'TO DO'";    
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);        
    $length=$result->num_rows;
    $response['actionNumberNotDone'] = $length;
    $conn->close();

    include 'connexion.php';
    $sql="SELECT COUNT(1) AS 'SOMME' FROM boxes WHERE STAANN != 'D'";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql); 
    $resultat = mysqli_fetch_assoc($result);
    $response['boxesNumberTotal']=$resultat['SOMME'];
    $conn->close();

    include 'connexion.php';
    $sql="SELECT COUNT(1) AS 'SOMME' FROM factures WHERE FACTURE_SENT='0'";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql); 
    $resultat = mysqli_fetch_assoc($result);
    $response['billsNumber']=$resultat['SOMME'];
    $conn->close();


    include 'connexion.php';


    $sql = "SELECT 1 FROM feedbacks WHERE STATUS='DONE'";

    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);
    $response['feedbacksNumber']=$result->num_rows;

    $conn->close();






    include 'connexion.php';
    $sql = "SELECT 1
            FROM entretiens
            INNER JOIN customer_bikes ON customer_bikes.ID = entretiens.BIKE_ID
            ORDER BY entretiens.DATE DESC;";
    if ($conn->query($sql) === FALSE) {
      $response = array ('response'=>'error', 'message'=> $conn->error);
      echo json_encode($response);
      die;
    }
    $result = mysqli_query($conn, $sql);
    $conn->close();
    $response['maintenancesNumberGlobal']=$result->num_rows;

}else{

    include 'connexion.php';
    $sql="SELECT COUNT(1) AS 'SOMME' FROM factures WHERE FACTURE_PAID='0' AND COMPANY='$company'";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql); 
    $resultat = mysqli_fetch_assoc($result);
    $response['billsNumber']=$resultat['SOMME'];
    $conn->close();
}





$response['response']="success";
echo json_encode($response);
die;


?>
