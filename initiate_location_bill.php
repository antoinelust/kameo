<?php
ob_start();
session_start();
$user=isset($_SESSION['userID']) ? $_SESSION['userID'] : NULL;
$user_ID = isset($_SESSION['ID']) ? $_SESSION['ID'] : NULL;
include './include/header5.php';
include './include/environment.php';
include './include/globalfunctions.php';


require_once dirname(__FILE__).'/vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;


?>

<script type="text/javascript" src="./js/language.js"></script>
<script type="text/javascript" src="./js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="./js/locales/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>
<script type="text/javascript" src="./js/addons/datatables.min.js"></script>
<script type="text/javascript" src="./js/datatable_default.js"></script>
<script type="text/javascript" src="./js/global_functions.js"></script>


<?php
$now=new DateTime('now');
$nowString=$now->format('Y-m-d');

include './include/connexion.php';
$sql="SELECT COMPANY, CONTRACT_START, BILLING_GROUP, substr(CONTRACT_START,9,2) as 'firstDay' from customer_bikes where CONTRACT_START<'$nowString' and CONTRACT_END is NULL GROUP BY COMPANY, CONTRACT_START, BILLING_GROUP";
if ($conn->query($sql) === FALSE) {
    echo $conn->error;
    die;
}
$result = mysqli_query($conn, $sql);   
$conn->close();    


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
                while($row = mysqli_fetch_array($result)){
                    
                    $data=array();
                    $company=$row['COMPANY'];
                    $firstDay=$row['firstDay'];
                    $billingGroup=$row['BILLING_GROUP'];
                    $contractStart=new DateTime($row['CONTRACT_START']);     
                    $contractStartString=$contractStart->format('Y-m-d');

                    if($firstDay==$now->format('d') || last_day_month($now->format('m'))==$now->format('d')){
                      
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
                                            
                        $lastDayMonth=last_day_month( $monthBefore->format('m') );
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
                    
                        
                        include 'include/connexion.php';
                        $sql="select max(ID) as MAX_TOTAL, max(ID_OUT_BILL) as MAX_OUT from factures";
                        if ($conn->query($sql) === FALSE) {
                            $response = array ('response'=>'error', 'message'=> $conn->error);
                            echo json_encode($response);
                            die;
                        }
                        $result = mysqli_query($conn, $sql);   
                        $resultat = mysqli_fetch_assoc($result);
                        $newID=$resultat['MAX_TOTAL'];
                        $newID=strval($newID+1);

                        $newIDOUT=$resultat['MAX_OUT'];
                        $newIDOUT=strval($newIDOUT+1);
                        
                        
                        
                        include './include/connexion.php';
                        $sql="SELECT * from customer_bikes where COMPANY='$company' and CONTRACT_START = '$contractStartString' and BILLING_GROUP='$billingGroup'";
                        if ($conn->query($sql) === FALSE) {
                            echo $conn->error;
                            die;
                        }
                        $result2 = mysqli_query($conn, $sql);   
                        $conn->close();
                        while($row2 = mysqli_fetch_array($result2)){

                            $data['ID'.$i] = $row2['ID'];
                            $data['price'.$i] = $row2['LEASING_PRICE'];
                            $data['type'.$i] = "bike";
                            $data['description'.$i] = $row2['FRAME_NUMBER'];
                            $i++;
                        }
                        $data['itemNumber'] = $i;
                        $test=CallAPI('POST', 'localhost:81/kameo/include/generate_bill.php', $data);
                        
                        $html2pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8', 3);
                        $html2pdf->pdf->SetDisplayMode('fullpage');
                        $html2pdf->writeHTML($test);

                        $path='/factures/'.date('Y').'.'.date('m').'.'.date('d').'_'.$company.'_'.$newID.'_facture_'.$newIDOUT.'.pdf';
                        $html2pdf->Output(__DIR__ . $path, 'F');
                        

                        var_dump($data);
                        var_dump($test);
                    }
                    
                }
                
                
                
                ?>
                
                
                
                
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>

      <div class="loader"><!-- Place at bottom of page --></div>

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
<script src="./js/theme-functions.js"></script>
<script type="text/javascript">
displayLanguage();
</script>

</body>
<?php
ob_end_flush();
?>

</html>