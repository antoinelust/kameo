<?php
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

if(!isset($_SESSION))
{
  session_start();
}

include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/globalfunctions.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/environment.php';
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;


$commentDevis=$_POST['commentDevis'];
$company=addslashes($_POST['company']);

$accessoriesNumber=isset($_POST['accessoryID']) ? count($_POST['accessoryID']) : 0;
$otherAccessoriesNumber=isset($_POST['otherAccessoryDescription']) ? count($_POST['otherAccessoryDescription']) : 0;

include 'connexion.php';
  $i=0;
  $j=0;
  while($j<$accessoriesNumber){
    $data['ID'.$i] = $_POST['accessoryID'][$j];
    $data['price'.$i] = $_POST['accessoryFinalPrice'][$j];
    $data['type'.$i] = "accessorySell";
    $data['TVA'.$i] = "21";
    $j++;
    $i++;
  }
  $j=0;
  while($j<$otherAccessoriesNumber){
    $data['price'.$i] = $_POST['otherAccessoryFinalPrice'][$j];
    $data['description'.$i] = $_POST['otherAccessoryDescription'][$j];
    $data['type'.$i] = "otherAccessorySell";
    $data['TVA'.$i] = "21";
    $j++;
    $i++;
  }
  if(isset($_POST['service'])){
    foreach ($_POST['service'] as $key=>$value) {
      $data['price'.$i] = $_POST['manualWorkloadTotal'][$key];
      $data['description'.$i] = $_POST['service'][$key];
      $data['minutes'.$i] = $_POST['manualWorkloadLength'][$key];
      $data['type'.$i] = "maintenance";
      $data['TVA'.$i] = "6";
      $i++;
    }
  }
  $data['itemNumber'] = $i;
  $data['company'] = $company;
  $data['dateStart'] = date('Y').'-'.date('m').'-'.date('d');
  $data['commentDevis']=$commentDevis;

$url='http://'.$_SERVER['HTTP_HOST'].'/scripts/generate_devis.php';
$test=CallAPI('POST', $url, $data);
try {
  $html2pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8', 3);
  $html2pdf->pdf->SetDisplayMode('fullpage');
  $html2pdf->writeHTML($test);
  $devisID=(execSQL("SELECT MAX(ID) as maxID FROM devis_entretien", array(), false)[0]['maxID']);
  $path=$_SERVER['DOCUMENT_ROOT'].'/devisEntretien/'.date('Y').'-'.date('m').'-'.date('d').'_'.$company.'_'.$devisID.'.pdf';
  $html2pdf->Output($path, 'F');
} catch (Html2PdfException $e) {
  $html2pdf->clean();
  $formatter = new ExceptionFormatter($e);
  $response = array ('response'=>'error', 'message'=> $formatter->getHtmlMessage());
  echo json_encode($response);
  die;
}
$fichier = date('Y').'.'.date('m').'.'.date('d').'_'.$company.'_devisEntretien.pdf';

successMessage("SM0012");
?>
