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
    if($action == "command"){
        $portoflioID=isset($_POST['ID']) ? $_POST['ID'] : NULL;
        $email=isset($_POST['email']) ? $_POST['email'] : NULL;
        $size=isset($_POST['size']) ? $_POST['size'] : NULL;
        $remark=isset($_POST['remark']) ? $_POST['remark'] : NULL;


        include 'connexion.php';
        $sql="SELECT * FROM client_orders where EMAIL='$email'";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);   
        $length = $result->num_rows;
        $conn->close();   

        if($length>0){
            errorMessage("ES0061");
        }

        include 'connexion.php';
        $sql="INSERT INTO client_orders (USR_MAJ, EMAIL, PORTFOLIO_ID, SIZE, REMARK, STATUS) VALUES('$email', '$email', '$portoflioID', '$size', '$remark', 'new')";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }

        $conn->close();   
        $response['sql']=$sql;
        successMessage("SM0027");
    }
}else if(isset($_GET['action'])){
    
    $action=isset($_GET['action']) ? $_GET['action'] : NULL;
    
    if($action=="list"){
        

        $email=isset($_GET['email']) ? $_GET['email'] : NULL;
        $response=array();
        include 'connexion.php';
        $sql="SELECT * FROM client_orders where EMAIL='$email' and status != 'cancelled'";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $conn->close();
        $length = $result->num_rows;    

        $response['commandNumber']=$length;

        $i=0;

        while($row = mysqli_fetch_array($result)){


            $catalogID=$row['PORTFOLIO_ID'];
            include 'connexion.php';
            $sql="SELECT * FROM bike_catalog where ID='$catalogID'";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result = mysqli_query($conn, $sql);
            $resultat = mysqli_fetch_assoc($result);
            $conn->close();
            $response[$i]['id']=$row['ID'];
            $response[$i]['catalogID']=$catalogID;
            $response[$i]['size']=$row['SIZE'];
            $response[$i]['color']=$row['COLOR'];
            $response[$i]['remark']=$row['REMARK'];
            $response[$i]['status']=$row['STATUS'];
            $response[$i]['brand']=$resultat['BRAND'];
            $response[$i]['model']=$resultat['MODEL'];
            $response[$i]['frameType']=$resultat['FRAME_TYPE'];
            $response[$i]['deliveryDate']=$row['ESTIMATED_DELIVERY_DATE'];
            $response[$i]['deliveryAddress']=$row['DELIVERY_ADDRESS'];
            $response[$i]['testDATE']=$row['TEST_DATE'];
            $response[$i]['testAddress']=$row['TEST_ADDRESS'];
            $i++;
        }
        $response['response']="success";
        echo json_encode($response);
        die;
        
    }
}
?>
