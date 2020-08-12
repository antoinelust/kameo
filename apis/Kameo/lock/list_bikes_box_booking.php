<?php
$rfid=$_GET['uid'];
$minutes=$_GET['minutes'];
$building=$_GET['building'];


include '../connexion.php';
$sql="SELECT * from building_access WHERE BUILDING_CODE='$building'";
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


include '../connexion.php';
$sql="SELECT aa.EMAIL, aa.COMPANY, cc.FRAME_NUMBER, cc.ID, cc.MODEL, dd.FRAME_TYPE from customer_referential aa, customer_bike_access bb, customer_bikes cc, bike_catalog dd WHERE RFID='$rfid' and aa.EMAIL=bb.EMAIL and bb.BIKE_ID=cc.ID AND cc.TYPE=dd.ID and aa.STAANN != 'D' AND bb.STAANN != 'D' AND dd.STAANN != 'D' and not exists (select 1 from reservations ee WHERE ee.BIKE_ID=bb.BIKE_ID and ee.DATE_START_2 < CURRENT_TIMESTAMP() and DATE_END_2 > CURRENT_TIMESTAMP() and ee.STAANN !='D')";


if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}


$result = mysqli_query($conn, $sql);  
$length = $result->num_rows;
$conn->close();

$i=0;
$bikes=[];

if($length > 0){   
    $dateNow=new DateTime();
    $dateNowString=$dateNow->format("Y-m-d H:i");

    $dateEnd=$dateNow;
    $interval="PT".$minutes."M";
    $dateEnd->add(new DateInterval($interval));
    $dateEndString=$dateEnd->format("Y-m-d H:i");
        
    while($row = mysqli_fetch_array($result)){
        $frameNumber=$row['FRAME_NUMBER'];
        
        include '../connexion.php';
        $sql2="SELECT min(DATE_START_2) as 'minimum' from reservations where FRAME_NUMBER='$frameNumber' AND BUILDING_START='$buildingReference' AND DATE_START_2 > CURRENT_TIMESTAMP() and DATE_START_2 < '$dateEndString'";
        
        if ($conn->query($sql2) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result2 = mysqli_query($conn, $sql2);  
        $resultat2 = mysqli_fetch_assoc($result2);
        
        $length = $result2->num_rows;
        $conn->close();
                
        
        if($length=0 || $resultat2['minimum'] == NULL){
            include '../connexion.php';
            $sql3="SELECT BUILDING_START from reservations where FRAME_NUMBER='$frameNumber' AND DATE_END_2 < CURRENT_TIMESTAMP()";
            if ($conn->query($sql3) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result3 = mysqli_query($conn, $sql3);  
            $resultat3 = mysqli_fetch_assoc($result3);
            $conn->close();
                        
            
            if($buildingReference==$resultat3['BUILDING_START']){
                $bikes[$i]['frameNumber']=$frameNumber;
                $bikes[$i]['model']=$row['MODEL'];
                switch ($row['FRAME_TYPE']) {
                    case 'M':
                        $bikes[$i]['frameType']="Mixte";
                        break;
                    case 'H':
                        $bikes[$i]['frameType']="Homme";
                        break;
                    case 'F':
                        $bikes[$i]['frameType']="Femme";
                        break;
                }
                
                $i++;
                
            }
        }
        
    }
    
    if($i>0){
        
        echo $bikes[0]['frameNumber']."/".$bikes[0]['model']."/".$bikes[0]['frameType'];
    }else{
        echo "-3";
    }
    

    
}else{
    //pas d'utilisateur trouvé
    echo "-1";
}
?>