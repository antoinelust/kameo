<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';




$email=isset($_POST['email']) ? $_POST['email'] : NULL;
$company=isset($_POST['company']) ? $_POST['company'] : NULL;
$admin=isset($_POST['admin']) ? $_POST['admin'] : NULL;

$response=array();

if($admin!="Y"){
    if($company==NULL){
        if($email != NULL){
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

        }else{
            errorMessage("ES0038");
        }
    }
    include 'connexion.php';
    $sql="SELECT * FROM customer_bikes where COMPANY='$company' AND STAANN != 'D'";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }              
}else{
    include 'connexion.php';
    $sql="SELECT * FROM customer_bikes WHERE STAANN != 'D'";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
}   
include 'connexion.php';

$result = mysqli_query($conn, $sql);        
$length = $result->num_rows;
$response['bikeNumber']=$length;
$response['response']="success";
$response['sql']=$sql;

$i=0;
while($row = mysqli_fetch_array($result))

{

    $response['bike'][$i]['frameNumber']=$row['FRAME_NUMBER'];
    $response['bike'][$i]['model']=$row['MODEL'];            
    $response['bike'][$i]['company']=$row['COMPANY'];            
    $response['bike'][$i]['contractReference']=$row['CONTRACT_REFERENCE'];
    $response['bike'][$i]['automatic_billing']=$row['LEASING'];
    if($row['LEASING']=="Y"){
        $response['bike'][$i]['contractType']="leasing";
    }else{
        $response['bike'][$i]['contractType']="other";
    }
    $response['bike'][$i]['contractStart']=$row['CONTRACT_START'];
    $response['bike'][$i]['contractEnd']=$row['CONTRACT_END'];
    $response['bike'][$i]['status']=$row['STATUS'];

    $i++;

}

include 'connexion.php';
$timestamp=mktime(0, 0, 0, 1, 1, date("Y"));
$sql2="SELECT count(1) FROM customer_bikes cc, reservations dd where COMPANY=(select COMPANY from customer_referential where EMAIL='$email') and cc.FRAME_NUMBER=dd.FRAME_NUMBER and cc.STAANN != 'D' and dd.STAANN!='D' and dd.DATE_START>'$timestamp'";
if ($conn->query($sql2) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result2 = mysqli_query($conn, $sql2);        
$resultat2 = mysqli_fetch_assoc($result2);

$response['numberOfBookings']=$resultat2['count(1)'];

echo json_encode($response);
die;
?>