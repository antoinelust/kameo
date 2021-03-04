<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';
log_inputs();

$bookingID=$_POST['reservationID'];
$response=array();

if($bookingID != NULL)
{
  include 'connexion.php';
  $sql="SELECT dd.ID, dd.TYPE, dd.FRAME_NUMBER, aa.EMAIL, aa.DATE_START_2, aa.DATE_END_2, aa.INITIAL_END_DATE,  bb.BUILDING_FR as building_start_fr, cc.BUILDING_FR as building_end_fr  FROM reservations aa, building_access bb, building_access cc, customer_bikes dd WHERE aa.ID = '$bookingID' AND aa.BUILDING_START=bb.BUILDING_REFERENCE and aa.BUILDING_END=cc.BUILDING_REFERENCE and aa.BIKE_ID=dd.ID";
  if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
  }
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);

  $response['response']="success";
  $response['bikeID']=$row['ID'];
  $response['img']=$row['TYPE'];
  $response['reservationBikeNumber']=$row['FRAME_NUMBER'];
  $response['reservation']['start']=$row['DATE_START_2'];
  $response['reservation']['end']=$row['DATE_END_2'];
  $response['reservation']['initialEndDate']=$row['INITIAL_END_DATE'];
  $response['reservationStartBuilding']=$row['building_start_fr'];
  $response['reservationEndBuilding']=$row['building_end_fr'];
  $response['reservationEmail']=$row['EMAIL'];
  $response['reservationsLogs']=execSQL("SELECT * FROM reservations_details WHERE RESERVATION_ID=?", array('i', $bookingID), false);

  echo json_encode($response);
  die;

}
else
{
	errorMessage("ES0006");
}

?>
