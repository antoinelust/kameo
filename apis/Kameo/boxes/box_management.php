<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include '../globalfunctions.php';


if(isset($_POST['action']))
{
    $company = isset($_POST["company"]) ? $_POST["company"] : NULL;
    $id = isset($_POST["id"]) ? $_POST["id"] : NULL;
    $user = isset($_POST["user"]) ? $_POST["user"] : NULL;
    $action = isset($_POST["action"]) ? $_POST["action"] : NULL;
    $reference = isset($_POST["reference"]) ? $_POST["reference"] : NULL;
    $boxModel = isset($_POST["boxModel"]) ? $_POST["boxModel"] : NULL;
    $amount = isset($_POST["amount"]) ? $_POST["amount"] : NULL;
    $contractStart = isset($_POST["contractStart"]) ? date($_POST["contractStart"]) : NULL;
    $contractEnd = isset($_POST["contractEnd"]) ? date($_POST["contractEnd"]) : NULL;
    $billing = isset($_POST["billing"]) ? $_POST["billing"] : NULL;
    $billingGroup = isset($_POST["billingGroup"]) ? date($_POST["billingGroup"]) : NULL;


    if(isset($_POST['billing'])){
        $automaticBilling="Y";
    }else{
        $automaticBilling="N";
    }

    if($contractStart!=NULL){
        $contractStart="'".$contractStart."'";
    }else{
        $contractStart='NULL';
    }

    if($contractEnd!=NULL){
        $contractEnd="'".$contractEnd."'";
    }else{
        $contractEnd='NULL';
    }

    if($amount!=NULL){
        $amount="'".$amount."'";
    }else{
        $amount='NULL';
    }


    if($action=="add"){
        include '../connexion.php';



        $sql="INSERT INTO boxes (HEU_MAJ, USR_MAJ, REFERENCE, MODEL, COMPANY, START, END, AMOUNT, BILLING_GROUP, AUTOMATIC_BILLING, STAANN) VALUES (CURRENT_TIMESTAMP, '$user', '$reference', '$boxModel', '$company', $contractStart, $contractEnd, $amount, '$billingGroup', '$automaticBilling', '')";

        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }

        $conn->close();
        $response['sql']=$sql;
        successMessage("SM0021");

    }else if($_POST["action"]=="update"){

        include '../connexion.php';
        $sql="UPDATE boxes SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ='$user', REFERENCE='$reference', MODEL='$boxModel', COMPANY='$company', START=$contractStart, END=$contractEnd, AMOUNT=$amount, BILLING_GROUP='$billingGroup', AUTOMATIC_BILLING='$automaticBilling' WHERE ID='$id'";

        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }

        $conn->close();
        $response['sql']=$sql;
        successMessage("SM0022");

    }else if($_POST["action"]=="switch"){
        include '../connexion.php';
        $place = $_POST["place"];

        $sql="UPDATE locking_bikes SET HEU_MAJ=CURRENT_TIMESTAMP, PLACE_IN_BUILDING='$place' WHERE BIKE_ID='$id'";

        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }

        $conn->close();
        $response['sql']=$sql;
        successMessage("SM0022");
    }
    else
    {
        errorMessage("ES0012");
    }
}else if(isset($_GET['action'])){
    $action = isset($_GET["action"]) ? $_GET["action"] : NULL;
    $id = isset($_GET["id"]) ? $_GET["id"] : NULL;

    if($action=="retrieve"){
        if($id){
            include '../connexion.php';
            $sql="SELECT * FROM boxes WHERE ID='$id'";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }

            $result = mysqli_query($conn, $sql);
            $resultat = mysqli_fetch_assoc($result);
            $response['response']="success";
            $response['id']=$resultat['ID'];
            $response['model']=$resultat['MODEL'];
            $response['reference']=$resultat['REFERENCE'];
            $response['company']=$resultat['COMPANY'];
            $response['start']=$resultat['START'];
            $response['end']=$resultat['END'];
            $response['automatic_billing']=$resultat['AUTOMATIC_BILLING'];
            $response['amount']=$resultat['AMOUNT'];
            $response['billing_group']=$resultat['BILLING_GROUP'];

            $sql="SELECT bb.ID as id, bb.MODEL as model, cc.PLACE_IN_BUILDING  as place
            FROM boxes aa INNER JOIN customer_bikes bb ON aa.COMPANY=bb.COMPANY
            INNER JOIN locking_bikes cc ON bb.ID=cc.BIKE_ID where aa.ID='$id' and aa.BUILDING=cc.BUILDING and cc.PLACE_IN_BUILDING !='-1' ORDER BY cc.PLACE_IN_BUILDING";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result = mysqli_query($conn, $sql);
            $response['keys_in'] = $result->fetch_all(MYSQLI_ASSOC);

            $sql="SELECT bb.ID as id, bb.TYPE as type, bb.MODEL as model, cc.PLACE_IN_BUILDING as place, ee.EMAIL
            FROM boxes aa
            INNER JOIN customer_bikes bb ON aa.COMPANY=bb.COMPANY
            INNER JOIN locking_bikes cc ON bb.ID=cc.BIKE_ID
            INNER JOIN bike_catalog dd ON dd.ID=bb.TYPE
            INNER JOIN reservations ee
            WHERE aa.ID='$id' and aa.BUILDING=cc.BUILDING and cc.PLACE_IN_BUILDING ='-1' and cc.RESERVATION_ID=ee.ID ORDER BY bb.FRAME_NUMBER";



            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result = mysqli_query($conn, $sql);
            $i = 0;
            //$response['keys_out'] = $result->fetch_all(MYSQLI_ASSOC);
            while($row = mysqli_fetch_array($result))
            {
                $response['keys_out'][$i]['id']=$row['id'];
                $response['keys_out'][$i]['model']=$row['model'];
                $response['keys_out'][$i]['place']=$row['place'];
                $response['keys_out'][$i]['img'] = get_image($row['type']);
                $response['keys_out'][$i]['email'] = $row['EMAIL'];
                $i++;
            }

            $conn->close();


            echo json_encode($response);
            die;
        }else{
            errorMessage("ES0012");
        }


    }else if($action=="list"){
        include '../connexion.php';
        $sql="SELECT * FROM boxes ORDER BY COMPANY";

        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $response['response']="success";
        $response['boxesNumber'] = $result->num_rows;
        $i=0;
        while($row = mysqli_fetch_array($result))
        {
            $response['box'][$i]['id']=$row['ID'];
            $response['box'][$i]['model']=$row['MODEL'];
            $response['box'][$i]['reference']=$row['REFERENCE'];
            $response['box'][$i]['company']=$row['COMPANY'];
            $response['box'][$i]['start']=$row['START'];
            $response['box'][$i]['end']=$row['END'];
            $response['box'][$i]['automatic_billing']=$row['AUTOMATIC_BILLING'];
            $response['box'][$i]['amount']=$row['AMOUNT'];
            $response['box'][$i]['billing_group']=$row['BILLING_GROUP'];
            $i++;
        }
        $conn->close();
        echo json_encode($response);
        die;
    }else{
    errorMessage("ES0012");
    }
}else{
    errorMessage("ES0012");
}
