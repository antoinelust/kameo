<?php
session_cache_limiter('nocache');

header('Expires: ' . gmdate('r', 0));

header('Content-type: application/json');

session_start();

include 'globalfunctions.php';





$action=isset($_POST['action']) ? $_POST['action'] : NULL;
$email=isset($_POST['email']) ? $_POST['email'] : NULL;
$month=isset($_POST['month']) ? $_POST['month'] : NULL;
$date=isset($_POST['timestamp']) ? $_POST['timestamp'] : NULL;
$year=isset($_POST['year']) ? $_POST['year'] : NULL;



if($action=="add"){

    include 'connexion.php';
    $sql= "SELECT * from calendar_manager WHERE EMAIL='$email' AND DATE='$date'";
    if ($conn->query($sql) === FALSE) {

		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
	$result = mysqli_query($conn, $sql);     
    $length = $result->num_rows;

    if($length==0)
    {
        $sql= "INSERT INTO calendar_manager (ID, EMAIL, DATE, STAANN) VALUES ('','$email','$date', '')";

        if ($conn->query($sql) === FALSE) {

            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        } 
        $conn->close();
    }
    else{
        $sql= "UPDATE calendar_manager SET STAANN=' ' WHERE EMAIL='$email' AND DATE='$date'";

        if ($conn->query($sql) === FALSE) {

            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        } 
        $conn->close();
    }

}

if($action=="remove"){

    include 'connexion.php';
    $sql= "UPDATE calendar_manager SET STAANN='D' WHERE EMAIL='$email' AND DATE='$date'";

   	if ($conn->query($sql) === FALSE) {

		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	} 
    $conn->close();
}


if($action=="retrieve"){
    
    include 'connexion.php';
    $dateStart=$year.$month."01";
    $nextMonth=$month+1;
    
    if(strlen($nextMonth)==1){
        $nextMonth="0".$nextMonth;
    }
    
    $dateEnd=$year.$nextMonth."01";

    $sql= "SELECT * FROM calendar_manager WHERE EMAIL='$email' AND DATE>='$dateStart' AND DATE<'$dateEnd' AND STAANN!='D'";
   	if ($conn->query($sql) === FALSE) {

		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	} 
	$result = mysqli_query($conn, $sql); 
    $i=0;
    
    $length = $result->num_rows;
    
    if($length==0){
        $response['days'][0]=0;
    }
    
    $response['length']=$length;
    
    while($row = mysqli_fetch_array($result)){
        $response['days'][$i]=intval(substr($row['DATE'], 6, 2));
        $i++;
    }
    $conn->close();
        
    echo json_encode($response);
    die;

}


if($action=="statistics"){
    
    include 'connexion.php';
    $sql= "SELECT * FROM calendar_manager WHERE EMAIL='$email' AND DATE>'$year' AND STAANN!='D'";

   	if ($conn->query($sql) === FALSE) {

		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	} 
	$result = mysqli_query($conn, $sql); 
    $response['count']=$result->num_rows;
    $conn->close();

    echo json_encode($response);
    die;

}


?>