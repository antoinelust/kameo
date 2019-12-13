<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';
$id = isset($_POST["id"]) ? $_POST["id"] : NULL;
$action = isset($_POST["action"]) ? $_POST["action"] : NULL;
$company = isset($_POST["company"]) ? $_POST["company"] : NULL;
$user = isset($_POST["user"]) ? $_POST["user"] : NULL;
$title=isset($_POST["title"]) ? addslashes($_POST["title"]) : NULL;
$description=isset($_POST["description"]) ? addslashes($_POST["description"]) : NULL;
$date=isset($_POST["date"]) ? date($_POST["date"]) : NULL;
$date_reminder=isset($_POST["date_reminder"]) ? date($_POST["date_reminder"]) : NULL;
$status=isset($_POST["status"]) ? $_POST["status"] : NULL;
$owner=isset($_POST["owner"]) ? $_POST["owner"] : NULL;
if($date_reminder==''){
    $date_reminder='NULL';
}else{
    $date_reminder="'".$date_reminder."'";
}


if(isset($_GET["company"])){
    $company = isset($_GET["company"]) ? $_GET["company"] : NULL;
    $status = isset($_GET["status"]) ? $_GET["status"] : NULL;
    $user = isset($_GET["user"]) ? $_GET["user"] : NULL;
    $owner = isset($_GET["owner"]) ? $_GET["owner"] : NULL;
    
    include 'connexion.php';
    if($company=="*"){
        $sql="SELECT * FROM company_actions WHERE 1";
    }else{
        $sql="SELECT * FROM company_actions WHERE COMPANY='$company'";
    }
    

    if($status=="TO DO"){
        $sql=$sql." AND STATUS='TO DO'";
    }else if($status=="LATE"){
        $sql=$sql." AND CURRENT_DATE()>DATE_REMINDER AND STATUS = 'TO DO'";
    }
    
    if($owner!='*' && $owner != NULL){
        $sql=$sql." AND OWNER='$owner'";
    }        
    
    $sql=$sql." ORDER BY ID DESC";

	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
    $result = mysqli_query($conn, $sql);        
    $length = $result->num_rows;
    $response['actionNumber']=$length;
    
    $currentDate= new DateTime();
    
    $response['user']=$user;
    $i=0;
    $response['response']="success";
    while($row = mysqli_fetch_array($result))
    {
        $response['action'][$i]['id']=$row['ID'];
        $response['action'][$i]['date']=$row['DATE'];
        $response['action'][$i]['title']=$row['TITLE'];
        $response['action'][$i]['description']=$row['DESCRIPTION'];
        $response['action'][$i]['company']=$row['COMPANY'];
        $response['action'][$i]['date_reminder']=$row['DATE_REMINDER'];
        $response['action'][$i]['status']=$row['STATUS'];
        $response['action'][$i]['owner']=$row['OWNER'];
        $ownerTask=$row['OWNER'];
        
        include 'connexion.php';
        $sql2="select * from customer_referential where email='$ownerTask'";
        $result2 = mysqli_query($conn, $sql2);
        $resultat2 = mysqli_fetch_assoc($result2);
        $response['action'][$i]['ownerName']=$resultat2['NOM'];
        $response['action'][$i]['ownerFirstName']=$resultat2['PRENOM'];
        
        
        $response['action'][$i]['id']=$row['ID'];
        $actionDate=new DateTime($row['DATE_REMINDER']);
        if($actionDate<$currentDate){
            $response['action'][$i]['late']=true;
        }else{
            $response['action'][$i]['late']=false;
        }

        $i++;
    }                                                       
    $conn->close();
    
    
    include 'connexion.php';
    $sql="SELECT * FROM company_actions";
    if($owner!='*' && $owner != NULL){
        $sql=$sql." WHERE OWNER='$owner'";
    }        
    
	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
    $result = mysqli_query($conn, $sql);
    $length = $result->num_rows;
    $response['actionNumberTotal']=$length;
    $response['sql1']=$sql;
    $conn->close(); 
    
    include 'connexion.php';
    $sql="SELECT * FROM company_actions WHERE STATUS != 'DONE'";
    if($owner!='*' && $owner != NULL){
        $sql=$sql." AND OWNER='$owner'";
    }
    
	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
    $result = mysqli_query($conn, $sql);        
    $length = $result->num_rows;
    $response['actionNumberNotDone']=$length;
    $conn->close();
        
    include 'connexion.php';
    $sql="SELECT * FROM company_actions WHERE STATUS != 'DONE' AND CURRENT_DATE()>DATE_REMINDER";
    if($owner!='*' && $owner != NULL){
        $sql=$sql." AND OWNER='$owner'";
    }        
	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
    $result = mysqli_query($conn, $sql);        
    $length = $result->num_rows;
    $response['actionNumberLate']=$length;
    $conn->close();
    
    
    include 'connexion.php';
    $sql="SELECT OWNER from company_actions GROUP BY OWNER";
	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
    $result = mysqli_query($conn, $sql);        
    $length = $result->num_rows;
    $response['ownerNumber']=$length;
    $i=0;
    while($row = mysqli_fetch_array($result)){
        $response['owner'][$i]['email']=$row['OWNER'];
        include 'connexion.php';
        $owner=$row['OWNER'];
        $sql2="SELECT * from customer_referential WHERE EMAIL='$owner'";
        $result2 = mysqli_query($conn, $sql2);
        if ($conn->query($sql2) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $resultat2 = mysqli_fetch_assoc($result2);
        $response['owner'][$i]['name']=$resultat2['NOM'];
        $response['owner'][$i]['firstName']=$resultat2['PRENOM'];
        $i++;
        
    }
    
    echo json_encode($response);
    die;    

    
}else if(isset($_GET['id'])){
    $id = isset($_GET["id"]) ? $_GET["id"] : NULL;
    include 'connexion.php';
    $sql="SELECT * FROM company_actions WHERE ID='$id'";     
	if ($conn->query($sql) === FALSE) {
		$response = array ('response'=>'error', 'message'=> $conn->error);
		echo json_encode($response);
		die;
	}
    $result = mysqli_query($conn, $sql);
    $resultat = mysqli_fetch_assoc($result);
    $conn->close();   
    
    $response['sql']=$sql;
    $response['action']['id']=$resultat['ID'];
    $response['action']['date']=$resultat['DATE'];
    $response['action']['title']=$resultat['TITLE'];
    $response['action']['description']=$resultat['DESCRIPTION'];
    $response['action']['company']=$resultat['COMPANY'];
    $response['action']['date_reminder']=$resultat['DATE_REMINDER'];
    $response['action']['status']=$resultat['STATUS'];
    $response['action']['owner']=$resultat['OWNER'];
    echo json_encode($response);
    die;    

} 

else if(isset($_POST["company"])){

    if($action=="create"){
        include 'connexion.php';
        $sql= "INSERT INTO  company_actions (USR_MAJ, HEU_MAJ, COMPANY, DATE, DATE_REMINDER, TITLE, DESCRIPTION, STATUS, OWNER) VALUES ('$user', CURRENT_TIMESTAMP, '$company', '$date', $date_reminder, '$title','$description', '$status', '$owner')";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }

        $conn->close();   
        $response['sql']=$sql;
        successMessage("SM0017");
        
    }else if($action=="update"){
        
        include 'connexion.php';
        $sql= "UPDATE  company_actions SET USR_MAJ='$user', HEU_MAJ=CURRENT_TIMESTAMP, COMPANY='$company', DATE='$date', DATE_REMINDER=$date_reminder, TITLE='$title', DESCRIPTION='$description', STATUS='$status', OWNER='$owner' WHERE ID='$id'";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }

        $conn->close();   
        $response['sql']=$sql;
        successMessage("SM0017");
    }
    
} else
{
	errorMessage("ES0012");
}
?>