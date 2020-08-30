<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';

$email=isset($_POST['email']) ? htmlspecialchars($_POST['email']) : NULL;
$type=$_POST['type'];

$response=array();

if($email){
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
    $response['company']=$company;
    
}




if($type=="users"){
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
    $response['response']="success";
    echo json_encode($response);
    die;
}

if($type=="bikes"){

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
    $response['response']="success";
    
    $conn->close();
    echo json_encode($response);
    die;
    
}

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

if($type=="bookings"){
    
    include 'connexion.php';
    $sql="SELECT 1 FROM customer_bikes cc, reservations dd where cc.COMPANY='$company' AND cc.ID=dd.BIKE_ID and dd.STAANN!='D' and dd.DATE_START_2>'$dateStartString' and dd.DATE_END_2<='$dateEndString'";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);
    $response['bookingNumber']=$result->num_rows;
    $response['response']="success";
    
    $conn->close();
    echo json_encode($response);
    die;
}

if($type=="ordersFleet"){
    include 'connexion.php';
    $sql = "select 1 from client_orders co, customer_referential cr WHERE STATUS='new' and co.EMAIL=cr.EMAIL and cr.COMPANY='$company'";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }

    $result = mysqli_query($conn, $sql);
    $length=$result->num_rows;

    $response['ordersNumber']=$length;
    $response['response']="success";

    $conn->close();
    echo json_encode($response);
    die;
}

if($type=="conditions"){
    include 'connexion.php';
    $sql = "select count(1) as SOMME from conditions where COMPANY='$company'";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }

    $response['conditionsNumber'] = mysqli_fetch_assoc(mysqli_query($conn, $sql))['SOMME'];
    $response['response']="success";

    $conn->close();
    echo json_encode($response);
    die;
}

if($type=="bills"){

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


if($company=='KAMEO'){


    if($type=="ordersAdmin"){
        include 'connexion.php';
        $sql = "select 1 from client_orders WHERE STATUS='new'";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }

        $result = mysqli_query($conn, $sql);
        $length=$result->num_rows;

        $response['ordersNumber']=$length;
        $response['response']="success";

        $conn->close();
        echo json_encode($response);
        die;
    }
    
    if($type=="bikesAdmin"){
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
        $response['response']="success";

        $conn->close();
        echo json_encode($response);
        die;
    }
    
    if($type=="portfolio"){
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
        $response['response']="success";
        $conn->close();
        echo json_encode($response);
        die;
    }
    if($type=="portfolioAccessories"){
        include 'connexion.php';
        $stmt = $conn->prepare("select count(1) as accessoriesNumberPortfolio, 'success' as response from accessories_catalog");
        if($stmt)
        {
            $stmt->execute();
            echo json_encode($stmt->get_result()->fetch_assoc());
            die;
        }
        else
            error_message('500', 'Unable to retrieve list of accessories');
    }

    if($type=="customers"){
        include 'connexion.php';
        $sql="SELECT * from companies WHERE TYPE='PROSPECT' OR TYPE='CLIENT' AND STAANN != 'D'";    
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);        
        $response['companiesNumberClientOrProspect'] = $result->num_rows;
        $response['response']="success";
        $conn->close();
        echo json_encode($response);
        die;
    }
    
    
    if($type=="chat"){
        //number of unread messages

        include 'connexion.php';
        $sql="SELECT COUNT(1) as 'TOTAL' FROM `chat` aa, customer_referential cc where NOT exists (select 1 from chat bb where aa.EMAIL_USER=bb.EMAIL_DESTINARY and aa.MESSAGE_TIMESTAMP<bb.MESSAGE_TIMESTAMP) and aa.EMAIL_USER=cc.EMAIL and cc.COMPANY != 'KAMEO'";
        $result = $conn->query($sql);
        if (!$result) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $response['response']="success";        
        $response['messagesNumberUnread']=intval($result->fetch_array(MYSQLI_ASSOC)['TOTAL']);
        $conn->close();
        echo json_encode($response);
        die;

    }
    
    if($type=="boxes"){
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
        $response['response']="success";
        $conn->close();
        echo json_encode($response);
        die;
    }
    
    if($type=="cashFlow"){

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
        $response['sumContractsCurrent']-=$resultat['PRICE'];  
        $response['response']="success";
        $conn->close();
        echo json_encode($response);
        die;
        
    }

    if($type=="tasks"){
        
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
        $response['response']="success";
        $conn->close();
        echo json_encode($response);
        die;
        
    }


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


    if($type=="feedback"){
        
    
        include 'connexion.php';


        $sql = "SELECT 1 FROM feedbacks WHERE STATUS='DONE'";

        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $response['feedbacksNumber']=$result->num_rows;

        $response['response']="success";
        $conn->close();
        echo json_encode($response);
        die;
    }





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

}





$response['response']="success";
echo json_encode($response);
die;


?>
