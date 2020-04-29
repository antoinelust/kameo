<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

if(!isset($_SESSION))
{
    session_start();
}

include 'globalfunctions.php';


if(isset($_GET['action'])){
    $action=isset($_GET['action']) ? $_GET['action'] : NULL;
    
    if($action=="list"){
        include 'connexion.php';
        $sql="SELECT * FROM customer_bikes WHERE STAANN != 'D'";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $conn->close();
        
        $dossier="../images_bikes/";
        $response=array();
        $i=0;
                
        while($row = mysqli_fetch_array($result)){
            $fichier=$row['FRAME_NUMBER'].'.jpg';
            $frameNumber=$row['FRAME_NUMBER'];
            $bikeID=$row['FRAME_NUMBER'];
            $fichierMini=$row['FRAME_NUMBER'].'_mini.jpg';
            
            if (!file_exists($dossier.$fichier) || !file_exists($dossier.$fichierMini)){                
                $response['bike']['img'][$i]['id']=$bikeID;
                $response['bike']['img'][$i]['frameNumber']=$frameNumber;
                $response['bike']['img'][$i]['path']=$dossier.$fichier;
                
                $i++;
            }
        }
        
        $response['bike']['img']['number']=$i;
        
        $response['response']="success";
        echo json_encode($response);
        die;
        
    }    
}else{
    errorMessage("ES0012");
}


?>
