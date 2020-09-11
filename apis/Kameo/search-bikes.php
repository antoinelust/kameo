<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

if(!isset($_SESSION))
{
    session_start();
}

include 'globalfunctions.php';

$email=htmlspecialchars($_POST['search-bikes-form-email']);
$date=htmlspecialchars($_POST['search-bikes-form-day']);


$intake_hour=htmlspecialchars($_POST['search-bikes-form-intake-hour']);

if (isset($_POST['search-bikes-form-intake-building']))
	$intake_building=htmlspecialchars($_POST['search-bikes-form-intake-building']);
else
	$intake_building='';
if (isset($_POST['search-bikes-form-intake-building']))				/** TEST ! **/
	$deposit_building=htmlspecialchars($_POST['search-bikes-form-deposit-building']);
else
	$deposit_building='';

$dayAndMonth=explode("-", $date);
$day_intake=$dayAndMonth[0];
$month_intake=$dayAndMonth[1];
$year_intake=$dayAndMonth[2];


$date=htmlspecialchars($_POST['search-bikes-form-day-deposit']);
$deposit_hour=htmlspecialchars($_POST['search-bikes-form-deposit-hour']);


$maxBookingsPerYear=htmlspecialchars($_POST['search-bikes-form-maxBookingPerYear']);
$maxBookingsPerMonth=htmlspecialchars($_POST['search-bikes-form-maxBookingPerMonth']);


$dayAndMonth=explode("-", $date);
$day_deposit=$dayAndMonth[0];
$month_deposit=$dayAndMonth[1];
$year_deposit=$dayAndMonth[2];



$x = explode('h', $intake_hour);

$intake_hour=$x[0];
$intake_minute=$x[1];


if($intake_minute=='0'){
    $intake_hour_2=$intake_hour-1;
    $intake_minute_2=45;
}else{
    $intake_minute_2=0;
    $intake_hour_2=$intake_hour;
}

$x = explode('h', $deposit_hour);
$deposit_hour=$x[0];
$deposit_minute=$x[1];

$dateStart=new DateTime();
$dateStart->setDate($year_intake, $month_intake, $day_intake);
$dateStart->setTime($intake_hour, $intake_minute);

$dateStart2=new DateTime();
$dateStart2->setDate($year_intake, $month_intake, $day_intake);
$dateStart2->setTime($intake_hour_2, $intake_minute_2);

$dateEnd=new DateTime();
$dateEnd->setDate($year_deposit, $month_deposit, $day_deposit);
$dateEnd->setTime($deposit_hour, $deposit_minute);


$dateStartString=$dateStart->format('Y-m-d H:i');
$dateStart2String=$dateStart2->format('Y-m-d H:i');
$dateEndString=$dateEnd->format('Y-m-d H:i');

//gérer le error handling de mktime !

$response = array ('response'=>'error', 'message'=> $deposit_building);
echo json_encode($response);
die;


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

    if ($dateStart< new DateTime('NOW')){
        errorMessage("ES0016");
    }

    if ($dateEnd<new DateTime('NOW')){
        errorMessage("ES0017");
    }

    if ($dateEnd<=$dateStart){
        errorMessage("ES0018");
    }

    include 'connexion.php';
    $sql= "select cc.ID from reservations aa, customer_bikes cc where aa.BIKE_ID=cc.ID and cc.STATUS!='KO' and aa.STAANN!='D' and cc.ID in (select BIKE_ID from customer_bike_access aa where EMAIL='$email' and STAANN != 'D') and not exists (select 1 from reservations bb where aa.BIKE_ID=bb.BIKE_ID and bb.STAANN!='D' AND ((bb.DATE_START_2>='$dateStart2String' and bb.DATE_START_2<='$dateEndString') OR (bb.DATE_START_2<='$dateStart2String' and bb.DATE_END_2>'$dateStart2String'))) group by ID";

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

        $sql2="SELECT max(DATE_END_2), BUILDING_END FROM reservations WHERE BIKE_ID='$bikeID' and DATE_END_2 < '$dateEndString' and STAANN!='D' group by BUILDING_END";


        if ($conn->query($sql2) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }

        $result2 = mysqli_query($conn, $sql2);
        $resultat2 = mysqli_fetch_assoc($result2);

        if($resultat2['BUILDING_END'] == $intake_building){

            $sql3="SELECT min(DATE_START_2), BUILDING_START FROM reservations WHERE BIKE_ID='$bikeID' and DATE_START_2 > '$dateEndString' and STAANN!='D' group by BUILDING_START";

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
                    $response['bike'][$length]['bikeID'] = $bikeID;
                    $response['bike'][$length]['frameNumber'] = $resultat5['FRAME_NUMBER'];
                    $response['bike'][$length]['type']= $resultat5['TYPE'];
                    $response['bike'][$length]['size']= $resultat5['SIZE'];
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
                        $response['bike'][$length]['brand'] = $resultat6['BRAND'];
                        $response['bike'][$length]['model'] = $resultat6['MODEL'];
                        $response['bike'][$length]['frameType'] = $resultat6['FRAME_TYPE'];
                    }

                    $file=__DIR__.'/images_bikes/'.$bikeID.'jpg';
                    if ((file_exists($file))){
                        $response['bike'][$length]['img']=$bikeID;
                    }else{
                        $response['bike'][$length]['img']=strtolower(str_replace(" ", "-", $resultat6['BRAND']))."_".strtolower(str_replace(" ", "-", $resultat6['MODEL']))."_".strtolower($resultat6['FRAME_TYPE']);
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

    echo json_encode($response);
    die;

}
else{
    errorMessage("ES0012");
}

?>
