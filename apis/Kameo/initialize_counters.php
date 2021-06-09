<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';
require_once __DIR__ .'/authentication.php';
$token = getBearerToken();


$type=isset($_POST['type']) ? addslashes($_POST['type']) : NULL;

$response=array();

$result=execSQL("SELECT COMPANY  FROM customer_referential WHERE TOKEN = ?", array('s', $token), false);
if(count($result)=='0'){
    errorMessage("ES0039");
}
$company=$result[0]['COMPANY'];
$response['company']=$company;


if($type=="users"){
  // number of users for the client, to be done for all companies with fleet manager access
  $response['usersNumber']=execSQL("SELECT COUNT(1) as SOMME FROM customer_referential dd where COMPANY=? ORDER BY NOM", array('s', $company), false)[0]['SOMME'];
  $response['response']="success";
  echo json_encode($response);
  die;
}

else if($type=='plannings'){
  $response['stillToDo']=execSQL("SELECT COUNT(1) as SOMME FROM (SELECT DATE FROM (SELECT substr(entretiens.DATE, 1, 10) as DATE FROM entretiens WHERE DATE>=? AND DATE <=? AND ADDRESS != '8 Rue de la brasserie, 4000 Liège' AND NOT EXISTS (SELECT 1 FROM plannings where (plannings.ITEM_TYPE='internalMaintenance' OR plannings.ITEM_TYPE='externalMaintenance') AND plannings.ITEM_ID=entretiens.ID) GROUP BY substr(entretiens.DATE, 1, 10) UNION ALL SELECT substr(client_orders.ESTIMATED_DELIVERY_DATE, 1, 10) as DATE FROM client_orders WHERE ESTIMATED_DELIVERY_DATE>=? AND ESTIMATED_DELIVERY_DATE <=? AND client_orders.DELIVERY_ADDRESS != '8 Rue de la brasserie, 4000 Liège' AND NOT EXISTS (SELECT 1 FROM plannings where plannings.ITEM_TYPE='order' AND plannings.ITEM_ID=client_orders.ID) GROUP BY substr(client_orders.ESTIMATED_DELIVERY_DATE, 1, 10)) as tt GROUP BY DATE) AS tt", array('ssss', $_POST['dateStart'], $_POST['dateEnd'], $_POST['dateStart'], $_POST['dateEnd']), false)[0]['SOMME'];
  echo json_encode($response);
  die;
}

else if($type=="bikes"){

    // number of bikes for the client, to be done for all companies with fleet manager access
    $response['bikeNumberClient']=execSQL("SELECT COUNT(1) AS SOMME FROM customer_bikes where COMPANY=? AND STAANN != 'D' AND CONTRACT_TYPE NOT IN ('order', 'stock', 'waiting_delivery')", array('s', $company), false)[0]['SOMME'];
    $response['response']="success";
    echo json_encode($response);
    die;

}else if($type=="bookings"){
  $dateEnd=new DateTime();


  if($dateEnd->format('m')==1){
      $monthBefore=12;
      $yearBefore=(($dateEnd->format('Y'))-1);
  }else{
      $monthBefore=(($dateEnd->format('m'))-1);
      $yearBefore=$dateEnd->format('Y');
  }
  $dayBefore=$dateEnd->format('d');

  if(strlen($monthBefore)==1){
      $monthBefore='0'.$monthBefore;
  }
  if(strlen($dayBefore)==1){
      $dayBefore='0'.$dayBefore;
  }

  $dateStart=new DateTime($yearBefore.'-'.$monthBefore.'-'.$dayBefore);

  $dateStartString=$dateStart->format('Y-m-d');
  $dateEndString=$dateEnd->format('Y-m-d');

  $response['response']="success";
  $response['bookingNumber']=execSQL("SELECT COUNT(1) AS SOMME FROM customer_bikes cc, reservations dd where cc.COMPANY=? AND cc.ID=dd.BIKE_ID and dd.STAANN!='D' and dd.DATE_START_2>? and dd.DATE_END_2<=?", array('sss', $company, $dateStartString, $dateEndString), false)[0]['SOMME'];
  echo json_encode($response);
  die;
}

else if($type=="ordersFleet"){
    $response['ordersNumber'] = execSQL("SELECT COUNT(1) AS SOMME from client_orders co, customer_referential cr, grouped_orders WHERE (STATUS='new' or STATUS='confirmed') and grouped_orders.EMAIL = cr.EMAIL AND grouped_orders.ID=co.GROUP_ID and cr.COMPANY=?", array('s', $company), false)[0]['SOMME'];
    $response['response']="success";
    echo json_encode($response);
    die;
}

else if($type=="boxesFleet"){
  if(get_user_permissions("fleetManager", $token)){
    include 'connexion.php';
    $response['boxesNumberTotal']=execSQL("SELECT COUNT(1) AS 'SOMME' FROM boxes WHERE COMPANY=? and STAANN != 'D'", array('s', $company), false)[0]['SOMME'];
    $response['response']="success";
    echo json_encode($response);
    die;
  }
}


else if($type=="conditions"){
    $response['conditionsNumber'] = execSQL("SELECT COUNT(1) as SOMME from conditions where COMPANY=?", array('s', $company), false)[0]['SOMME'];
    $response['response']="success";
    echo json_encode($response);
    die;
}

else if($type=="bills"){
    $response['billsNumber']=execSQL("SELECT COUNT(1) AS 'SOMME' FROM factures WHERE FACTURE_PAID='0' AND COMPANY=?", array('s', $company), false)[0]['SOMME'];
    $response['response']="success";
    echo json_encode($response);
    die;
}

else if($type=="customersCollab"){
  if(get_user_permissions("espaceCollaboratif", $token)){
    $response['companiesNumber']=execSQL("SELECT COUNT(1) as SOMME FROM companies WHERE AQUISITION = (SELECT COMPANY FROM customer_referential WHERE TOKEN=?) AND STAANN != 'D'", array('s', $token), false)[0]['SOMME'];
    $response['response']="success";
    echo json_encode($response);
    die;
  }
}
else if($type=="bikesCollab"){
  if(get_user_permissions("espaceCollaboratif", $token)){
    $response['bikesNumber']=execSQL("SELECT COUNT(1) as SOMME FROM customer_bikes WHERE COMPANY = (SELECT INTERNAL_REFERENCE FROM companies WHERE INTERNAL_REFERENCE = (SELECT COMPANY FROM customer_referential WHERE TOKEN=?)) AND STAANN != 'D'", array('s', $token), false)[0]['SOMME'];
    $response['response']="success";
    echo json_encode($response);
    die;
  }
}

else if($company=='KAMEO'){
  if(get_user_permissions("admin", $token)){
    if($type=="ordersAdmin"){
        $response['ordersNumber']= execSQL("SELECT COUNT(1) AS SOMME from client_orders WHERE STATUS='new'", array(), false)[0]['SOMME'];
        $response['response']="success";
        echo json_encode($response);
        die;
    }
    if($type=="tasks"){
        $response['actionNumberNotDone']=execSQL("SELECT COUNT(1) as somme from company_actions WHERE STATUS='TO-DO'", array(), false)[0]['somme'];
        $response['response']="success";
        echo json_encode($response);
        die;
    }

    if($type=="ordersAccessoryAdmin"){
      $response['response']="success";
      $response['ordersAccessoryNumber']=execSQL("SELECT COUNT(1) as SOMME from order_accessories", array(), false)[0]['SOMME'];
      echo json_encode($response);
      die;
    }
    if($type=="groupedOrders"){
      $response['response']="success";
      $response['ordersNumber']=execSQL("SELECT COUNT(1) as SOMME FROM (SELECT * FROM(SELECT GROUP_ID FROM client_orders WHERE client_orders.STATUS != 'closed' UNION SELECT order_accessories.ORDER_ID as GROUP_ID FROM order_accessories WHERE order_accessories.STATUS != 'closed') as tt GROUP BY tt.GROUP_ID) as yy", array(), false)[0]['SOMME'];
      echo json_encode($response);
      die;
    }


    if($type=="bikesAdmin"){
        $response['bikeNumber'] = execSQL("SELECT COUNT(1) AS SOMME from customer_bikes where STAANN != 'D' AND (CONTRACT_TYPE='stock' OR CONTRACT_TYPE='leasing' OR CONTRACT_TYPE='renting' OR CONTRACT_TYPE='order')", array(), false)[0]['SOMME'];
        $response['bikeOrdersLate'] = execSQL("SELECT COUNT(1) as SOMME FROM customer_bikes WHERE CONTRACT_TYPE='order' AND STAANN != 'D' AND (ESTIMATED_DELIVERY_DATE < CURRENT_DATE OR ESTIMATED_DELIVERY_DATE is NULL OR ESTIMATED_DELIVERY_DATE = '0000-00-00')", array(), false)[0]['SOMME'];
        $response['response']="success";
        echo json_encode($response);
        die;
    }

    if($type=="stockAccessories"){
      $response['response']="success";
      $response['accessoriesNumber']=execSQL("SELECT COUNT(1) AS SOMME from accessories_stock where STAANN != 'D'", array(), false)[0]['SOMME'];
      echo json_encode($response);
      die;
    }

    if($type=="portfolio"){
        $response['bikeNumberPortfolio'] = execSQL("select COUNT(1) AS SOMME from bike_catalog where STAANN != 'D'", array(), false)[0]['SOMME'];
        $response['response']="success";
        echo json_encode($response);
        die;
    }
    if($type=="portfolioAccessories"){
        $response = execSQL("SELECT COUNT(1) as accessoriesNumberPortfolio, 'success' as response from accessories_catalog", array(), false)[0];
        echo json_encode($response);
        die;
    }

    if($type=="customers"){
      $response['companiesNumberClientOrProspect']=execSQL("SELECT COUNT(1) AS SOMME from companies WHERE TYPE='PROSPECT' OR TYPE='CLIENT' AND STAANN != 'D'", array(), false)[0]['SOMME'];
      $response['response']="success";
      echo json_encode($response);
      die;
    }


    if($type=="chat"){
        //number of unread messages
        $response['messagesNumberUnread']=execSQL("SELECT COUNT(1) as 'TOTAL' FROM `chat` aa, customer_referential cc where NOT exists (select 1 from chat bb where aa.EMAIL_USER=bb.EMAIL_DESTINARY and aa.MESSAGE_TIMESTAMP<bb.MESSAGE_TIMESTAMP) and aa.EMAIL_USER=cc.EMAIL and cc.COMPANY != 'KAMEO'", array(), false)[0]['TOTAL'];
        $response['response']="success";
        echo json_encode($response);
        die;

    }

    if($type=="boxes"){
        $response['boxesNumberTotal']=execSQL("SELECT COUNT(1) AS 'SOMME' FROM boxes WHERE STAANN != 'D'", array(), false)[0]['SOMME'];
        $response['response']="success";
        echo json_encode($response);
        die;
    }

    if($type=="cashFlow"){

        $response['leasingBikes'] = execSQL("SELECT SUM(CASE WHEN BILLING_TYPE = 'annual'  THEN LEASING_PRICE/12 ELSE LEASING_PRICE END) as 'SOMME' from customer_bikes WHERE CONTRACT_START < CURRENT_TIMESTAMP AND (CONTRACT_END > CURRENT_TIMESTAMP OR CONTRACT_END is NULL) AND CONTRACT_TYPE IN ('location', 'leasing') AND STAANN !='D' AND COMPANY != 'KAMEO'", array(), false)[0]['SOMME'];
        $response['sumContractsCurrent'] = $response['leasingBikes'];
        $response['leasingBoxes'] = execSQL("SELECT SUM(AMOUNT) as 'PRICE' FROM boxes WHERE START<CURRENT_TIMESTAMP AND STAANN != 'D' and COMPANY != 'KAMEO' and COMPANY!='KAMEO VELOS TEST'", array(), false)[0]['PRICE'];
        $response['sumContractsCurrent'] += $response['leasingBoxes'];
        $now = time(); // or your date as well
        $firstJanuary = strtotime("2021-01-01");
        $datediff = $now - $firstJanuary;
        $days=round($datediff / (60 * 60 * 24));
        $sellingBikesSince1stJanuary=execSQL("SELECT SUM(factures_details.AMOUNT_HTVA) as SOMME FROM factures_details WHERE factures_details.ITEM_TYPE='bike' AND factures_details.DATE_START=factures_details.DATE_END AND factures_details.DATE_START>='2021-01-01'", array(), false)[0]['SOMME'];
        $response['sellingBikes'] = ($sellingBikesSince1stJanuary/$days)*30;
        $response['sumContractsCurrent'] += $response['sellingBikes'];
        $response['costs'] = execSQL("SELECT SUM(AMOUNT) as 'PRICE' FROM costs WHERE START<CURRENT_TIMESTAMP AND (END > CURRENT_TIMESTAMP OR END is NULL) AND STAANN != 'D'", array(), false)[0]['PRICE'];
        $response['sumContractsCurrent'] -= $response['costs'];

        $response['response']="success";

        echo json_encode($response);
        die;

    }

    if($type=='statistics'){
      echo json_encode(execSQL("SELECT (SUM(CASE WHEN year='thisYear' THEN CA ELSE 0 END)-SUM(CASE WHEN year='lastYear' THEN CA ELSE 0 END))/SUM(CASE WHEN year='lastYear' THEN CA ELSE 0 END) as progressCA FROM (SELECT SUM(aa.AMOUNT_HTVA) as CA, 'thisYear' as year FROM factures aa WHERE aa.DATE >= MAKEDATE(year(now()),1) AND aa.AMOUNT_HTVA>0 UNION ALL SELECT SUM(bb.AMOUNT_HTVA) as CA, 'lastYear' as year FROM factures bb WHERE bb.DATE >= MAKEDATE(year(now()-interval 1 year),1) AND bb.DATE <= MAKEDATE(year(now()-interval 1 year),DAYOFYEAR(CURDATE())) AND bb.AMOUNT_HTVA>0) as tt", array(), false)[0]);
      die;
    }

    /*include 'connexion.php';
    $sql="SELECT COUNT(1) AS 'SOMME' FROM factures WHERE FACTURE_SENT='0'";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);
    $resultat = mysqli_fetch_assoc($result);
    $response['billsNumber']=$resultat['SOMME'];
    $conn->close();*/


    if($type=="feedback"){
        $response['feedbacksNumber'] = execSQL("SELECT COUNT(1) AS SOMME FROM feedbacks WHERE STATUS='DONE'", array(), false)[0]['SOMME'];
        $response['response']="success";
        echo json_encode($response);
        die;
    }




    if($type=="maintenances"){
        $sql_auto_plan="SELECT COUNT(ID) FROM entretiens
        WHERE STATUS = 'AUTOMATICALY_PLANNED' AND DATE >= CAST(NOW() AS DATE) AND DATE < DATE(NOW() + INTERVAL 2 MONTH)";
        $sql_confirmed = "SELECT COUNT(ID) FROM entretiens
        WHERE STATUS = 'CONFIRMED' AND DATE >= CAST(NOW() AS DATE) AND DATE < DATE(NOW() + INTERVAL 2 MONTH)";

        $response = execSQL("SELECT ($sql_auto_plan) AS maintenancesNumberAuto, ($sql_confirmed) AS maintenancesNumberGlobal, 'success' as response from entretiens", array(), false)[0];
        echo json_encode($response);
        die;

    }
  }
}
else{
  error_message('405');
}





$response['response']="success";
echo json_encode($response);
die;


?>
