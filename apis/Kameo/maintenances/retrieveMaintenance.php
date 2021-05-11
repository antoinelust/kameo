<?php
if (isset($_GET['ID'])) {
  $ID = $_GET['ID'];
  $resultat = execSQL("SELECT entretiens.ID AS MAINTENANCE_ID, entretiens.BIKE_ID AS BIKE_ID, EXTERNAL_BIKE, entretiens.DATE AS MAINTENANCE_DATE, entretiens.OUT_DATE_PLANNED AS OUT_DATE_PLANNED, entretiens.STATUS AS MAINTENANCE_STATUS, COMMENT, INTERNAL_COMMENT FROM entretiens WHERE entretiens.ID = ?", array('i', $ID), false)[0];

  $response['maintenance']['id']=$resultat['MAINTENANCE_ID'];
  $response['maintenance']['bike_id']=$resultat['BIKE_ID'];
  $response['maintenance']['dateMaintenance']=$resultat['MAINTENANCE_DATE'];
  $response['maintenance']['dateOutPlanned']=$resultat['OUT_DATE_PLANNED'];
  $response['maintenance']['status']=$resultat['MAINTENANCE_STATUS'];
  $response['maintenance']['comment']=$resultat['COMMENT'];
  $response['maintenance']['internalComment']=$resultat['INTERNAL_COMMENT'];

  if($resultat['EXTERNAL_BIKE']=='1'){
    $resultat=execSQL("SELECT companies.INTERNAL_REFERENCE as COMPANY, companies.ID as COMPANY_ID, external_bikes.MODEL as MODEL, 'personnel' as TYPE_V, FRAME_REFERENCE, 'external' as FRAME_NUMBER FROM external_bikes, companies WHERE external_bikes.ID=? AND external_bikes.COMPANY_ID=companies.ID", array('i', $resultat['BIKE_ID']), false)[0];
  }else{
    $resultat=execSQL("SELECT companies.INTERNAL_REFERENCE as COMPANY, companies.ID as COMPANY_ID, customer_bikes.MODEL as MODEL, 'personnel' as TYPE_V, FRAME_REFERENCE, FRAME_NUMBER FROM customer_bikes, companies WHERE customer_bikes.ID=? AND customer_bikes.COMPANY=companies.INTERNAL_REFERENCE", array('i', $resultat['BIKE_ID']), false)[0];
  }
  $response['maintenance']['frame_number']=$resultat['FRAME_NUMBER'];
  $response['maintenance']['company']=$resultat['COMPANY'];
  $response['maintenance']['COMPANY_ID']=$resultat['COMPANY_ID'];
  $response['maintenance']['model']=$resultat['MODEL'];
  $response['maintenance']['type']=$resultat['TYPE_V'];
  $response['maintenance']['frame_reference']=$resultat['FRAME_REFERENCE'];

  $dossier = $_SERVER['DOCUMENT_ROOT'].'/images_entretiens/'.$ID.'/publicFile/';
  $files = glob($dossier.'*.{jpg,jpeg,png,gif,pdf,jfif}', GLOB_BRACE);
  $publicFiles=array();

  foreach ($files as $file) {
    array_push($publicFiles, substr($file, strrpos($file, '/') + 1));
  }

  $response['maintenance']['publicFiles'] = $publicFiles;

  $dossier = $_SERVER['DOCUMENT_ROOT'].'/images_entretiens/'.$ID.'/internalFile/';
  $files = glob($dossier.'*.{jpg,jpeg,png,gif,pdf,jfif}', GLOB_BRACE);
  $internalFiles=array();

  foreach ($files as $file) {
    array_push($internalFiles, substr($file, strrpos($file, '/') + 1));
  }

  $response['maintenance']['internalFiles'] = $internalFiles;
  echo json_encode($response);
  die;
}else{
  $response = array('response' => "error", "message" => "Pas d'ID");
  echo json_encode($response);
  die;
}
?>
