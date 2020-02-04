<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

session_start();
include 'globalfunctions.php';


if(isset($_POST['action']))
{
    $id = isset($_POST["ID"]) ? $_POST["ID"] : NULL;
    $action = isset($_POST["action"]) ? $_POST["action"] : NULL;
    $requestor = isset($_POST["requestor"]) ? $_POST["requestor"] : NULL;
    $company = isset($_POST["company"]) ? $_POST["company"] : NULL;
    $title = isset($_POST["title"]) ? $_POST["title"] : NULL;
    $description = isset($_POST["description"]) ? $_POST["description"] : NULL;
    $type = isset($_POST["type"]) ? date($_POST["type"]) : NULL;
    $probability = isset($_POST["probability"]) ? $_POST["probability"] : NULL;
    $amount = isset($_POST["amount"]) ? $_POST["amount"] : NULL;
    $date = isset($_POST["date"]) ? date($_POST["date"]) : NULL;
    $start = isset($_POST["start"]) ? date($_POST["start"]) : NULL;
    $end = isset($_POST["end"]) ? date($_POST["end"]) : NULL;
    $margin = isset($_POST["margin"]) ? date($_POST["margin"]) : NULL;

    if($date!=NULL){
        $date="'".$date."'";
    }else{
        $date='NULL';
    }       

    if($start!=NULL){
        $start="'".$start."'";
    }else{
        $start='NULL';
    }       

    if($end!=NULL){
        $end="'".$end."'";
    }else{
        $end='NULL';
    }       

    if(isset($_POST["action"])){
        if($_POST["action"]=="add"){
            include 'connexion.php';
            $sql="INSERT INTO offers (HEU_MAJ, USR_MAJ, TITRE, DESCRIPTION, PROBABILITY, AMOUNT, MARGIN, DATE, START, END, COMPANY, STAANN) VALUES (CURRENT_TIMESTAMP, '$requestor', '$title', '$description', '$probability', '$amount
            ', '$margin', $date, $start, $end, '$company', '')";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }

            $conn->close();   
            $response['sql']=$sql;
            successMessage("SM0019");
            
        }else if($_POST["action"]=="update"){
            
            include 'connexion.php';
            $sql="UPDATE offers SET HEU_MAJ=CURRENT_TIMESTAMP, USR_MAJ='$requestor', TITRE='$title', DESCRIPTION='$description', PROBABILITY='$probability', MARGIN='$margin', AMOUNT='$amount', DATE=$date, START=$start, END=$end WHERE ID='$id'";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }

            $conn->close();   
            $response['sql']=$sql;
            successMessage("SM0020");
            
        }
    } 
    else
    {
        errorMessage("ES0012");
    }
}else if(isset($_GET['action'])){
    $action = isset($_GET["action"]) ? $_GET["action"] : NULL;
    $id = isset($_GET["ID"]) ? $_GET["ID"] : NULL;
    $company = isset($_GET["company"]) ? $_GET["company"] : NULL;
    $graphics = isset($_GET["graphics"]) ? "1" : NULL;

    
    if($action=="retrieve"){
        
            
        if($graphics){
            echo "------------------<br />";
            echo "Début du script: ".date("H:m:s")."<br>";
            echo "------------------<br />";
            include "connexion.php";
            $sql="select MAX(CONTRACT_END) as 'DATE_END' from customer_bikes WHERE LEASING='Y'";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result = mysqli_query($conn, $sql);        
            $resultat = mysqli_fetch_assoc($result);
            $conn->close();  

            $date_end=$resultat['DATE_END'];
            
            $date_start = new DateTime("NOW");            
            $contracts=array();
            $i=0;
            while(($date_start->format('Y-m-d'))<$date_end){
                $date_start->add(new DateInterval('P1D'));
                
                $date_start_string=$date_start->format('Y-m-d');
                
                include 'connexion.php';
                $sql="SELECT SUM(LEASING_PRICE) AS 'PRICE' FROM customer_bikes WHERE CONTRACT_START <= '$date_start_string' AND CONTRACT_END >= '$date_start_string'";
                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
                $result = mysqli_query($conn, $sql);        
                $resultat = mysqli_fetch_assoc($result);
                $conn->close();  
                $i++;
                $contracts[]=[$i, $date_start_string, $resultat['PRICE']];

                
            }
            
            print_r($contracts);
            //echo date("d-m-Y", $date_start);
            echo "------------------<br />";
            echo "Fin du script: ".date("H:m:s")."<br>";
            echo "------------------<br />";
            

        }else{
            if($id){
                include 'connexion.php';
                $sql="SELECT * FROM offers WHERE ID='$id'";
                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }

                $result = mysqli_query($conn, $sql);        
                $resultat = mysqli_fetch_assoc($result);
                $conn->close();  

                $response['response']="success";
                $response['title']=$resultat['TITRE'];
                $response['description']=$resultat['DESCRIPTION'];
                $response['type']=$resultat['TYPE'];
                $response['probability']=$resultat['PROBABILITY'];
                $response['margin']=$resultat['MARGIN'];
                $response['amount']=$resultat['AMOUNT'];
                $response['date']=$resultat['DATE'];
                $response['start']=$resultat['START'];
                $response['end']=$resultat['END'];

                echo json_encode($response);
                die;                

            }
            else if($company){            


                include 'connexion.php';
                $sql="SELECT COMPANY, CONTRACT_START, CONTRACT_END, SUM(LEASING_PRICE) as 'PRICE', COUNT(1) AS 'BIKE_NUMBER' FROM customer_bikes WHERE LEASING='Y'";
                if($company!="*"){
                    $sql=$sql." AND COMPANY='$company'";
                }
                $sql=$sql." GROUP BY COMPANY, CONTRACT_START, CONTRACT_END";

                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }


                $result = mysqli_query($conn, $sql);        
                $conn->close();  

                $response['contractsNumber'] = $result->num_rows;
                $i=0;
                while($row = mysqli_fetch_array($result))
                {

                    $response['response']="success";
                    $response['contract'][$i]['company']=$row['COMPANY'];
                    $response['contract'][$i]['description']=$row['BIKE_NUMBER']." vélos en leasing";
                    $response['contract'][$i]['amount']=$row['PRICE'];
                    $response['contract'][$i]['start']=$row['CONTRACT_START'];
                    $response['contract'][$i]['end']=$row['CONTRACT_END'];
                    $i++;
                }


                include 'connexion.php';
                $sql="SELECT SUM(LEASING_PRICE) as 'PRICE' FROM customer_bikes WHERE LEASING='Y' AND CONTRACT_START<CURRENT_TIMESTAMP AND CONTRACT_END>CURRENT_TIMESTAMP";
                if($company!="*"){
                    $sql=$sql." AND COMPANY='$company'";
                }

                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
                $result = mysqli_query($conn, $sql);        
                $resultat = mysqli_fetch_assoc($result);
                $conn->close();  

                $response['sumContractsCurrent']=$resultat['PRICE'];


                include 'connexion.php';
                $sql="SELECT * FROM offers WHERE STAANN != 'D'";
                if($company!="*"){
                    $sql=$sql." AND COMPANY='$company'";
                }

                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }            
                $result = mysqli_query($conn, $sql);        
                $conn->close();  

                $response['offersNumber'] = $result->num_rows;
                $i=0;
                while($row = mysqli_fetch_array($result))
                {

                    $response['response']="success";
                    $response['offer'][$i]['id']=$row['ID'];
                    $response['offer'][$i]['company']=$row['COMPANY'];
                    $response['offer'][$i]['type']=$row['TYPE'];
                    $response['offer'][$i]['title']=$row['TITRE'];
                    $response['offer'][$i]['amount']=$row['AMOUNT'];
                    $response['offer'][$i]['probability']=$row['PROBABILITY'];
                    $response['offer'][$i]['start']=$row['START'];
                    $response['offer'][$i]['end']=$row['END'];
                    $response['offer'][$i]['margin']=$row['MARGIN'];
                    $i++;
                }


                echo json_encode($response);
                die;      

            }else{
            errorMessage("ES0012");
            }
        }

    }else{
        errorMessage("ES0012");
    }
}
else
{
    errorMessage("ES0012");
}

?>
