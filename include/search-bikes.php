<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');



if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

include 'globalfunctions.php';

$frame_number=$_POST['search-bikes-form-frame-number'];
$date=$_POST['search-bikes-form-day'];

$intake_hour=$_POST['search-bikes-form-intake-hour'];

$intake_building=$_POST['search-bikes-form-intake-building'];
$dayAndMonth=explode("-", $date);
$day_intake=$dayAndMonth[0];
$month_intake=$dayAndMonth[1];

$date=$_POST['search-bikes-form-day-deposit'];
$deposit_hour=$_POST['search-bikes-form-deposit-hour'];
$deposit_building=$_POST['search-bikes-form-deposit-building'];
$dayAndMonth=explode("-", $date);
$day_deposit=$dayAndMonth[0];
$month_deposit=$dayAndMonth[1];


$x = explode('h', $intake_hour);

$intake_hour=$x[0];
$intake_minute=$x[1];

$x = explode('h', $deposit_hour);
$deposit_hour=$x[0];
$deposit_minute=$x[1];

$dateStart=new DateTime();
$dateStart->setDate(2019, $month_intake, $day_intake);
$dateStart->setTime($intake_hour, $intake_minute);

$dateEnd=new DateTime();
$dateEnd->setDate(2019, $month_deposit, $day_deposit);
$dateEnd->setTime($deposit_hour, $deposit_minute);

$timestamp_start_booking=mktime($intake_hour, $intake_minute, 0, $month_intake, $day_intake, 2019);
$timestamp_end_booking=mktime($deposit_hour, $deposit_minute, 0, $month_deposit, $day_deposit, 2019);

//gérer le error handling de mktime !




if( $_SERVER['REQUEST_METHOD'] == 'POST' && $intake_building != NULL & $timestamp_start_booking != NULL && $deposit_building != NULL && $timestamp_end_booking != NULL ) {

    
        
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
    $sql= "select aa.FRAME_NUMBER from reservations aa where aa.STAANN!='D' and aa.FRAME_NUMBER like '$frame_number%' and not exists (select 1 from reservations bb where aa.FRAME_NUMBER=bb.FRAME_NUMBER and bb.STAANN!='D' AND ((bb.DATE_START>='$timestamp_start_booking' and bb.DATE_START<='$timestamp_end_booking') OR (bb.DATE_START<='$timestamp_start_booking' and bb.DATE_END>'$timestamp_start_booking'))) group by FRAME_NUMBER";    

    
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
    $response=array('response'=>'success', 'timestampStartBooking' => $timestamp_start_booking, 'timestampEndBooking' => $timestamp_end_booking, 'buildingStart' => $intake_building, 'buildingEnd' => $deposit_building, 'dateStart' => $dateStart, 'dateEnd' => $dateEnd);
    $length=0;
    while($row = mysqli_fetch_array($result))
    {
        $frameNumber=$row['FRAME_NUMBER'];

        $sql2="SELECT max(DATE_END), BUILDING_END FROM reservations WHERE FRAME_NUMBER='$frameNumber' and DATE_END < '$timestamp_end_booking' and STAANN!='D' group by BUILDING_END";
     
        
        if ($conn->query($sql2) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }

        $result2 = mysqli_query($conn, $sql2);        
        $resultat2 = mysqli_fetch_assoc($result2);

        if($resultat2['BUILDING_END'] == $intake_building){

            $sql3="SELECT min(DATE_START), BUILDING_START FROM reservations WHERE FRAME_NUMBER='$frameNumber' and DATE_START > '$timestamp_end_booking' and STAANN!='D' group by BUILDING_START";
            
            if ($conn->query($sql3) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result3 = mysqli_query($conn, $sql3);        
            $resultat3 = mysqli_fetch_assoc($result3);

            if($resultat3['BUILDING_START'] == $deposit_building or $resultat3['BUILDING_START'] == NULL){
                $length++;
                
                $sql4="SELECT * FROM customer_bikes WHERE FRAME_NUMBER='$frameNumber'";
                $result4 = mysqli_query($conn, $sql4);        
                $resultat4 = mysqli_fetch_assoc($result4);           
                $response['bike'][$length]['frameNumber'] = $frameNumber;
                $response['bike'][$length]['type']= $resultat4['TYPE'];
                $type=$resultat4['TYPE'];
                
                $sql5="SELECT * FROM bike_models WHERE ID='$type'";
                $result5 = mysqli_query($conn, $sql5);        
                $resultat5 = mysqli_fetch_assoc($result5);           
                $response['bike'][$length]['typeDescription']= $resultat5['MODEL_FR'];
                
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