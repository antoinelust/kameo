<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();

	
include 'include/connexion.php';
$sql="SELECT * from customer_bikes";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}

$result = mysqli_query($conn, $sql);        
while($row = mysqli_fetch_array($result)){
        $dossier = './images_bikes/';
        $fichier = $row['FRAME_NUMBER'].".jpg";
        $fichierMini = $row['FRAME_NUMBER']."_mini.jpg";
        
        if(file_exists($dossier.$fichier)){
            copy($dossier . $fichier, $dossier .$row['ID'].".jpg");            
            unlink($dossier.$fichier) or die("Couldn't delete file");
        }

        if(file_exists($dossier.$fichierMini)){
            echo $fichierMini;
            copy($dossier . $fichierMini, $dossier .$row['ID']."_mini.jpg");                        
            unlink($dossier.$fichierMini) or die("Couldn't delete file");
        }        
    
}



