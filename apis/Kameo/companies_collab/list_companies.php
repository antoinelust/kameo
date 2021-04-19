<?php
  $response=execSQL('SELECT aa.ID, aa.COMPANY_NAME, (SELECT COUNT(1) FROM customer_bikes WHERE customer_bikes.COMPANY=aa.INTERNAL_REFERENCE AND customer_bikes.STAANN != "D") as bikeNumber FROM companies aa WHERE aa.STAANN != "D" AND aa.AQUISITION = (SELECT COMPANY FROM customer_referential WHERE TOKEN = ?)',array('s', $token), false);
  echo json_encode($response);
  die;
?>
