<?php
require_once dirname(__FILE__).'/../vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
include_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/globalfunctions.php';

$company=$_POST['company'];

$itemNumber=$_POST['itemNumber'];
$commentDevis=$_POST['commentDevis'];

$totalTVA6=0;
$totalTVA21=0;

$dateStart=new DateTime();
$date1monthAfter=new DateTime('now');
$interval = new DateInterval('P30D');
$date1monthAfter->add($interval);
$date1monthAfterString=$date1monthAfter->format('Y-m-d');
$date=$dateStart->format('Y-m-d');

$simulation='N';

include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';

$today=date('Y-m-d');

$sql_reference="select max(ID) as MAX_TOTAL from devis_entretien";

if ($conn->query($sql_reference) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}
$result_reference = mysqli_query($conn, $sql_reference);
$resultat_reference = mysqli_fetch_assoc($result_reference);
$reference=$resultat_reference['MAX_TOTAL'];
$reference=strval($reference+1);
$idDevis= $reference;

include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';
$sql="select * from companies where INTERNAL_REFERENCE='$company'";
if ($conn->query($sql) === FALSE) {
    $response = array ('response'=>'error', 'message'=> $conn->error);
    echo json_encode($response);
    die;
}

$result = mysqli_query($conn, $sql);
$length = $result->num_rows;
$resultat = mysqli_fetch_assoc($result);
$companyName=$resultat['COMPANY_NAME'];
$street=$resultat['STREET'];
$zip=$resultat['ZIP_CODE'];
$town=$resultat['TOWN'];
$vat=$resultat['VAT_NUMBER'];

$monthFR=array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');



if ((file_exists($_SERVER['DOCUMENT_ROOT'].'/images/'.$resultat['INTERNAL_REFERENCE'].'.jpg'))){
    $fichier=$company;
}else{
    $fichier="default";
}



$test1='<page backtop="10mm" backbottom="10mm" backleft="20mm" backright="20mm">
    <page_header>
        <table style="width: 100%; border: solid 1px black;">
            <tr>
                <td style="text-align: left;    width: 33%">KAMEO Bikes</td>
                <td style="text-align: center;    width: 34%">Devis déstiné à  '.$companyName.'</td>
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
            <h4 style="color: #3CB195">DEVIS n°'.$idDevis.'</h4>

        </td>
        <td>

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
                $priceTVAC=round((1+$TVA/100)*$price, 2);
                $type=$_POST['type'.$i];

                $ID=isset($_POST['ID'.$i]) ? $_POST['ID'.$i] : NULL;
                $description=isset($_POST['description'.$i]) ? $_POST['description'.$i] : NULL;
                error_log("type :".$type."\n", 3, "generate_invoices.log");


                if($type=="accessorySell"){

                    $resultat2=execSQL("SELECT * from accessories_catalog where ID=?", array('i', $ID), false)[0];
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
                        <td>
                          Vente<br><br>
                        </td>
                    </tr>';
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
                        <td>
                          Vente<br><br>
                        </td>
                    </tr>';
                }else if($type=="maintenance"){

                  $comment=$_POST['description'.$i].'/'.($_POST['minutes'.$i]);
                  $description=execSQL("SELECT DESCRIPTION FROM services_entretiens WHERE ID=?", array('i', $description), false)[0]['DESCRIPTION'];

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
                      <td>
                        Main d\'oeuvre<br><br>
                      </td>
                  </tr>';
                }

                $i+=1;
                if($TVA == "21")
                {
                  $totalTVA21+=$price;
                }else if($TVA=="6"){
                  $totalTVA6+=$price;
                }
            }
            $fileName=date('Y').'.'.date('m').'.'.date('d').'_'.$company.'_'.$idDevis.'_devisEntretien.pdf';

            $tva6=($totalTVA6*0.06);
            $tva21=($totalTVA21*0.21);
            $totalTVAIncluded6=$totalTVA6+$tva6;
            $totalTVAIncluded21=$totalTVA21+$tva21;

            error_log("test2 :".$test2."\n", 3, "generate_invoices.log");

	        $test3='</tbody>
	      </table>

 		<br><br>';

    if($commentDevis != NULL && $commentDevis != ''){
      $test3.="<p><strong>Remarques :</strong><br><br>".$commentDevis.'</p><br>';
    }

    $test3.='<table style="border-collapse: collapse; background-color: #E4E4E4">
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


</page>';


include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';
  $sql= "INSERT INTO  devis_entretien (STATUS, DATE_DEVIS, COMPANY,COMMENT,AMOUNT_HTVA,AMOUNT_TVAC) VALUES ('NONE','$date','$company','$commentDevis',round($totalTVA6+$totalTVA21,2), round($totalTVAIncluded6+$totalTVAIncluded21,2))";
  if ($conn->query($sql) === FALSE) {
      $response = array ('response'=>'error', 'message'=> $conn->error);
      echo json_encode($response);
      die;
  }

echo $test1.$test2.$test3;


?>
