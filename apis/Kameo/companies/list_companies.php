<?php 

    $company=isset($_GET['company']) ? $conn->real_escape_string($_GET['company']) : "*";
    $type=isset($_GET['type']) ? $conn->real_escape_string($_GET['type']) : NULL;    
    $filter=isset($_GET['filter']) ? $conn->real_escape_string($_GET['filter']) : NULL;    


    if($type!="*" && $type != NULL){
        $stmt = $conn->prepare("SELECT * from companies WHERE TYPE=? ORDER BY INTERNAL_REFERENCE");        
        $stmt->bind_param("s", $type);
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
        $response['company'][$i]['internalReference']=$row['INTERNAL_REFERENCE'];
        $response['company'][$i]['type']=$row['TYPE'];
        $internalReference=$row['INTERNAL_REFERENCE'];
        $HEU_MAJ=$row['HEU_MAJ'];

        
        //@TODO refector
        $sql2="SELECT * FROM customer_bikes aa, customer_bike_access bb WHERE aa.COMPANY='$internalReference' and aa.EMAIL = bb.EMAIl";
        $result2 = execute_sql_query($sql2, $conn);
        $response['company'][$i]['companyBikeNumber'] = $result2->num_rows;
        $bikeAccessStatus="OK";
        $customerBuildingStatus="OK";

        if($response['company'][$i]['companyBikeNumber']==0){
            $bikeAccessStatus="OK";
        }
        while($row2 = mysqli_fetch_array($result2)){
            $bikeID=$row2['ID'];
            $sql3="SELECT * from customer_bike_access where BIKE_ID='$bikeID' and STAANN!='D'";
            $result3 = execute_sql_query($sql3, $conn);
            if($result3->num_rows=='0'){
                $bikeAccessStatus="KO";
            }
        }
                

        $sql3="SELECT * from customer_building_access where EMAIL in (select EMAIL from customer_referential where COMPANY='$internalReference') and BUILDING_CODE in (select BUILDING_REFERENCE FROM building_access where COMPANY='$internalReference')";
        $result3 = execute_sql_query($sql3, $conn);
        if($result3->num_rows=='0'){
            $customerBuildingStatus="OK";
        }else{
            $sql4="SELECT * from building_access where COMPANY='$internalReference'";
            $result4 = execute_sql_query($sql4, $conn);
            while($row4 = mysqli_fetch_array($result4)){
                $buildingReference=$row4['BUILDING_REFERENCE'];
                $sql5="SELECT * from customer_building_access where BUILDING_CODE='$buildingReference' and STAANN!='D'";
                $result5 = execute_sql_query($sql5, $conn);
                if($result5->num_rows=='0'){
                    $customerBuildingStatus="KO";
                }
            }            
        }

        $response['company'][$i]['bikeAccessStatus'] = $bikeAccessStatus;
        $response['company'][$i]['customerBuildingAccess'] = $customerBuildingStatus;


        $sql6="SELECT MAX(HEU_MAJ) as HEU_MAJ from company_actions where COMPANY='$internalReference'";
        $result6 = execute_sql_query($sql6, $conn);
        $resultat6=mysqli_fetch_array($result6);

        if($resultat6['HEU_MAJ'] > $HEU_MAJ){
            $HEU_MAJ=$resultat6['HEU_MAJ'];
        }
        $response['company'][$i]['HEU_MAJ'] = $HEU_MAJ;
        
        $i++;
    }

    $conn->close();
    $stmt->close();

    echo json_encode($response);
    die;
?>
                
