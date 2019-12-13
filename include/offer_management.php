<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';

$action = isset($_POST["action"]) ? $_POST["action"] : NULL;
$requestor = isset($_POST["requestor"]) ? $_POST["requestor"] : NULL;
$company = isset($_POST["company"]) ? $_POST["company"] : NULL;
$title = isset($_POST["title"]) ? $_POST["title"] : NULL;
$description = isset($_POST["description"]) ? $_POST["description"] : NULL;
$type = isset($_POST["type"]) ? date($_POST["type"]) : NULL;
$probability = isset($_POST["probability"]) ? $_POST["probability"] : NULL;
$amount = isset($_POST["amount"]) ? $_POST["amount"] : NULL;
$date = isset($_POST["date"]) ? date($_POST["date"]) : NULL;
$start = isset($_POST["start"]) ? date($_POST["start"]) : NULL;
$end = isset($_POST["end"]) ? date($_POST["end"]) : NULL;
$margin="30";

if($date!=NULL){
    $date="'".$date."'";
}else{
    $date='NULL';
}       

if($start!=NULL){
    $start="'".$start."'";
}else{
    $start='NULL';
}       

if($end!=NULL){
    $end="'".$end."'";
}else{
    $end='NULL';
}       

if(isset($_POST["action"])){
    if($_POST["action"]=="add"){
        include 'connexion.php';
        $sql="INSERT INTO offers (HEU_MAJ, USR_MAJ, TITRE, DESCRIPTION, PROBABILITY, AMOUNT, MARGIN, DATE, START, END, COMPANY, STAANN) VALUES (CURRENT_TIMESTAMP, '$requestor', '$title', '$description', '$probability', '$amount
        ', '$margin', $date, $start, $end, '$company', '')";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }

        $conn->close();   
        $response['sql']=$sql;
        successMessage("SM0017");
    }
} 
else
{
	errorMessage("ES0012");
}
?>
