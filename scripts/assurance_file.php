<?php
include '../apis/Kameo/connexion.php';
include '../apis/Kameo/globalfunctions.php';

$now=new DateTime();
$nowString=$now->format("Y-m-d");

$listBikes=execSQL("SELECT customer_bikes.ID, customer_bikes.FRAME_NUMBER, customer_bikes.FRAME_REFERENCE, customer_bikes.COMPANY,bike_catalog.BRAND, bike_catalog.MODEL, (SELECT bills_catalog_bikes_link.BUYING_PRICE FROM bills_catalog_bikes_link WHERE bills_catalog_bikes_link.BIKE_ID=customer_bikes.ID) as 'Valeur vélo', customer_bikes.CONTRACT_START as 'contractStart', customer_bikes.CONTRACT_END

FROM customer_bikes, bike_catalog

WHERE customer_bikes.TYPE=bike_catalog.ID AND (customer_bikes.INSURANCE='Y' OR CONTRACT_TYPE='leasing' OR CONTRACT_TYPE='renting') AND (CONTRACT_END >= ? OR CONTRACT_END is NULL)", array('s', $nowString), false);

// Création de l'excel
header('Content-Encoding: UTF-8');
header("Content-type: application/vnd.ms-excel; charset=UTF-8");
header("Content-disposition: attachment; filename=KameoBikesAEDESOnmium.xls");
echo pack('H*','EFBBBF');

$excel ="Référence du vélo ; Nom KAMEO ; Numéro de série; Société; Marque ; Modèle ; Valeur d'achat du vélo ; Prise d'effet; Fin d'effet \n";

// Remplissage des champs
foreach($listBikes as $bike)
{
  $i = 0;
  foreach($bike as $champ)
  {
    $i++;
    if( $champ instanceof \DateTime ){
      if($champ->format('d/m/Y') == "30/11/-0001"){
        $excel .= " ";
      } else{
        $excel .= $champ->format('d/m/Y');
      }
    }
    else{
      $excel .= $champ;
    }
    // Vérifie si c'est le dernier champs du projet pour soit changer de colonne soit de ligne
    if($i == count($bike)){
      $excel .= " \n";
    } else{
      $excel .= " ;";
    }
  }
}
// Enregistrement de l'excel
print $excel;
exit;


?>
