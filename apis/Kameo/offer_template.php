<?php
    session_cache_limiter('nocache');
    header('Expires: ' . gmdate('r', 0));
    header('Content-type: application/json');

  include 'globalfunctions.php';

  ini_set('internal_encoding', 'utf-8');
  //récupération des données du $_POST (pré boucle)
  $email = isset($_POST["email"]) ? $_POST["email"] : NULL;
  $companyId = isset($_POST["companyIdTemplate"]) ? $_POST["companyIdTemplate"] : NULL;
  $buyOrLeasing = isset($_POST["buyOrLeasing"]) ? $_POST["buyOrLeasing"] : NULL;
  $leasingDuration = isset($_POST["leasingDuration"]) ? $_POST["leasingDuration"] : NULL;
  $numberMaintenance = isset($_POST["numberMaintenance"]) ? $_POST["numberMaintenance"] : NULL;
  $assurance = isset($_POST["assurance"]) ? $_POST["assurance"] : false;
  $bikesNumber = isset($_POST["bikesNumber"]) ? $_POST["bikesNumber"] : NULL;
  $boxesNumber = isset($_POST["boxesNumber"]) ? $_POST["boxesNumber"] : NULL;
  $accessoriesNumber = isset($_POST["accessoriesNumber"]) ? $_POST["accessoriesNumber"] : NULL;
  $othersNumber = isset($_POST["othersNumber"]) ? $_POST["othersNumber"] : NULL;
  $contact['id'] = isset($_POST["contactSelect"]) ? $_POST["contactSelect"] : NULL;
  $contactKameo = isset($_POST["offer_template_kameo_contact"]) ? $_POST["offer_template_kameo_contact"] : NULL;
  $delais = isset($_POST["delais"]) ? $_POST["delais"] : NULL;
  $offerValidity = isset($_POST["offerValidity"]) ? $_POST["offerValidity"] : NULL;
  $bikeFinalPrice = isset($_POST["bikeFinalPrice"]) ? $_POST["bikeFinalPrice"] : NULL;
  $boxFinalInstallationlPrice = isset($_POST["boxFinalInstallationPrice"]) ? $_POST["boxFinalInstallationPrice"] : NULL;
  $boxFinalLocationPrice = isset($_POST["boxFinalLocationPrice"]) ? $_POST["boxFinalLocationPrice"] : NULL;
  $boxFinalPrice=[$boxFinalInstallationlPrice, $boxFinalLocationPrice];
  $probability = isset($_POST["probability"]) ? $_POST["probability"] : NULL;
  $dateSignature = isset($_POST["dateSignature"]) ? $_POST["dateSignature"] : NULL;
  $dateStart = isset($_POST["dateStart"]) ? $_POST["dateStart"] : NULL;
  $dateEnd = isset($_POST["dateEnd"]) ? $_POST["dateEnd"] : NULL;
  $totalPerMonth = 0;


  $delais = explode("\n",$delais);
  $offerValidity = date_create($offerValidity);
  $offerValidity = date_format($offerValidity,"d/m/Y");


  $others = array();
  //création des tableaux destinés a recevoir les id des différents item
  $bikesId = $bikesNumber > 0 ? getIds('bikeBrandModel',$bikesNumber) : NULL;
  $boxesId = $boxesNumber > 0 ? getIds('boxModel',$boxesNumber) : NULL;


  $accessoriesId = $accessoriesNumber > 0 ? getIds('accessoryAccessory',$accessoriesNumber) : NULL;
  $others = $othersNumber > 0 ? getOthers($othersNumber) : array();


  include 'connexion.php';
  $sql = "SELECT * FROM customer_referential WHERE EMAIL='$contactKameo'";
  if ($conn->query($sql) === FALSE) {
      $response = array ('response'=>'error', 'message'=> $conn->error);
      echo json_encode($response);
      die;
  }
  $result=mysqli_query($conn, $sql);
  $resultat=mysqli_fetch_assoc($result);
  $prenomKameo=$resultat['PRENOM'];
  $nomKameo=$resultat['NOM'];
  $phoneKameo=$resultat['PHONE'];


  $bikes = array();
  $boxes = array();
  $accessories = array();
  //recuperation des données nécéssaire en db
  $bikes = getItemsInDatabase($bikesId, 'bike_catalog');
  $boxes = getItemsInDatabase($boxesId, 'boxes_catalog');

  $accessories = getItemsInDatabase($accessoriesId, 'accessories_catalog');
  $contact = getItemInDatabase($contact['id'], 'companies_contact');


    for ($i=0; $i < $boxesNumber ; $i++) {

      $boxes[$i]['FINAL_INSTALLATION_PRICE'] = $boxFinalPrice[0][$i];
      $boxes[$i]['FINAL_LOCATION_PRICE'] = $boxFinalPrice[1][$i];
      $totalPerMonth += intval($boxFinalPrice[1][$i]);

    }



  for ($i=0; $i < $bikesNumber ; $i++) {
      $bikes[$i]['FINAL_LEASING_PRICE'] = $bikeFinalPrice[$i];
      $totalPerMonth += intval($bikeFinalPrice[$i]);

  }

  //transforme le tableau pour n avoir qu'une itération de chaque
  $bikes = distinct($bikes, $bikeFinalPrice);
  $boxes = distinct($boxes);
  $accessories = distinct($accessories);

  $company = getCompany($companyId);
  include 'get_prices.php';
  for ($i=0; $i < count($bikes) ; $i++) {
    $response=get_prices($bikes[$i]['PRICE_HTVA']);
    $bikes[$i]['LEASING_PRICE'] = $response['leasingPrice'];
  }

  $currentDate = getDate();

  $pdfTitle = strtolower(str_replace(" ", "-", $company['INTERNAL_REFERENCE'])).'_'.$currentDate['year'].'_'.$currentDate['mon'].'_'.$currentDate['mday'].'_temp';

  require_once 	$_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
  use Spipu\Html2Pdf\Html2Pdf;
  use Spipu\Html2Pdf\Exception\Html2PdfException;
  use Spipu\Html2Pdf\Exception\ExceptionFormatter;
  ob_start();
  try {
    //adresse de la racine du site
    $root = $_SERVER['DOCUMENT_ROOT'].'/kameo';
    //generation de l'objet html2pdf
    $html2pdf = new Html2Pdf('P','A4','fr',true,'UTF-8');
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->addFont('ArcaMajora', '', $_SERVER['DOCUMENT_ROOT'].'/TO INCLUDE/pdf/template/fonts/ArcaMajora.php');
    $html2pdf->addFont('ArcaMajora', 'b', $_SERVER['DOCUMENT_ROOT'].'/TO INCLUDE/pdf/template/fonts/ArcaMajorab.php');
    $html2pdf->addFont('helveticaneue', '', $_SERVER['DOCUMENT_ROOT'].'/TO INCLUDE/pdf/template/fonts/helvetica_neue.php');
    $html2pdf->addFont('helveticaneue', 'b', $_SERVER['DOCUMENT_ROOT'].'/TO INCLUDE/pdf/template/fonts/helvetica_neueb.php');
    $html2pdf->addFont('helveticaneue-light', '', $_SERVER['DOCUMENT_ROOT'].'/TO INCLUDE/pdf/template/fonts/helvetica_neuel.php');
    $html2pdf->addFont('helveticaneue-light', 'b', $_SERVER['DOCUMENT_ROOT'].'/TO INCLUDE/pdf/template/fonts/helvetica_neuelb.php');
    $html2pdf->addFont('Akkurat', '', $_SERVER['DOCUMENT_ROOT'].'/TO INCLUDE/pdf/template/fonts/akkurat.php');
    $html2pdf->addFont('Akkurat', 'b', $_SERVER['DOCUMENT_ROOT'].'/TO INCLUDE/pdf/template/fonts/akkurat-bold.php');
    $html2pdf->addFont('Akkurat-Light', '', $_SERVER['DOCUMENT_ROOT'].'/TO INCLUDE/pdf/template/fonts/akkurat-light.php');
    $html2pdf->addFont('Akkurat-Light', 'b', $_SERVER['DOCUMENT_ROOT'].'/TO INCLUDE/pdf/template/fonts/akkurat-light-b.php');
    //ajout du fichier contenant le HTML a convertir
    include $_SERVER['DOCUMENT_ROOT'].'/TO INCLUDE/pdf/template/PDFContent.php';
    //fin de tampon
    $content = ob_get_clean();
    $html2pdf->writeHTML($content);
    //sort le fichier PDF sur le serveur
    $html2pdf->Output($_SERVER['DOCUMENT_ROOT'].'/offres/'.$pdfTitle.'.pdf', 'F');
    //ajoute le PDF a la table


    $offerID= add_PDF($companyId, $pdfTitle, $bikesNumber, $boxesNumber, $buyOrLeasing, $accessoriesNumber, $email, $dateSignature, $dateStart, $dateEnd, $totalPerMonth, $company['INTERNAL_REFERENCE'], $probability);
    $response['id'] = $offerID;
    $newPdfFile = str_replace('temp',$response['id'], $pdfTitle);

    for ($i=0; $i < $boxesNumber ; $i++) {

        $boxFinalInstallationPrice = $boxFinalPrice[0][$i];
        $boxFinalLocationPrice = $boxFinalPrice[1][$i];
        $boxId=$boxesId[$i];
        include 'connexion.php';
        $sql = "INSERT INTO offers_details (USR_MAJ, OFFER_ID, ITEM_TYPE, ITEM_ID, ITEM_LOCATION_PRICE, ITEM_INSTALLATION_PRICE, STAANN) VALUES ('$email', '$offerID', 'box', '$boxId', '$boxFinalLocationPrice', '$boxFinalInstallationPrice', '')";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $conn->close();

    }

    for ($i=0; $i < $bikesNumber ; $i++) {
        $bikeFinalLocationPrice = $bikeFinalPrice[$i];
        include 'connexion.php';
        $sql = "INSERT INTO offers_details (USR_MAJ, OFFER_ID, ITEM_TYPE, ITEM_ID, ITEM_LOCATION_PRICE, ITEM_INSTALLATION_PRICE, STAANN) VALUES ('$email', '$offerID', 'bike', '$boxId', '$bikeFinalLocationPrice', 0, '')";
        if ($conn->query($sql) === FALSE) {
            $response = array ('response'=>'error', 'message'=> $conn->error);
            echo json_encode($response);
            die;
        }
        $conn->close();
    }


    rename($_SERVER['DOCUMENT_ROOT'].'/offres/'.$pdfTitle.'.pdf', $_SERVER['DOCUMENT_ROOT'].'/offres/'.$newPdfFile.'.pdf');

    //response
    $response['response'] = 'true';
    $response['file'] = $newPdfFile.'.pdf';
    $response['bikesNumber'] = $bikesNumber;
    $response['boxesNumber'] = $boxesNumber;
    $response['buyOrLeasing'] = $buyOrLeasing;

    header('Content-type: application/json');
    echo json_encode($response);

  }  catch (Html2PdfException $e) {
      $html2pdf->clean();

      $formatter = new ExceptionFormatter($e);
      echo $formatter->getHtmlMessage();
  }


/*==========FONCTIONS==========*/

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
    for ($i=0; $i < $counter ; $i++) {
      $arr[$i]['othersDescription'] = $_POST['othersDescription'][$i];
      $arr[$i]['othersSellingPrice'] = $_POST['othersSellingPrice'][$i];
      $arr[$i]['othersSellingPriceFinal'] = $_POST['othersSellingPriceFinal'][$i];
    }
    return $arr;
  }

  function getItemsInDatabase($ids, $table){
    $arr = array();
    if($ids != NULL){
      include 'connexion.php';
      foreach ($ids as $id) {
        $sql = "SELECT * FROM $table WHERE ID = $id";
        $res = mysqli_query($conn, $sql);
        $res = mysqli_fetch_assoc($res);
        array_push($arr, $res);
      }
      $conn->close();
    }
    return $arr;
  }

  function getCompany($id){
    include 'connexion.php';
    $sql = "SELECT * FROM companies WHERE ID = $id";
    $res = mysqli_query($conn, $sql);
    $res = mysqli_fetch_assoc($res);
    $conn->close();
    return $res;
  }
  function getItemInDatabase($id, $table){
    include 'connexion.php';
    $sql = "SELECT * FROM $table WHERE ID = $id";
    $res = mysqli_query($conn, $sql);
    $res = mysqli_fetch_assoc($res);
    $conn->close();
    return $res;
  }
  function distinct($arr){
    $temp = $arr;
    //parcours le tableau
    for ($i=0; $i < count($temp); $i++) {
      $count = 1;
      $temp[$i]['count'] = $count;

      //parcours le tableau
      for ($j=0; $j < count($temp) ; $j++) {
        //si on est pas au même index et qu'on a le même item
        if ($temp[$i]['ID'] == $temp[$j]['ID'] && $i != $j) {
          //incrémente le compteur
          $count = $count + 1;
          //ajoute la valeur du compteur au tableau
          $temp[$i]['count'] = $count;
          //retire l'item en trop du tableau
          array_splice($temp, $j, 1);
          //on reste au même index car array_splice retire les index non utilisé
          $j--;
        }
      }
    }
    return $temp;
  }

  function add_PDF($id, $file, $bikesNumber, $boxesNumber, $buyOrLeasing, $accessoriesNumber, $email, $dateSignature, $dateStart, $dateEnd, $totalPerMonth, $company, $probability){
    include 'connexion.php';
    $description="Facture générée depuis mykameo pour ".$bikesNumber." vélos, ".$boxesNumber." bornes et ".$accessoriesNumber." accessoires.";

    $sql="INSERT INTO offers (HEU_MAJ, USR_MAJ, TITRE, DESCRIPTION, STATUS, PROBABILITY, TYPE, AMOUNT, MARGIN, DATE, START, END, COMPANY, COMPANY_ID, FILE_NAME, STAANN) VALUES (CURRENT_TIMESTAMP, '$email', 'Facture générée via le template', '$description', 'ongoing', '$probability', '$buyOrLeasing', '$totalPerMonth', '40', '$dateSignature', '$dateStart', '$dateEnd', '$company', '$id', '$file',  '')";

    if ($conn->query($sql) === FALSE) {
        $response = array ('response'=>'error', 'message'=> $conn->error);
        echo json_encode($response);
        die;
    }
    $id = $conn->insert_id;
    $file = str_replace('temp', $id, $file);
    $conn->close();

    include 'connexion.php';
    $sql = "UPDATE `offers` SET `FILE_NAME` = '$file' WHERE `offers`.`ID` = '$id'";
    $res = $conn->query($sql);
    $conn->close();
    return $id;
  }

 ?>
