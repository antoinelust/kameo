<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

if(!isset($_SESSION))
{
    session_start();
}
require_once __DIR__ .'/globalfunctions.php';
require_once __DIR__ .'/authentication.php';
require_once __DIR__ .'/connexion.php';

$action = isset($_POST['action']) ? $_POST['action'] : NULL;

$token = getBearerToken();
if(!get_user_permissions("search", $token)){
  error_message('403');
}
log_inputs($token);

$user_data=getCondition()['conditions'];
$minutesToAdd=$user_data['MINUTES_FOR_AUTOMATIC_CANCEL'];
$time = new DateTime('NOW', new DateTimeZone('Europe/Brussels'));
$time->sub(new DateInterval('PT' . $minutesToAdd . 'M'));
$stamp = $time->format('Y-m-d H:i');

if($user_data['LOCKING'] == "Y"){
  $sql="SELECT aa.ID, bb.MODEL from reservations aa, customer_bikes bb WHERE aa.DATE_START_2 < '$stamp' AND aa.BIKE_ID=bb.ID AND aa.STAANN != 'D' AND aa.STATUS='Open' AND bb.COMPANY = (select COMPANY from customer_referential WHERE TOKEN='$token') AND NOT EXISTS (select 1 FROM locking_bikes WHERE RESERVATION_ID=aa.ID AND PLACE_IN_BUILDING='-1')";
  if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
  }
  $resultSendMail=mysqli_query($conn, $sql);
  while($row = mysqli_fetch_array($resultSendMail)){
    $ID=$row['ID'];
    $customName = $row['MODEL'];
    $sql="UPDATE reservations SET HEU_MAJ=CURRENT_TIMESTAMP, STAANN = 'D', USR_MAJ='script' WHERE ID='$ID'";
    if ($conn->query($sql) === FALSE) {
      $response = array ('response'=>'error', 'message'=> $conn->error);
      echo json_encode($response);
      die;
    }
    include 'sendMailCancellation.php';
  }
}
if($user_data['LOCKING'] == 'Y'){
  $sql = "SELECT COUNT(1) as SOMME from boxes aa, customer_referential bb WHERE aa.COMPANY=bb.COMPANY and bb.TOKEN='$token'";
  if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
   }
  $result = mysqli_query($conn, $sql);
  $resultat = mysqli_fetch_assoc($result);
  $boxesNumber = $resultat['SOMME'];
}else{
  $boxesNumber = 0;
}

$resultat = execSQL("SELECT EMAIL FROM customer_referential WHERE TOKEN='$token'", array(), false);
$email = $resultat[0]['EMAIL'];
$date=htmlspecialchars($_POST['intakeDay']);
$intake_hour=htmlspecialchars($_POST['intakeHour']);

if (isset($_POST['intakeBuilding']))
	$intake_building=htmlspecialchars($_POST['intakeBuilding']);
else
	$intake_building='';
if (isset($_POST['depositBuilding']))
	$deposit_building=htmlspecialchars($_POST['depositBuilding']);
else
	$deposit_building='';

$dayAndMonth=explode("-", $date);
$day_intake=intval($dayAndMonth[0]);
$month_intake=intval($dayAndMonth[1]);
$year_intake=intval($dayAndMonth[2]);

$date=htmlspecialchars($_POST['depositDay']);
$deposit_hour=htmlspecialchars($_POST['depositHour']);

$maxBookingsPerYear=$user_data['MAX_BOOKINGS_YEAR'];
$maxBookingsPerMonth=$user_data['MAX_BOOKINGS_MONTH'];


$dayAndMonth=explode("-", $date);
$day_deposit=intval($dayAndMonth[0]);
$month_deposit=intval($dayAndMonth[1]);
$year_deposit=intval($dayAndMonth[2]);



$x = explode('h', $intake_hour);

$intake_hour=intval($x[0]);
$intake_minute=intval($x[1]);


if($intake_minute=='0'){
    $intake_hour_2=$intake_hour-1;
    $intake_minute_2=45;
}else{
    $intake_minute_2=$intake_minute-15;
    $intake_hour_2=$intake_hour;
}

$x = explode('h', $deposit_hour);
$deposit_hour=intval($x[0]);
$deposit_minute=intval($x[1]);


$dateStart=new DateTime('NOW', new DateTimeZone('Europe/Brussels'));
$dateStart->setDate($year_intake, $month_intake, $day_intake);
$dateStart->setTime($intake_hour, $intake_minute);

$dateStart2=new DateTime('NOW', new DateTimeZone('Europe/Brussels'));
$dateStart2->setDate($year_intake, $month_intake, $day_intake);
$dateStart2->setTime($intake_hour_2, $intake_minute_2);

$dateEnd=new DateTime('NOW', new DateTimeZone('Europe/Brussels'));
$dateEnd->setDate($year_deposit, $month_deposit, $day_deposit);
$dateEnd->setTime($deposit_hour, $deposit_minute);

$dateStartString=$dateStart->format('Y-m-d H:i');
$dateStart2String=$dateStart2->format('Y-m-d H:i');
$dateEndString=$dateEnd->format('Y-m-d H:i');


//gérer le error handling de mktime !


if( $_SERVER['REQUEST_METHOD'] == 'POST' && $intake_building != NULL & $dateStart != NULL && $deposit_building != NULL && $dateEnd != NULL ) {


    include 'connexion.php';
    $date1stJanuary=date('Y-01-01');
    $sql="select * from reservations where DATE_START_2>'$date1stJanuary' and EMAIL='$email' and STAANN != 'D'";

   	if ($conn->query($sql) === FALSE) {
  		$response = array ('response'=>'error', 'message'=> $conn->error);
  		echo json_encode($response);
  		die;
	  }
    $result = mysqli_query($conn, $sql);
    $length = $result->num_rows;
    if($length>=$maxBookingsPerYear && $maxBookingsPerYear!='9999'){
        errorMessage("ES0043");
    }



    $date1stOfMonth=date('Y-m-01');
    $sql="select * from reservations where DATE_START_2>'$date1stOfMonth' and EMAIL='$email' and STAANN != 'D'";
   	if ($conn->query($sql) === FALSE) {
  		$response = array ('response'=>'error', 'message'=> $conn->error);
  		echo json_encode($response);
  		die;
	   }



    $result = mysqli_query($conn, $sql);
    $length = $result->num_rows;


    if($length>=$maxBookingsPerMonth && $maxBookingsPerMonth != '9999'){
        errorMessage("ES0044");
    }



    $sql= "select * from bike_building_access aa, customer_bikes bb, customer_referential cc where cc.EMAIL='$email' and bb.STATUS!='KO' and cc.COMPANY=bb.COMPANY and bb.ID=aa.BIKE_ID and aa.BUILDING_CODE='$deposit_building'";

    if ($conn->query($sql) === FALSE) {
  		$response = array ('response'=>'error', 'message'=> $conn->error);
  		echo json_encode($response);
  		die;
	  }

    $result = mysqli_query($conn, $sql);
    $length = $result->num_rows;
    if($length == 0){
      errorMessage("ES0027");
    }

    if ($action != "replaceBooking" && $dateStart < (new DateTime('NOW', new DateTimeZone('Europe/Brussels')))){
      errorMessage("ES0016");
    }

    if ($action != "replaceBooking" && $dateEnd< (new DateTime('NOW', new DateTimeZone('Europe/Brussels')))){
      errorMessage("ES0017");
    }


    if ($dateEnd<=$dateStart){
      errorMessage("ES0018");
    }

    include 'connexion.php';

    if($user_data['LOCKING'] == "Y" && $action != 'replaceBooking'){
      $sql="select count(1) as SOMME from reservations where EMAIL='$email' and STAANN != 'D' and STATUS='Open' and ((DATE_START_2 <= '$dateStartString' and DATE_END_2 >= '$dateStartString') OR (DATE_START_2<='$dateEndString' AND DATE_END_2>='$dateEndString'))";
      if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
      }
      $result = mysqli_query($conn, $sql);
      $resultat = mysqli_fetch_assoc($result);
      if($resultat['SOMME']>0){
        errorMessage("ES0062");
      }
    }

    if($user_data['LOCKING']=="Y"){
      if($user_data['LOCKING']=="Y" && ($boxesNumber > 1 || $action == "replaceBooking")){
        $sql= "select cc.ID from reservations aa, customer_bikes cc where aa.BIKE_ID=cc.ID and cc.STATUS!='KO' and aa.STAANN!='D' and cc.ID in (select BIKE_ID from customer_bike_access aa where EMAIL='$email' and STAANN != 'D') and not exists (select 1 from reservations bb where aa.BIKE_ID=bb.BIKE_ID and bb.STAANN!='D' AND bb.STATUS != 'Closed') group by ID";
      }else{
        $sql= "select cc.ID from reservations aa, customer_bikes cc where aa.BIKE_ID=cc.ID and cc.STATUS!='KO' and aa.STAANN!='D' and cc.ID in (select BIKE_ID from customer_bike_access aa where EMAIL='$email' and STAANN != 'D') and not exists (select 1 from reservations bb where aa.BIKE_ID=bb.BIKE_ID and bb.STAANN!='D' AND bb.STATUS ='Open' AND ((bb.DATE_START_2>='$dateStart2String' and bb.DATE_START_2<='$dateEndString') OR (bb.DATE_START_2<='$dateStart2String' and bb.DATE_END_2>'$dateStart2String'))) and not exists (SELECT 1 from reservations dd WHERE dd.STAANN !='D' AND dd.BIKE_ID=cc.ID AND dd.STATUS='Open' AND dd.EMAIL='$email') group by ID";
      }
    }else{
      $sql= "select cc.ID from reservations aa, customer_bikes cc where aa.BIKE_ID=cc.ID and cc.STATUS!='KO' and aa.STAANN!='D' and cc.ID in (select BIKE_ID from customer_bike_access aa where EMAIL='$email' and STAANN != 'D') and not exists (select 1 from reservations bb where aa.BIKE_ID=bb.BIKE_ID and bb.STAANN!='D' AND ((bb.DATE_START_2>='$dateStart2String' and bb.DATE_START_2<='$dateEndString') OR (bb.DATE_START_2<='$dateStart2String' and bb.DATE_END_2>'$dateStart2String'))) group by ID";
    }

   	if ($conn->query($sql) === FALSE) {
  		$response = array ('response'=>'error', 'message'=> $conn->error);
  		echo json_encode($response);
  		die;
	  }

    $result = mysqli_query($conn, $sql);
    $length = $result->num_rows;

    if($length == 0){
        errorMessage("ES0015");
    }

    $bike=array();

    $response=array('response'=>'success', 'buildingStart' => $intake_building, 'buildingEnd' => $deposit_building, 'dateStart' => $dateStartString, 'dateEnd' => $dateEndString);
    $length=0;
    while($row = mysqli_fetch_array($result))
    {
        $bikeID=$row['ID'];

        $sql2="SELECT DATE_END_2, BUILDING_END FROM reservations WHERE BIKE_ID='$bikeID' and DATE_END_2 < '$dateEndString' and STAANN!='D' ORDER BY DATE_END_2 DESC LIMIT 1";
        if ($conn->query($sql2) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }

        $result2 = mysqli_query($conn, $sql2);
        $resultat2 = mysqli_fetch_assoc($result2);

        if($resultat2['BUILDING_END'] == $intake_building){

            if($user_data['LOCKING']=="Y" && $boxesNumber > 1){
              $sql4="SELECT * FROM bike_building_access WHERE BIKE_ID='$bikeID' and BUILDING_CODE='$deposit_building' and STAANN!='D'";

              if ($conn->query($sql4) === FALSE) {
                  $response = array ('response'=>'error7', 'message'=> $conn->error);
                  echo json_encode($response);
                  die;
              }
              $result4 = mysqli_query($conn, $sql4);
              $access = $result4->num_rows;

              if($access==1){
                  $length++;

                  $sql5="SELECT * FROM customer_bikes WHERE ID='$bikeID'";
                  if ($conn->query($sql5) === FALSE) {
                      $response = array ('response'=>'error', 'message'=> $conn->error);
                      echo json_encode($response);
                      die;
                  }
                  $result5 = mysqli_query($conn, $sql5);
                  $resultat5 = mysqli_fetch_assoc($result5);
                  $response['bike'][$length-1]['bikeID'] = $bikeID;
                  $response['bike'][$length-1]['frameNumber'] = $resultat5['FRAME_NUMBER'];
                  $response['bike'][$length-1]['type']= $resultat5['TYPE'];
                  $response['bike'][$length-1]['size']= $resultat5['SIZE'];
                  $type=$resultat5['TYPE'];
                  $response['bike'][$length]['typeDescription']= $resultat5['MODEL'];

                  include 'connexion.php';
                  $sql6="SELECT * FROM bike_catalog WHERE ID='$type'";
                  if ($conn->query($sql6) === FALSE) {
                      $response = array ('response'=>'error', 'message'=> $conn->error);
                      echo json_encode($response);
                      die;
                  }
                  $result6 = mysqli_query($conn, $sql6);
                  if($result6->num_rows == 1){
                      $resultat6 = mysqli_fetch_assoc($result6);
                      $response['bike'][$length-1]['img']=$resultat6['ID'];
                      $response['bike'][$length-1]['brand'] = $resultat6['BRAND'];
                      $response['bike'][$length-1]['model'] = $resultat6['MODEL'];
                      $response['bike'][$length-1]['frameType'] = $resultat6['FRAME_TYPE'];
                  }else{
                      $length--;
                  }
              }
            }else{
              $sql3="SELECT DATE_START_2, BUILDING_START FROM reservations WHERE BIKE_ID='$bikeID' and DATE_START_2 > '$dateEndString' and STAANN!='D' ORDER BY DATE_START_2 LIMIT 1";
              if ($conn->query($sql3) === FALSE) {
                  $response = array ('response'=>'error', 'message'=> $conn->error);
                  echo json_encode($response);
                  die;
              }
              $result3 = mysqli_query($conn, $sql3);
              $resultat3 = mysqli_fetch_assoc($result3);

              if($resultat3['BUILDING_START'] == $deposit_building or $resultat3['BUILDING_START'] == NULL){
                  $sql4="SELECT * FROM bike_building_access WHERE BIKE_ID='$bikeID' and BUILDING_CODE='$deposit_building' and STAANN!='D'";
                  if ($conn->query($sql4) === FALSE) {
                      $response = array ('response'=>'error7', 'message'=> $conn->error);
                      echo json_encode($response);
                      die;
                  }
                  $result4 = mysqli_query($conn, $sql4);
                  $access = $result4->num_rows;

                  if($access==1){
                      $length++;

                      $sql5="SELECT * FROM customer_bikes WHERE ID='$bikeID'";
                      if ($conn->query($sql5) === FALSE) {
                          $response = array ('response'=>'error', 'message'=> $conn->error);
                          echo json_encode($response);
                          die;
                      }
                      $result5 = mysqli_query($conn, $sql5);
                      $resultat5 = mysqli_fetch_assoc($result5);
                      $response['bike'][$length-1]['bikeID'] = $bikeID;
                      $response['bike'][$length-1]['frameNumber'] = $resultat5['FRAME_NUMBER'];
                      $response['bike'][$length-1]['type']= $resultat5['TYPE'];
                      $response['bike'][$length-1]['size']= $resultat5['SIZE'];
                      $type=$resultat5['TYPE'];
                      $response['bike'][$length-1]['typeDescription']= $resultat5['MODEL'];

                      include 'connexion.php';
                      $sql6="SELECT * FROM bike_catalog WHERE ID='$type'";
                      if ($conn->query($sql6) === FALSE) {
                          $response = array ('response'=>'error', 'message'=> $conn->error);
                          echo json_encode($response);
                          die;
                      }
                      $result6 = mysqli_query($conn, $sql6);
                      if($result6->num_rows == 1){
                          $resultat6 = mysqli_fetch_assoc($result6);
                          $file=__DIR__.'/images_bikes/'.$resultat6['ID'].'jpg';
                          $response['bike'][$length-1]['img']=$resultat6['ID'];
                          $response['bike'][$length-1]['brand'] = $resultat6['BRAND'];
                          $response['bike'][$length-1]['model'] = $resultat6['MODEL'];
                          $response['bike'][$length-1]['frameType'] = $resultat6['FRAME_TYPE'];
                      }else{
                          $length--;
                      }
                  }
              }
            }
        }
    }

    $response['length']=$length;


    if($length==0)
    {
        errorMessage("ES0015");
    }


    if ($_SESSION['langue']=='fr')
  	{
  		$response['message']= "Veuillez choisir votre vélo ci-dessous.";
  	}
  	elseif ($_SESSION['langue']=='en')
  	{
  		$response['message']= "Please select your bike here below.";
  	}
  	elseif ($_SESSION['langue']=='nl')
  	{
  		$response['message']= "Selecteer hieronder je fiets.";
  	}
  	else
  	{
  		$response['message']= "Veuillez choisir votre vélo ci-dessous.";
  	}
    log_output($response);
    echo json_encode($response);
    die;
}else{
    errorMessage("ES0012");
}
?>
