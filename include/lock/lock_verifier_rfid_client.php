<?php
include '../connexion.php';

$building=$_GET['building'];
$rfid=$_GET['uid'];


$sql="SELECT * from customer_referential WHERE RFID='$rfid'";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}


$result = mysqli_query($conn, $sql);  
$resultat = mysqli_fetch_assoc($result);
$length = $result->num_rows;

if($length=="1"){
    $client=$resultat['EMAIL'];
    $sql="SELECT * FROM reservations WHERE EMAIL='$client' AND BUILDING_START='$building' AND DATE_START_2 <= CURRENT_TIMESTAMP() AND DATE_END_2 >= CURRENT_TIMESTAMP()";
    
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    
    $result = mysqli_query($conn, $sql);  
    $length = $result->num_rows;
    $resultat = mysqli_fetch_assoc($result);
    
    if($length=='1'){
        if ($resultat['STAANN']=='D' || $resultat['STATUS']=='closed'){
            //réservation annulée ou déjà finie
            echo "-2";
        }else{
            $idReservation=$resultat['ID'];
            $sql = "SELECT * from locking_bikes WHERE RESERVATION_ID='$idReservation'";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result = mysqli_query($conn, $sql);  
            $resultat = mysqli_fetch_assoc($result);
            echo $resultat['PLACE_IN_BUILDING'];
        }
    }else{
        //pas de réservations trouvées
        echo "-1";
    }
    
    
}else{
    //pas d'utilisateur trouvé
    echo "-3";
}
$conn->close();

?>