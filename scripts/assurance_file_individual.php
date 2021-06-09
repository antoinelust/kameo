<?php
include '../apis/Kameo/connexion.php';
include '../apis/Kameo/globalfunctions.php';

$now=new DateTime();
$nowString=$now->format("Y-m-d");

$listBikes=execSQL("SELECT customer_bikes.ID, customer_bikes.COMPANY, IF(bikeOwner!='partage',
(SELECT PRENOM FROM customer_referential WHERE customer_referential.EMAIL=tt.bikeOwner), '') as PRENOM, IF(bikeOwner!='partage', (SELECT NOM FROM customer_referential WHERE customer_referential.EMAIL=tt.bikeOwner), '') as NOM,
bike_catalog.BRAND, bike_catalog.MODEL, bike_catalog.UTILISATION, customer_bikes.FRAME_REFERENCE, (SELECT bills_catalog_bikes_link.BUYING_PRICE FROM bills_catalog_bikes_link WHERE bills_catalog_bikes_link.BIKE_ID=customer_bikes.ID) as 'Valeur vélo', (SELECT SUM(accessories_catalog.BUYING_PRICE) FROM accessories_catalog, accessories_stock WHERE accessories_catalog.ID=accessories_stock.CATALOG_ID AND accessories_stock.BIKE_ID=customer_bikes.ID) as 'Valeur accessoires', customer_bikes.CONTRACT_START as 'contractStart'

FROM (SELECT customer_bikes.ID as bikeID, IF( EXISTS( SELECT * FROM customer_bike_access WHERE customer_bike_access.TYPE='personnel' AND customer_bike_access.BIKE_ID=customer_bikes.ID), (SELECT EMAIL FROM customer_bike_access WHERE customer_bike_access.TYPE='personnel' AND customer_bike_access.BIKE_ID=customer_bikes.ID) , 'partage') as bikeOwner FROM customer_bikes) as tt, customer_bikes, bike_catalog

WHERE tt.bikeID=customer_bikes.ID
AND customer_bikes.TYPE=bike_catalog.ID AND (customer_bikes.INSURANCE_INDIVIDUAL=1) AND (CONTRACT_END >= ? OR CONTRACT_END is NULL)", array('s', $nowString), false);

// Création de l'excel
header('Content-Encoding: UTF-8');
header("Content-type: application/vnd.ms-excel; charset=UTF-8");
header("Content-disposition: attachment; filename=KameoBikesAEDESPoliceIndividuelle.xls");
echo pack('H*','EFBBBF');

$excel ="Référence du vélo ; Société ; Prénom ; Nom ; Marque ; Modèle ; Type ; Numéro de série ; Valeur d'achat du vélo ; Valeur d'achat des accessoires ; Prise d'effet \n";

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
