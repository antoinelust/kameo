<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/lang_management.php';

require_once dirname(__FILE__).'/vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
ob_start();

include 'apis/Kameo/globalfunctions.php';

$simulation = isset($_GET["simulation"]) ? addslashes($_GET["simulation"]) : NULL;
$forced = isset($_GET["forced"]) ? addslashes($_GET["forced"]) : NULL;
$company = isset($_GET["company"]) ? addslashes($_GET["company"]) : NULL;
$dateStart = isset($_GET["dateStart"]) ? addslashes($_GET["dateStart"]) : NULL;
$dateEnd = isset($_GET["dateEnd"]) ? addslashes($_GET["dateEnd"]) : NULL;

$monthFR=array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');


function requireToVar($file){
    ob_start();
    require($file);
    return ob_get_clean();
}


include 'apis/Kameo/connexion.php';
$sql= "SELECT * FROM ((SELECT COMPANY, customer_bikes.BILLING_GROUP FROM customer_bikes WHERE CONTRACT_TYPE='leasing' AND STAANN != 'D') UNION (SELECT COMPANY, boxes.BILLING_GROUP FROM boxes WHERE boxes.STAANN!='D') UNION (SELECT INTERNAL_REFERENCE, accessories_stock.BILLING_GROUP from accessories_stock, companies WHERE accessories_stock.CONTRACT_TYPE='leasing' and accessories_stock.STAANN != 'D' and companies.ID=accessories_stock.COMPANY_ID)) T WHERE COMPANY != 'KAMEO'";


if(isset($company)){
    $sql=$sql." AND COMPANY='$company'";
}

error_log("SQL1:".$sql."\n", 3, "generate_invoices.log");


if ($conn->query($sql) === FALSE) {
    echo $conn->error;
    die;
}


$result = mysqli_query($conn, $sql);
$i=0;

while($row = mysqli_fetch_array($result))
{

    $internalReference=$row['COMPANY'];

    error_log("--------------------\n", 3, "generate_invoices.log");
    error_log("Company :".$internalReference."\n", 3, "generate_invoices.log");
    $currentDate=date('Y-m-d');
    $billingGroup=$row['BILLING_GROUP'];
    if($dateStart==NULL){
      $dateStart = new DateTime("now");
      $dateStart = $dateStart->format('Y-m-d');
    }

    $sql_dateStart="SELECT MIN(start) as start, COMPANY, BILLING_GROUP from ((select min(SUBSTR(CONTRACT_START, 9, 2)) as 'start', COMPANY, BILLING_GROUP from customer_bikes aa where aa.CONTRACT_START<='$dateStart' and (aa.CONTRACT_END>'$dateStart' or aa.CONTRACT_END is NULL) and aa.COMPANY='$internalReference' and aa.BILLING_GROUP='$billingGroup') UNION (select min(SUBSTR(START, 9, 2)) as 'start', COMPANY, BILLING_GROUP from boxes aa where aa.START<='$dateStart' and (aa.END>'$dateStart' or aa.END is NULL) and aa.COMPANY='$internalReference' and aa.BILLING_GROUP='$billingGroup') UNION (SELECT min(SUBSTR(accessories_stock.CONTRACT_START, 9, 2)), companies.INTERNAL_REFERENCE as COMPANY, accessories_stock.BILLING_GROUP FROM accessories_stock, companies WHERE companies.ID=accessories_stock.COMPANY_ID AND accessories_stock.CONTRACT_TYPE='leasing' AND accessories_stock.CONTRACT_START<'$dateStart' AND accessories_stock.CONTRACT_END>'$dateStart' AND accessories_stock.STAANN != 'D' AND companies.INTERNAL_REFERENCE='$internalReference')) AS T1 WHERE start is NOT NULL";
    error_log("SQL2 :".$sql_dateStart."\n", 3, "generate_invoices.log");

    if ($conn->query($sql_dateStart) === FALSE) {
        echo $conn->error;
        die;
    }
    
    $result_dateStart = mysqli_query($conn, $sql_dateStart);
    $length=$result_dateStart->num_rows;
    while($resultat_dateStart = mysqli_fetch_array($result_dateStart)){
        if (ob_get_contents()) ob_end_clean();
        ob_start();
        $billingGroup=$resultat_dateStart['BILLING_GROUP'];

        if($resultat_dateStart['start'])
        {
            $firstDay=$resultat_dateStart['start'];
            $today=substr($currentDate, 8 ,2);
            error_log("Today :".$today."\n", 3, "generate_invoices.log");
            error_log("First Day :".$firstDay."\n", 3, "generate_invoices.log");
            error_log("Date Start :".substr($dateStart, 8, 2)."\n", 3, "generate_invoices.log");


            if($today==$firstDay || $firstDay==substr($dateStart, 8, 2) || $forced)
            {
                error_log("Generation d'offre \n", 3, "generate_invoices.log");


                $sql_companyDetails="select COMPANY_NAME from companies where INTERNAL_REFERENCE='$internalReference' and BILLING_GROUP='$billingGroup'";
                error_log("SQL3 :".$sql_companyDetails."\n", 3, "generate_invoices.log");

                if ($conn->query($sql_companyDetails) === FALSE) {
                    echo $conn->error;
                    die;
                }
                $result_companyDetails = mysqli_query($conn, $sql_companyDetails);
                $resultat_companyDetails = mysqli_fetch_assoc($result_companyDetails);
                $companyName=$resultat_companyDetails['COMPANY_NAME'];

                $file = __DIR__.'/temp/company.txt';
                $myfile = fopen($file, "w")  or die("Unable to open file!");
                fwrite($myfile, $internalReference);
                fclose($myfile);

                $file = __DIR__.'/temp/billingGroup.txt';
                $myfile = fopen($file, "w")  or die("Unable to open file!");
                fwrite($myfile, $billingGroup);
                fclose($myfile);

                if($dateStart){
                    $file = __DIR__.'/temp/dateStart.txt';
                    $myfile = fopen($file, "w")  or die("Unable to open file!");
                    fwrite($myfile, $dateStart);
                    fclose($myfile);
                }
                if($dateEnd){
                    $file = __DIR__.'/temp/dateEnd.txt';
                    $myfile = fopen($file, "w")  or die("Unable to open file!");
                    fwrite($myfile, $dateEnd);
                    fclose($myfile);
                }
                if($simulation){
                    $file = __DIR__.'/temp/simulation.txt';
                    $myfile = fopen($file, "w")  or die("Unable to open file!");
                    fwrite($myfile, $simulation);
                    fclose($myfile);
                }


                $test=requireToVar(__DIR__.'/facture.php');
                $file = 'facture'.$i.'.php';
                $myfile = fopen(__DIR__.'/'.$file, "w")  or die("Unable to open file!");
                fwrite($myfile, $test);
                fclose($myfile);

                include 'apis/Kameo/connexion.php';
                $sql_reference="select max(ID) as MAX_TOTAL, max(ID_OUT_BILL) as MAX_OUT from factures";
                if ($conn->query($sql_reference) === FALSE) {
                    echo $conn->error;
                    die;
                }
                $result_reference = mysqli_query($conn, $sql_reference);
                $resultat_reference = mysqli_fetch_assoc($result_reference);
                $newID=$resultat_reference['MAX_TOTAL'];
                $newIDOUT=$resultat_reference['MAX_OUT'];



                try {
                    include dirname(__FILE__).'/'.$file;
                    $content = ob_get_clean();
                    $html2pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8', 3);
                    $html2pdf->pdf->SetDisplayMode('fullpage');
                    $html2pdf->writeHTML($content);
                    $path='/factures/'.date('Y').'.'.date('m').'.'.date('d').'_'.$internalReference.'_'.$newID.'_facture_'.$newIDOUT.'.pdf';
                    $html2pdf->Output(__DIR__ . $path, 'F');
                } catch (Html2PdfException $e) {
                    $html2pdf->clean();
                    $formatter = new ExceptionFormatter($e);
                    error_log("ERROR ---  Erreur génération HTML:".$formatter->getHtmlMessage()."\n", 3, "generate_invoices.log");
                }


                $file = __DIR__.'/temp/company.txt';
                if ((file_exists($file))){
                    unlink($file);
                }
                $file = __DIR__.'/temp/billingGroup.txt';
                if ((file_exists($file))){
                    unlink($file);
                }

                $file = __DIR__.'/temp/dateStart.txt';
                if ((file_exists($file))){
                    unlink($file);
                }

                $file = __DIR__.'/temp/dateEnd.txt';
                if ((file_exists($file))){
                    unlink($file);
                }

                $file = __DIR__.'/temp/simulation.txt';
                if ((file_exists($file))){
                    unlink($file);
                }

                $file = __DIR__.'/facture'.$i.'.php';;
                if ((file_exists($file))){
                    unlink($file);
                }

            }else{
              error_log("PASSED \n", 3, "generate_invoices.log");

            }
        }
        if (ob_get_contents()) ob_end_clean();

    }
}

?>
