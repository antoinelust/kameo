<?php 
    include '../connexion.php';   

    $company=isset($_GET['company']) ? $conn->real_escape_string($_GET['company']) : "*";
    $type=isset($_GET['type']) ? $conn->real_escape_string($_GET['type']) : NULL;    
    $filter=isset($_GET['filter']) ? $conn->real_escape_string($_GET['filter']) : NULL;    


    if($type!="*" && $type != NULL){
        $stmt = $conn->prepare("SELECT * from companies WHERE 1 AND TYPE='?' ORDER BY INTERNAL_REFERENCE");
        $stmt->bind_param("s", $filter);
    }else{
        $stmt = $conn->prepare("SELECT * from companies ORDER BY INTERNAL_REFERENCE");
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $response['companiesNumber'] = $result->num_rows;


    $i=0;
    $response['response']="success";
    while($row = $result->fetch_assoc()){
        
        $response['company'][$i]['ID']=$row['ID'];
        $response['company'][$i]['companyName']=$row['COMPANY_NAME'];
        $currentCompany=$row['INTERNAL_REFERENCE'];
        $response['company'][$i]['internalReference']=$row['INTERNAL_REFERENCE'];
        $response['company'][$i]['type']=$row['TYPE'];
        $internalReference=$row['INTERNAL_REFERENCE'];
        $HEU_MAJ=$row['HEU_MAJ'];

        $sql2="SELECT * FROM customer_bikes WHERE COMPANY='$internalReference'";
        if ($conn->query($sql2) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result2 = mysqli_query($conn, $sql2);        
        $response['company'][$i]['companyBikeNumber'] = $result2->num_rows;
        $bikeAccessStatus="OK";
        $customerBuildingStatus="OK";

        if($response['company'][$i]['companyBikeNumber']==0){
            $bikeAccessStatus="OK";
        }
        while($row2 = mysqli_fetch_array($result2)){
            $bikeID=$row2['ID'];
            $sql3="SELECT * from customer_bike_access where BIKE_ID='$bikeID' and STAANN!='D'";
            if ($conn->query($sql3) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                die;
            }
            $result3 = mysqli_query($conn, $sql3);     
            if($result3->num_rows=='0'){
                $bikeAccessStatus="KO";
            }
        }
                

        $sql3="SELECT * from customer_building_access where EMAIL in (select EMAIL from customer_referential where COMPANY='$internalReference') and BUILDING_CODE in (select BUILDING_REFERENCE FROM building_access where COMPANY='$internalReference')";
        if ($conn->query($sql3) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $result3 = mysqli_query($conn, $sql3);     
        if($result3->num_rows=='0'){
            $customerBuildingStatus="OK";
        }else{
            $sql4="SELECT * from building_access where COMPANY='$internalReference'";
            if ($conn->query($sql4) === FALSE) {
                $response = array ('response'=>'error', 'message'=> $conn->error);
                echo json_encode($response);
                echo json_encode($response);
                die;
            }
            $result4 = mysqli_query($conn, $sql4);     
            while($row4 = mysqli_fetch_array($result4)){
                $buildingReference=$row4['BUILDING_REFERENCE'];
                $sql5="SELECT * from customer_building_access where BUILDING_CODE='$buildingReference' and STAANN!='D'";
                if ($conn->query($sql5) === FALSE) {
                    $response = array ('response'=>'error', 'message'=> $conn->error);
                    echo json_encode($response);
                    die;
                }
                $result5 = mysqli_query($conn, $sql5);     
                if($result5->num_rows=='0'){
                    $customerBuildingStatus="KO";
                }
            }            
        }

        $response['company'][$i]['bikeAccessStatus'] = $bikeAccessStatus;
        $response['company'][$i]['customerBuildingAccess'] = $customerBuildingStatus;


        $sql6="SELECT MAX(HEU_MAJ) as HEU_MAJ from company_actions where COMPANY='$currentCompany'";
        if ($conn->query($sql6) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }

        $result6 = mysqli_query($conn, $sql6);     
        $resultat6=mysqli_fetch_array($result6);

        if($resultat6['HEU_MAJ'] > $HEU_MAJ){
            $HEU_MAJ=$resultat6['HEU_MAJ'];
        }
        $response['company'][$i]['HEU_MAJ'] = $HEU_MAJ;
        
        $i++;
    }


    $result->free();
    $stmt->close();
    $conn->close();                


    echo json_encode($response);
    die;
?>
                
