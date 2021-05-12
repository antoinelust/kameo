<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';
include_once 'authentication.php';
$token = getBearerToken();

$action=isset($_GET["action"]) ? $_GET["action"] : NULL;
$owner=isset($_GET["owner"]) ? $_GET["owner"] : NULL;

if($action=="graphic"){
    $company = isset($_GET["company"]) ? $_GET["company"] : NULL;
    $owner = isset($_GET["owner"]) ? $_GET["owner"] : NULL;
    $numberOfDays = isset($_GET["numberOfDays"]) ? $_GET["numberOfDays"] : NULL;
    $intervalStop="P".$numberOfDays."D";

    $date_start = new DateTime("NOW");
    $date_start->sub(new DateInterval($intervalStop));
    $date_start_string=$date_start->format('Y-m-d');

    $date_end = new DateTime("NOW");
    $date_end->add(new DateInterval('P1D'));
    $date_end->sub(new DateInterval($intervalStop));

    $arrayTotalTasks=array();
    $arrayContacts=array();
    $arrayReminder=array();
    $arrayRDVPlan=array();
    $arrayRDV=array();
    $arrayOffers=array();
    $arrayOffersSigned=array();
    $arrayDelivery=array();
    $arrayOther=array();
    $arrayDates=array();

    $date_now=new DateTime("NOW");
    $date_nom_string=$date_now->format('Y-m-d');

    include 'connexion.php';
    $sql="SELECT TYPE, COUNT(1) AS 'SUM' FROM company_actions WHERE STATUS='TO DO' AND DATE < '$date_nom_string'";

    if($owner!='*' && $owner != ''){
        $sql=$sql. "AND OWNER='$owner'";
    }
    $sql=$sql." GROUP BY TYPE";

    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $result = mysqli_query($conn, $sql);


    $presenceTotalTasks=0;
    $presenceContacts=0;
    $presenceReminder=0;
    $presenceRDVPlan=0;
    $presenceRDV=0;
    $presenceOffers=0;
    $presenceOffersSigned=0;
    $presenceDelivery=0;
    $presenceOther=0;

    while($row = mysqli_fetch_array($result))
    {
        if($row['TYPE']=="contact"){
            $presenceContacts=1;
        }
        if($row['TYPE']=="rappel"){
            $presenceReminder=1;
        }
        if($row['TYPE']=="plan rdv"){
            $presenceRDVPlan=1;
        }
        if($row['TYPE']=="rdv"){
            $presenceRDV=1;
        }
        if($row['TYPE']=="offre"){
            $presenceOffers=1;
        }
        if($row['TYPE']=="offreSigned"){
            $presenceOffersSigned=1;
        }
        if($row['TYPE']=="delivery"){
            $presenceDelivery=1;
        }
        if($row['TYPE']=="other"){
            $presenceOther=1;
        }
    }

    $response['response']="success";
    $response['presenceContacts']=$presenceContacts;
    $response['presenceReminder']=$presenceReminder;
    $response['presenceRDVPlan']=$presenceRDVPlan;
    $response['presenceRDV']=$presenceRDV;
    $response['presenceOffers']=$presenceOffers;
    $response['presenceOffersSigned']=$presenceOffersSigned;
    $response['presenceDelivery']=$presenceOffersSigned;
    $response['presenceOther']=$presenceOther;


    $conn->close();



    while($date_start<=$date_now){
        $date_start_string=$date_start->format('Y-m-d');

        include 'connexion.php';
        $sql="SELECT COUNT(1) AS 'SUM' FROM company_actions WHERE STATUS='TO DO' AND DATE<='$date_start_string'";

        if($owner!='*' && $owner != ''){
            $sql=$sql. "AND OWNER='$owner'";
        }


        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $resultat = mysqli_fetch_assoc($result);
        array_push($arrayTotalTasks, $resultat['SUM']);
        $conn->close();





        include 'connexion.php';
        $sql="SELECT TYPE, COUNT(1) AS 'SUM' FROM company_actions WHERE STATUS='TO DO' AND DATE<='$date_start_string'";
        if($owner!='*' && $owner != ''){
            $sql=$sql. "AND OWNER='$owner'";
        }

        $sql=$sql." GROUP BY TYPE";


        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result = mysqli_query($conn, $sql);
        $conn->close();

        $presenceTotalTasks=0;
        $presenceContacts=0;
        $presenceReminder=0;
        $presenceRDVPlan=0;
        $presenceRDV=0;
        $presenceOffers=0;
        $presenceOffersSigned=0;
        $presenceDelivery=0;
        $presenceOther=0;

        while($row = mysqli_fetch_array($result))
        {

            if($row['TYPE']=="contact"){
                $presenceContacts=1;
                array_push($arrayContacts, $row['SUM']);
            }
            if($row['TYPE']=="rappel"){
                $presenceReminder=1;
                array_push($arrayReminder, $row['SUM']);
            }
            if($row['TYPE']=="plan rdv"){
                $presenceRDVPlan=1;
                array_push($arrayRDVPlan, $row['SUM']);
            }
            if($row['TYPE']=="rdv"){
                $presenceRDV=1;
                array_push($arrayRDV, $row['SUM']);
            }
            if($row['TYPE']=="offre"){
                $presenceOffers=1;
                array_push($arrayOffers, $row['SUM']);
            }
            if($row['TYPE']=="offreSigned"){
                $presenceOffersSigned=1;
                array_push($arrayOffersSigned, $row['SUM']);
            }
            if($row['TYPE']=="delivery"){
                $presenceDelivery=1;
                array_push($arrayDelivery, $row['SUM']);
            }
            if($row['TYPE']=="other"){
                $presenceOther=1;
                array_push($arrayOther, $row['SUM']);
            }

        }

        if($presenceContacts==0){
            array_push($arrayContacts, "0");
        }
        if($presenceReminder==0){
            array_push($arrayReminder, "0");
        }
        if($presenceRDVPlan==0){
            array_push($arrayRDVPlan, "0");
        }
        if($presenceRDV==0){
            array_push($arrayRDV, "0");
        }
        if($presenceOffers==0){
            array_push($arrayOffers, "0");
        }
        if($presenceOffersSigned==0){
            array_push($arrayOffersSigned, "0");
        }
        if($presenceDelivery==0){
            array_push($arrayDelivery, "0");
        }
        if($presenceOther==0){
            array_push($arrayOther, "0");
        }

        array_push($arrayDates, $date_start_string);


        $date_start->add(new DateInterval('P1D'));
        $date_end->add(new DateInterval('P1D'));
    }

    $response['response']="success";
    $response['arrayTotalTasks']=$arrayTotalTasks;
    $response['arrayContacts']=$arrayContacts;
    $response['arrayReminder']=$arrayReminder;
    $response['arrayRDVPlan']=$arrayRDVPlan;
    $response['arrayRDV']=$arrayRDV;
    $response['arrayOffers']=$arrayOffers;
    $response['arrayOffersSigned']=$arrayOffersSigned;
    $response['arrayDelivery']=$arrayDelivery;
    $response['arrayOther']=$arrayOther;
    $response['arrayDates']=$arrayDates;
    echo json_encode($response);
    die;

}else if(isset($_GET["company"])){



}else if(isset($_GET['id'])){

} else if(isset($_POST["company"])){
} else
{
	errorMessage("ES0012");
}
?>
