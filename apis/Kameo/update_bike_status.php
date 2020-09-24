<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';



$user=$_POST['user'];
$bikeID=$_POST['bikeID'];
$model=$_POST['bikeModel'];
$status=$_POST['bikeStatus'];

$response=array();


if($bikeID != NULL && $status != NULL)
{

    include 'connexion.php';
    $sql="select * from customer_bikes WHERE ID = '$bikeID'";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql); 
    $resultat = mysqli_fetch_assoc($result);    
    $conn->close();

    $company=$resultat['COMPANY'];

    if($status!=$resultat['STATUS']){
        include 'connexion.php';
        $sql="update customer_bikes set STATUS = '$status', USR_MAJ = '$user', HEU_MAJ = CURRENT_TIMESTAMP WHERE ID = '$bikeID'";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $conn->close();     
    }

    if($model!=$resultat['MODEL']){
        include 'connexion.php';
        $sql="update customer_bikes set MODEL = '$model', USR_MAJ = '$user', HEU_MAJ = CURRENT_TIMESTAMP WHERE ID = '$bikeID'";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $conn->close();     
    }    


    foreach($_POST as $name => $value){

        if($name=="buildingAccess"){   
            foreach($_POST['buildingAccess'] as $valueInArray){
                include 'connexion.php';
                $sql= "SELECT * FROM bike_building_access WHERE BIKE_ID='$bikeID' and BUILDING_CODE='$valueInArray'";
                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
                $result = mysqli_query($conn, $sql);        
                $length = $result->num_rows;
                $conn->close(); 




                if($length==0){
                    include 'connexion.php';
                    $sql= "INSERT INTO  bike_building_access (USR_MAJ, BIKE_ID, BUILDING_CODE, STAANN) VALUES ('$user','$bikeID', '$valueInArray', '')";
                    if ($conn->query($sql) === FALSE) {
                        $response = array ('response'=>'error', 'message'=> $conn->error);
                        echo json_encode($response);
                        die;
                    }
                    $conn->close();   
                }           

                include 'connexion.php';
                $sql= "SELECT * FROM bike_building_access WHERE BIKE_ID='$bikeID' and BUILDING_CODE='$valueInArray' and STAANN='D'";
                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
                $result = mysqli_query($conn, $sql);        
                $length = $result->num_rows;
                $conn->close();   

                if($length==1){
                    include 'connexion.php';
                    $sql= "UPDATE  bike_building_access set STAANN='' WHERE BIKE_ID='$bikeID' and BUILDING_CODE='$valueInArray'";
                    if ($conn->query($sql) === FALSE) {
                        $response = array ('response'=>'error', 'message'=> $conn->error);
                        echo json_encode($response);
                        die;
                    }
                    $conn->close();
                }                                


            }
        }
    }

    if(!isset(($_POST['buildingAccess']))){
        include 'connexion.php';
        $type_bike = $_POST['bikeType'];
        $sql="update bike_building_access set STAANN='D', USR_MAJ='$user', TIMESTAMP=CURRENT_TIMESTAMP where BIKE_ID = '$bikeID'";

        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }

        if( $type_bike == 'partage'){
            $sql="UPDATE customer_bike_access SET TIMESTAMP=CURRENT_TIMESTAMP, USR_MAJ='$user', TYPE='$type_bike' WHERE BIKE_ID = '$bikeID' AND TYPE= 'personnel'";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }

        }else{
            $sql="DELETE FROM customer_bike_access WHERE BIKE_ID = '$bikeID'";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }

            $email = $_POST['email'];

            $sql="INSERT INTO customer_bike_access (TIMESTAMP, USR_MAJ, EMAIL, BIKE_ID, TYPE, STAANN) VALUES (CURRENT_TIMESTAMP, '$user', '$email', '$bikeID', '$type_bike', '')";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }

        }
        $conn->close();

          

    }else{

        include 'connexion.php';
        $sql= "SELECT * FROM building_access WHERE COMPANY='$company'";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);        
        $length = $result->num_rows;
        $conn->close(); 

        while($row = mysqli_fetch_array($result)){
            $presence=false;
            foreach($_POST['buildingAccess'] as $valueInArray2){
                if($row['BUILDING_REFERENCE']==$valueInArray2){
                    $presence=true;
                }
            }
            if($presence==false){
                $building=$row['BUILDING_REFERENCE'];
                include 'connexion.php';
                $sql="select * from bike_building_access where BIKE_ID = '$bikeID' and BUILDING_CODE='$building'";
                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
                $result2 = mysqli_query($conn, $sql); 
                $length = $result2->num_rows;
                $conn->close();  
                if($length==1){
                    include 'connexion.php';
                    $sql="update bike_building_access set STAANN='D', USR_MAJ='$user', TIMESTAMP=CURRENT_TIMESTAMP where BIKE_ID = '$bikeID' and BUILDING_CODE='$building'";

                    if ($conn->query($sql) === FALSE) {
                        $response = array ('response'=>'error', 'message'=> $conn->error);
                        echo json_encode($response);
                        die;
                    }

                }

            }

        }                    


    }    

    successMessage("SM0003");

}
else
{
    errorMessage("ES0012");
}
?>