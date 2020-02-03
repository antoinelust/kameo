<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');


include 'globalfunctions.php';

if(isset($_GET['action'])){
    $action=$_GET['action'];
    if($action=='retrieveBooking'){
        $ID = $_GET["ID"];

        include 'connexion.php';


        $sql = "SELECT * FROM reservations where ID='$ID'";

        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $resultat = mysqli_fetch_assoc($result);
        $conn->close();


        $response = array ('response'=>'success');
        $response['start']=$resultat['DATE_START'];
        $response['end']=$resultat['DATE_END'];
        $response['bikeNumber']=$resultat['FRAME_NUMBER'];
        $response['email']=$resultat['EMAIL'];
        $response['ID']=$resultat['ID'];
        echo json_encode($response);
        die;
    }
    else if($action=='retrieveFeedback'){
        $ID = $_GET["ID"];

        include 'connexion.php';
        $sql = "SELECT * FROM feedbacks where ID_RESERVATION='$ID'";

        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $resultat = mysqli_fetch_assoc($result);
        $conn->close();

        $response = array ('response'=>'success');
        $response['ID']=$resultat['ID'];
        $response['bike']=$resultat['BIKE_NUMBER'];
        $response['note']=$resultat['NOTE'];
        $response['comment']=$resultat['COMMENT'];
        $response['entretien']=$resultat['ENTRETIEN'];
        
        include 'connexion.php';
        $sql = "SELECT * FROM reservations where ID='$ID'";

        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $resultat = mysqli_fetch_assoc($result);
        $conn->close();

        $response['start']=$resultat['DATE_START'];
        $response['end']=$resultat['DATE_END'];
        $response['email']=$resultat['EMAIL'];
                
        echo json_encode($response);
        die;
    }
    else if($action=='list'){

        include 'connexion.php';


        $sql = "SELECT * FROM feedbacks";

        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $conn->close();

        $i=0;
        $response = array ('response'=>'success');
        $response['feedbacksNumber']=$result->num_rows;
        while($row = mysqli_fetch_array($result))
        {
            $IDReservation=$row['ID_RESERVATION'];
            $response['feedback'][$i]['bikeNumber']=$row['BIKE_NUMBER'];
            $response['feedback'][$i]['IDReservation']=$row['ID_RESERVATION'];
            $response['feedback'][$i]['note']=$row['NOTE'];
            $response['feedback'][$i]['comment']=$row['COMMENT'];
            $response['feedback'][$i]['entretien']=$row['ENTRETIEN'];
            
            include 'connexion.php';
            $sql2="SELECT * FROM reservations where ID='$IDReservation'";
            if ($conn->query($sql2) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result2 = mysqli_query($conn, $sql2);
            $resultat2 = mysqli_fetch_assoc($result2);
            $conn->close();
            
            $response['feedback'][$i]['start']=$resultat2['DATE_START'];
            $response['feedback'][$i]['end']=$resultat2['DATE_END'];
            $email=$resultat2['EMAIL'];
            $response['feedback'][$i]['email']=$email;
            
            include 'connexion.php';
            $sql3="SELECT * FROM customer_referential where email='$email'";
            if ($conn->query($sql3) === FALSE){
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result3 = mysqli_query($conn, $sql3);
            $resultat3 = mysqli_fetch_assoc($result3);
            $conn->close();
            
            $response['feedback'][$i]['company']=$resultat3['COMPANY'];
            $i++;
        }
        
        echo json_encode($response);
        die;
        
    }
}else if(isset($_POST['action'])){
    $action=$_POST['action'];
    if($action=='add'){
        $ID=$_POST['ID'];
        $note=$_POST['note'];
        $entretien = isset($_POST["entretien"]) ? "1" : "0";
        $comment = isset($_POST["comment"]) ? $_POST["comment"] : NULL;
        $user = isset($_POST["user"]) ? $_POST["user"] : NULL;
        $bike = isset($_POST["bike"]) ? $_POST["bike"] : NULL;
        include 'connexion.php';
        $sql="select * from feedbacks WHERE ID_RESERVATION='$ID' and STATUS='DONE'";

        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $length = $result->num_rows;
        $conn->close();
        if($length>0){
            errorMessage('ES0055');
        }
        

        if($comment!=NULL){
            $comment="'".$comment."'";
        }else{
            $comment='NULL';
        }    

        
        
        include 'connexion.php';
        $sql="UPDATE feedbacks SET USR_MAJ='$user', HEU_MAJ=CURRENT_TIMESTAMP, BIKE_NUMBER='$bike', ID_RESERVATION='$ID', NOTE='$note', COMMENT='$comment', ENTRETIEN='$entretien'";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $conn->close();
        successMessage("SM0023");
    }
    
}


?>