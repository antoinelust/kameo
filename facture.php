<?php 

$company = file_get_contents(__DIR__.'/temp/company.txt');
$billingGroup = file_get_contents(__DIR__.'/temp/billingGroup.txt');



if ((file_exists(__DIR__.'/temp/date.txt'))) {   
   $date = file_get_contents(__DIR__.'/temp/date.txt');
}else{
    $date=date("d");
}if ((file_exists(__DIR__.'/temp/month.txt'))) {   
    $month = file_get_contents(__DIR__.'/temp/month.txt');
}else{
    $month=date("m");
}if ((file_exists(__DIR__.'/temp/year.txt'))) {   
    $year = file_get_contents(__DIR__.'/temp/year.txt');
}else{
    $year=date("Y");
}if ((file_exists(__DIR__.'/temp/simulation.txt'))) {   
   $simulation = file_get_contents(__DIR__.'/temp/simulation.txt');
}else{
    $simulation=null;
}

$mktime=mktime(0, 0, 0, $month, $date, $year);
$currentDate=new DateTime($year.'-'.$month.'-'.$date);
$currentDateString = $currentDate->format('Y-m-d');

include 'include/connexion.php';
$sql_reference="select max(ID) as MAX_TOTAL, max(ID_OUT_BILL) as MAX_OUT from factures";
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


$today=date('Y-m-d');
$OneMonthAfter=clone $currentDate;

$OneMonthAfter->add(new DateInterval("P1M"));
$OneMonthAfterString=$OneMonthAfter->format('Y-m-d');

include 'include/connexion.php';
$sql="select * from companies where INTERNAL_REFERENCE='$company' and BILLING_GROUP='$billingGroup'";
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

$length=strlen($newID);
$i=(3-$length);
$reference=$newID;
while($i>0){
    $i-=1;
    $reference="0".$reference;
}

$month=$currentDate->format('m');
$year=$currentDate->format('Y');


$base_modulo=$month.substr($year,2,2).$reference;
$modulo_check=($base_modulo % 97);

$reference='000/'.$month.substr($year,2,2).'/'.$reference.$modulo_check;

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
        
                <img class="img-responsive" src="'.__DIR__.'/images/logo-dark.png" alt="">
				
				<p>KAMEO Bikes sprl</p>
				
				<p>Quai Marcellis, 24
				<br>B-4000 Liège
				<br>Belgium</p>
				
				<p>TVA/VAT : BE0681.879.712
				<br>BE38 0689 0775 9672</p>
				
				<p>Liège, le '.date('d/m/Y').'</p>

        
        </td>
        <td style="width: 50%">
				<img class="img-responsive" src="'.__DIR__.'/images/'.$company.'.jpg" alt="">
				
				<p>'.$companyName.'</p>
				
				<p>'.$street.'
				<br>'.$zip.' '.$town.'
				<br>Belgium</p>
				
				<p>TVA/VAT : '.$vat.'</p>
				
				<p>Référence client : '.$company.'</p>
        </td>
    
    </tr>
    <tr>
        <td>
            <h4 style="color: #3CB195">FACTURE</h4>

        </td>
        <td>
            <h4 style="color: #C72C28">Référence : '.$reference.'</h4>
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

            $monthAfter=clone $currentDate;
            $monthAfter->add(new DateInterval("P1M"));
            $monthAfterString=$monthAfter->format('Y-m-d');

            
            include 'include/connexion.php';
            $sql2="select * from customer_bikes where COMPANY='$company' and CONTRACT_START<='$currentDateString' and (CONTRACT_END>='$monthAfterString' or CONTRACT_END IS NULL) and BILLING_GROUP='$billingGroup' and AUTOMATIC_BILLING='Y' and STAANN !='D'";
            if ($conn->query($sql2) === FALSE) {
                echo $conn->error;
                die;
            }
            $result2 = mysqli_query($conn, $sql2);   
            $length = $result2->num_rows;
            $conn->close();

            
            $i=0;
            $total=0;

            while($row2 = mysqli_fetch_array($result2)){
                
                $contractStart= new DateTime();
                $contractStart->setDate(substr($row2['CONTRACT_START'], 0, 4), substr($row2['CONTRACT_START'],5,2), substr($row2['CONTRACT_START'], 8,2));                
                
                
                
                $dateStart = new DateTime();
                $dateStart->setDate($currentDate->format('Y'), $currentDate->format('m'), $contractStart->format('d'));
                
                $dateEnd = new DateTime();
                $dateEnd->setDate($monthAfter->format('Y'), $monthAfter->format('m'), $contractStart->format('d'));
                $temp1=$dateStart->format('d-m-Y');                
                $temp2=$dateEnd->format('d-m-Y');      
                $comment='Période du '.$temp1.' au '.$temp2;
                $leasingPrice=$row2['LEASING_PRICE'];
                $leasingPriceTVAC=1.21*$row2['LEASING_PRICE'];
                $frameNumber=$row2['FRAME_NUMBER'];
                $bikeID=$row2['ID'];
                
                if(!$simulation || $simulation == 'N'){
                    include 'include/connexion.php';
                    $sql="INSERT INTO factures_details (USR_MAJ, FACTURE_ID, BIKE_ID, FRAME_NUMBER, COMMENTS, AMOUNT_HTVA, AMOUNT_TVAC) VALUES('script', '$newID', '$bikeID','$frameNumber', '$comment', '$leasingPrice', '$leasingPriceTVAC')";
                    if ($conn->query($sql) === FALSE) {
                        echo $conn->error;
                    } 
                    $conn->close();
                }
                

                
                $difference=$dateStart->diff($contractStart);
                
                $monthDifference=(($difference->format('%y'))*12+$difference->format('%m')+1);
                
                if($row2['CONTRACT_END']){
                    $contractEnd= new DateTime();
                    $contractEnd->setDate(substr($row2['CONTRACT_END'], 0, 4), substr($row2['CONTRACT_END'],5,2), substr($row2['CONTRACT_END'], 8,2));                
                    $numberOfMonthContract=$contractEnd->diff($contractStart);
                    $numberOfMonthContract=(($numberOfMonthContract->format('%y'))*12+$numberOfMonthContract->format('%m'));
                }
                
                
                $test2.='<tr>
                    <td style="width: 20; text-align: left; border-top: solid 1px grey; border-bottom: solid 1px grey">'.$i.'</td>
                    <td style="width: 430; text-align: left; border-top: solid 1px grey; border-bottom: solid 1px grey">'.$row2['MODEL'].' - CADRE: '.$row2['FRAME_REFERENCE'].'</td>
                    <td style="width: 150; text-align: left; border-top: solid 1px grey; border-bottom: solid 1px grey">'.round($row2['LEASING_PRICE'],2).' € / mois HTVA</td>
                </tr>
                <tr>
                    <td></td>
                    <td style="color: grey">Période du '.$dateStart->format('d-m-Y').' au '.$dateEnd->format('d-m-Y').'</td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td><img class="img-responsive" src="'.__DIR__.'/images_bikes/'.$row2['FRAME_NUMBER'].'_mini.jpg" alt=""></td>
                    ';
                if(($row2['CONTRACT_END'])){
                    $test2=$test2.'<td>Période '.($monthDifference).'/'.$numberOfMonthContract.'</td></tr>';
                }else{
                    $test2=$test2."<td>Location</td></tr>";
                }
                
                $i+=1;
                $total+=$row2['LEASING_PRICE'];
                
                

            }

            include 'include/connexion.php';
            $sql2="select * from boxes where COMPANY='$company' and START<='$currentDateString' and (END>='$monthAfterString' or END is NULL) and BILLING_GROUP='$billingGroup' and AUTOMATIC_BILLING='Y' and STAANN != 'D'";
            if ($conn->query($sql2) === FALSE) {
                echo $conn->error;
                die;
            }
            $result2 = mysqli_query($conn, $sql2);   
            $length = $result2->num_rows;
            $conn->close();

            
            while($row2 = mysqli_fetch_array($result2)){
                
                $contractStart= new DateTime();
                $contractStart->setDate(substr($row2['START'], 0, 4), substr($row2['START'],5,2), substr($row2['START'], 8,2));      
                $dateStart = new DateTime();
                $dateStart->setDate($currentDate->format('Y'), $currentDate->format('m'), $contractStart->format('d'));
                $dateEnd = new DateTime();
                $dateEnd->setDate($monthAfter->format('Y'), $monthAfter->format('m'), $contractStart->format('d'));
                $temp1=$dateStart->format('d-m-Y');                
                $temp2=$dateEnd->format('d-m-Y');                
                
                if($row2['END']){
                    $contractEnd= new DateTime();
                    $contractEnd->setDate(substr($row2['END'], 0, 4), substr($row2['END'],5,2), substr($row2['END'], 8,2));                
                }
                
                
                $comment='Période du '.$temp1.' au '.$temp2;
                $leasingPrice=$row2['AMOUNT'];
                $leasingPriceTVAC=1.21*$row2['AMOUNT'];
                $reference2=$row2['REFERENCE'];
                $boxID=$row2['ID'];
                if(!$simulation || $simulation == 'N'){
                    include 'include/connexion.php';
                    $sql="INSERT INTO factures_details (USR_MAJ, FACTURE_ID, BIKE_ID, FRAME_NUMBER, COMMENTS, AMOUNT_HTVA, AMOUNT_TVAC) VALUES('script', '$newID', '$boxID','$reference2', '$comment', '$leasingPrice', '$leasingPriceTVAC')";
                    if ($conn->query($sql) === FALSE) {
                        echo $conn->error;
                    } 
                    $conn->close();
                }
                

                
                $difference=$dateStart->diff($contractStart);
                
                $monthDifference=(($difference->format('%y'))*12+$difference->format('%m')+1);
                
                if($row2['END']){
                    $lengthLeasing=(($contractEnd->diff($contractStart))->format('%y'))*12+(($contractEnd->diff($contractStart))->format('%m'));
                }
                
                $test2.='<tr>
                    <td style="width: 20; text-align: left; border-top: solid 1px grey; border-bottom: solid 1px grey">'.$i.'</td>
                    <td style="width: 430; text-align: left; border-top: solid 1px grey; border-bottom: solid 1px grey">'.$row2['MODEL'].' - REFERENCE: '.$row2['REFERENCE'].'</td>
                    <td style="width: 150; text-align: left; border-top: solid 1px grey; border-bottom: solid 1px grey">'.round($row2['AMOUNT'],2).' € / mois HTVA</td>
                </tr>
                <tr>
                    <td></td>
                    <td style="color: grey">Période du '.$dateStart->format('d-m-Y').' au '.$dateEnd->format('d-m-Y').'</td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td><img class="img-responsive" src="'.__DIR__.'/images_bikes/'.$row2['MODEL'].'_mini.png" alt=""></td>';
                if($row2['END']){
                    $test2=$test2.'<td>Période '.($monthDifference).'/'.($lengthLeasing).'</td></tr>';
                }else{
                    $test2=$test2.'<td>Location</td></tr>';
                }
                $i+=1;
                $total+=$row2['AMOUNT'];
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
               <td style="background-color: white"><p> Total HTVA: '.round($total,2).' €/mois<br>+TVA: '.round($tva,2).' /mois </p></td>
            </tr>
            <tr>
                <td height="35">'.round($total,2).' €/mois</td>
                <td>21%</td>
                <td>'.round($tva,2).' €/mois</td>
                <td>'.round($totalTVAIncluded,2).' €/mois</td>
                <td style="background-color: white" width="100"></td>
                <td style="background-color: white"> Total TVAC : <strong>'.round($totalTVAIncluded,2).' €/mois</strong></td>
            </tr>
        </tbody>
    </table>
    
    <br><br>
    <div>
        <table style="border-collapse: collapse">
           <tbody>
               <tr>
                    <td width="400" height="35"><strong>Communication libre lors du paiements</strong></td>
                    <td width="200" height="35"><strong>Délai de paiement</strong></td>
                </tr>
                <tr>
                    <td height="35"><strong>'.$reference.'</strong></td>
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
    <p>Par ce paiement, vous adhérez à nos conditions générales de vente.</p>



</page>';

echo $test1.$test2.$test3;






$fileName=date('Y').'.'.date('m').'.'.date('d').'_'.$company.'_'.$newID.'_facture_'.$newIDOUT.'.pdf';

include 'include/connexion.php';
$sql3="select EMAIL_CONTACT, NOM_CONTACT, PRENOM_CONTACT, EMAIL_CONTACT_BILLING, FIRSTNAME_CONTACT_BILLING, LASTNAME_CONTACT_BILLING, PHONE_CONTACT_BILLING, BILLS_SENDING from companies where INTERNAL_REFERENCE='$company' and BILLING_GROUP='$billingGroup'";
if ($conn->query($sql3) === FALSE) {
    echo $conn->error;
    die;
}
$result3 = mysqli_query($conn, $sql3);   
$resultat3 = mysqli_fetch_assoc($result3);


if(!$simulation || $simulation == 'N'){
    if($resultat3['BILLS_SENDING'] == "Y" && $resultat3['EMAIL_CONTACT_BILLING'] != "" && $resultat3['LASTNAME_CONTACT_BILLING'] != ""){
        $sql= "INSERT INTO factures (ID, ID_OUT_BILL, USR_MAJ, COMPANY, BENEFICIARY_COMPANY, DATE, AMOUNT_HTVA, AMOUNT_TVAINC, COMMUNICATION_STRUCTUREE, FILE_NAME, FACTURE_SENT, FACTURE_PAID, FACTURE_LIMIT_PAID_DATE, TYPE) VALUES ('$newID', '$newIDOUT', 'facture.php', '$company', 'KAMEO', '$today', round($total,2), round($totalTVAIncluded,2), '$reference', '$fileName', '0', '0', '$OneMonthAfterString','leasing')";
    }else{
        $sql= "INSERT INTO factures (ID, ID_OUT_BILL, USR_MAJ, COMPANY, BENEFICIARY_COMPANY, DATE, AMOUNT_HTVA, AMOUNT_TVAINC, COMMUNICATION_STRUCTUREE, FILE_NAME, FACTURE_SENT, FACTURE_PAID, FACTURE_LIMIT_PAID_DATE, TYPE, FACTURE_SENT_DATE) VALUES ('$newID', '$newIDOUT', 'facture.php', '$company', 'KAMEO', '$today', round($total,2), round($totalTVAIncluded,2), '$reference', '$fileName', '0', '0', '$OneMonthAfterString','leasing', '$today')";
    }
}

include 'include/connexion.php';
if ($conn->query($sql) === FALSE) {
    echo $conn->error;
} 
$conn->close();


?>