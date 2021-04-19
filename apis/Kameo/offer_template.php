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
  $remarques = isset($_POST["remarques"]) ? nl2br($_POST["remarques"]) : NULL;
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
  $globalDDiscount = isset($_POST["globalDiscount"]) ? $_POST["globalDiscount"] : NULL;


  $delais = explode("\n",$delais);
  $offerValidity = date_create($offerValidity);
  $offerValidity = date_format($offerValidity,"d/m/Y");


  $others = array();
  //création des tableaux destinés a recevoir les id des différents item
  //$bikesId = $bikesNumber > 0 ? getIds('bikeBrandModel',$bikesNumber) : NULL;
  $boxesId = $boxesNumber > 0 ? getIds('boxModel',$boxesNumber) : NULL;
  $others = $othersNumber > 0 ? getOthers($othersNumber) : array();


  $bikes=array();
  $totalBikes=0;
  if(isset($_POST['bikeBrandModel'])){
    foreach($_POST['bikeBrandModel'] as $key=>$bike){
      $totalBikes += $_POST['bikeNumber'][$key];
      $information=execSQL("SELECT * FROM bike_catalog WHERE ID=?", array('i', $bike), false)[0];
      $bikes[$key]['ID']=$bike;
      $bikes[$key]['BRAND']=$information['BRAND'];
      $bikes[$key]['MODEL']=$information['MODEL'];
      $bikes[$key]['bikeSize']=$_POST['bikeSize'][$key];
      $bikes[$key]['finalPrice']=$_POST['bikeFinalPrice'][$key];
      $bikes[$key]['initialPrice']=$_POST['bikeInitialPrice'][$key];
      $bikes[$key]['UTILISATION']=$information['UTILISATION'];
      $bikes[$key]['ELECTRIC']=$information['ELECTRIC'];
      $bikes[$key]['bikeNumber']=$_POST['bikeNumber'][$key];
      $bikes[$key]['bikePriceAchat']=$_POST['bikeFinalPriceAchat'][$key];
    }
  }

  $accessories=array();
  $accessoriesTotalLeasing = 0;
  $accessoriesTotalAchat = 0;
  if(isset($_POST['accessoryAccessory'])){
    foreach($_POST['accessoryAccessory'] as $key=>$accessory) {
        $information=execSQL("SELECT * FROM accessories_catalog WHERE ID=?", array('i', $accessory), false)[0];
        $accessories[$key]['ID']=$accessory;
        $accessories[$key]['BRAND']=$information['BRAND'];
        $accessories[$key]['MODEL']=$information['MODEL'];
        $accessories[$key]['finance']=$_POST['accessoryFinance'][$key];
        $accessories[$key]['finalPrice']=$_POST['accessoryFinalPrice'][$key];
        $accessories[$key]['accessoryNumber']=$_POST['accessoryNumber'][$key];
        if($_POST['accessoryFinance'][$key] == 'leasing'){
          $accessoriesTotalLeasing += $_POST['accessoryNumber'][$key];
          $accessories[$key]['initialPrice']=$information['PRICE_HTVA']*1.25/$leasingDuration;
        }else{
          $accessoriesTotalAchat += $_POST['accessoryNumber'][$key];
          $accessories[$key]['initialPrice']=$information['PRICE_HTVA']*1;
        }
    }
  }


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


  $boxes = array();
  //recuperation des données nécéssaire en db
  //$bikes = getItemsInDatabase($bikesId, 'bike_catalog');
  $boxes = getItemsInDatabase($boxesId, 'boxes_catalog');

  $contact = getItemInDatabase($contact['id'], 'companies_contact');


    for ($i=0; $i < $boxesNumber ; $i++) {

      $boxes[$i]['FINAL_INSTALLATION_PRICE'] = $boxFinalPrice[0][$i];
      $boxes[$i]['FINAL_LOCATION_PRICE'] = $boxFinalPrice[1][$i];
      $totalPerMonth += intval($boxFinalPrice[1][$i]);

    }


  $totalPerMonth=0;

  $boxes = distinct($boxes);

  $company = getCompany($companyId);
  include 'get_prices.php';

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
    error_log(date("Y-m-d H:i:s")." - Contenu :".$content."\n", 3, "offer-template.log");
    $html2pdf->writeHTML($content);
    //sort le fichier PDF sur le serveur
    $html2pdf->Output($_SERVER['DOCUMENT_ROOT'].'/offres/'.$pdfTitle.'.pdf', 'F');
    //ajoute le PDF a la table



    for ($i=0; $i < $boxesNumber ; $i++) {
      $boxFinalLocationPrice = $boxFinalPrice[1][$i];
      $totalPerMonth+=$boxFinalLocationPrice;
    }

    if(count($bikes) > 0){
      foreach($bikes as $bike) {
        for($i=0; $i<$bike['bikeNumber']; $i++){
          if($buyOrLeasing=='buy'){
            $totalPerMonth+=$bike['bikePriceAchat'];
          }else{
            $totalPerMonth+=$bike['finalPrice'];
          }
        }
      }
    }

    if(count($accessories) > 0){
      foreach($accessories as $accessory) {
        for($i=0; $i<$accessory['accessoryNumber']; $i++){
          $totalPerMonth+=$accessory['finalPrice'];
        }
      }
    }

    $offerID= add_PDF($companyId, $pdfTitle, $totalBikes, $boxesNumber, $buyOrLeasing, ($accessoriesTotalAchat+$accessoriesTotalLeasing), $email, $dateSignature, $dateStart, $dateEnd, $totalPerMonth, $company['INTERNAL_REFERENCE'], $probability);
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
        $totalPerMonth+=$boxFinalLocationPrice;

    }

    if(count($bikes) > 0){
      foreach($bikes as $bike) {
        for($i=0; $i<$bike['bikeNumber']; $i++){
          if($buyOrLeasing=='buy'){
            $totalPerMonth+=$bike['bikePriceAchat'];
            execSQL("INSERT INTO offers_details (USR_MAJ, OFFER_ID, ITEM_TYPE, ITEM_ID, ITEM_LOCATION_PRICE, ITEM_INSTALLATION_PRICE, SIZE, STAANN) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", array('sisiiiss', $email, $offerID, 'bike', $bike['ID'], 0, $bike['bikePriceAchat'], $bike['bikeSize'], ''), true);
          }else{
            $totalPerMonth+=$bike['finalPrice'];
            execSQL("INSERT INTO offers_details (USR_MAJ, OFFER_ID, ITEM_TYPE, ITEM_ID, ITEM_LOCATION_PRICE, ITEM_INSTALLATION_PRICE, CONTRACT_DURATION, SIZE, STAANN) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)", array('sisiiisds', $email, $offerID, 'bike', $bike['ID'], $bike['finalPrice'], 0, $leasingDuration, $bike['bikeSize'], ''), true);
          }
        }
      }
    }
    if(count($accessories) > 0){
      foreach($accessories as $accessory) {
        for($i=0; $i<$accessory['accessoryNumber']; $i++){
          $totalPerMonth+=$accessory['finalPrice'];
          if($accessory['finance']=='achat'){
            execSQL("INSERT INTO offers_details (USR_MAJ, OFFER_ID, ITEM_TYPE, ITEM_ID, ITEM_LOCATION_PRICE, ITEM_INSTALLATION_PRICE, STAANN) VALUES (?, ?, ?, ?, ?, ?, ?)", array('sisiiis', $email, $offerID, 'accessory', $accessory['ID'], 0, $accessory['finalPrice'], ''), true);
          }else{
            execSQL("INSERT INTO offers_details (USR_MAJ, OFFER_ID, ITEM_TYPE, ITEM_ID, ITEM_LOCATION_PRICE, ITEM_INSTALLATION_PRICE, STAANN) VALUES (?, ?, ?, ?, ?, ?, ?)", array('sisiiis', $email, $offerID, 'accessory', $accessory['ID'], $accessory['finalPrice'], 0, ''), true);
          }
        }
      }
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
