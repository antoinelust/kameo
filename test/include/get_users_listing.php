<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';




$email=isset($_POST['email']) ? $_POST['email'] : NULL;
$company=isset($_POST['company']) ? $_POST['company'] : NULL;

$response=array();
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
        $conn->close();   
        
    }else{
        errorMessage("ES0038");
    }
}


$timestamp_now=time();

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



$i=0;
while($row = mysqli_fetch_array($result))

{

    $response['response']="success";
    $response['user'][$i]['name']=$row['NOM'];
    $response['user'][$i]['firstName']=$row['PRENOM'];            
    $response['user'][$i]['email']=$row['EMAIL'];  
    $response['user'][$i]['staann']=$row['STAANN'];
    $i++;

}
echo json_encode($response);
die;

?>