<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

include 'globalfunctions.php';

global $requestor;
global $email;
global $password_unencrypted;


$email=$_POST['widget-addUser-form-mail'];
$name=$_POST['widget-addUser-form-name'];
$firstName=$_POST['widget-addUser-form-firstname'];
$requestor=$_POST['widget-addUser-form-requestor'];



include 'connexion.php';
$sql="select * from customer_referential where EMAIL='$requestor'";

if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);
$resultat = mysqli_fetch_assoc($result);

$company=$resultat['COMPANY'];
$password_unencrypted="test";
$pass=password_hash($password_unencrypted, PASSWORD_DEFAULT);



include 'connexion.php';
$sql= "INSERT INTO  customer_referential (USR_MAJ, NOM_INDEX, PRENOM_INDEX, NOM, PRENOM, PHONE, POSTAL_CODE, CITY, ADRESS, WORK_ADRESS, WORK_POSTAL_CODE, WORK_CITY, COMPANY, EMAIL, PASSWORD, ADMINISTRATOR, STAANN) VALUES ('mykameo', UPPER('$name'), UPPER('$firstName'), '$name', '$firstName', '', '0', '', '', '', '0', '', '$company', '$email', '$pass', 'N', '')";

if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$conn->close();   



foreach($_POST as $name => $value){
    if($name=="buildingAccess"){
        foreach($_POST['buildingAccess'] as $valueInArray) {
            include 'connexion.php';
            $sql= "INSERT INTO  customer_building_access (USR_MAJ, EMAIL, BUILDING_CODE, STAANN) VALUES ('mykameo','$email', '$valueInArray', '')";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $conn->close();   
        }
    }
        
    if($name=="bikeAccess"){
        foreach($_POST['bikeAccess'] as $valueInArray) {
            include 'connexion.php';
            $sql= "INSERT INTO  customer_bike_access (USR_MAJ, EMAIL, BIKE_NUMBER, TYPE, STAANN) VALUES ('mykameo','$email', '$valueInArray', 'partage', '')";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }        
            $conn->close(); 
        }
    }
    
}
writeMail();

successMessage("SM0008");



function writeMail(){
    global $requestor;
    global $email;
    global $password_unencrypted;

    require_once('php-mailer/PHPMailerAutoload.php');
    $mail = new PHPMailer();

    $mail->IsHTML(true);
    $mail->CharSet = 'UTF-8';
    
    if(substr($_SERVER[REQUEST_URI], 1, 4) != "test" && substr($_SERVER['HTTP_HOST'], 0, 9)!="localhost"){
        $mail->AddAddress($email);
    }else{
        $mail->AddAddress($requestor);        
    }
    
    $mail->From = "info@kameobikes.com";
    $mail->FromName = "Kameo Bikes";

    $mail->AddReplyTo("info@kameobikes.com");
    $subject = "Compte créé pour la plateforme MyKameo ! ";
    $mail->Subject = $subject;

    $body = "Bonjour,<br>. Un compte Kameo Bikes a été créé par ".$requestor." pour votre adresse mail.<br>Vous trouverez ci-dessous les informations de connexion:<ul><li>Login : ".$email."</li><li>Mot de passe : ".$password_unencrypted."</li></ul>.<br>Afin de vous connecter, rendez-vous sur <a href=\"www.kameobikes.com/mykameo.php</a>MyKameo</a> afin de pouvoir utiliser toutes les fonctionnalités offertes par notre plateforme.<br>N'oubliez pas de changer votre mot de passe ! <br><br>L'équipe Kameo Bikes.";
    $mail->Body = $body;



    if(!$mail->Send()) {
        $response = array ('response'=>'error', 'message'=> $mail->ErrorInfo);  
        echo json_encode($response);
        die;
    }    

}

?>
