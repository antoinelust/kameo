<?php
  //récupération des données du $_POST (pré boucle)
  $companyId = isset($_POST["companyIdTemplate"]) ? $_POST["companyIdTemplate"] : NULL;
  $buyOrLeasing = isset($_POST["buyOrLeasing"]) ? $_POST["buyOrLeasing"] : NULL;
  $leasingDuration = isset($_POST["leasingDuration"]) ? $_POST["leasingDuration"] : NULL;
  $numberMaintenance = isset($_POST["numberMaintenance"]) ? $_POST["numberMaintenance"] : NULL;
  $assurance = isset($_POST["assurance"]) ? true : false;
  $bikesNumber = isset($_POST["bikesNumber"]) ? $_POST["bikesNumber"] : NULL;
  $boxesNumber = isset($_POST["boxesNumber"]) ? $_POST["boxesNumber"] : NULL;
  $accessoriesNumber = isset($_POST["accessoriesNumber"]) ? $_POST["accessoriesNumber"] : NULL;
  $othersNumber = isset($_POST["othersNumber"]) ? $_POST["othersNumber"] : NULL;

  //création des tableaux destinés a recevoir les id des différents item
  $bikes = $bikesNumber > 0 ? getIds('bikeBrandModel',$bikesNumber) : NULL;
  $boxes = $boxesNumber > 0 ? getIds('boxModel',$boxesNumber) : NULL;
  $accessories = $accessoriesNumber > 0 ? getIds('accessoryAccessory',$accessoriesNumber) : NULL;
  $others = $othersNumber > 0 ? getOthers($othersNumber) : NULL;

  //creation de la response
  $response['companyId'] = $companyId;
  $response['buyOrLeasing'] = $buyOrLeasing;
  $response['leasingDuration'] = $leasingDuration;
  $response['numberMaintenance'] = $numberMaintenance;
  $response['assurance'] = $assurance;
  $response['bikes'] = $bikes;
  $response['boxes'] = $boxes;
  $response['accessories'] = $accessories;
  $response['others'] = $others;

  //affichage de la réponse en front (debug, a supprimer en prod)
  //echo json_encode($response);

  require_once dirname(__FILE__).'/../vendor/autoload.php';
  use Spipu\Html2Pdf\Html2Pdf;
  use Spipu\Html2Pdf\Exception\Html2PdfException;
  use Spipu\Html2Pdf\Exception\ExceptionFormatter;
  ob_start();
  try {
    //generation de l'objet html2pdf
    $html2pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8', 3);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->addFont('ArcaMajora', '', dirname(__FILE__).'/pdf/template/fonts/ArcaMajora.php');
    $html2pdf->addFont('ArcaMajora', 'b', dirname(__FILE__).'/pdf/template/fonts/ArcaMajorab.php');
    $html2pdf->addFont('helveticaneue', '', dirname(__FILE__).'/pdf/template/fonts/helvetica_neue.php');
    $html2pdf->addFont('helveticaneue', 'b', dirname(__FILE__).'/pdf/template/fonts/helvetica_neueb.php');
    $html2pdf->addFont('helveticaneue-light', '', dirname(__FILE__).'/pdf/template/fonts/helvetica_neuel.php');
    $html2pdf->addFont('helveticaneue-light', 'b', dirname(__FILE__).'/pdf/template/fonts/helvetica_neuelb.php');
    //ajout du fichier contenant le HTML a convertir
    include dirname(__FILE__).'/pdf/template/PDFContent.php';
    //fin de tampon
    $content = ob_get_clean();
    $html2pdf->writeHTML($content);
    //sort le fichier PDF sur le serveur
    $html2pdf->Output(__DIR__ .'/test.pdf', 'F');
    //Affiche le PDF dans le navigateur
    //$html2pdf->Output(__DIR__ .'/test.pdf');
  }  catch (Html2PdfException $e) {
      $html2pdf->clean();

      $formatter = new ExceptionFormatter($e);
      echo $formatter->getHtmlMessage();
  }


  function getIds($key, $counter){
    $arr = array();
    $composedKey = '';
    for ($i=1; $i <= $counter ; $i++) {
      $composedKey = $key . $i;
      array_push($arr, $_POST[$composedKey]);
    }
    return $arr;
  }

  function getOthers($counter){
    $arr = array();
    for ($i=1; $i <= $counter ; $i++) {
      $composedDescription = 'othersDescription'.$i;
      $composedCost = 'othersCost'.$i;
      $arr[$i-1]['othersDescription'] = $_POST[$composedDescription];
      $arr[$i-1]['othersCost'] = $_POST[$composedCost];
    }
    return $arr;
  }


 ?>
