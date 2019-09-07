<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';




$frameNumber=$_POST['widget-updateBikeStatusAdmin-form-bikeNumber'];
$model=$_POST['widget-updateBikeStatusAdmin-form-model'];
$size=isset($_POST['widget-updateBikeStatusAdmin-form-size']) ? $_POST['widget-updateBikeStatusAdmin-form-size'] : NULL;
$frameReference=isset($_POST['widget-updateBikeStatusAdmin-form-frameReference']) ? $_POST['widget-updateBikeStatusAdmin-form-frameReference'] : NULL;
$company=isset($_POST['widget-updateBikeStatusAdmin-form-company']) ? $_POST['widget-updateBikeStatusAdmin-form-company'] : NULL;
$contractStart=isset($_POST['widget-updateBikeStatusAdmin-form-contractStart']) ? date($_POST['widget-updateBikeStatusAdmin-form-contractStart']) : NULL;
$contractEnd=isset($_POST['widget-updateBikeStatusAdmin-form-contractEnd']) ? date($_POST['widget-updateBikeStatusAdmin-form-contractEnd']) : NULL;
$assistanceReference=isset($_POST['widget-updateBikeStatusAdmin-form-assistanceReference']) ? $_POST['widget-updateBikeStatusAdmin-form-assistanceReference'] : NULL;
$billing=isset($_POST['widget-updateBikeStatusAdmin-form-billing']) ? 'Y' : 'N';
$billingPrice=isset($_POST['widget-updateBikeStatusAdmin-form-billingPrice']) ? $_POST['widget-updateBikeStatusAdmin-form-billingPrice'] : NULL;
$billingGroup=isset($_POST['widget-updateBikeStatusAdmin-form-billingGroup']) ? $_POST['widget-updateBikeStatusAdmin-form-billingGroup'] : NULL;
$user=isset($_POST['widget-updateBikeStatusAdmin-form-user']) ? $_POST['widget-updateBikeStatusAdmin-form-user'] : NULL;
    
    
if(isset($_FILES['widget-updateBikeStatusAdmin-form-picture'])){

    $extensions = array('.jpg');
    $extension = strrchr($_FILES['widget-updateBikeStatusAdmin-form-picture']['name'], '.');
    if(!in_array($extension, $extensions))
    {
          errorMessage("ES0041");
    }


    $taille_maxi = 6291456;
    $taille = filesize($_FILES['widget-updateBikeStatusAdmin-form-picture']['tmp_name']);
    if($taille>$taille_maxi)
    {
          errorMessage("ES0023");
    }

    //upload of Bike picture

    $dossier = '../images_bikes/'; 

    $fichier=$frameNumber.$extension;
    unlink($dossier.$fichier) or die("Couldn't delete file");
    
    $fichierMini=$frameNumber."_mini".$extension;
    unlink($dossier.$fichierMini) or die("Couldn't delete file");
    

    $fichier=$frameNumber.$extension;

     if(move_uploaded_file($_FILES['widget-updateBikeStatusAdmin-form-picture']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
     {
        $upload=true;
        $path= $dossier . $fichier;
     }
     else
     {
          errorMessage("ES0024");
     }

    $img = resize_image($dossier . $fichier, 200, 200);
    $fichierMini=$frameNumber."_mini".$extension;
    imagejpeg($img, $dossier . $fichierMini);
}
    
$response=array();

if($frameNumber != NULL && $user != NULL)
{

    include 'connexion.php';
    if($contractStart!=NULL){
        $contractStart="'".$contractStart."'";
    }else{
        $contractStart='NULL';
    }    
    if($contractEnd!=NULL){
        $contractEnd="'".$contractEnd."'";
    }else{
        $contractEnd='NULL';
    }
    $sql="update customer_bikes set HEU_MAJ = CURRENT_TIMESTAMP, USR_MAJ='$user', MODEL='$model', SIZE='$size', CONTRACT_START=$contractStart, CONTRACT_END=$contractEnd, CONTRACT_REFERENCE='$assistanceReference', COMPANY='$company', FRAME_REFERENCE='$frameReference', LEASING='$billing', LEASING_PRICE='$billingPrice', BILLING_GROUP='$billingGroup' where FRAME_NUMBER = '$frameNumber'";

    
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }

    $conn->close();
    successMessage("SM0003");

}
else
{
	errorMessage("ES0012");
}

?>