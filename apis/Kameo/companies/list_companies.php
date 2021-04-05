<?php

  $response = array();
  $sql="SELECT COUNT(*) as count FROM companies WHERE TYPE='PROSPECT' OR TYPE='CLIENT'";
  $result = $conn->query($sql);
  if ($result && $result->num_rows>0)
	$response['companiesNumberClientOrProspect'] = $result->fetch_array(MYSQLI_ASSOC)['count'];

  $sql="SELECT c.ID, COMPANY_NAME AS companyName, INTERNAL_REFERENCE AS internalReference, c.TYPE AS type, c.AUDIENCE AS audience,
  (SELECT COUNT(*) FROM customer_bikes WHERE c.INTERNAL_REFERENCE = COMPANY) AS companyBikeNumber,
  (SELECT COUNT(*) FROM boxes WHERE c.INTERNAL_REFERENCE = COMPANY) AS companyBoxNumber,
  (SELECT CASE WHEN COUNT(*) > 0 THEN 'OK' ELSE 'KO' END FROM customer_bike_access cba WHERE BIKE_ID IN (SELECT ID FROM customer_bikes cb WHERE cb.COMPANY=c.INTERNAL_REFERENCE) AND STAANN!='D') AS bikeAccessStatus,
  (SELECT CASE WHEN COUNT(*) > 0 THEN 'OK' ELSE 'KO' END FROM customer_building_access WHERE EMAIL IN (SELECT EMAIL FROM customer_referential WHERE COMPANY=c.INTERNAL_REFERENCE) AND BUILDING_CODE IN (SELECT BUILDING_REFERENCE FROM building_access WHERE COMPANY=c.INTERNAL_REFERENCE)) AS customerBuildingAccess,
  (SELECT CASE WHEN MAX(ca1.HEU_MAJ) > c1.HEU_MAJ THEN MAX(ca1.HEU_MAJ) ELSE c1.HEU_MAJ END FROM company_actions ca1, companies c1 WHERE ca1.COMPANY='KAMEO') as HEU_MAJ,
  (SELECT BOOKING from conditions WHERE COMPANY = c.INTERNAL_REFERENCE AND NAME='generic' LIMIT 1) as BOOKING,
  (SELECT CAFETARIA from conditions WHERE COMPANY = c.INTERNAL_REFERENCE AND NAME='generic' LIMIT 1) as CAFETARIA
  FROM companies c WHERE 1";

  $company=isset($_GET['company']) ? $conn->real_escape_string($_GET['company']) : "*";
  $type=isset($_GET['type']) ? $conn->real_escape_string($_GET['type']) : "*";
  $filter=isset($_GET['filter']) ? $conn->real_escape_string($_GET['filter']) : NULL;

  if($type!="*")
      $sql=$sql." AND TYPE='$type'";

  $sql=$sql." ORDER BY INTERNAL_REFERENCE";

  $response['company']=execSQL($sql, array(), false);
  $response['response']="success";
  echo json_encode($response);
?>
