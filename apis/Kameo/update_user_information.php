<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';




$email=$_POST['widget-updateUser-form-mail'];
$name=$_POST['widget-updateUser-form-name'];
$firstName=$_POST['widget-updateUser-form-firstname'];
$fleetManager=isset($_POST['fleetManager']) ? "Y" : "N";
$phone=$_POST['widget-updateUser-form-phone'];



$response=array();

if($email != NULL)
{

    include 'connexion.php';
  	$resultat=execSQL("select * from customer_referential where EMAIL = ?", array('s', $email), false)[0];
    $company=$resultat['COMPANY'];

    if($name!=$resultat['NOM']){
        execSQL("UPDATE customer_referential set NOM=? where EMAIL = ?", array('ss',$name, $email), true);
    }

    if($firstName!=$resultat['PRENOM']){
        execSQL("UPDATE customer_referential set PRENOM=? where EMAIL = ?", array('ss',$firstName, $email), true);
    }

    if($phone!=$resultat['PHONE']){
        execSQL("UPDATE customer_referential set PHONE=? where EMAIL = ?", array('ss',$phone, $email), true);
    }

    if($fleetManager == "Y" && strpos($resultat['ACCESS_RIGHTS'], 'fleetManager') === false){
        $newAccessRights = $resultat['ACCESS_RIGHTS'].",fleetManager";
        execSQL("UPDATE customer_referential set ACCESS_RIGHTS=? where EMAIL = ?", array('ss', $newAccessRights, $email), true);
    }else if($fleetManager == "N" && strpos($resultat['ACCESS_RIGHTS'], 'fleetManager') !== false){
      $newAccessRights = str_replace(",fleetManager", "", $resultat['ACCESS_RIGHTS']);
      execSQL("UPDATE customer_referential set HEU_MAJ=CURRENT_TIMESTAMP, ACCESS_RIGHTS=? where EMAIL = ?", array('ss', $newAccessRights, $email), true);
    }

    foreach($_POST as $name => $value){

        if($name=="buildingAccess"){
          foreach($_POST['buildingAccess'] as $valueInArray){
            $result= execSQL("SELECT * FROM customer_building_access WHERE EMAIL=? and BUILDING_CODE=?", array('ss', $email, $valueInArray), false);
            if(count($result)==0){
              execSQL("INSERT INTO  customer_building_access (USR_MAJ, EMAIL, BUILDING_CODE, STAANN) VALUES ('mykameo',?, ?, '')", array('ss', $email, $valueInArray), true);
            }else{
              execSQL("UPDATE  customer_building_access set STAANN='', USR_MAJ='mykameo', TIMESTAMP=CURRENT_TIMESTAMP WHERE EMAIL=? and BUILDING_CODE=?", array('ss', $email, $valueInArray), true);
            }

            $result= execSQL("SELECT * FROM building_access WHERE COMPANY=?", array('s', $company), false);
            foreach($result as $row){
              $presence=false;
              foreach($_POST['buildingAccess'] as $valueInArray2){
                  if($row['BUILDING_REFERENCE']==$valueInArray2){
                      $presence=true;
                  }
              }
              if($presence==false){
                $building=$row['BUILDING_REFERENCE'];
                $result2=execSQL("select * from customer_building_access where EMAIL = ? and BUILDING_CODE=?", array('ss', $email, $building), false);
                $result2 = mysqli_query($conn, $sql);
                if(count($result2)==1){
                  execSQL("update customer_building_access set STAANN='D', USR_MAJ='mykameo', TIMESTAMP=CURRENT_TIMESTAMP where EMAIL = ? and BUILDING_CODE=?", array('ss', $email, $building), true);
                }
              }
            }
          }
        }

        if($name=="bikeAccess"){
            foreach($_POST['bikeAccess'] as $valueInArray){
                $resultat= execSQL("SELECT TYPE FROM customer_bike_access WHERE BIKE_ID=?", array('i', $valueInArray), false);
                if(count($resultat)==1){
                  $type_bike=$resultat[0][TYPE];
                }else{
                  $type_bike='partage';
                }
                if ($type_bike != 'personnel'){
                    include 'connexion.php';
                    $sql= "SELECT * FROM customer_bike_access WHERE EMAIL='$email' and BIKE_ID='$valueInArray'";
                    if ($conn->query($sql) === FALSE) {
                        $response = array ('response'=>'error', 'message'=> $conn->error);
                        echo json_encode($response);
                        die;
                    }
                    $result = mysqli_query($conn, $sql);
                    $resultat = mysqli_fetch_assoc($result);
                    $type_bike=$resultat['TYPE'];
                    $length = $result->num_rows;
                    $conn->close();

                    if($length==0){
                        include 'connexion.php';
                        $sql= "INSERT INTO  customer_bike_access (USR_MAJ, EMAIL, BIKE_ID, TYPE, STAANN) VALUES ('mykameo','$email', '$valueInArray', 'partage', '')";
                        if ($conn->query($sql) === FALSE) {
                            $response = array ('response'=>'error', 'message'=> $conn->error);
                            echo json_encode($response);
                            die;
                        }
                        $conn->close();
                    }

                    include 'connexion.php';
                    $sql= "SELECT * FROM customer_bike_access WHERE EMAIL='$email' and BIKE_ID='$valueInArray' and STAANN='D'";
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
                        $sql= "UPDATE  customer_bike_access set STAANN='' WHERE EMAIL='$email' and BIKE_ID='$valueInArray'";
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
                            if($row['ID']==$valueInArray2){
                                $presence=true;

                            }
                        }
                        if($presence==false){
                            $bikeID=$row['ID'];

                            include 'connexion.php';
                            $sql="select * from customer_bike_access where EMAIL = '$email' and BIKE_ID='$bikeID'";

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
                                $sql="update customer_bike_access set STAANN='D', USR_MAJ='mykameo', TIMESTAMP=CURRENT_TIMESTAMP where EMAIL = '$email' and BIKE_ID='$bikeID'";

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
    }
  if(!isset(($_POST['buildingAccess']))){
    execSQL("update customer_building_access set STAANN='D', USR_MAJ='mykameo', TIMESTAMP=CURRENT_TIMESTAMP where EMAIL = ?", array('s', $email), true);
  }

  if(!isset(($_POST['bikeAccess']))){
    execSQL("update customer_bike_access set STAANN='D', USR_MAJ='mykameo', TIMESTAMP=CURRENT_TIMESTAMP where EMAIL = ?", array('s', $email), true);
  }
  successMessage("SM0003");
}
else
{
	errorMessage("ES0012");
}

?>
