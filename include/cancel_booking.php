<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';




$bookingID=$_POST['bookingID'];



if($bookingID != NULL)
{
  include 'connexion.php';
  $sql= "update reservations set STAANN='D' where ID='$bookingID'";
  if ($conn->query($sql) === FALSE) {

    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
  }

  /*  //désactivation de la notification de réservation

  include 'connexion.php';
  $sql= "update notifications set STAAN='D' where TYPE = 'reservation' AND TYPE_ITEM='$bookingID'";
  if ($conn->query($sql) === FALSE) {

  $response = array ('response'=>'error', 'message'=> $conn->error);
  echo json_encode($response);
  die;
}*/

    //désactivation de la notification de feedback
    include 'connexion.php';
    $sql= "update notifications set STAAN='D' where TYPE = 'feedback' AND TYPE_ITEM='$bookingID'";
    if ($conn->query($sql) === FALSE) {

      $response = array ('response'=>'error', 'message'=> $conn->error);
      echo json_encode($response);
      die;
    }



  $sql= "update locking_code set STAANN='D', VALID='N' where ID_reservation='$bookingID'";

  if ($conn->query($sql) === FALSE) {

    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
  }
    
  $sql= "update feedbacks set STATUS='CANCELLED' where ID_reservation='$bookingID'";

  if ($conn->query($sql) === FALSE) {

    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
  }
  $conn->close();
    
    
  successMessage("SM0007");
}
else
{
  errorMessage("ES0012");
}

?>
