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
    $link=isset($_POST['$link']) ? $_POST['$link'] : NULL;
    $display=isset($_POST['display']) ? "Y" : "N";
    $motor = $_POST["motor"];
    $battery = $_POST["battery"];
    $transmission = $_POST["transmission"];
    $season = $_POST["season"];
    $priority = $_POST["priority"];
    $sizes="";

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
    if(isset($_FILES['file'])){

        $extensions = array('.jpg', '.JPG');
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

        $dossier = '../../images_bikes/';


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

        $fichier=$ID.$extension;

        if (file_exists($dossier.$fichier)) {
            unlink($dossier.$fichier) or die("Couldn't delete file");
        }

         if(move_uploaded_file($_FILES['file']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
         {
            $upload=true;
            $path= $dossier . $fichier;
         }
         else
         {
              errorMessage("ES0024");
         }
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

        $dossier = '../../images_bikes/';

        $fichier=$ID."_mini".$extension;

        if (file_exists($dossier.$fichier)) {
            unlink($dossier.$fichier) or die("Couldn't delete file");
        }
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

        if($priority<0 || $priority>100)
        {
            errorMessage("ES0063");
        }

        if(isset($_POST['sizes'])){
          foreach($_POST['sizes'] as $size){
            $sizes=$sizes.$size.",";
          }
          $sizes=substr($sizes, 0, -1);
        }
        $stmt = $conn->prepare("update bike_catalog set HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ=?, BRAND=?, MODEL=?, FRAME_TYPE=?, UTILISATION=?,  ELECTRIC=?, BUYING_PRICE=?, PRICE_HTVA=?, STOCK=?, DISPLAY=?, LINK=?, MOTOR=?, BATTERY=?, TRANSMISSION=?, SEASON=?, PRIORITY=?, SIZES=? WHERE ID=?");
        if ($stmt)
        {
            $stmt->bind_param("ssssssddissssssisi", $user, $brand, $model, $frameType, $utilisation, $electric, $buyPrice, $price, $stock, $display, $link, $motor, $battery, $transmission, $season, $priority, $sizes, $ID);
            $stmt->execute();
            $stmt->close();
        }else{
            error_message('500', 'Unable to update catalog bike');
        }

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
