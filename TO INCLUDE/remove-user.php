<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';




$email=$_POST['widget-updateUser-form-mail'];

$response=array();

if($email != NULL)
{

    include 'connexion.php';
	$sql="select * from customer_referential where EMAIL = '$email'";

    if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
    }
	$result = mysqli_query($conn, $sql); 
    $resultat = mysqli_fetch_assoc($result);
    $conn->close();  
    $company=$resultat['COMPANY'];

    foreach($_POST as $name => $value){

        if($name=="buildingAccess"){   
            foreach($_POST['buildingAccess'] as $valueInArray){
                include 'connexion.php';
                $sql= "SELECT * FROM customer_building_access WHERE EMAIL='$email' and BUILDING_CODE='$valueInArray'";
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
                    $sql= "INSERT INTO  customer_building_access (USR_MAJ, EMAIL, BUILDING_CODE, STAANN) VALUES ('mykameo','$email', '$valueInArray', '')";
                    if ($conn->query($sql) === FALSE) {
                        $response = array ('response'=>'error', 'message'=> $conn->error);
                        echo json_encode($response);
                        die;
                    }
                    $conn->close();   
                }           
                
                include 'connexion.php';
                $sql= "SELECT * FROM customer_building_access WHERE EMAIL='$email' and BUILDING_CODE='$valueInArray' and STAANN='D'";
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
                    $sql= "UPDATE  customer_building_access set STAANN='', USR_MAJ='mykameo', TIMESTAMP=CURRENT_TIMESTAMP WHERE EMAIL='$email' and BUILDING_CODE='$valueInArray'";
                    if ($conn->query($sql) === FALSE) {
                        $response = array ('response'=>'error', 'message'=> $conn->error);
                        echo json_encode($response);
                        die;
                    }
                    $conn->close();
                }                                

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
                        $sql="select * from customer_building_access where EMAIL = '$email' and BUILDING_CODE='$building'";

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
                            $sql="update customer_building_access set STAANN='D', USR_MAJ='mykameo', TIMESTAMP=CURRENT_TIMESTAMP where EMAIL = '$email' and BUILDING_CODE='$building'";

                            if ($conn->query($sql) === FALSE) {
                                $response = array ('response'=>'error', 'message'=> $conn->error);
                                echo json_encode($response);
                                die;
                            }

                        }

                    }

                }                
            }
        }
        
        if($name=="bikeAccess"){   
            foreach($_POST['bikeAccess'] as $valueInArray){
                include 'connexion.php';
                $sql= "SELECT * FROM customer_bike_access WHERE EMAIL='$email' and BIKE_NUMBER='$valueInArray'";
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
                    $sql= "INSERT INTO  customer_bike_access (USR_MAJ, EMAIL, BIKE_NUMBER, TYPE, STAANN) VALUES ('mykameo','$email', '$valueInArray', 'partage', '')";
                    if ($conn->query($sql) === FALSE) {
                        $response = array ('response'=>'error', 'message'=> $conn->error);
                        echo json_encode($response);
                        die;
                    }
                    $conn->close();   
                }           
                
                include 'connexion.php';
                $sql= "SELECT * FROM customer_bike_access WHERE EMAIL='$email' and BIKE_NUMBER='$valueInArray' and STAANN='D'";
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
                    $sql= "UPDATE  customer_bike_access set STAANN='' WHERE EMAIL='$email' and BIKE_NUMBER='$valueInArray'";
                    if ($conn->query($sql) === FALSE) {
                        $response = array ('response'=>'error', 'message'=> $conn->error);
                        echo json_encode($response);
                        die;
                    }
                    $conn->close();
                }    
                
                include 'connexion.php';
                $sql= "SELECT * FROM customer_bikes WHERE COMPANY='$company'";
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
                    foreach($_POST['bikeAccess'] as $valueInArray2){
                        if($row['FRAME_NUMBER']==$valueInArray2){
                            $presence=true;

                        }
                    }
                    if($presence==false){
                        $bike=$row['FRAME_NUMBER'];
                    
                        include 'connexion.php';
                        $sql="select * from customer_bike_access where EMAIL = '$email' and BIKE_NUMBER='$bike'";

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
                            $sql="update customer_bike_access set STAANN='D', USR_MAJ='mykameo', TIMESTAMP=CURRENT_TIMESTAMP where EMAIL = '$email' and BIKE_NUMBER='$bike'";

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
        }        
    }
    if(!isset(($_POST['buildingAccess']))){
        include 'connexion.php';
        $sql="update customer_building_access set STAANN='D', USR_MAJ='mykameo', TIMESTAMP=CURRENT_TIMESTAMP where EMAIL = '$email'";

        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $conn->close();  

    }
    
    if(!isset(($_POST['bikeAccess']))){
        include 'connexion.php';
        $sql="update customer_bike_access set STAANN='D', USR_MAJ='mykameo', TIMESTAMP=CURRENT_TIMESTAMP where EMAIL = '$email'";

        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $conn->close();  

    }
    
    
    successMessage("SM0003");

}
else
{
	errorMessage("ES0012");
}

?>