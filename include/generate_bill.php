<?php 
require_once dirname(__FILE__).'/../vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
include 'globalfunctions.php';

$company=$_POST['company'];
$dateStart=$_POST['dateStart'];
$dateEnd=$_POST['dateEnd'];
$billingGroup=$_POST['billingGroup'];
$itemNumber=$_POST['itemNumber'];

$dateStart=new DateTime($dateStart);
$dateStartString1 = $dateStart->format('Y-m-d');
$dateStartString2 = $dateStart->format('d-m-Y');

$dateEnd=new DateTime($dateEnd);
$dateEndString1 = $dateEnd->format('Y-m-d');
$dateEndString2 = $dateEnd->format('d-m-Y');

$date1monthAfter=new DateTime('now');
$interval = new DateInterval('P30D');
$date1monthAfter->add($interval);
$date1monthAfterString=$date1monthAfter->format('Y-m-d');



$simulation='N';

include 'connexion.php';
$sql_reference="select max(ID) as MAX_TOTAL, max(ID_OUT_BILL) as MAX_OUT from factures";
error_log("SQL4 :".$sql_reference."\n", 3, "generate_invoices.log");    

if ($conn->query($sql_reference) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result_reference = mysqli_query($conn, $sql_reference);   
$resultat_reference = mysqli_fetch_assoc($result_reference);
$newID=$resultat_reference['MAX_TOTAL'];
$newID=strval($newID+1);

$newIDOUT=$resultat_reference['MAX_OUT'];
$newIDOUT=strval($newIDOUT+1);


$today=date('Y-m-d');

include 'connexion.php';
$sql="select * from companies where INTERNAL_REFERENCE='$company' and BILLING_GROUP='$billingGroup'";
error_log("SQL5 :".$sql."\n", 3, "generate_invoices.log");    

if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result = mysqli_query($conn, $sql);   
$length = $result->num_rows;
if($length=='0'){
    $sql="select * from companies where INTERNAL_REFERENCE='$company' and BILLING_GROUP='1'";
    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
}

$resultat = mysqli_fetch_assoc($result);

$companyName=$resultat['COMPANY_NAME'];
$street=$resultat['STREET'];
$zip=$resultat['ZIP_CODE'];
$town=$resultat['TOWN'];
$vat=$resultat['VAT_NUMBER'];

$length=strlen($newID);
$i=(3-$length);
$reference=$newID;
while($i>0){
    $i-=1;
    $reference="0".$reference;
}


$base_modulo=$dateStart->format('m').substr($dateStart->format('Y'),2,2).$reference;
$modulo_check=($base_modulo % 97);

$reference='000/'.$dateStart->format('m').substr($dateStart->format('Y'),2,2).'/'.$reference.$modulo_check;



if($dateEnd->format('m')==12){
    $monthAfter=1;
    $yearAfter=(($dateEnd->format('Y'))+1);
}else{
    $monthAfter=(($dateEnd->format('m'))+1);
    $yearAfter=$dateEnd->format('Y');
}
$dayAfter=$dateEnd->format('d');

if(strlen($monthAfter)==1){
    $monthAfter='0'.$monthAfter;
}
if(strlen($dayAfter)==1){
    $dayAfter='0'.$dayAfter;
}


$lastDayMonth=last_day_month( $monthAfter);
if($lastDayMonth < $dayAfter){
    $dayAfter=$lastDayMonth;
}



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
        
                <img class="img-responsive" src="./images/logo-dark.png" alt="">
				
				<p>KAMEO Bikes sprl</p>
				
				<p>Quai Marcellis, 24
				<br>B-4000 Liège
				<br>Belgium</p>
				
				<p>TVA/VAT : BE0681.879.712
				<br>BE38 0689 0775 9672</p>
				
				<p>Liège, le '.date('d/m/Y').'</p>

        
        </td>
        <td style="width: 50%">
				<img class="img-responsive" src="./images/'.$company.'.jpg" alt="">
				
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
            <h4 style="color: #3CB195">FACTURE n°'.$newIDOUT.'</h4>

        </td>
        <td>
            <h4 style="color: #C72C28">Référence : '.$reference.'</h4>

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

            
            $i=0;
            $total=0;



            while($i<$itemNumber){
                
                $price=intval($_POST['price'.$i]);
                $priceTVAC=1.21*$price;
                $type=$_POST['type'.$i];
                $ID=$_POST['ID'.$i];
                $description=$_POST['description'.$i];
                
                
                
                if($type=="bike"){
                    include 'connexion.php';
                    $sql="SELECT MODEL, FRAME_REFERENCE, substr(CONTRACT_START,9,2) as 'firstDay', FRAME_NUMBER from customer_bikes where ID='$ID'";
                    if ($conn->query($sql) === FALSE) {
                        echo $conn->error;
                        die;
                    }
                    $result = mysqli_query($conn, $sql);
                    $resultat = mysqli_fetch_assoc($result);
                    $conn->close();     
                    
                    $contractStart=$dateStart;
                    $contractEnd=$dateEnd;
                    
                    
                    
                    $contractStart->setDate($dateStart->format('Y'), $dateStart->format('m'), $resultat['firstDay']);
                    $contractEnd->setDate($dateEnd->format('Y'), $dateEnd->format('m'), $resultat['firstDay']);
                    
                    $contractStartString=$contractStart->format('d-m-Y');
                    $contractStartString2=$contractStart->format('Y-m-d');
                    $contractEndString=$contractEnd->format('d-m-Y');
                    $contractEndString2=$contractEnd->format('Y-m-d');
                    
                    
                    $comment='Période du '.$contractStartString.' au '.$contractEndString;
                    
                    
                }
                
                
                
                
                
                
                
                $test2.='<tr>
                    <td style="width: 20; text-align: left; border-top: solid 1px grey; border-bottom: solid 1px grey">'.$i.'</td>
                    <td style="width: 430; text-align: left; border-top: solid 1px grey; border-bottom: solid 1px grey">'.$resultat['MODEL'].' - CADRE: '.$resultat['FRAME_REFERENCE'].'</td>
                    <td style="width: 150; text-align: left; border-top: solid 1px grey; border-bottom: solid 1px grey">'.round($price).' € HTVA</td>
                </tr>
                <tr>
                    <td></td>
                    <td style="color: grey">Période du '.$contractStartString.' au '.$contractStartString.'</td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td><img class="img-responsive" src="./images_bikes/'.$resultat['FRAME_NUMBER'].'_mini.jpg" alt=""></td>
                    ';
                
                $test2=$test2."<td>Location </td></tr>";
                
                if(!$simulation || $simulation == 'N'){
                    include 'connexion.php';
                    $sql="INSERT INTO factures_details (USR_MAJ, FACTURE_ID, BIKE_ID, FRAME_NUMBER, COMMENTS, DATE_START, DATE_END, AMOUNT_HTVA, AMOUNT_TVAC) VALUES('script', '$newID', '$ID','$description', '$comment', '$contractStartString2', '$contractEndString2', '$price', '$priceTVAC')";
                    if ($conn->query($sql) === FALSE) {
                        $response = array ('response'=>'error', 'message'=> $conn->error);
                        echo json_encode($response);
                        die;
                    } 
                    $conn->close();
                    
                    $fileName=date('Y').'.'.date('m').'.'.date('d').'_'.$company.'_'.$newID.'_facture_'.$newIDOUT.'.pdf';

                    
                }                
                
                
                $i+=1;
                $total+=$price;
                
                

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
               <td style="background-color: white"><p> Total HTVA: '.round($total,2).' €<br>+TVA: '.round($tva,2).'</p></td>
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

include 'connexion.php';
$sql= "INSERT INTO factures (ID, ID_OUT_BILL, USR_MAJ, COMPANY, BILLING_GROUP, BENEFICIARY_COMPANY, DATE, AMOUNT_HTVA, AMOUNT_TVAINC, COMMUNICATION_STRUCTUREE, FILE_NAME, FACTURE_SENT, FACTURE_PAID, FACTURE_LIMIT_PAID_DATE, TYPE, FACTURE_SENT_DATE) VALUES ('$newID', '$newIDOUT', 'facture.php', '$company', '$billingGroup', 'KAMEO', '$today', round($total,2), round($totalTVAIncluded,2), '$reference', '$fileName', '0', '0', '$date1monthAfterString','leasing', NULL)";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
} 
$conn->close();
                    


echo $test1.$test2.$test3;

?>