<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');



if(!isset($_SESSION))
{
    session_start();
}

include 'globalfunctions.php';

$email=$_POST['search-bikes-form-email'];
$date=$_POST['search-bikes-form-day'];

$intake_hour=$_POST['search-bikes-form-intake-hour'];

$intake_building=$_POST['search-bikes-form-intake-building'];
$dayAndMonth=explode("-", $date);
$day_intake=$dayAndMonth[0];
$month_intake=$dayAndMonth[1];
$year_intake=$dayAndMonth[2];






$date=$_POST['search-bikes-form-day-deposit'];
$deposit_hour=$_POST['search-bikes-form-deposit-hour'];
$deposit_building=$_POST['search-bikes-form-deposit-building'];


$maxBookingsPerYear=$_POST['search-bikes-form-maxBookingPerYear'];
$maxBookingsPerMonth=$_POST['search-bikes-form-maxBookingPerMonth'];


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

$dateEnd=new DateTime();
$dateEnd->setDate($year_deposit, $month_deposit, $day_deposit);
$dateEnd->setTime($deposit_hour, $deposit_minute);

$timestamp_start_booking=mktime($intake_hour, $intake_minute, 0, $month_intake, $day_intake, $year_intake);
$timestamp_start_booking_2=mktime($intake_hour_2, $intake_minute_2, 0, $month_intake, $day_intake, $year_intake);

$timestamp_end_booking=mktime($deposit_hour, $deposit_minute, 0, $month_deposit, $day_deposit, $year_deposit);



//gérer le error handling de mktime !




if( $_SERVER['REQUEST_METHOD'] == 'POST' && $intake_building != NULL & $timestamp_start_booking != NULL && $deposit_building != NULL && $timestamp_end_booking != NULL ) {


    include 'connexion.php';
    $date1stJanuary=strtotime(date('Y-01-01'));
    $sql="select * from reservations where DATE_START>'$date1stJanuary' and EMAIL='$email' and STAANN != 'D'";

   	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response); error_log(json_encode($response));
		die;
	}
    $result = mysqli_query($conn, $sql);
    $length = $result->num_rows;
    if($length>=$maxBookingsPerYear && $maxBookingsPerYear!='9999'){
        errorMessage("ES0043");
    }



    $date1stOfMonth=strtotime(date('Y-m-01'));
    $sql="select * from reservations where DATE_START>'$date1stOfMonth' and EMAIL='$email' and STAANN != 'D'";
   	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response); error_log(json_encode($response));
		die;
	}



    $result = mysqli_query($conn, $sql);
    $length = $result->num_rows;


    if($length>=$maxBookingsPerMonth && $maxBookingsPerMonth != '9999'){
        errorMessage("ES0044");
    }



    $sql= "select * from bike_building_access aa, customer_bikes bb, customer_referential cc where cc.EMAIL='$email' and bb.STATUS!='KO' and cc.COMPANY=bb.COMPANY and bb.FRAME_NUMBER=aa.BIKE_NUMBER and aa.BUILDING_CODE='$deposit_building'";
   	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response); error_log(json_encode($response));
		die;
	}

    $result = mysqli_query($conn, $sql);
    $length = $result->num_rows;
    if($length == 0){
        errorMessage("ES0027");
    }

    if ($timestamp_start_booking<time()){
        errorMessage("ES0016");
    }

    if ($timestamp_end_booking<time()){
        errorMessage("ES0017");
    }

    if ($timestamp_end_booking<=$timestamp_start_booking){
        errorMessage("ES0018");
    }

    include 'connexion.php';
    $sql= "select aa.FRAME_NUMBER from reservations aa, customer_bikes cc where aa.FRAME_NUMBER=cc.FRAME_NUMBER and cc.STATUS!='KO' and aa.STAANN!='D' and aa.FRAME_NUMBER in (select BIKE_NUMBER from customer_bike_access where EMAIL='$email' and STAANN != 'D') and not exists (select 1 from reservations bb where aa.FRAME_NUMBER=bb.FRAME_NUMBER and bb.STAANN!='D' AND ((bb.DATE_START>='$timestamp_start_booking_2' and bb.DATE_START<='$timestamp_end_booking') OR (bb.DATE_START<='$timestamp_start_booking_2' and bb.DATE_END>'$timestamp_start_booking'))) group by FRAME_NUMBER";

   	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response); error_log(json_encode($response));
		die;
	}
    $result = mysqli_query($conn, $sql);
    $length = $result->num_rows;

    if($length == 0){
        errorMessage("ES0015");
    }

    $bike=array();

    $response=array('response'=>'success', 'timestampStartBooking' => $timestamp_start_booking, 'timestampEndBooking' => $timestamp_end_booking, 'buildingStart' => $intake_building, 'buildingEnd' => $deposit_building, 'dateStart' => $dateStart, 'dateEnd' => $dateEnd);
    $length=0;
    while($row = mysqli_fetch_array($result))
    {
        $frameNumber=$row['FRAME_NUMBER'];

        $sql2="SELECT max(DATE_END), BUILDING_END FROM reservations WHERE FRAME_NUMBER='$frameNumber' and DATE_END < '$timestamp_end_booking' and STAANN!='D' group by BUILDING_END";


        if ($conn->query($sql2) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response); error_log(json_encode($response));
            die;
        }

        $result2 = mysqli_query($conn, $sql2);
        $resultat2 = mysqli_fetch_assoc($result2);

        if($resultat2['BUILDING_END'] == $intake_building){

            $sql3="SELECT min(DATE_START), BUILDING_START FROM reservations WHERE FRAME_NUMBER='$frameNumber' and DATE_START > '$timestamp_end_booking' and STAANN!='D' group by BUILDING_START";

            if ($conn->query($sql3) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response); error_log(json_encode($response));
                die;
            }
            $result3 = mysqli_query($conn, $sql3);
            $resultat3 = mysqli_fetch_assoc($result3);

            if($resultat3['BUILDING_START'] == $deposit_building or $resultat3['BUILDING_START'] == NULL){
                $sql4="SELECT * FROM bike_building_access WHERE BIKE_NUMBER='$frameNumber' and BUILDING_CODE='$deposit_building' and STAANN!='D'";
                if ($conn->query($sql4) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response); error_log(json_encode($response));
                    die;
                }
                $result4 = mysqli_query($conn, $sql4);
                $access = $result4->num_rows;


                if($access==1){
                    $length++;

                    $sql5="SELECT * FROM customer_bikes WHERE FRAME_NUMBER='$frameNumber'";
                    if ($conn->query($sql5) === FALSE) {
                        $response = array ('response'=>'error', 'message'=> $conn->error);
                        echo json_encode($response); error_log(json_encode($response));
                        die;
                    }
                    $result5 = mysqli_query($conn, $sql5);
                    $resultat5 = mysqli_fetch_assoc($result5);
                    $response['bike'][$length]['frameNumber'] = $frameNumber;
                    $response['bike'][$length]['type']= $resultat5['TYPE'];
                    $response['bike'][$length]['size']= $resultat5['SIZE'];
                    $type=$resultat5['TYPE'];
                    $response['bike'][$length]['typeDescription']= $resultat5['MODEL'];

                    include 'connexion.php';
                    $sql6="SELECT * FROM bike_catalog WHERE ID='$type'";
                    if ($conn->query($sql6) === FALSE) {
                        $response = array ('response'=>'error', 'message'=> $conn->error);
                        echo json_encode($response); error_log(json_encode($response));
                        die;
                    }
                    $result6 = mysqli_query($conn, $sql6);
                    if($result6->num_rows == 1){
                        $resultat6 = mysqli_fetch_assoc($result6);
                        $response['bike'][$length]['brand'] = $resultat6['BRAND'];
                        $response['bike'][$length]['model'] = $resultat6['MODEL'];
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

    echo json_encode($response); error_log(json_encode($response));
    die;

}
else{
    errorMessage("ES0012");
}

?>
