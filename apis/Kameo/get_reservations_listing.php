<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
require_once 'globalfunctions.php';
require_once 'authentication.php';
$token = getBearerToken();
log_inputs();

$dateStart=isset( $_GET['dateStart'] ) ? new DateTime($_GET['dateStart']) : NULL;
$dateEnd=isset( $_GET['dateEnd'] ) ? new DateTime($_GET['dateEnd']) : NULL;
$dateEnd->setTime(23, 59);

$response=array();

if($dateStart != NULL && $dateEnd != NULL)
{

  $dateStartString=$dateStart->format('Y-m-d H:i');
  $dateEndString=$dateEnd->format('Y-m-d H:i');
  $response['bookings']=execSQL("SELECT tt.*,
    CASE
    	WHEN (tt.STATUS='Open' AND EXISTS (SELECT 1 FROM locking_bikes WHERE locking_bikes.BIKE_ID=tt.BIKE_ID AND locking_bikes.PLACE_IN_BUILDING!='-1')) THEN 'bikeNotTaken'
    	WHEN  (tt.STATUS='Open' AND tt.bookingStarted = 'true' and tt.bookingPassed = 'true' AND NOT EXISTS (SELECT 1 FROM locking_bikes WHERE locking_bikes.BIKE_ID=tt.BIKE_ID AND locking_bikes.PLACE_IN_BUILDING!='-1')) THEN 'bikeTaken'
      ELSE tt.STATUS END as 'status'
    FROM (SELECT dd.ID, dd.BIKE_ID, cc.FRAME_NUMBER, cc.MODEL, dd.STATUS, ee.BUILDING_FR as building_start_fr, gg.BUILDING_FR as building_end_fr, ee.BUILDING_EN, ee.BUILDING_NL, dd.EMAIL, dd.DATE_START_2, dd.DATE_END_2, CASE WHEN dd.DATE_START_2 < CURRENT_TIMESTAMP THEN 'true' ELSE 'false' END as 'bookingStarted', CASE WHEN dd.DATE_END_2 < CURRENT_TIMESTAMP THEN 'true' ELSE 'false' END as 'bookingPassed' FROM customer_bikes cc, reservations dd, building_access ee, building_access gg where cc.COMPANY=(select ff.COMPANY from customer_referential ff where TOKEN=?) AND cc.ID=dd.BIKE_ID and dd.STAANN!='D' and dd.DATE_START_2>? and dd.DATE_START_2<=? and dd.BUILDING_START=ee.BUILDING_REFERENCE and dd.BUILDING_END=gg.BUILDING_REFERENCE ORDER BY DATE_START_2) as tt", array('sss', $token, $dateStartString, $dateEndString), false);

	echo json_encode($response);
  die;
}
else
{
    if($email==NULL){
        errorMessage("ES0006");
    }else{
        errorMessage("ES0012");
    }

}

?>
