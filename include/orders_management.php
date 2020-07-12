<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

if(!isset($_SESSION))
{
    session_start();
}

include 'globalfunctions.php';

if(isset($_POST['action'])){
    
    
    $action=isset($_POST['action']) ? $_POST['action'] : NULL;    
    if($action=='add'){
    }else if($action=='update'){
        
        $email=isset($_POST['email']) ? $_POST['email'] : NULL;   
        $ID=isset($_POST['ID']) ? $_POST['ID'] : NULL;   
        $status=isset($_POST['status']) ? $_POST['status'] : NULL;   
        $portfolioID=isset($_POST['portfolioID']) ? $_POST['portfolioID'] : NULL;   
        $size=isset($_POST['size']) ? $_POST['size'] : NULL;   
        $testBoolean=isset($_POST['testBoolean']) ? "Y" : "N";
        $testDate=isset($_POST['testDate']) ? $_POST['testDate'] : NULL;   
        $testStatus=isset($_POST['testStatus']) ? $_POST['testStatus'] : NULL;   
        $testAddress=isset($_POST['testAddress']) ? addslashes($_POST['testAddress']) : NULL;   
        $testResult=isset($_POST['testResult']) ? addslashes($_POST['testResult']) : NULL;   
        $deliveryDate=isset($_POST['deliveryDate']) ? $_POST['deliveryDate'] : NULL;   
        $deliveryAddress=isset($_POST['deliveryAddress']) ? addslashes($_POST['deliveryAddress']) : NULL;   
        
        
        if($deliveryAddress!=NULL){
            $deliveryAddress="'".$deliveryAddress."'";
        }else{
            $deliveryAddress='NULL';
        }
        
        
        include 'connexion.php';
        $sql= "UPDATE client_orders  SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ='$email', STATUS='$status', PORTFOLIO_ID='$portfolioID', SIZE='$size', DELIVERY_ADDRESS=$deliveryAddress WHERE ID='$ID'";
                
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $conn->close();
        
        if($deliveryDate != NULL){
            include 'connexion.php';
            $sql= "UPDATE client_orders  SET ESTIMATED_DELIVERY_DATE='$deliveryDate' WHERE ID='$ID'";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $conn->close();
        }
        
        if($testBoolean=="Y"){
            include 'connexion.php';
            
            if($testDate!=NULL){
                $testDate="'".$testDate."'";
            }else{
                $testDate='NULL';
            }
            if($testAddress!=NULL){
                $testAddress="'".$testAddress."'";
            }else{
                $testAddress='NULL';
            }
            if($testResult!=NULL){
                $testResult="'".$testResult."'";
            }else{
                $testResult='NULL';
            }
            if($deliveryAddress!=NULL){
                $deliveryAddress="'".$deliveryAddress."'";
            }else{
                $deliveryAddress='NULL';
            }
        
            $sql= "UPDATE client_orders  SET TEST_BOOLEAN='Y', TEST_DATE=$testDate, TEST_ADDRESS=$testAddress, TEST_RESULT=$testResult WHERE ID='$ID'";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $conn->close();
        }
        successMessage("SM0003");        
    }else if($action=="delete"){
    }
    
    
}else if(isset($_GET['action'])){
    
    $action=isset($_GET['action']) ? $_GET['action'] : NULL;   
    if($action=='list'){
        include 'connexion.php';
        $sql= "SELECT * FROM client_orders";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result2 = mysqli_query($conn, $sql);
        $length = $result2->num_rows;
        $conn->close();
        $response=array();
        $response['response']="success";
        $response['ordersNumber']=$length;
        $i=0;
        
        while($row = mysqli_fetch_array($result2)){
            $response['order'][$i]['ID']=$row['ID'];
            $response['order'][$i]['size']=$row['SIZE'];
            $response['order'][$i]['status']=$row['STATUS'];
            $response['order'][$i]['estimatedDeliveryDate']=$row['ESTIMATED_DELIVERY_DATE'];
            $response['order'][$i]['testStatus']=$row['TEST_STATUS'];
            $response['order'][$i]['testDate']=$row['TEST_DATE'];
            $response['order'][$i]['testBoolean']=$row['TEST_BOOLEAN'];
            $portfolioID=$row['PORTFOLIO_ID'];
            include 'connexion.php';
            $sql= "SELECT * FROM bike_catalog WHERE ID='$portfolioID'";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result = mysqli_query($conn, $sql);
            $resultat=mysqli_fetch_assoc($result);
            $conn->close();
            $response['order'][$i]['brand']=$resultat['BRAND'];
            $response['order'][$i]['model']=$resultat['MODEL'];
            
            $emailUser=$row['EMAIL'];
            include 'connexion.php';
            $sql= "SELECT * FROM customer_referential WHERE EMAIL='$emailUser'";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result = mysqli_query($conn, $sql);
            $resultat=mysqli_fetch_assoc($result);
            $conn->close();
            $response['order'][$i]['user']=$resultat['PRENOM']." ".$resultat['NOM'];
            $company=$resultat['COMPANY'];
            
            include 'connexion.php';
            $sql= "SELECT * FROM companies WHERE INTERNAL_REFERENCE='$company'";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result = mysqli_query($conn, $sql);
            $resultat=mysqli_fetch_assoc($result);
            $conn->close();
            $response['order'][$i]['companyID']=$resultat['ID'];
            $response['order'][$i]['companyName']=$resultat['COMPANY_NAME'];
            $i++;

        }
        
        echo json_encode($response);
        die;
        
    }else if($action=='retrieve'){
        $ID=isset($_GET['ID']) ? $_GET['ID'] : NULL;   
        
        include 'connexion.php';
        $sql= "SELECT * FROM client_orders WHERE ID='$ID'";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $resultat = mysqli_fetch_assoc($result);
        $conn->close();
        $response=array();
        $response['response']="success";
        $response['order']['ID']=$resultat['ID'];
        $response['order']['email']=$resultat['EMAIL'];
        $response['order']['size']=$resultat['SIZE'];
        $response['order']['status']=$resultat['STATUS'];
        $response['order']['testBoolean']=$resultat['TEST_BOOLEAN'];
        $response['order']['testDate']=$resultat['TEST_DATE'];
        $response['order']['testAddress']=$resultat['TEST_ADDRESS'];
        $response['order']['testStatus']=$resultat['TEST_STATUS'];
        $response['order']['testResult']=$resultat['TEST_RESULT'];
        $response['order']['sql1']=$sql;
        
        $portfolioID=$resultat['PORTFOLIO_ID'];
        
        include 'connexion.php';
        $sql= "SELECT * FROM bike_catalog WHERE ID='$portfolioID'";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $resultat = mysqli_fetch_assoc($result);
        $conn->close();
        
        $response['order']['portfolioID']=$portfolioID;
        $response['order']['brand']=$resultat['BRAND'];
        $response['order']['model']=$resultat['MODEL'];
        $response['order']['frameType']=$resultat['FRAME_TYPE'];
        $response['order']['sql2']=$sql;
        
        
        
        echo json_encode($response);
        die;
    }
    
}
else{
    errorMessage("ES0012");
}


?>
