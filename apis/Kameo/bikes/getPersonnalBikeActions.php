<?php

$actions[] = array();

$actions[]=execSQL("SELECT action_log.DATE as date, 'action' as title, action_log.DESCRIPTION as description, action_log.PUBLIC as public, action_log.BIKE_ID as bikeID from customer_bike_access, action_log WHERE action_log.BIKE_ID=customer_bike_access.BIKE_ID AND EMAIL = (SELECT EMAIL FROM customer_referential WHERE TOKEN=?)", array('s', $token), false);
$actions[]=execSQL("SELECT DATE as date, 'Maintenance' as title, COMMENT as description, '1' as public from entretiens, customer_bike_access WHERE entretiens.BIKE_ID=customer_bike_access.BIKE_ID and customer_bike_access.EMAIL=(SELECT EMAIL FROM customer_referential WHERE TOKEN = ?) and entretiens.STATUS='DONE' ORDER BY entretiens.DATE DESC", array('s', $token), false);


$response['actions'] = array();

foreach($actions as $action) {
    if(is_array($action)) {
        $response['actions'] = array_merge($response['actions'], $action);
    }
}

echo json_encode($response);
die;
?>
