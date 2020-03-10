<?php
/*
status possibles : AUTOMATICLY_PLANNED, CONFIRMED, DONE, CANCELLED
 */
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

if (isset($_GET['action'])) {

  $action = $_GET['action'];

  if ($action = "list") {
    //récupération des entretiens
    include 'connexion.php';
    $sql = "SELECT entretiens.ID AS MAINTENANCE_ID, entretiens.DATE AS MAINTENANCE_DATE,
            entretiens.STATUS AS MAINTENANCE_STATUS, COMMENT, FRAME_NUMBER, COMPANY, MODEL, FRAME_REFERENCE
            FROM entretiens
            INNER JOIN customer_bikes ON customer_bikes.ID = entretiens.BIKE_ID
            ORDER BY entretiens.DATE DESC;";
    if ($conn->query($sql) === FALSE) {
      $response = array ('response'=>'error', 'message'=> $conn->error);
      echo json_encode($response);
      die;
    }
    $result = mysqli_query($conn, $sql);
    $conn->close();
    $i=0;
    $response = array ('response'=>'success');
    $response['maintenancesNumberGlobal']=$result->num_rows;
    while($row = mysqli_fetch_array($result))
    {
      $response['maintenance'][$i]['id']=$row['MAINTENANCE_ID'];
      $response['maintenance'][$i]['date']=$row['MAINTENANCE_DATE'];
      $response['maintenance'][$i]['status']=$row['MAINTENANCE_STATUS'];
      $response['maintenance'][$i]['comment']=$row['COMMENT'];
      $response['maintenance'][$i]['frame_number']=$row['FRAME_NUMBER'];
      $response['maintenance'][$i]['company']=$row['COMPANY'];
      $response['maintenance'][$i]['model']=$row['MODEL'];
      $response['maintenance'][$i]['frame_reference']=$row['FRAME_REFERENCE'];
      $i++;
    }

    //count des entretiens a valider
    include 'connexion.php';
    $sql="SELECT COUNT(ID) FROM entretiens WHERE STATUS = 'AUTOMATICLY_PLANNED';";

    if ($conn->query($sql) === FALSE) {
      $response = array ('response'=>'error', 'message'=> $conn->error);
      echo json_encode($response);
      die;
    }
    $result = mysqli_query($conn, $sql);
    $row =  mysqli_fetch_array($result);
    $conn->close();
    $i=0;
    $response['response'] = 'success';
    $response['maintenancesNumberAuto']=$row['COUNT(ID)'];



    echo json_encode($response);
    die;
  }
}
