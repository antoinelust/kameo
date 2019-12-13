<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';




$email=isset($_GET['email']) ? $_GET['email'] : NULL;

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


include 'connexion.php';
$sql="SELECT * FROM conditions where COMPANY='$company'";

if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}

$result = mysqli_query($conn, $sql);        
$length = $result->num_rows;
$response['conditionNumber']=$length;
$conn->close();   


$i=0;
while($row = mysqli_fetch_array($result))

{
    $response['response']="success";
    $id=$row['ID'];
    $response['condition'][$i]['id']=$row['ID'];
    $name=$row['NAME'];
    
    $response['condition'][$i]['name']=$name;    
    
    include 'connexion.php';
    
    if($name!='generic'){
        $sql="SELECT * FROM specific_conditions where CONDITION_REFERENCE='$id' AND COMPANY='$company' and STAANN != 'D'";        
    }else{
        $sql="SELECT * FROM customer_referential aa where COMPANY='$company' AND STAANN != 'D' and not exists(select 1 from specific_conditions bb where bb.EMAIL=aa.EMAIL and bb.STAANN!='D')";
    }
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }

    $result2 = mysqli_query($conn, $sql);        
    $length = $result2->num_rows;
    $response['condition'][$i]['length']=$length;
    $conn->close();   
    
    $i++;

}
echo json_encode($response);
die;

?>