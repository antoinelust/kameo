<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

if (isset($_GET['action'])) {
  $action = $_GET['action'];
  if ($action = "list") {
    include 'connexion.php';
    $sql = "SELECT entretiens.ID AS MAINTENANCE_ID, entretiens.DATE AS MANAGEMENT_DATE,
            STATUS, COMMENT, FRAME_NUMBER, COMPANY, MODEL, FRAME_REFERENCE
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
    $response['maintenancesNumber']=$result->num_rows;
    while($row = mysqli_fetch_array($result))
    {
      $response['maintenance'][$i]['id']=$row['MAINTENANCE_ID'];
      $response['maintenance'][$i]['IDReservation']=$row['ID_RESERVATION'];
      $response['maintenance'][$i]['note']=$row['NOTE'];
      $response['maintenance'][$i]['comment']=$row['COMMENT'];
      $response['maintenance'][$i]['entretien']=$row['ENTRETIEN'];
      $response['maintenance'][$i]['status']=$row['STATUS'];
      $response['maintenance'][$i]['company']=$row['COMPANY'];
      $response['maintenance'][$i]['firstName']=$row['PRENOM'];
      $i++;
    }

    echo json_encode($response);
    die;
  }

}
