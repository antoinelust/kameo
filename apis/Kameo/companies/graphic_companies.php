    <?php
    include __DIR__ .'/../connexion.php';

    $numberOfDays=isset($_GET['numberOfDays']) ? $conn->real_escape_string($_GET['numberOfDays']) : NULL;
    $dateStartInput=isset($_GET['dateStart']) ? $conn->real_escape_string($_GET['dateStart']) : NULL;
    $dateEndInput=isset($_GET['dateEnd']) ? $conn->real_escape_string($_GET['dateEnd']) : NULL;


    $intervalStop="P".$numberOfDays."D";

    $date_start = new DateTime($dateStartInput);
    $date_start_string=$date_start->format('Y-m-d');

    $date_end= new DateTime($dateEndInput);


    $date_now=new DateTime("NOW");
    $companiesContact=array();
    $companiesOffer=array();
    $companiesOfferSigned=array();
    $companiesNotInterested=array();
    $dates=array();
    while($date_start<=$date_end){

        $date_start_string=$date_start->format('Y-m-d');

        $stmt = $conn->prepare("SELECT COUNT(1) AS 'SUM' FROM company_actions aa WHERE DATE<=? AND TYPE = 'contact' AND NOT EXISTS (select 1 from company_actions bb where aa.COMPANY=bb.COMPANY AND( bb.TYPE='offre' OR bb.TYPE='offreSigned' OR bb.TYPE='delivery'))");
        $stmt->bind_param("s", $date_start_string);

        $stmt->execute();
        $stmt->bind_result($somme);
        $stmt->fetch();
        $stmt->close();
        array_push($companiesContact, $somme);

        $stmt = $conn->prepare("SELECT COUNT(1) AS 'SUM' FROM company_actions aa WHERE DATE<=? AND TYPE = 'offre' AND NOT EXISTS (select 1 from company_actions bb where aa.COMPANY=bb.COMPANY AND( bb.TYPE='offreSigned' OR bb.TYPE='delivery'))");
        $stmt->bind_param("s", $date_start_string);
        $stmt->bind_result($somme);
        $stmt->fetch();
        $stmt->close();
        array_push($companiesOffer, $somme);

        $stmt = $conn->prepare("SELECT COUNT(1) AS 'SUM' FROM company_actions aa, companies bb WHERE bb.INTERNAL_REFERENCE=aa.COMPANY AND DATE<=? AND aa.TYPE = 'offreSigned' AND NOT EXISTS (select 1 from company_actions bb where aa.COMPANY=bb.COMPANY AND bb.TYPE='delivery')");
        $stmt->bind_param("s", $date_start_string);
        $stmt->bind_result($somme);
        $stmt->fetch();
        $stmt->close();
        array_push($companiesOfferSigned, $somme);

        $stmt = $conn->prepare("SELECT COUNT(1) AS 'SUM' FROM companies aa WHERE HEU_MAJ<=? AND TYPE = 'NOT INTERESTED'");
        $stmt->bind_param("s", $date_start_string);
        $stmt->bind_result($somme);
        $stmt->fetch();
        $stmt->close();
        array_push($companiesNotInterested, $somme);


        array_push($dates, $date_start_string);
        $date_start->add(new DateInterval('P10D'));
    }

    $response['response']="success";
    $response['dates']=$dates;
    $response['companiesContact']=$companiesContact;
    $response['companiesOffer']=$companiesOffer;
    $response['companiesOfferSigned']=$companiesOfferSigned;
    $response['companiesNotInterested']=$companiesNotInterested;
    echo json_encode($response);
    die;
?>
