<?php
$company = file_get_contents(__DIR__.'/temp/company.txt');
$billingGroup = file_get_contents(__DIR__.'/temp/billingGroup.txt');



if ((file_exists(__DIR__.'/temp/dateStart.txt'))) {
   $dateStart = file_get_contents(__DIR__.'/temp/dateStart.txt');
}else{
   $dateStart=date("Y-m-d");
}
$dateStartObject=new DateTime($dateStart);
$dateStartObject->setTime(0, 0);

if ((file_exists(__DIR__.'/temp/dateEnd.txt'))) {
   $dateEnd = file_get_contents(__DIR__.'/temp/dateEnd.txt');
}else{
    $dateEnd=date("Y-m-d");
}
$dateEndObject=new DateTime($dateEnd);
$dateEndObject->setTime(23, 59);

$currentDateObject=new DateTime($dateStart);

include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';

$sql_reference="select max(ID) as MAX_TOTAL, max(ID_OUT_BILL) as MAX_OUT from factures";
error_log(date("Y-m-d H:i:s")." - SQL4 :".$sql_reference."\n", 3, "generate_invoices.log");

if ($conn->query($sql_reference) === FALSE) {
    echo $conn->error;
    die;
}
$result_reference = mysqli_query($conn, $sql_reference);
$resultat_reference = mysqli_fetch_assoc($result_reference);
$newID=$resultat_reference['MAX_TOTAL'];
$newID=strval($newID+1);

$newIDOUT=$resultat_reference['MAX_OUT'];
$newIDOUT=strval($newIDOUT+1);


$today=new DateTime();
$todayString=date("Y-m-d");
if($today->format('m')==12){
    $monthAfter=1;
    $yearAfter=(($today->format('Y'))+1);
}else{
    $monthAfter=(($today->format('m'))+1);
    $yearAfter=$today->format('Y');
}
$dayAfter=$today->format('d');

$lastDayMonth= last_day_month( $monthAfter );
if($lastDayMonth < $dayAfter){
    $dayAfter=$lastDayMonth;
}

if(strlen($monthAfter)==1){
    $monthAfter='0'.$monthAfter;
}
if(strlen($dayAfter)==1){
    $dayAfter='0'.$dayAfter;
}

$inOneMonth=new DateTime();
$inOneMonth->setDate($yearAfter, $monthAfter, $dayAfter);
$inOneMonthString=$inOneMonth->format("Y-m-d");


$sql="select * from companies where INTERNAL_REFERENCE='$company' and BILLING_GROUP='$billingGroup'";
error_log(date("Y-m-d H:i:s")." - SQL5 :".$sql."\n", 3, "generate_invoices.log");

if ($conn->query($sql) === FALSE) {
    echo $conn->error;
    die;
}
$result = mysqli_query($conn, $sql);
$length = $result->num_rows;
if($length=='0'){
    $sql="select * from companies where INTERNAL_REFERENCE='$company' and BILLING_GROUP='1'";
    if ($conn->query($sql) === FALSE) {
        echo $conn->error;
        die;
    }
}

$resultat = mysqli_fetch_assoc($result);

$companyName=$resultat['COMPANY_NAME'];
$street=$resultat['STREET'];
$zip=$resultat['ZIP_CODE'];
$town=$resultat['TOWN'];
$vat=$resultat['VAT_NUMBER'];
$email=$resultat['EMAIL_CONTACT'];
$nom=$resultat['NOM_CONTACT'];
$prenom=$resultat['PRENOM_CONTACT'];
$reference=$newID;
$base_modulo=substr('00'.$reference, -6);
$base_modulo=date('Y').$base_modulo;
$modulo_check=($base_modulo % 97);
$modulo_check=substr('0'+$modulo_check, -2);
$reference=substr($base_modulo.$modulo_check, -12);
$reference=substr($reference, 0,3).'/'.substr($reference, 3,4).'/'.substr($reference, 7,5);

$monthFR=array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');


$test1='<page backtop="10mm" backbottom="10mm" backleft="20mm" backright="20mm">
    <page_header>
        <table style="width: 100%; border: solid 1px black;">
            <tr>
                <td style="text-align: left;    width: 33%">KAMEO Bikes</td>
                <td style="text-align: center;    width: 34%">Facture '.$companyName.'</td>
                <td style="text-align: right;    width: 33%">'.date('d/m/Y').'</td>
            </tr>
        </table>
    </page_header>
    <page_footer>
        <table style="width: 100%; border: solid 1px black;">
            <tr>
                <td style="text-align: left;    width: 50%">KAMEO Bikes</td>
                <td style="text-align: right;    width: 50%">page [[page_cu]]/[[page_nb]]</td>
            </tr>
        </table>
    </page_footer>


<table style="width: 100%;border: solid 1px #5544DD; border-collapse: collapse" align="center">
    <tr>
        <td style="width: 50%">

                <img class="img-responsive" src="'.$_SERVER['DOCUMENT_ROOT'].'/images/logo-dark.png" alt="">

				<p>KAMEO Bikes sprl</p>

				<p>Quai Marcellis, 24
				<br>B-4000 Liège
				<br>Belgium</p>

				<p>TVA/VAT : BE0681.879.712
				<br>BE38 0689 0775 9672
        <br>RPM Liège</p>

				<p>Liège, le '.date('d/m/Y').'</p>


        </td>
        <td style="width: 50%">';

        if(file_exists($_SERVER['DOCUMENT_ROOT'].'/images/'.$company.'.jpg')){
          $test1.='<img class="img-responsive" src="'.$_SERVER['DOCUMENT_ROOT'].'/images/'.$company.'.jpg" alt="">';
        }else{
          $test1.= '<img class="img-responsive" src="'.$_SERVER['DOCUMENT_ROOT'].'/images/default.jpg" alt="">';
        }

				$test1.= '<p>'.$companyName.'</p>

				<p>'.$street.'
				<br>'.$zip.' '.$town.'
				<br>Belgium</p>

				<p>TVA/VAT : '.$vat.'</p>

				<p>Référence client : '.$company.'</p>
        </td>

    </tr>
    <tr>
        <td>
            <h4 style="color: #3CB195">FACTURE n°'.$newIDOUT.'</h4>

        </td>
        <td>
            <h4 style="color: #C72C28">Référence : +++'.$reference.'+++</h4>
			<p>'.$monthFR[(date('n')-1)].' '.date('Y').'</p>

        </td>
    </tr>
</table>
<br />
<br />
<br />
<br />

	      <table>
	        <thead>
	          <tr>
	            <th>ID</th>
	            <th>Elément</th>
	            <th>Prix</th>
	          </tr>
	        </thead>
	        <tbody>';
            $test2='';

            if($currentDateObject->format('m')==12){
                $monthAfter=1;
                $yearAfter=(($currentDateObject->format('Y'))+1);
            }else{
                $monthAfter=(($currentDateObject->format('m'))+1);
                $yearAfter=$currentDateObject->format('Y');
            }

            $dayAfter=$currentDateObject->format('d');

            $lastDayMonth= last_day_month( $monthAfter );
            if($lastDayMonth < $dayAfter){
                $dayAfter=$lastDayMonth;
            }

            if(strlen($monthAfter)==1){
                $monthAfter='0'.$monthAfter;
            }
            if(strlen($dayAfter)==1){
                $dayAfter='0'.$dayAfter;
            }

            $dateAfter=new DateTime();
            $dateAfter->setDate($yearAfter, $monthAfter, $dayAfter);
            $dateAfterString=$dateAfter->format('Y-m-d');

            if(isset($_GET['bikeID'])){
              $sqlBike = " AND customer_bikes.ID='".$_GET['bikeID']."' ";
            }else {
              $sqlBike = "";
            }


            $sql2="select * from customer_bikes where COMPANY='$company' and CONTRACT_START<='$dateEnd' and (CONTRACT_END>='$dateEnd' or CONTRACT_END IS NULL) and BILLING_GROUP='$billingGroup' and AUTOMATIC_BILLING='Y' and STAANN !='D'".$sqlBike;
            error_log(date("Y-m-d H:i:s")."SQL6 :".$sql2."\n", 3, "generate_invoices.log");
            if ($conn->query($sql2) === FALSE){
                echo $conn->error;
                die;
            }
            $result2 = mysqli_query($conn, $sql2);
            $length = $result2->num_rows;


            $i=1;
            $total=0;

            while($row2 = mysqli_fetch_array($result2)){

              if($row2['BILLING_TYPE']=='monthly'){
                $units = '€/mois';
              }else{
                $units = '€/an';
              }

              error_log(date("Y-m-d H:i:s")." - BILLING_TYPE :".$row2['BILLING_TYPE']."\n", 3, "generate_invoices.log");
              error_log(date("Y-m-d H:i:s")." - UNITS :".$units."\n", 3, "generate_invoices.log");


              $catalogID=$row2['TYPE'];

              error_log(date("Y-m-d H:i:s")." - FRAME NUMBER :".$row2['FRAME_NUMBER']."\n", 3, "generate_invoices.log");
              $sql="SELECT * FROM bike_catalog WHERE ID ='$catalogID'";
              error_log(date("Y-m-d H:i:s")." - SQL CATALOG :".$sql."\n", 3, "generate_invoices.log");

              if ($conn->query($sql) === FALSE) {
                  echo $conn->error;
                  die;
              }
              $result4 = mysqli_query($conn, $sql);
              $resultat4 = mysqli_fetch_assoc($result4);
              $fichier = $_SERVER['DOCUMENT_ROOT']."/images_bikes/".$catalogID."_mini.jpg";


              $contractStart= new DateTime($row2['CONTRACT_START']);
              error_log(" contractStart : ".$contractStart->format("d-m-Y H:m:i")."\n", 3, "generate_invoices.log");

              $lastDayMonth= last_day_month( $currentDateObject->format("m") );
              if($lastDayMonth < $contractStart->format('d')){
                  $tempDay=$lastDayMonth;
              }else{
                $tempDay = substr($row2['CONTRACT_START'], 8,2);
              }



              $dateStartTemp = new DateTime();
              $dateStartTemp->setDate($currentDateObject->format("Y"), $currentDateObject->format("m"), $tempDay);

              $temp1=$dateStartTemp->format('d-m-Y');

              if($dateStartTemp <= $dateEndObject){
                error_log(" Temp1 : ".$dateStartTemp->format("d-m-Y")."\n", 3, "generate_invoices.log");
              }

              error_log(" Temp1 : ".$dateStartTemp->format("d-m-Y H:m:i")."\n", 3, "generate_invoices.log");
              error_log(" Date Start : ".$dateStartObject->format("d-m-Y H:m:i")."\n", 3, "generate_invoices.log");
              error_log(" DateEnd : ".$dateEndObject->format("d-m-Y H:m:i")."\n", 3, "generate_invoices.log");


              if($dateStartTemp >= $dateStartObject && $dateStartTemp <= $dateEndObject){
                if($row2['BILLING_TYPE'] == "monthly"){
                  if($dateStartTemp->format('m')==12){
                      $monthAfter2=1;
                      $yearAfter2=(($dateStartTemp->format('Y'))+1);
                  }else{
                      $monthAfter2=(($dateStartTemp->format('m'))+1);
                      $yearAfter2=$dateStartTemp->format('Y');
                  }
                  $dayAfter2=$contractStart->format('d');


                  $lastDayMonth= last_day_month( $monthAfter2 );
                  if($lastDayMonth < $dayAfter2){
                      $dayAfter2=$lastDayMonth;
                  }
                  if(strlen($monthAfter2)==1){
                      $monthAfter2='0'.$monthAfter2;
                  }
                  if(strlen($dayAfter2)==1){
                      $dayAfter2='0'.$dayAfter2;
                  }
                  $dateAfter2=new DateTime();
                  $dateAfter2->setDate($yearAfter2, $monthAfter2, $dayAfter2);
                }else if($row2['BILLING_TYPE']=="annual"){
                  $dateAfter2=new DateTime($dateStartTemp->format("Y-m-d"));
                  $dateAfter2->add(new DateInterval('P1Y'));
                }
                error_log(date("Y-m-d H:i:s")." - dateAfter :".$dateAfter2->format('d/m/Y')."\n", 3, "generate_invoices.log");


                $temp2=$dateAfter2->format('d-m-Y');
                $temp3=$dateStartTemp->format('Y-m-d');
                $temp4=$dateAfter2->format('Y-m-d');



                $comment='Période du '.$temp1.' au '.$temp2;
                $leasingPrice=$row2['LEASING_PRICE'];
                $leasingPriceTVAC=1.21*$row2['LEASING_PRICE'];
                $frameNumber=$row2['FRAME_NUMBER'];
                $bikeID=$row2['ID'];
                $commentBilling=$row2['COMMENT_BILLING'];

                $sql="INSERT INTO factures_details (USR_MAJ, FACTURE_ID, ITEM_TYPE, ITEM_ID, COMMENTS, DATE_START, DATE_END, AMOUNT_HTVA, AMOUNT_TVAC) VALUES('script', '$newID', 'bike', '$bikeID', '$comment', '$temp3', '$temp4', '$leasingPrice', '$leasingPriceTVAC')";
                if ($conn->query($sql) === FALSE) {
                    echo $conn->error;
                }

                $sql5="SELECT bb.NOM, bb.PRENOM, bb.EMAIL FROM customer_bike_access aa, customer_referential bb WHERE aa.BIKE_ID='$bikeID' and aa.TYPE='personnel' and aa.EMAIL=bb.EMAIL";
                if ($conn->query($sql5) === FALSE) {
                    echo $conn->error;
                    die;
                }
                $result5 = mysqli_query($conn, $sql5);
                $j=0;
                $totalTemp=$row2['LEASING_PRICE'];
                if($result5->num_rows>0){
                    $resultat5 = mysqli_fetch_assoc($result5);
                    $nameBikeUser=$resultat5['NOM'];
                    $firstNameBikeUser=$resultat5['PRENOM'];
                    $emailBikeUser=$resultat5['EMAIL'];
                    $sqlAccessories="SELECT accessories_stock.ID as accessoryID, accessories_catalog.MODEL, accessories_categories.CATEGORY, CONTRACT_AMOUNT FROM accessories_stock, accessories_catalog, accessories_categories WHERE accessories_categories.ID= accessories_catalog.ACCESSORIES_CATEGORIES AND accessories_stock.CATALOG_ID=accessories_catalog.ID AND accessories_stock.USER_EMAIL='$emailBikeUser' and accessories_stock.CONTRACT_TYPE='leasing' and accessories_stock.CONTRACT_START<='$temp3' AND accessories_stock.CONTRACT_END>='$temp3'";
                    if ($conn->query($sqlAccessories) === FALSE) {
                        echo $conn->error;
                        die;
                    }
                    error_log(date("Y-m-d H:i:s")." - SQL accessories :".$sqlAccessories."\n", 3, "generate_invoices.log");

                    $resultAccessories = mysqli_query($conn, $sqlAccessories);
                    while($rowAccessories = mysqli_fetch_array($resultAccessories)){
                      $accessoryID = $rowAccessories['accessoryID'];
                      $amount = $rowAccessories["CONTRACT_AMOUNT"];
                      $amountTVAC = round($amount*1.21);
                      $sql="INSERT INTO factures_details (USR_MAJ, FACTURE_ID, ITEM_TYPE, ITEM_ID, COMMENTS, DATE_START, DATE_END, AMOUNT_HTVA, AMOUNT_TVAC) VALUES('script', '$newID', 'accessory', '$accessoryID', '$comment', '$temp3', '$temp4', '$amount', '$amountTVAC')";
                      if ($conn->query($sql) === FALSE){
                          echo $conn->error;
                      }
                      $accessoryBike[$i][$j]['CATEGORY']=$rowAccessories["CATEGORY"];
                      $accessoryBike[$i][$j]['MODEL']=$rowAccessories["MODEL"];
                      $accessoryBike[$i][$j]['CONTRACT_AMOUNT']= $amount;
                      $total+=$rowAccessories['CONTRACT_AMOUNT'];
                      $totalTemp+=$rowAccessories['CONTRACT_AMOUNT'];
                      $j++;
                    }

                }else{
                  $nameBikeUser = NULL;
                  $firstNameBikeUser = NULL;
                  $emailBikeUser = NULL;
                }

                $difference=$dateStartTemp->diff($contractStart);

                $monthDifference=(($difference->format('%y'))*12+$difference->format('%m')+1);
                $yearDifference=(($difference->format('%y'))+1);

                if($row2['CONTRACT_END']){
                    $contractEnd= new DateTime();
                    $contractEnd->setDate(substr($row2['CONTRACT_END'], 0, 4), substr($row2['CONTRACT_END'],5,2), substr($row2['CONTRACT_END'], 8,2));
                    $numberOfMonthContract=$contractEnd->diff($contractStart);
                    $numberOfMonthContract=(($numberOfMonthContract->format('%y'))*12+$numberOfMonthContract->format('%m'));
                    $numberOfYearContract=$contractEnd->diff($contractStart);
                    $numberOfYearContract=(($numberOfYearContract->format('%y')));
                }
                if($nameBikeUser != NULL){
                  $test2=$test2.'<tr>
                      <td style="width: 20; text-align: left; border-top: solid 1px grey; border-bottom: solid 1px grey">'.$i.'</td>
                      <td style="width: 430; text-align: left; border-top: solid 1px grey; border-bottom: solid 1px grey">Vélo personnel de : '.$firstNameBikeUser.' '.$nameBikeUser.'</td>
                      <td style="width: 150; text-align: left; border-top: solid 1px grey; border-bottom: solid 1px grey">'.round($totalTemp, 2).' €</td>
                  </tr>';

                }else{
                  $test2.='
                  <tr>
                      <td style="width: 20; text-align: left; border-top: solid 1px grey; border-bottom: solid 1px grey">'.$i.'</td>
                      <td style="width: 430; text-align: left; border-top: solid 1px grey; border-bottom: solid 1px grey">Vélo partagé</td>
                      <td style="width: 150; text-align: left; border-top: solid 1px grey; border-bottom: solid 1px grey">'.$totalTemp.' €</td>
                  </tr>';

                }


                $test2.='<tr>
                    <td></td>
                    <td style="color: grey">'.$row2['MODEL'].' - CADRE: '.$row2['FRAME_REFERENCE'].'<br> Période du '.$dateStartTemp->format('d-m-Y').' au '.$dateAfter2->format('d-m-Y').'<br>';

                if(($row2['CONTRACT_END'])){
                  if($row2['BILLING_TYPE']=='annual'){
                    $test2=$test2.'<br>Période '.($yearDifference).'/'.($numberOfYearContract);
                  }else{
                    $test2=$test2.'<br>Période '.($monthDifference).'/'.$numberOfMonthContract;
                  }
                }else{
                  $test2=$test2."<br>Location";
                }

                if($commentBilling != NULL && $commentBilling != ''){
                  $test2.='<br/>'.$commentBilling;
                }

                $test2.='</td>
                <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td><img class="img-responsive" src="'.$fichier.'" alt="">';
                $test2=$test2."</td><td style='color: grey'>".round($row2['LEASING_PRICE'],2)." ".$units." HTVA</td></tr>";

                if($j>0){
                  foreach($accessoryBike[$i] as &$accessory){
                      $test2=$test2.'<tr><td style="width: 20; text-align: left; border-top: solid 1px grey;"></td><td style="width: 20; text-align: left; border-top: solid 1px grey; color: grey">'.$accessory['CATEGORY'].' - '.$accessory['MODEL'].'</td><td style="width: 20; text-align: left; border-top: solid 1px grey; color: grey">'.$accessory['CONTRACT_AMOUNT'].' '.$units.'</td></tr>';
                  }
                }
                $i+=1;
                $total+=$row2['LEASING_PRICE'];
              }
            }

            $sql2="select * from boxes where COMPANY='$company' and START<='$dateEnd' and (END>='$dateEnd' or END is NULL) and BILLING_GROUP='$billingGroup' and AUTOMATIC_BILLING='Y' and STAANN != 'D'";
            if ($conn->query($sql2) === FALSE) {
                echo $conn->error;
                die;
            }
            $result2 = mysqli_query($conn, $sql2);
            $length = $result2->num_rows;

            while($row2 = mysqli_fetch_array($result2)){

                $units = '€/mois';

                error_log(date("Y-m-d H:i:s")." - Units :".$units."\n", 3, "generate_invoices.log");


                $contractStart= new DateTime();
                $contractStart->setDate(substr($row2['START'], 0, 4), substr($row2['START'],5,2), substr($row2['START'], 8,2));
                $dateStartTemp = new DateTime();
                $dateStartTemp->setDate($currentDateObject->format("Y"), $currentDateObject->format("m"), $contractStart->format("d"));
                $temp1=$dateStartTemp->format('d-m-Y');

                if($dateStartTemp->format('m')==12){
                    $monthAfter2=1;
                    $yearAfter2=(($dateStartTemp->format('Y'))+1);
                }else{
                    $monthAfter2=(($dateStartTemp->format('m'))+1);
                    $yearAfter2=$dateStartTemp->format('Y');
                }
                $dayAfter2=$contractStart->format('d');
                $dateAfter2=new DateTime();
                $dateAfter2->setDate($yearAfter2, $monthAfter2, $dayAfter2);

                if($dateStartTemp >= $dateStartObject && ($dateStartTemp <= $dateEndObject || $dateStartTemp == $dateEndObject)){


                $temp2=$dateAfter2->format('d-m-Y');

                $temp3=$dateStartTemp->format('Y-m-d');
                $temp4=$dateAfter2->format('Y-m-d');

                if($row2['END']){
                    $contractEnd= new DateTime();
                    $contractEnd->setDate(substr($row2['END'], 0, 4), substr($row2['END'],5,2), substr($row2['END'], 8,2));
                }

                $comment='Période du '.$temp1.' au '.$temp2;
                $leasingPrice=$row2['AMOUNT'];
                $leasingPriceTVAC=1.21*$row2['AMOUNT'];
                $boxID=$row2['ID'];
                $sql="INSERT INTO factures_details (USR_MAJ, FACTURE_ID, ITEM_TYPE, ITEM_ID, COMMENTS, DATE_START, DATE_END, AMOUNT_HTVA, AMOUNT_TVAC) VALUES('script', '$newID', 'box', '$boxID', '$comment', '$temp3', '$temp4', '$leasingPrice', '$leasingPriceTVAC')";
                if ($conn->query($sql) === FALSE) {
                    echo $conn->error;
                }



                $difference=$dateStartTemp->diff($contractStart);
                $monthDifference=(($difference->format('%y'))*12+$difference->format('%m')+1);

                if($row2['END']){
                    $lengthLeasing=(($contractEnd->diff($contractStart))->format('%y'))*12+(($contractEnd->diff($contractStart))->format('%m'));
                }

                $test2.='<tr>
                    <td style="width: 20; text-align: left; border-top: solid 1px grey; border-bottom: solid 1px grey">'.$i.'</td>
                    <td style="width: 430; text-align: left; border-top: solid 1px grey; border-bottom: solid 1px grey">'.$row2['MODEL'].' - REFERENCE: '.$row2['REFERENCE'].'</td>
                    <td style="width: 150; text-align: left; border-top: solid 1px grey; border-bottom: solid 1px grey">'.round($row2['AMOUNT'],2).' '.$units.' HTVA</td>
                </tr>
                <tr>
                    <td></td>
                    <td style="color: grey">Période du '.$dateStartTemp->format('d-m-Y').' au '.$dateAfter2->format('d-m-Y').'</td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td><img class="img-responsive" src="'.$_SERVER['DOCUMENT_ROOT'].'/images_bikes/'.$row2['MODEL'].'_mini.png" alt=""></td>';
                if($row2['END']){
                  $test2=$test2.'<td>Période '.($monthDifference).'/'.($lengthLeasing).'</td></tr>';
                }else{
                  $test2=$test2.'<td>Location</td></tr>';
                }
                $i+=1;
                $total+=$row2['AMOUNT'];
              }
            }

            $tva=($total*0.21);
            $totalTVAIncluded=$total+$tva;

	        $test3='</tbody>
	      </table>

 		<br><br>
    <table style="border-collapse: collapse; background-color: #E4E4E4">
	   <tbody>
           <tr>
                <td width="100" height="35" style="border-bottom: solid 1px grey">Montant HTVA</td>
                <td width="70" style="border-bottom: solid 1px grey">% TVA</td>
                <td width="100" style="border-bottom: solid 1px grey">Montant TVA</td>
                <td width="100" style="border-bottom: solid 1px grey">Montant TVAC</td>
               <td style="background-color: white" witdth="100"></td>
               <td style="background-color: white"><p> Total HTVA: '.round($total,2).' €<br>+TVA: '.round($tva,2).' €</p></td>
            </tr>
            <tr>
                <td height="35">'.round($total,2).' €</td>
                <td>21%</td>
                <td>'.round($tva,2).' €</td>
                <td>'.round($totalTVAIncluded,2).' €</td>
                <td style="background-color: white" width="100"></td>
                <td style="background-color: white"> Total TVAC : <strong>'.round($totalTVAIncluded,2).' €</strong></td>
            </tr>
        </tbody>
    </table>

    <br><br>
    <div>
        <table style="border-collapse: collapse">
           <tbody>
               <tr>
                    <td width="400" height="35"><strong>Communication structurée lors du paiement</strong></td>
                    <td width="200" height="35"><strong>Délai de paiement</strong></td>
                </tr>
                <tr>
                    <td height="35"><strong>+++'.$reference.'+++</strong></td>
                    <td style="color: #3CB195" height="35"><strong>30 jours</strong></td>
                </tr>
            </tbody>
        </table>
        <br><br>

        <table style="border-collapse: collapse">
           <tbody>
               <tr>
                    <td width="400" height="20" style="color: grey">KAMEO Bikes SPRL</td>
                    <td width="200" height="20" style="color: grey">KAMEO Bikes SPRL</td>
                </tr>
                <tr>
                    <td width="400" height="20" style="color: grey">Belfius Bank : BE38 0689 0775 9672 </td>
                    <td width="200" height="20" style="color: grey">SWIFT/BIC Code : GKCCBEBB </td>
                </tr>
                <tr>
                    <td width="400" height="20" style="color: grey">TVA/VAT : BE0681.879.712</td>
                    <td width="200" height="20" style="color: grey">RPM : 0681.879.712 Liège (Belgium) </td>
                </tr>
            </tbody>
        </table>
    </div>
    <br><br>
    <p>Par ce paiement, vous adhérez à nos conditions générales de vente.<br>
    Les conditions générales de vente sont disponibles à l’URL suivante : <a href="https://www.kameobikes.com/docs/KAMEO-CGV.pdf">Lien</a>.<br>
    Elles sont supposées avoir été lues et acceptées par le client</p>



</page>';

echo $test1.$test2.$test3;


//error_log(date("Y-m-d H:i:s")." - <Result :".$test1.$test2.$test3."\n", 3, "generate_invoices.log");




$fileName=date('Y').'.'.date('m').'.'.date('d').'_'.$company.'_'.$newID.'_facture_'.$newIDOUT.'.pdf';

$sql3="select EMAIL_CONTACT, NOM_CONTACT, PRENOM_CONTACT, EMAIL_CONTACT_BILLING, FIRSTNAME_CONTACT_BILLING, LASTNAME_CONTACT_BILLING, PHONE_CONTACT_BILLING, BILLS_SENDING from companies where INTERNAL_REFERENCE='$company' and BILLING_GROUP='$billingGroup'";
if ($conn->query($sql3) === FALSE) {
    echo $conn->error;
    die;
}
$result3 = mysqli_query($conn, $sql3);
$resultat3 = mysqli_fetch_assoc($result3);

$sql= "INSERT INTO factures (ID, ID_OUT_BILL, USR_MAJ, COMPANY, BILLING_GROUP, BENEFICIARY_COMPANY, DATE, AMOUNT_HTVA, AMOUNT_TVAINC, COMMUNICATION_STRUCTUREE, FILE_NAME, FACTURE_SENT, FACTURE_PAID, FACTURE_LIMIT_PAID_DATE, TYPE, FACTURE_SENT_DATE) VALUES ('$newID', '$newIDOUT', 'facture.php', '$company', '$billingGroup', 'KAMEO', '$todayString', round($total,2), round($totalTVAIncluded,2), '$reference', '$fileName', '0', '0', '$inOneMonthString','leasing', '$todayString')";

if ($conn->query($sql) === FALSE) {
    echo $conn->error;
}
$conn->close();


?>
