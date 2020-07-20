<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

if(!isset($_SESSION))
{
    session_start();
}

include 'globalfunctions.php';

if(isset($_POST['action'])){
    
    
    $action=isset($_POST['action']) ? $_POST['action'] : NULL;    
    if($action=='add'){
        
        $email=isset($_POST['email']) ? $_POST['email'] : NULL;   
        $emailBeneficiary=isset($_POST['emailBeneficiary']) ? $_POST['emailBeneficiary'] : NULL;   
        $message=isset($_POST['message']) ? $_POST['message'] : NULL;   
        $type=isset($_POST['type']) ? $_POST['type'] : NULL;   
        $domain = substr($email, -14);
        
        include 'connexion.php';
        $sql= "INSERT INTO chat (USR_MAJ, EMAIL_USER, EMAIL_DESTINARY, TYPE, MESSAGE) VALUES('$email', '$email', '$emailBeneficiary', '$type', '$message')";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        
        $response['response']="success";
        echo json_encode($response);
        die;
        
    }else if($action=='update'){
        successMessage("SM0003");        
    }else if($action=="delete"){
    }
    
    
}else if(isset($_GET['action'])){
    
    $action=isset($_GET['action']) ? $_GET['action'] : NULL;   
    if($action=='list'){
        $user=isset($_GET['email']) ? $_GET['email'] : NULL;   
        $type=isset($_GET['type']) ? $_GET['type'] : NULL;   

        include 'connexion.php';
        $sql= "SELECT * FROM chat where TYPE='$type' and (EMAIL_USER='$user' OR EMAIL_DESTINARY='$user')";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $length = $result->num_rows;
        $conn->close();
        $response=array();
        $response['response']="success";
        $response['chatNumber']=$length;
        $i=0;
        
        while($row = mysqli_fetch_array($result)){
            $response['chat'][$i]['emailUser']=$row['EMAIL_USER'];
            $emailUser=$row['EMAIL_USER'];
            $response['chat'][$i]['emailDestinary']=$row['EMAIL_DESTINARY'];
            $response['chat'][$i]['message']=$row['MESSAGE'];
            $messageTimestamp=new DateTime($row['MESSAGE_TIMESTAMP']);
            $response['chat'][$i]['messageDate']=$messageTimestamp->format('d/m');
            $response['chat'][$i]['messageHour']=$messageTimestamp->format('h:m');
            include 'connexion.php';
            $sql2= "SELECT * FROM customer_referential where email='$emailUser'";
            if ($conn->query($sql2) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result2 = mysqli_query($conn, $sql2);
            $resultat2 = mysqli_fetch_assoc($result2);
            $conn->close();
            $response['chat'][$i]['firstName']=$resultat2['PRENOM'];
            $response['chat'][$i]['name']=$resultat2['NOM'];
            
            $dossier='../images/images_users/';
            $fichier=strtolower($resultat2['PRENOM']." ".$resultat2['NOM'].".jpg");
            if(file_exists($dossier.$fichier)){
                $response['chat'][$i]['img']=$fichier;
            }else{
                $response['chat'][$i]['img']="none";
            }
            
            $i++;
        }
        
        echo json_encode($response);
        die;
        
    }else if($action=='retrieve'){

        
        echo json_encode($response);
        die;
    }
    
}
else{
    errorMessage("ES0012");
}


?>
