<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';



$action=isset($_POST['action']) ? $_POST['action'] : NULL;


if($action=="update"){
    $ID = $_POST["ID"];
    $user = $_POST["user"];
    $brand = $_POST["brand"];
    $model = $_POST["model"];
    $frameType = $_POST["frame"];
    $utilisation = $_POST["utilisation"];
    $electric = $_POST["electric"];
    $buyPrice = $_POST["buyPrice"];
    $price = $_POST["price"];
    $stock = $_POST["stock"];
    $link = $_POST["link"];
    $display=isset($_POST['display']) ? "Y" : "N";
    
    include 'connexion.php';
    $sql = "select * from bike_catalog where ID='$ID'";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);
    $resultat = mysqli_fetch_assoc($result);    
    $conn->close();
    if($resultat['BRAND'] != $brand || $resultat['MODEL'] != $model){

        $dossier = '../images_bikes/';

        $oldFile=strtolower(str_replace(" ", "-", $resultat['BRAND']))."_".strtolower(str_replace(" ", "-", $resultat['MODEL']))."_".strtolower($resultat['FRAME_TYPE']).".jpg";
        $newFile=strtolower(str_replace(" ", "-", $brand))."_".strtolower(str_replace(" ", "-", $model))."_".strtolower($frameType).".jpg";
        
        copy($dossier . $oldFile, $dossier . $newFile);
        unlink($dossier . $oldFile);


        $oldFile=strtolower(str_replace(" ", "-", $resultat['BRAND']))."_".strtolower(str_replace(" ", "-", $resultat['MODEL']))."_".strtolower($resultat['FRAME_TYPE'])."_mini.jpg";
        $newFile=strtolower(str_replace(" ", "-", $brand))."_".strtolower(str_replace(" ", "-", $model))."_".strtolower($frameType)."_mini.jpg";

        copy($dossier . $oldFile, $dossier . $newFile);
        unlink($dossier . $oldFile);
    }

    if(isset($_FILES['file'])){

        $extensions = array('.jpg');
        $extension = strrchr($_FILES['file']['name'], '.');
        if(!in_array($extension, $extensions))
        {
              errorMessage("ES0041");
        }


        $taille_maxi = 6291456;
        $taille = filesize($_FILES['file']['tmp_name']);
        if($taille>$taille_maxi)
        {
              errorMessage("ES0023");
        }

        //upload of Bike picture

        $dossier = '../images_bikes/';
        
        
        include 'connexion.php';
        $sql = "select * from bike_catalog where ID='$ID'";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $resultat = mysqli_fetch_assoc($result);    
        $conn->close();

        $fichier=strtolower(str_replace(" ", "-", $resultat['BRAND']))."_".strtolower(str_replace(" ", "-", $resultat['MODEL']))."_".strtolower($frameType).$extension;

        if (file_exists($dossier.$fichier)) {   
            unlink($dossier.$fichier) or die("Couldn't delete file");
        }    

        $fichier = strtolower(str_replace(" ", "-", $brand))."_".strtolower(str_replace(" ", "-", $model))."_".strtolower($frameType).$extension;

         if(move_uploaded_file($_FILES['file']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
         {
            $upload=true;
            $path= $dossier . $fichier;
         }
         else
         {
              errorMessage("ES0024");
         }

        /*$img = resize_image($dossier . $fichier, 200, 200);
        $fichierMini = strtolower(str_replace(" ", "-", $brand))."_".strtolower(str_replace(" ", "-", $model))."_".strtolower($frameType)."_mini".$extension;
        imagejpeg($img, $dossier . $fichierMini);*/
    }


    if(isset($_FILES['fileMini'])){

        $extensions = array('.jpg');
        $extension = strrchr($_FILES['fileMini']['name'], '.');
        if(!in_array($extension, $extensions))
        {
              errorMessage("ES0041");
        }


        $taille_maxi = 6291456;
        $taille = filesize($_FILES['fileMini']['tmp_name']);
        if($taille>$taille_maxi)
        {
              errorMessage("ES0023");
        }

        //upload of Bike picture

        $dossier = '../images_bikes/';

        $sql = "select * from bike_catalog where ID='$ID'";


        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $resultat = mysqli_fetch_assoc($result);    

        $fichier=strtolower(str_replace(" ", "-", $resultat['BRAND']))."_".strtolower(str_replace(" ", "-", $resultat['MODEL']))."_".strtolower($frameType)."_mini".$extension;

        if (file_exists($dossier.$fichier)) {   
            unlink($dossier.$fichier) or die("Couldn't delete file");
        }


        $fichier = strtolower(str_replace(" ", "-", $brand))."_".strtolower(str_replace(" ", "-", $model))."_".strtolower($frameType)."_mini".$extension;

         if(move_uploaded_file($_FILES['fileMini']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
         {
            $upload=true;
            $path= $dossier . $fichier;
         }
         else
         {
              errorMessage("ES0024");
         }
    }


    if( $_SERVER['REQUEST_METHOD'] == 'POST') {

     if($ID != '' && $brand != '' && $model != '' && $frameType != '' && $utilisation != '' && $electric != '' && $price != '' && $stock != '') {

        include 'connexion.php';
        $sql = "update bike_catalog set HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ='$user', BRAND='$brand', MODEL='$model', FRAME_TYPE='$frameType', UTILISATION='$utilisation',  ELECTRIC='$electric', BUYING_PRICE='$buyPrice', PRICE_HTVA='$price', STOCK='$stock', DISPLAY='$display', LINK='$link' WHERE ID='$ID'";

         if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $conn->close();

         successMessage("SM0003");

        } else {
            $response = array ('response'=>'error');     
            echo json_encode($response);
            die;
        }

    }
    else
    {
        errorMessage("ES0012");
    }
}else if($action=="delete"){
    $id = $_POST["id"];
    $user = $_POST["user"];
    
    include 'connexion.php';
    $sql = "update bike_catalog set HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ='$user', STAANN='D' WHERE ID='$id'";

    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);
    $conn->close();

     successMessage("SM0018");
    
    
}else{
    errorMessage("ES0012");
}
?>
