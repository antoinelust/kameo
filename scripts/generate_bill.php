<?php
require_once dirname(__FILE__).'/../vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/globalfunctions.php';


$company=$_POST['company'];
$dateStart=$_POST['dateStart'];
$dateEnd=isset($_POST['dateEnd']) ? $_POST['dateEnd'] : NULL;

$billingGroup=$_POST['billingGroup'];
$itemNumber=$_POST['itemNumber'];

$totalTVA6=0;
$totalTVA21=0;


$dateStart=new DateTime($dateStart);
if($dateEnd && $dateEnd != NULL){
    $dateEnd=new DateTime($dateEnd);
}
$date1monthAfter=new DateTime('now');
$interval = new DateInterval('P30D');
$date1monthAfter->add($interval);
$date1monthAfterString=$date1monthAfter->format('Y-m-d');

$simulation='N';

include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';
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

include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';
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
$base_modulo=date('d').date('m').$reference;
$modulo_check=($base_modulo % 97);
$reference=substr('0000'.$base_modulo.$modulo_check, -12);
$reference=substr($reference, 0,3).'/'.substr($reference, 3,4).'/'.substr($reference, 7,5);

if($dateEnd && $dateEnd != NULL){

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
}


$monthFR=array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');



if ((file_exists($_SERVER['DOCUMENT_ROOT'].'/images/'.$company.'.jpg'))){
    $fichier=$company;
}else{
    $fichier="default";
}



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
				<br>BE38 0689 0775 9672</p>

				<p>Liège, le '.date('d/m/Y').'</p>


        </td>
        <td style="width: 50%">
				<img class="img-responsive" src="'.$_SERVER['DOCUMENT_ROOT'].'/images/'.$fichier.'.jpg" alt="">

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
            <h4 style="color: #C72C28">Référence : +++'.$reference.'+++</h4>

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
                $price=floatval($_POST['price'.$i]);
                $TVA=($_POST['TVA'.$i]);
                $priceTVAC=(1+$TVA/100)*$price;
                $type=$_POST['type'.$i];

                $ID=isset($_POST['ID'.$i]) ? $_POST['ID'.$i] : NULL;
                $description=isset($_POST['description'.$i]) ? $_POST['description'.$i] : NULL;


                if($type=="bike"){
                    include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';
                    $sql="SELECT MODEL, FRAME_REFERENCE, TYPE, substr(CONTRACT_START,9,2) as 'firstDay', FRAME_NUMBER from customer_bikes where ID='$ID'";
                    if ($conn->query($sql) === FALSE) {
                        echo $conn->error;
                        die;
                    }
                    $result = mysqli_query($conn, $sql);
                    $resultat = mysqli_fetch_assoc($result);
                    $conn->close();
                    $catalogID=$resultat['TYPE'];

                    $contractStart=$dateStart;
                    $contractEnd=$dateEnd;


                    include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';
                    $sql2="SELECT * from bike_catalog where ID='$catalogID'";
                    if ($conn->query($sql2) === FALSE) {
                        echo $conn->error;
                        die;
                    }
                    $result2 = mysqli_query($conn, $sql2);
                    $resultat2 = mysqli_fetch_assoc($result2);
                    $conn->close();

                    $img=$resultat2['ID'];



                    $contractStart->setDate($dateStart->format('Y'), $dateStart->format('m'), $resultat['firstDay']);
                    $contractEnd->setDate($dateEnd->format('Y'), $dateEnd->format('m'), $resultat['firstDay']);

                    $contractStartString=$contractStart->format('d-m-Y');
                    $contractStartString2=$contractStart->format('Y-m-d');
                    $contractEndString=$contractEnd->format('d-m-Y');
                    $contractEndString2=$contractEnd->format('Y-m-d');


                    $comment='Période du '.$contractStartString.' au '.$contractEndString;

                    $test2.='<tr>
                        <td style="width: 20; text-align: left; border-top: solid 1px grey; border-bottom: solid 1px grey">'.($i+1).'</td>
                        <td style="width: 430; text-align: left; border-top: solid 1px grey; border-bottom: solid 1px grey">'.$resultat['MODEL'].' - CADRE: '.$resultat['FRAME_REFERENCE'].'</td>
                        <td style="width: 150; text-align: left; border-top: solid 1px grey; border-bottom: solid 1px grey">'.($price).' € HTVA</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="color: grey">'.$comment.'</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><img class="img-responsive" src="'.$_SERVER['DOCUMENT_ROOT'].'/images_bikes/'.$img.'_mini.jpg" width="200" alt=""></td>
                        ';

                    $test2=$test2."<td>Location </td></tr>";
                    if(!$simulation || $simulation == 'N'){
                        include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';
                        $sql="INSERT INTO factures_details (USR_MAJ, FACTURE_ID, ITEM_TYPE, ITEM_ID, COMMENTS, DATE_START, DATE_END, AMOUNT_HTVA, AMOUNT_TVAC) VALUES('script', '$newID', 'bike', '$ID', '$comment', '$contractStartString2', '$contractEndString2', '$price', '$priceTVAC')";
                        if ($conn->query($sql) === FALSE) {
                            $response = array ('response'=>'error', 'message'=> $conn->error);
                            echo json_encode($response);
                            die;
                        }
                        $conn->close();
                    }


                }else if($type=="bikeSell"){
                    include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';
                    $sql2="SELECT * from customer_bikes where ID='$ID'";
                    if ($conn->query($sql2) === FALSE) {
                        echo $conn->error;
                        die;
                    }
                    $result2 = mysqli_query($conn, $sql2);
                    $resultat2 = mysqli_fetch_assoc($result2);
                    $conn->close();
                    $catalogID=$resultat2['TYPE'];

                    include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';
                    $sql3="SELECT * from bike_catalog where ID='$catalogID'";
                    if ($conn->query($sql3) === FALSE) {
                        echo $conn->error;
                        die;
                    }
                    $result3 = mysqli_query($conn, $sql3);
                    $resultat3 = mysqli_fetch_assoc($result3);
                    $conn->close();



                    $img=$resultat3['ID'];
                    $comment='Vente au '.$dateStart->format('d-m-Y');

                    $test2.='<tr>
                        <td style="width: 20; text-align: left; border-top: solid 1px grey; border-bottom: solid 1px grey">'.($i+1).'</td>
                        <td style="width: 430; text-align: left; border-top: solid 1px grey; border-bottom: solid 1px grey">'.$resultat2['MODEL'].'</td>
                        <td style="width: 150; text-align: left; border-top: solid 1px grey; border-bottom: solid 1px grey">'.($price).' € HTVA</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="color: grey">'.$comment.'</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><img class="img-responsive" src="'.$_SERVER['DOCUMENT_ROOT'].'/images_bikes/'.$img.'_mini.jpg" alt="" width="200"></td>
                        ';

                    $test2=$test2."<td>Vente </td></tr>";

                    $contractStartString=$dateStart->format('Y-m-d');

                    if(!$simulation || $simulation == 'N'){
                        include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';
                        $sql="INSERT INTO factures_details (USR_MAJ, FACTURE_ID, ITEM_TYPE, ITEM_ID, COMMENTS, DATE_START, DATE_END, AMOUNT_HTVA, AMOUNT_TVAC) VALUES('script', '$newID', 'bike', '$ID', '$comment', '$contractStartString', '$contractStartString', '$price', '$priceTVAC')";
                        if ($conn->query($sql) === FALSE) {
                            $response = array ('response'=>'error', 'message'=> $conn->error);
                            echo json_encode($response);
                            die;
                        }
                        $conn->close();


                        include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';
                        $sql="UPDATE customer_bikes SET HEU_MAJ=CURRENT_TIMESTAMP, CONTRACT_TYPE='selling', SELLING_DATE='$contractStartString', SOLD_PRICE='$price', COMPANY='$company' WHERE ID='$ID'";
                        if ($conn->query($sql) === FALSE) {
                            $response = array ('response'=>'error', 'message'=> $conn->error);
                            echo json_encode($response);
                            die;
                        }
                        $conn->close();

                    }
                }else if($type=="accessorySell"){

                    include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';
                    $sql2="SELECT * from accessories_catalog where ID='$ID'";
                    if ($conn->query($sql2) === FALSE) {
                        echo $conn->error;
                        die;
                    }
                    $result2 = mysqli_query($conn, $sql2);
                    $resultat2 = mysqli_fetch_assoc($result2);
                    $conn->close();

                    $comment='Vente au '.$dateStart->format('d-m-Y');

                    $test2.='<tr>
                        <td style="width: 20; text-align: left; border-top: solid 1px grey; border-bottom: solid 1px grey">'.($i+1).'</td>
                        <td style="width: 430; text-align: left; border-top: solid 1px grey; border-bottom: solid 1px grey">'.$resultat2['BRAND'].' - '.$resultat2['MODEL'].'</td>
                        <td style="width: 150; text-align: left; border-top: solid 1px grey; border-bottom: solid 1px grey">'.($price).' € HTVA</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="color: grey">'.$comment.'</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        ';
                    $test2=$test2."<td></td></tr>";
                }else if($type=="otherAccessorySell"){

                    $comment='Vente au '.$dateStart->format('d-m-Y');

                    $test2.='<tr>
                        <td style="width: 20; text-align: left; border-top: solid 1px grey; border-bottom: solid 1px grey">'.($i+1).'</td>
                        <td style="width: 430; text-align: left; border-top: solid 1px grey; border-bottom: solid 1px grey">'.$description.'</td>
                        <td style="width: 150; text-align: left; border-top: solid 1px grey; border-bottom: solid 1px grey">'.($price).' € HTVA</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="color: grey">'.$comment.'</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        ';
                    $test2=$test2."<td>Vente<br><br></td></tr>";
                }else if($type=="maintenance"){

                    $test2.='<tr>
                        <td style="width: 20; text-align: left; border-top: solid 1px grey; border-bottom: solid 1px grey">'.($i+1).'</td>
                        <td style="width: 430; text-align: left; border-top: solid 1px grey; border-bottom: solid 1px grey">'.$description.'</td>
                        <td style="width: 150; text-align: left; border-top: solid 1px grey; border-bottom: solid 1px grey">'.($price).' € HTVA</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="color: grey">T.V.A. 6%</td>
                        <td></td>
                    </tr>

                    <tr>
                        <td></td>
                        <td></td>
                        ';
                    $test2=$test2."<td>Main d'oeuvre<br><br></td></tr>";
                }

                $i+=1;
                if($TVA == "21")
                {
                  $totalTVA21+=$price;
                }else if($TVA=="6"){
                  $totalTVA6+=$price;
                }
            }
            $fileName=date('Y').'.'.date('m').'.'.date('d').'_'.$company.'_'.$newID.'_facture_'.$newIDOUT.'.pdf';

            $tva6=($totalTVA6*0.06);
            $tva21=($totalTVA21*0.21);

            $totalTVAIncluded6=$totalTVA6+$tva6;
            $totalTVAIncluded21=$totalTVA21+$tva21;

	        $test3='</tbody>
	      </table>

 		<br><br>
    <table style="border-collapse: collapse; background-color: #E4E4E4">
	   <tbody>
           <tr>
                <th width="100" height="35" style="border-bottom: solid 1px grey">Montant HTVA</th>
                <th width="70" style="border-bottom: solid 1px grey">% TVA</th>
                <th width="100" style="border-bottom: solid 1px grey">Montant TVA</th>
                <th width="100" style="border-bottom: solid 1px grey">Montant TVAC</th>
                <th width="100" style="border-bottom: solid 1px grey">TOTAL</th>
            </tr>';
    if($totalTVAIncluded21 != 0){
      $test3.='<tr><td height="35">'.round($totalTVA21,2).' €</td>
                <td>21%</td>
                <td>'.round($tva21,2).' €</td>
                <td>'.round($totalTVAIncluded21,2).' €</td>
                <td></td>
              </tr>';
    }
    if($totalTVAIncluded6 != 0){
      $test3.='<tr><td height="35">'.round($totalTVA6,2).' €</td>
                <td>6%</td>
                <td>'.round($tva6,2).' €</td>
                <td>'.round($totalTVAIncluded6,2).' €</td>
                <td></td>
                </tr>';
    }

    $test3.='<tr><td height="35"></td>
              <td></td>
              <td></td>
              <td></td>
              <td>'.round($totalTVAIncluded6+$totalTVAIncluded21,2).' €</td>
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
    <p>Par ce paiement, vous adhérez à nos conditions générales de vente.</p>



</page>';

error_log("test3 :".$test3."\n", 3, "generate_invoices.log");

include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';
$sql= "INSERT INTO factures (ID, ID_OUT_BILL, USR_MAJ, COMPANY, BILLING_GROUP, BENEFICIARY_COMPANY, DATE, AMOUNT_HTVA, AMOUNT_TVAINC, COMMUNICATION_STRUCTUREE, FILE_NAME, FACTURE_SENT, FACTURE_PAID, FACTURE_LIMIT_PAID_DATE, TYPE, FACTURE_SENT_DATE) VALUES ('$newID', '$newIDOUT', 'facture.php', '$company', '$billingGroup', 'KAMEO', '$today', round($totalTVA6+$totalTVA21,2), round($totalTVAIncluded6+$totalTVAIncluded21,2), '$reference', '$fileName', '0', '0', '$date1monthAfterString','leasing', NULL)";

if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$conn->close();

echo $test1.$test2.$test3;

error_log(date("Y-m-d H:i:s")." - <Result :".$test1.$test2.$test3."\n", 3, "generate_bill.log");



?>
