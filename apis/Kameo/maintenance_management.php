<?php
/*
status possibles : AUTOMATICLY_PLANNED, CONFIRMED, DONE, CANCELLED
 */
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');


require_once __DIR__ .'/authentication.php';
require_once __DIR__ .'/globalfunctions.php';

$token = getBearerToken();
log_inputs($token);


if (isset($_GET['action'])) {
  $action = $_GET['action'];

  if ($action == "list") {
    include 'connexion.php';
    $response = array ();

    $date_start = new DateTime($_GET['dateStart']);
    $date_start_string=$date_start->format('Y-m-d');

    $date_end = new DateTime($_GET['dateEnd']);
    $date_end_string=$date_end->format('Y-m-d');

    $response['maintenance'] = execSQL("SELECT * FROM
      (SELECT entretiens.ID AS id, entretiens.DATE AS date, entretiens.OUT_DATE_PLANNED AS OUT_DATE_PLANNED, entretiens.STATUS AS status,
         COMMENT AS comment, customer_bikes.FRAME_NUMBER AS frame_number, customer_bikes.COMPANY AS company, MODEL AS model, customer_bikes.ADDRESS as bikeAddress,
         FRAME_REFERENCE AS frame_reference, customer_bikes.ID AS bike_id,customer_referential.PHONE AS phone, customer_referential.ADRESS AS street, customer_referential.POSTAL_CODE AS zip_code,
         customer_referential.CITY AS town, customer_bike_access.TYPE AS type, customer_bike_access.EMAIL AS email
         FROM entretiens
         INNER JOIN customer_bikes ON customer_bikes.ID = entretiens.BIKE_ID
         INNER JOIN companies ON companies.INTERNAL_REFERENCE = customer_bikes.COMPANY
         INNER JOIN customer_bike_access ON customer_bike_access.BIKE_ID = customer_bikes.ID AND customer_bike_access.TYPE='personnel'
         INNER JOIN customer_referential ON customer_referential.EMAIL=customer_bike_access.EMAIL
         WHERE entretiens.DATE >= '$date_start_string' AND entretiens.DATE <= '$date_end_string' AND entretiens.EXTERNAL_BIKE=0
       UNION
       SELECT entretiens.ID AS id, entretiens.DATE AS date,entretiens.OUT_DATE_PLANNED AS OUT_DATE_PLANNED, entretiens.STATUS AS status,
          COMMENT AS comment, customer_bikes.FRAME_NUMBER AS frame_number, customer_bikes.COMPANY AS company, MODEL AS model, customer_bikes.ADDRESS as bikeAddress,
          FRAME_REFERENCE AS frame_reference, customer_bikes.ID AS bike_id, (SELECT PHONE from companies_contact WHERE ID_COMPANY=companies.ID LIMIT 1) AS phone, companies.STREET AS street, companies.ZIP_CODE AS zip_code,
          companies.TOWN AS town, 'partage' AS type, 'N/A' AS email
          FROM entretiens
          INNER JOIN customer_bikes ON customer_bikes.ID = entretiens.BIKE_ID
          INNER JOIN companies ON companies.INTERNAL_REFERENCE = customer_bikes.COMPANY
          WHERE entretiens.DATE >= '$date_start_string' AND entretiens.DATE <= '$date_end_string' AND NOT EXISTS (SELECT 1 from customer_bike_access WHERE customer_bike_access.BIKE_ID = customer_bikes.ID AND customer_bike_access.TYPE='personnel') and EXTERNAL_BIKE=0
        UNION
        SELECT entretiens.ID AS id, entretiens.DATE AS date,entretiens.OUT_DATE_PLANNED AS OUT_DATE_PLANNED, entretiens.STATUS AS status,
           COMMENT AS comment, 'External Bike' AS frame_number, companies.INTERNAL_REFERENCE AS company, external_bikes.MODEL AS model, '' as bikeAddress,
           external_bikes.FRAME_REFERENCE AS frame_reference, external_bikes.ID AS bike_id, (SELECT PHONE from companies_contact WHERE ID_COMPANY=companies.ID LIMIT 1) AS phone, companies.STREET AS street, companies.ZIP_CODE AS zip_code,
           companies.TOWN AS town, 'external' AS type, 'N/A' AS email
           FROM entretiens
           INNER JOIN external_bikes ON external_bikes.ID = entretiens.BIKE_ID
           INNER JOIN companies ON companies.ID = external_bikes.COMPANY_ID
           WHERE entretiens.DATE >= '$date_start_string' AND entretiens.DATE <= '$date_end_string' AND EXTERNAL_BIKE=1
      ) as tt
      GROUP BY id
      ORDER BY date", array(), false);


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
}
