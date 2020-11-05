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


include 'connexion.php';
$sql="SELECT * from building_access where COMPANY = '$company'";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);
$response['buildingNumber'] = $result->num_rows;
$i=0;
$response['response']="success";
while($row = mysqli_fetch_array($result)){
    $response['building'][$i]['code']=$row['BUILDING_REFERENCE'];
    $response['building'][$i]['descriptionFR']=$row['BUILDING_FR'];
    $i++;
}
$conn->close();


echo json_encode($response);
die;

?>
