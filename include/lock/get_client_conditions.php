<?php
include '../connexion.php';

$rfid=$_GET['uid'];


$sql="SELECT * from customer_referential WHERE RFID='$rfid'";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}


$result = mysqli_query($conn, $sql);  
$resultat = mysqli_fetch_assoc($result);
$company=$resultat['COMPANY'];
$length = $result->num_rows;

if($length=="1"){
    $client=$resultat['EMAIL'];
    $sql="SELECT * FROM conditions WHERE COMPANY='$company'";
    
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    
    $result = mysqli_query($conn, $sql);  
    $length = $result->num_rows;
    $resultat = mysqli_fetch_assoc($result);
    
    if($length=='1'){
        if($resultat['BOX_BOOKING']=='Y'){
            //utilisateur trouvé et il a la condition
            echo "1";
            echo "\n";
            echo "1";
        }else{
            //utilisateur trouvé mais il n'a la condition
            echo "1";
            echo "\n";
            echo "0";
        }
    }else{
    //utilisateur trouvé mais pas de condition définie (ne devrait jamais arrivé)
        echo "1";
        echo "\n";
        echo "0";
    }
    
}else{
    //pas d'utilisateur trouvé
    echo "-1";
}
$conn->close();

?>