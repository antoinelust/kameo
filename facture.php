<?php 

$company = file_get_contents(__DIR__.'/temp/company.txt');
$currentDate = new DateTime('now');
$currentDateString = date('Y-m-d');


include 'include/connexion.php';
$sql_reference="select max(ID) from factures";
if ($conn->query($sql_reference) === FALSE) {
    echo $conn->error;
    die;
}
$result_reference = mysqli_query($conn, $sql_reference);   
$resultat_reference = mysqli_fetch_assoc($result_reference);
$newID=$resultat_reference['max(ID)'];
$newID=strval($newID+1);


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
                <td style="text-align: center;    width: 34%">Facture Sia Partners</td>
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
				
				<p>Sia Partners</p>
				
				<p>Avenue Henri Jasparlaan, 128
				<br>B-1060 Bruxelles
				<br>Belgium</p>
				
				<p>TVA/VAT : BE0878.103.386</p>
				
				<p>Référence client : '.$company.'</p>
        </td>
    
    </tr>
    <tr>
        <td>
            <h4 style="color: #3CB195">FACTURE</h4>
			<p style="; margin: Opx; padding: 0px">Leasing sur une période de 36 mois</p>

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
            $sql2="select * from customer_bikes where COMPANY='$company' and CONTRACT_START<='$currentDateString' and CONTRACT_END>='$monthAfterString'";
            if ($conn->query($sql2) === FALSE) {
                echo $conn->error;
                die;
            }
            $result2 = mysqli_query($conn, $sql2);   
            $length = $result2->num_rows;
            
            $i=0;
            $total=0;

            while($row2 = mysqli_fetch_array($result2)){
                $i+=1;
                $total+=$row2['LEASING_PRICE'];
                
                
                $contractStart= new DateTime();
                $contractStart->setDate(substr($row2['CONTRACT_START'], 0, 4), substr($row2['CONTRACT_START'],5,2), substr($row2['CONTRACT_START'], 8,2));
                

                $dateStart = new DateTime();
                $dateStart->setDate($currentDate->format('Y'), $currentDate->format('m'), $contractStart->format('d'));
                
                $dateEnd = new DateTime();
                $dateEnd->setDate($monthAfter->format('Y'), $monthAfter->format('m'), $contractStart->format('d'));
                
                $difference=$dateStart->diff($contractStart);

                
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
                    <td>Période '.($difference->format('%m')+1).'/36</td>
                </tr>';

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




include 'include/connexion.php';
$today=date('Y-m-d');
$fileName=$company.$monthFR[(date('n')-1)].date('Y').'.pdf';
$sql= "INSERT INTO factures (ID, COMPANY, DATE, AMOUNT_HTVA, AMOUNT_TVAINC, COMMUNICATION_STRUCTUREE, FILE_NAME) VALUES ('$newID', '$company', '$today', round($total,2), round($totalTVAIncluded,2), '$reference', '$fileName')";


if ($conn->query($sql) === FALSE) {
    echo $conn->error;
} 
$conn->close();


?>