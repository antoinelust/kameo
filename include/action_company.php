<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';

if(isset($_GET["company"])){
    $company = isset($_GET["company"]) ? $_GET["company"] : NULL;
    $user = isset($_GET["user"]) ? $_GET["user"] : NULL;
    include 'connexion.php';
    $sql="SELECT * FROM company_actions WHERE COMPANY='$company'";
    
     
	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
    $result = mysqli_query($conn, $sql);        
    $length = $result->num_rows;
    $response['actionNumber']=$length;
    $response['user']=$user;
    $i=0;
    $response['response']="success";
    while($row = mysqli_fetch_array($result))
    {
        $response['action'][$i]['date']=$row['DATE'];
        $response['action'][$i]['description']=$row['DESCRIPTION'];
        $response['action'][$i]['company']=$row['COMPANY'];
        $response['action'][$i]['date_reminder']=$row['DATE_REMINDER'];
        $response['action'][$i]['status']=$row['STATUS'];
        $i++;
    }                                                       
    $conn->close();
    echo json_encode($response);
    die;    

    
} else if(isset($_POST["company"])){
    $company = isset($_POST["company"]) ? $_POST["company"] : NULL;
    $user = isset($_POST["user"]) ? $_POST["user"] : NULL;
    $description=isset($_POST["description"]) ? $_POST["description"] : NULL;
    $date=isset($_POST["date"]) ? date($_POST["date"]) : NULL;
    $date_reminder=isset($_POST["date_reminder"]) ? date($_POST["date_reminder"]) : NULL;
    $status=isset($_POST["status"]) ? $_POST["status"] : NULL;

    if($date_reminder==''){
        $date_reminder='NULL';
    }else{
        $date_reminder="'".$date_reminder."'";
    }
    
    include 'connexion.php';
    $sql= "INSERT INTO  company_actions (USR_MAJ, HEU_MAJ, COMPANY, DATE, DATE_REMINDER, DESCRIPTION, STATUS) VALUES ('$user', CURRENT_TIMESTAMP, '$company', '$date', $date_reminder, '$description', '$status')";

    
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }

    $conn->close();   
    $response['sql']=$sql;
    successMessage("SM0017");

    
    
} else
{
	errorMessage("ES0012");
}
?>