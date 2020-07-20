<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');
session_start();

include 'connexion.php';
$response=array();
$id = isset($_POST['ID']) ? $_POST['ID'] : NULL;

  if ($id != NULL) {
    $sql="SELECT * FROM notifications WHERE USER_ID = '$id' AND (STAAN <> 'D' OR STAAN IS NULL) ORDER BY FIELD(`READ`, 'N', 'Y'), DATE DESC LIMIT 10";
    if ($conn->query($sql) === FALSE) {
      $response = array ('response'=>'error', 'message'=> $conn->error);
      echo json_encode($response);
      die;
    }
    $result = mysqli_query($conn, $sql);
    $length = $result->num_rows;
    $response['notificationsNumber']=$length;
    $response['response']="success";
    $response['notification'] = array();
	$conn->close();
    $i=0;
    while($row = mysqli_fetch_array($result))
    {
      if ($row['TYPE'] == "feedback") {
        date_default_timezone_set('Europe/Brussels');
        $currentDate = strtotime('now');
        $dateFinReservation = strtotime($row['DATE']);
        if ($currentDate >= $dateFinReservation) {
          $response['notification'][$i]['ID']=$row['ID'];
          $response['notification'][$i]['DATE']=$row['DATE'];
          $response['notification'][$i]['TYPE']=$row['TYPE'];
          $response['notification'][$i]['TEXT']=$row['TEXT'];
          $response['notification'][$i]['READ']=$row['READ'];
          $response['notification'][$i]['USER_ID'] = $id;
          $i++;
        }
      } else {
        $response['notification'][$i]['ID']=$row['ID'];
        $response['notification'][$i]['DATE']=$row['DATE'];
        $response['notification'][$i]['TYPE']=$row['TYPE'];
        $response['notification'][$i]['TEXT']=$row['TEXT'];
        $response['notification'][$i]['READ']=$row['READ'];
        $response['notification'][$i]['USER_ID'] = $id;
        $i++;
      }
    }
    echo json_encode($response);
    die;
  } else{
    $response['response']="error";
    $response['message'] = "ID manquant";
    echo json_encode($response);
    die;
  }
?>