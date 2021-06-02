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


            if($owner == '*'){
                $result=execSQL("SELECT company_actions.*, companies.COMPANY_NAME from company_actions, companies  where company_actions.COMPANY_ID=companies.ID AND (company_actions.TYPE='contact' OR company_actions.TYPE='rappel' OR company_actions.TYPE='plan rdv') AND company_actions.STATUS='DONE' AND company_actions.DATE >= ? AND company_actions.DATE <= ?", array('ss', $start, $end), false);
            }else{
                $result=execSQL("SELECT company_actions.*, companies.COMPANY_NAME from company_actions, companies  where company_actions.COMPANY_ID=companies.ID AND (company_actions.TYPE='contact' OR company_actions.TYPE='rappel' OR company_actions.TYPE='plan rdv') AND company_actions.STATUS='DONE' AND company_actions.DATE >= ? AND company_actions.DATE <= ? and company_actions.OWNER='$owner'", array('ss', $start, $end), false);
            }

            $i=0;

            foreach($result as $row){

                $companyID=$row['COMPANY_ID'];
                $email=$row['OWNER'];
                $date=$row['DATE'];
                $response['sales']['contact'][$i]['id']=$row['ID'];
                $response['sales']['contact'][$i]['companyID']=$companyID;
                $response['sales']['contact'][$i]['companyName']=$row['COMPANY_NAME'];
                $response['sales']['contact'][$i]['date']=$row['DATE'];
                $response['sales']['contact'][$i]['type']=$row['TYPE'];
                $response['sales']['contact'][$i]['description']=$row['DESCRIPTION'];

                if($row['TYPE']=="contact"){


                    $result=execSQL("SELECT * FROM company_actions WHERE TYPE='contact' AND STATUS = 'DONE' AND COMPANY_ID=? AND DATE < ?", array('is', $companyID, $date), false);
                    if(count($result) == 0){
                        $response['sales']['contact'][$i]['type']="premier contact";
                    }else{
                        $response['sales']['contact'][$i]['type']="rappel";
                    }
                }

                $resultat2=execSQL("SELECT * FROM customer_referential WHERE EMAIL=?", array('s', $email), false)[0];
                $response['sales']['contact'][$i]['owner']=$resultat2['PRENOM']." ".$resultat2["NOM"];
                $i++;
            }
            $response['sales']['contact']['number']=$i;
            echo json_encode($response);
            die;


        }else if($item=="owners"){

            global $conn;
            include 'connexion.php';
            $stmt = $conn->prepare("SELECT OWNER as email, bb.NOM as name, bb.PRENOM as firstName from company_actions aa, customer_referential bb where aa.OWNER=bb.EMAIL and bb.STAANN != 'D' GROUP BY OWNER");
            if($stmt)
            {
                $stmt->execute();
                echo json_encode($stmt->get_result()->fetch_all(MYSQLI_ASSOC));
            }
        else
            error_message('500', 'Unable to retrieve Actions owners');
            }
    }
}else{
    errorMessage("ES0012");
}


?>
