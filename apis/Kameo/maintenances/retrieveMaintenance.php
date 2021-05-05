<?php
if (isset($_GET['ID'])) {
  $ID = $_GET['ID'];
  $resultat = execSQL("SELECT entretiens.ID AS MAINTENANCE_ID, entretiens.BIKE_ID AS BIKE_ID, entretiens.DATE AS MAINTENANCE_DATE, entretiens.OUT_DATE_PLANNED AS OUT_DATE_PLANNED, entretiens.STATUS AS MAINTENANCE_STATUS, COMMENT, INTERNAL_COMMENT, FRAME_NUMBER, COMPANY, companies.ID as COMPANY_ID, MODEL, FRAME_REFERENCE, customer_bike_access.TYPE AS TYPE_V FROM entretiens INNER JOIN customer_bikes ON customer_bikes.ID = entretiens.BIKE_ID INNER JOIN companies ON companies.INTERNAL_REFERENCE = customer_bikes.COMPANY INNER JOIN customer_bike_access ON customer_bike_access.BIKE_ID = entretiens.BIKE_ID WHERE entretiens.ID = '$ID'
  UNION
  SELECT entretiens.ID AS MAINTENANCE_ID, entretiens.BIKE_ID AS BIKE_ID, entretiens.DATE AS MAINTENANCE_DATE,entretiens.OUT_DATE_PLANNED as OUT_DATE_PLANNED, entretiens.STATUS AS MAINTENANCE_STATUS, COMMENT, INTERNAL_COMMENT, FRAME_NUMBER, COMPANY, companies.ID as COMPANY_ID, MODEL, FRAME_REFERENCE, 'partage' as TYPE_V FROM entretiens INNER JOIN customer_bikes ON customer_bikes.ID = entretiens.BIKE_ID INNER JOIN companies ON companies.INTERNAL_REFERENCE = customer_bikes.COMPANY WHERE entretiens.ID = '$ID' AND NOT EXISTS(SELECT 1 FROM customer_bike_access WHERE customer_bike_access.BIKE_ID=customer_bikes.ID)", array(), false)[0];

  $response['maintenance']['id']=$resultat['MAINTENANCE_ID'];
  $response['maintenance']['bike_id']=$resultat['BIKE_ID'];
  $response['maintenance']['dateMaintenance']=$resultat['MAINTENANCE_DATE'];
  $response['maintenance']['dateOutPlanned']=$resultat['OUT_DATE_PLANNED'];
  $response['maintenance']['status']=$resultat['MAINTENANCE_STATUS'];
  $response['maintenance']['comment']=$resultat['COMMENT'];
  $response['maintenance']['internalComment']=$resultat['INTERNAL_COMMENT'];
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
