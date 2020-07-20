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
$conn->close();

if($length=="1"){
    $client=$resultat['EMAIL'];
    $company=$resultat['COMPANY'];
    
    
    
    include '../connexion.php';
    $sql="SELECT * FROM building_access WHERE BUILDING_CODE='$building'";    
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    
    $result = mysqli_query($conn, $sql);  
    $length = $result->num_rows;
    $resultat = mysqli_fetch_assoc($result);
    $conn->close();

    
    $buildingReference=$resultat['BUILDING_REFERENCE'];
    
    $dateStartBooking=new DateTime();
    $interval = new DateInterval("PT15M");
    $dateStartBooking->add($interval);
    $dateStartBookingString=$dateStartBooking->format("Y-m-d H:i");
    
    
    include '../connexion.php';
    $sql="SELECT * FROM reservations WHERE EMAIL='$client' AND BUILDING_START='$buildingReference' AND DATE_START_2 <='$dateStartBookingString' AND DATE_END_2 >= '$dateStartBookingString' AND STATUS='Open' AND STAANN != 'D'";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    
    $result = mysqli_query($conn, $sql);  
    $length = $result->num_rows;
    $resultat = mysqli_fetch_assoc($result);
    $conn->close();
    
    
    if($length>0){
        $idReservation=$resultat['ID'];
        include '../connexion.php';            
        $sql = "SELECT * from reservations WHERE ID='$idReservation'";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);  
        $resultat = mysqli_fetch_assoc($result);
        $conn->close();

        $frameNumber=$resultat['FRAME_NUMBER'];

        include '../connexion.php';            
        $sql = "SELECT * from locking_bikes WHERE FRAME_NUMBER='$frameNumber'";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);  
        $resultat = mysqli_fetch_assoc($result);
        $conn->close();

        echo $resultat['PLACE_IN_BUILDING'];
    }else{
        include '../connexion.php';
        $sql="SELECT * FROM reservations WHERE EMAIL='$client' AND BUILDING_START='$buildingReference' AND DATE_START_2 <= CURRENT_TIMESTAMP() AND DATE_END_2 >= CURRENT_TIMESTAMP() AND STAANN = 'D'";

        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }

        $result = mysqli_query($conn, $sql);  
        $length2 = $result->num_rows;
        $resultat = mysqli_fetch_assoc($result);
        $conn->close();
        
        if($length2==1){
            echo "-2";
            echo "/";
            
        }else{
            include '../connexion.php';        
            $sql="SELECT * FROM reservations WHERE EMAIL='$client' AND BUILDING_START='$buildingReference'";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }

            $result = mysqli_query($conn, $sql);  
            $length = $result->num_rows;
            $conn->close();

            if($length=='0'){
                //pas de réservations trouvées

                echo "-1";
                echo "/";

            }else{
                //réservation hors délai
                echo "-4";
                echo "/";
            }
        }
        
        
        
        
        
        include '../connexion.php';
        $sql="select * from specific_conditions where EMAIL='$client' AND STAANN != 'D'";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql); 
        $resultat = mysqli_fetch_assoc($result);
        $length = $result->num_rows;    
        $conn->close();   

        if($length==0){
            include '../connexion.php';
            $sql="select * from conditions where COMPANY = '$company'";

            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result = mysqli_query($conn, $sql); 
            $resultat = mysqli_fetch_assoc($result);
            $conn->close();   
        }else{

            $conditionReference=$resultat['CONDITION_REFERENCE'];
            include '../connexion.php';
            $sql="select * from conditions where ID = '$conditionReference'";

            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result = mysqli_query($conn, $sql); 
            $resultat = mysqli_fetch_assoc($result);
            $conn->close();   
        }
        
        $maxLengthCondition=$resultat['BOOKING_LENGTH'];
        
        include '../connexion.php';
        $sql="SELECT aa.BIKE_NUMBER from customer_bike_access aa, customer_bikes bb WHERE aa.EMAIL='$client' and aa.STAANN != 'D' and aa.BIKE_NUMBER=bb.FRAME_NUMBER and bb.STAANN != 'D'";
        
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql); 
        $conn->close();   
        
        $maxLengthBooking=$maxLengthCondition;
        $maxLengthBookingMinutes=$maxLengthCondition*60+15;
        
        $i=0;
        
        
        while($row = mysqli_fetch_array($result)){
            $frameNumber=$row['BIKE_NUMBER'];
            
            include '../connexion.php';            
            $sql2="SELECT aa.DATE_START_2, aa.FRAME_NUMBER FROM reservations aa WHERE FRAME_NUMBER='$frameNumber' AND DATE_START_2 > CURRENT_TIMESTAMP() AND STAANN != 'D' and not exists (select 1 from reservations bb WHERE bb.STAANN != 'D' and bb.DATE_END > CURRENT_TIMESTAMP and bb.DATE_END_2 < aa.DATE_START_2) ORDER BY aa.DATE_START_2";
            
            
            if ($conn->query($sql2) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result2 = mysqli_query($conn, $sql2);
            $length = $result2->num_rows;    
            $conn->close();   
            
            
            $response=[];
            
            if($length>0){
                $resultat2 = mysqli_fetch_assoc($result2);
                $dateStartTimestamp=strtotime($resultat2['DATE_START_2']);
                $timestampNow=strtotime("now");
                $diff = abs($dateStartTimestamp - $timestampNow);
                $diffMinutes=round($diff/60);
                
                if($diffMinutes > $maxLengthBookingMinutes){
                    $diffMinutes = $maxLengthBookingMinutes;
                }
                $response[$i]['frameNumber']=$frameNumber;
                $response[$i]['bookingMaxLength']=$diffMinutes;
                $i++;                
            }else{
                include '../connexion.php';            
                $sql3="SELECT aa.DATE_START_2, aa.FRAME_NUMBER FROM reservations aa WHERE FRAME_NUMBER='$frameNumber' AND DATE_START_2 < CURRENT_TIMESTAMP() AND DATE_END_2 > CURRENT_TIMESTAMP() AND STAANN != 'D'";
                if ($conn->query($sql3) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
                $result3 = mysqli_query($conn, $sql3);
                $length = $result3->num_rows;    
                $conn->close(); 
                if($length==0){
                    $diffMinutes = $maxLengthBookingMinutes;
                    $response[$i]['frameNumber']=$frameNumber;
                    $response[$i]['bookingMaxLength']=$maxLengthBookingMinutes;
                    $i++;                
                }
            }
        
        }
        
        $j=0;
        $max=0;
                
        
        while($j<$i){
            if($response[$j]['bookingMaxLength']>$max){
                $max=$response[$j]['bookingMaxLength'];
            }
            $j++;
        }
        
        if($max>15){
            $max=$max-15;
        }else{
            $max=0;
        }
        
        echo $max;
        echo "/";
        
        include '../connexion.php';
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
            }else{
                //utilisateur trouvé mais il n'a la condition
                echo "-1";
            }
        }else{
        //utilisateur trouvé mais pas de condition définie (ne devrait jamais arriver)
            echo "-2";
        }
        
        
    }
    
    
}else{
    //pas d'utilisateur trouvé
    echo "-3";
}
?>