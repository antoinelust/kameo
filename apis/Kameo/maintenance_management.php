<?php
/*
status possibles : AUTOMATICLY_PLANNED, CONFIRMED, DONE, CANCELLED
 */
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

if (isset($_GET['action'])) {

  $action = $_GET['action'];

  if ($action == "list") {
    include 'connexion.php';
    $response = array ();

    $date_start = new DateTime($_GET['dateStart']);
    $date_start_string=$date_start->format('Y-m-d');

    $date_end = new DateTime($_GET['dateEnd']);
    $date_end_string=$date_end->format('Y-m-d');

    //récupération des entretiens de moins de 2 mois
    $sql = "SELECT entretiens.ID AS id, entretiens.DATE AS date,
            entretiens.STATUS AS status, COMMENT AS comment, FRAME_NUMBER AS frame_number, COMPANY AS company,
            MODEL AS model, FRAME_REFERENCE AS frame_reference, BIKE_ID AS bike_id, STREET AS street, ZIP_CODE AS zip_code, TOWN AS town
            FROM entretiens
            INNER JOIN customer_bikes ON customer_bikes.ID = entretiens.BIKE_ID
            INNER JOIN companies ON companies.INTERNAL_REFERENCE = customer_bikes.COMPANY
            WHERE entretiens.DATE >= '$date_start_string' AND entretiens.DATE <= '$date_end_string'
            GROUP BY BIKE_ID
            ORDER BY entretiens.DATE;";
    if ($conn->query($sql) === FALSE) {
      $response = array ('response'=>'error', 'message'=> $conn->error);
      echo json_encode($response);
      die;
    }
    $result = mysqli_query($conn, $sql);

    $response['maintenance'] = $result->fetch_all(MYSQLI_ASSOC);

    //count des entretiens auto planifiés ET confirmés de moins de 2 mois
    $sql_auto_plan="SELECT COUNT(ID) FROM entretiens
    WHERE STATUS = 'AUTOMATICALY_PLANNED' AND DATE >= '$date_start_string' AND DATE < '$date_end_string' GROUP BY entretiens.BIKE_ID
            ORDER BY entretiens.DATE";
    if ($conn->query($sql_auto_plan) === FALSE) {
      $response = array ('response'=>'error', 'message'=> $conn->error);
      echo json_encode($response);
      die;
    }
    $result = mysqli_query($conn, $sql_auto_plan);
    $row =  mysqli_fetch_array($result);

    if ($row['COUNT(ID)'] != NULL){
      $response['maintenancesNumberAuto']=$result->num_rows;
    }else{
      $response['maintenancesNumberAuto']=0;
    }
    
    
    $sql_confirmed = "SELECT COUNT(ID) FROM entretiens
    WHERE STATUS = 'CONFIRMED' AND DATE >= '$date_start_string' AND DATE < '$date_end_string' GROUP BY entretiens.BIKE_ID
            ORDER BY entretiens.DATE";
    if ($conn->query($sql_confirmed) === FALSE) {
      $response = array ('response'=>'error', 'message'=> $conn->error);
      echo json_encode($response);
      die;
    }
    $result = mysqli_query($conn, $sql_confirmed);
    $row =  mysqli_fetch_array($result);
    $conn->close();

    if($row['COUNT(ID)'] == NULL){
      $response['maintenancesNumberGlobal']=0;
    }else{
      $response['maintenancesNumberGlobal']=$result->num_rows;
    }
    $response['response'] = 'success';
    

    echo json_encode($response);
    die;
  }
  else if($action == "getOne"){
    if (isset($_GET['ID'])) {
      $ID = $_GET['ID'];
      include 'connexion.php';
      $sql = "SELECT entretiens.ID AS MAINTENANCE_ID, entretiens.BIKE_ID AS BIKE_ID, entretiens.DATE AS MAINTENANCE_DATE,
              entretiens.STATUS AS MAINTENANCE_STATUS, COMMENT, FRAME_NUMBER, COMPANY, MODEL, FRAME_REFERENCE, 
              STREET, ZIP_CODE, TOWN
              FROM entretiens
              INNER JOIN customer_bikes ON customer_bikes.ID = entretiens.BIKE_ID
              INNER JOIN companies ON companies.INTERNAL_REFERENCE = customer_bikes.COMPANY
              WHERE entretiens.ID = $ID";
      if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
      }
      
      $result = mysqli_query($conn, $sql);
      $resultat = mysqli_fetch_assoc($result);
      $conn->close();

      $response['maintenance']['id']=$resultat['MAINTENANCE_ID'];
      $response['maintenance']['bike_id']=$resultat['BIKE_ID'];
      $response['maintenance']['dateMaintenance']=$resultat['MAINTENANCE_DATE'];
      $response['maintenance']['status']=$resultat['MAINTENANCE_STATUS'];
      $response['maintenance']['comment']=$resultat['COMMENT'];
      $response['maintenance']['frame_number']=$resultat['FRAME_NUMBER'];
      $response['maintenance']['company']=$resultat['COMPANY'];
      $response['maintenance']['model']=$resultat['MODEL'];
      $response['maintenance']['street']=$resultat['STREET'];
      $response['maintenance']['town']=$resultat['TOWN'];
      $response['maintenance']['zip_code']=$resultat['ZIP_CODE'];
      $response['maintenance']['frame_reference']=$resultat['FRAME_REFERENCE'];

      $images = array();
      $dossier = $_SERVER['DOCUMENT_ROOT'].'/images_entretiens/';
      $fichier = strtolower(strval($ID)) ."_";

      foreach (glob($dossier . $fichier . "*") as $filename) {
        $path = explode("/", $filename);
        $images[] = end($path);
      }

      $response['maintenance']['images'] = $images;

      echo json_encode($response);
      die;
    }else{
      $response = array('response' => "error", "message" => "Pas d'ID");
      echo json_encode($response);
      die;
    }
  }else if($action == "deleteImage"){
    $url=$_GET['url'];
    $path = explode("/", $url);
    $id = explode("_", $path[1]);
    if(file_exists( $_SERVER['DOCUMENT_ROOT']."/".$url )){
      unlink($_SERVER['DOCUMENT_ROOT']."/".$url);
      $response = array('response' => "success", 'id' => $id[0], "message" => "Image supprimée");
      echo json_encode($response);
      die;
    }else{
      $response = array('response' => "error", "message" => "Fichier non trouvé");
      echo json_encode($response);
      die;
    }
  }
}
