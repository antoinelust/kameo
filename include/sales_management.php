<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

if(!isset($_SESSION))
{
    session_start();
}

include 'globalfunctions.php';


if(isset($_GET['action'])){
    $action=isset($_GET['action']) ? $_GET['action'] : NULL;
    $item=isset($_GET['item']) ? $_GET['item'] : NULL;
    
    if($action=="list"){
        if($item=="sales"){
            
            
            $owner=isset($_GET['owner']) ? $_GET['owner'] : NULL;
            $start=isset($_GET['start']) ? $_GET['start'] : NULL;
            $end=isset($_GET['end']) ? $_GET['end'] : NULL;
                        
            
            $response=array();
            $response['response']="success";
            
            include 'connexion.php';
            
            if($owner == '*'){
                $sql="SELECT * from company_actions  where TYPE='CONTACT' AND STATUS='DONE' AND DATE >= '$start' AND DATE <= '$end'";
            }else{
                $sql="SELECT * from company_actions  where TYPE='CONTACT' AND STATUS='DONE' AND DATE >= '$start' AND DATE <= '$end' and OWNER='$owner'";
            }
            
            $response['sales']['sql']=$sql;
                        
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result = mysqli_query($conn, $sql);
            $conn->close();

            $i=0;

            while($row = mysqli_fetch_array($result)){
                
                $company=$row['COMPANY'];                
                $email=$row['OWNER'];
                $date=$row['DATE'];
                
                $response['sales']['contact'][$i]['id']=$row['ID'];
                $response['sales']['contact'][$i]['company']=$company;
                $response['sales']['contact'][$i]['date']=$row['DATE'];
                $response['sales']['contact'][$i]['description']=$row['DESCRIPTION'];
                
                
                include 'connexion.php';
                
                $sql="SELECT ID FROM companies where INTERNAL_REFERENCE='$company'";
                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
                $result2 = mysqli_query($conn, $sql);
                $resultat2=mysqli_fetch_assoc($result2);
                $conn->close();
                
                $response['sales']['contact'][$i]['companyID']=$resultat2['ID'];
                
                
                include 'connexion.php';
                
                $sql="SELECT * FROM company_actions WHERE TYPE='contact' AND STATUS = 'DONE' AND COMPANY='$company' AND DATE < '$date'";
                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
                $result2 = mysqli_query($conn, $sql);
                $length = $result2->num_rows;
                $conn->close();
                if($length == 0){
                    $response['sales']['contact'][$i]['type']="premier contact";
                }else{
                    $response['sales']['contact'][$i]['type']="relance";
                }
                
                $response['sales']['contact'][$i]['sql']=$sql;
                $response['sales']['contact'][$i]['length']=$length;
                
                
                include 'connexion.php';
                
                $sql="SELECT * FROM customer_referential WHERE EMAIL='$email'";
                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
                $result2 = mysqli_query($conn, $sql);
                $resultat2=mysqli_fetch_assoc($result2);
                $conn->close();
                $response['sales']['contact'][$i]['owner']=$resultat2['PRENOM']." ".$resultat2["NOM"];                
                
                $i++;
            }
            $response['sales']['contact']['number']=$i;
            echo json_encode($response);
            die;
            
            
        }else if($item=="owners"){
            $i=0;
            include 'connexion.php';
            $sql="SELECT OWNER from company_actions aa, customer_referential bb where aa.OWNER=bb.EMAIL and bb.STAANN != 'D' GROUP BY OWNER";
            if ($conn->query($sql) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result = mysqli_query($conn, $sql);
            $conn->close();
            
            $response=array();
            $response['response']="success";
            while($row = mysqli_fetch_array($result)){
                $email=$row['OWNER'];
                include 'connexion.php';
                $sql="SELECT * from customer_referential WHERE EMAIL='$email'";
                if ($conn->query($sql) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
                $result2 = mysqli_query($conn, $sql);
                $resultat2=mysqli_fetch_assoc($result2);
                $conn->close();
                
                
                $response['owner'][$i]['name']=$resultat2['NOM'];
                $response['owner'][$i]['firstName']=$resultat2['PRENOM'];
                $response['owner'][$i]['email']=$resultat2['EMAIL'];
                
                $i++;
            }
            $response['ownerNumber']=$i;
            echo json_encode($response);
            die;
        }
    }
}else{
    errorMessage("ES0012");
}


?>
