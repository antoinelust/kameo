<!DOCTYPE html>
<html lang="fr">
<?php
include 'include/head.php';

$user=isset($_SESSION['userID']) ? $_SESSION['userID'] : NULL;
$user_ID = isset($_SESSION['ID']) ? $_SESSION['ID'] : NULL;
require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/environment.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/globalfunctions.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/include/header.php';


require_once dirname(__FILE__).'/vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
?>

<script type="text/javascript" src="./js/language.js"></script>
<script type="text/javascript" src="./js/addons/datatables/datatables.min.js"></script>
<script type="text/javascript" src="./js/global_functions.js"></script>

<?php

$now=new DateTime('now');


if(isset($_GET['day'])){
    $day=$_GET['day'];
}else{
    $day=$now->format('d');
}

if(isset($_GET['month'])){
    $month=$_GET['month'];
}else{
    $month=$now->format('m');
}

if(isset($_GET['year'])){
    $year=$_GET['year'];
}else{
    $year=null;
    $year=$now->format('Y');
}
if(isset($_GET['simulation'])){
    $simulation=$_GET['simulation'];
}else{
    $simulation=null;
}

$company=isset($_GET['company']) ? $_GET['company'] : NULL;



$nowString=$now->format('Y-m-d');

$date=$now;
$date->setDate($year, $month, $day);
$dateString=$date->format('Y-m-d');

if($now->format('m')==1){
    $monthBefore=12;
    $yearBefore=(($now->format('Y'))-1);
}else{
    $monthBefore=(($now->format('m'))-1);
    $yearBefore=$now->format('Y');
}
$dayBefore=$now->format('d');

$date1MonthBefore=new DateTime('now');
$date1MonthBefore->setDate($yearBefore, $monthBefore, $dayBefore);
$date1MonthBeforeString=$date1MonthBefore->format('Y-m-d');

$lastDayMonth=last_day_month( $monthBefore);
if($lastDayMonth < $dayBefore){
    $dayBefore=$lastDayMonth;
}


require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';

if($company){
    $sql="SELECT COMPANY, BILLING_GROUP, max(substr(CONTRACT_START,9,2)) as 'lastDay' from customer_bikes where CONTRACT_START<='$date1MonthBeforeString' and CONTRACT_END is NULL and CONTRACT_TYPE != 'selling' and COMPANY='$company' GROUP BY COMPANY, BILLING_GROUP";
}else{
    $sql="SELECT COMPANY, BILLING_GROUP, max(substr(CONTRACT_START,9,2)) as 'lastDay' from customer_bikes where CONTRACT_START<='$date1MonthBeforeString' and CONTRACT_END is NULL and CONTRACT_TYPE != 'selling' GROUP BY COMPANY, BILLING_GROUP";
}
if ($conn->query($sql) === FALSE) {
    echo $conn->error;
    die;
}
$result = mysqli_query($conn, $sql);

?>


<!-- CONTENT -->
<section class="content">
  <div class="container">
    <div class="row">


      <!-- post content -->
      <div class="post-content float-right col-md-9">
        <!-- Post item-->
        <div class="post-item">
          <div class="post-content-details">
            <div class="heading heading text-left m-b-20">
              <div class="row" style="position: relative;">
                <h2 class="col-sm-8">Generation des factures de location</h2>
              </div>
            </div>
            <br />
            <div class="col-md-12">
                <?php

                ob_start();


                while($row = mysqli_fetch_array($result)){

                    $data=array();
                    $company=$row['COMPANY'];
                    $lastDay=$row['lastDay'];
                    $billingGroup=$row['BILLING_GROUP'];

                    echo "<h4 class='text-green'>Company: ".$company."</h4><br/>Billing Group: ".$billingGroup."<br/>Last Day of contract :".$lastDay."<br/>Current day : ".$day."<br /><br/><u>Details of bikes</u><br/>";

                    include $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/connexion.php';
                    $sql="SELECT * FROM customer_bikes where COMPANY='$company' and BILLING_GROUP='$billingGroup' and CONTRACT_START<='$date1MonthBeforeString' and CONTRACT_END is NULL and CONTRACT_TYPE != 'selling'";
                    if ($conn->query($sql) === FALSE) {
                        echo $conn->error;
                        die;
                    }
                    $result2 = mysqli_query($conn, $sql);
                    while($row2 = mysqli_fetch_array($result2)){
                        echo "<br/>Bike Number : ".$row2['FRAME_NUMBER']."<br/>Contract Start : ".$row2['CONTRACT_START']."<br/>";
                    }


                    if($lastDay==$day || (last_day_month($now->format('m'))==$day && last_day_month($now->format('m'))<$lastDay)){

                        echo "<br/>Result: Generation of bill <br/>";

                        $i=0;
                        $data['company'] = $company;

                        if($now->format('m')==1){
                            $monthBefore=12;
                            $yearBefore=(($now->format('Y'))-1);
                        }else{
                            $monthBefore=(($now->format('m'))-1);
                            $yearBefore=$now->format('Y');
                        }
                        $dayBefore=$now->format('d');

                        $lastDayMonth=last_day_month( $monthBefore);
                        if($lastDayMonth < $dayBefore){
                            $dayBefore=$lastDayMonth;
                        }


                        if(strlen($monthBefore)==1){
                            $monthBefore='0'.$monthBefore;
                        }
                        if(strlen($dayBefore)==1){
                            $dayBefore='0'.$dayBefore;
                        }



                        $data['dateStart'] = $yearBefore.'-'.$monthBefore.'-'.$dayBefore;
                        $data['dateEnd'] = $now->format('Y-m-d');
                        $data['billingGroup'] = $billingGroup;


                        $sql="select max(ID) as MAX_TOTAL, max(ID_OUT_BILL) as MAX_OUT from factures";
                        if ($conn->query($sql) === FALSE) {
                            $response = array ('response'=>'error', 'message'=> $conn->error);
                            echo json_encode($response);
                            die;
                        }
                        $result3 = mysqli_query($conn, $sql);
                        $resultat = mysqli_fetch_assoc($result3);
                        $newID=$resultat['MAX_TOTAL'];
                        $newID=strval($newID+1);

                        $newIDOUT=$resultat['MAX_OUT'];
                        $newIDOUT=strval($newIDOUT+1);



                        $sql="SELECT * from customer_bikes where COMPANY='$company' and BILLING_GROUP='$billingGroup' and CONTRACT_START <= '$date1MonthBeforeString' and  CONTRACT_END is NULL and CONTRACT_TYPE != 'selling'";
                        if ($conn->query($sql) === FALSE) {
                            echo $conn->error;
                            die;
                        }
                        $result2 = mysqli_query($conn, $sql);
                        while($row2 = mysqli_fetch_array($result2)){

                            $data['ID'.$i] = $row2['ID'];
                            $data['price'.$i] = $row2['LEASING_PRICE'];
                            $data['type'.$i] = "bike";
                            $data['description'.$i] = $row2['FRAME_NUMBER'];
                            $i++;
                        }
                        $data['itemNumber'] = $i;
                        if(substr($_SERVER['REQUEST_URI'], 1, 4) != "test" && substr($_SERVER['HTTP_HOST'], 0, 9)!="localhost"){
                            $test=CallAPI('POST', 'https://www.kameobikes.com/include/generate_bill.php', $data);
                        }else if(substr($_SERVER['REQUEST_URI'], 1, 4) == "test"){
                            $test=CallAPI('POST', 'https://www.kameobikes.com/test/include/generate_bill.php', $data);
                        }else{
                            $test=CallAPI('POST', 'localhost:81/kameo/include/generate_bill.php', $data);
                        }





                        $html2pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8', 3);
                        $html2pdf->pdf->SetDisplayMode('fullpage');
                        $html2pdf->writeHTML($test);

                        $path='/factures/'.date('Y').'.'.date('m').'.'.date('d').'_'.$company.'_'.$newID.'_facture_'.$newIDOUT.'.pdf';
                        $html2pdf->Output(__DIR__ . $path, 'F');


                        var_dump($data);
                        var_dump($test);
                    }else{
                        echo "<br/>Result: Passed <br/>";
                    }

                    echo "<div class=\"separator\"></div>";

                }


                if (ob_get_contents()) ob_end_clean();
                ?>




            </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>

			<!-- FOOTER -->
		<footer class="background-dark text-grey" id="footer">
	    <div class="footer-content">
	        <div class="container">

	        <br><br>

	            <div class="row text-center">

	                <div class="copyright-text text-center"><ins>Kameo Bikes SPRL</ins>
						<br>BE 0681.879.712
						<br>+32 498 72 75 46 </div>
						<br>
	                <div class="social-icons center">
								<ul>
									<li class="social-facebook"><a href="https://www.facebook.com/Kameo-Bikes-123406464990910/" target="_blank"><i class="fa fa-facebook"></i></a></li>

									<li class="social-linkedin"><a href="https://www.linkedin.com/company/kameobikes/" target="_blank"><i class="fa fa-linkedin"></i></a></li>

								</ul>
					</div>

					<div><a href="faq.php" class="text-green text-bold"><h3 class="text-green">FAQ</h3></a><!-- | <a href="bonsplans.php" class="text-green text-bold">Les bons plans</a>--></div>

					<br>
					<br>

	            </div>
	        </div>
	    </div>
	</footer>
	<!-- END: FOOTER -->


	</div>
	<!-- END: WRAPPER -->

	<!-- Theme Base, Components and Settings -->
	<script src="js/theme-functions.js"></script>

	<!-- Language management -->
	<script type="text/javascript" src="js/language.js"></script>



</body>

</html>
