<?php 
include 'include/header2.php';
require_once('include/php-mailer/PHPMailerAutoload.php');

?>
    <!-- CONTENT -->
    <section class="content">
        <div class="container">
            <div class="row">		   

                <!-- post content -->
                <div class="post-content float-right col-md-9">
                    <div class="col-md-12">
                        <div class="heading heading text-left m-b-20">
                            <h2>Informations sur les feedbacks</h2>

                            <div class="m-t-30">
                                <?php
                                echo "------------------<br />";
                                echo "Début du script: ".date("H:m:s")."<br>";
                                echo "------------------<br />";

                                $date_day=mktime(0, 0, 0, intval(date('m')), intval(getDate()), intval(date('Y')));
                                                                
                                include 'include/connexion.php';
                                $sql= "SELECT aa.ID as 'ID', bb.COMPANY as 'COMPANY', aa.DATE_START as 'DATE_START', aa.FRAME_NUMBER as 'FRAME_NUMBER', aa.EMAIL as 'EMAIL' FROM reservations aa, customer_referential bb where aa.DATE_END>'$date_day' and aa.email=bb.email and not exists (select 1 from feedbacks cc where aa.ID=cc.ID_RESERVATION)";

                                if ($conn->query($sql) === FALSE) {
                                    $response = array ('response'=>'error', 'message'=> $conn->error);
                                    echo json_encode($response);
                                    die;
                                }
                                $result = mysqli_query($conn, $sql);   
                                $conn->close();

                                $part2="<table style=\"width:100%\" class=\"tableResume\"><tr><th class=\"tableResume\">ID</th><th class=\"tableResume\">Société</th><th class=\"tableResume\">Bike Number</th><th class=\"tableResume\">Date</th><th class=\"tableResume\">E-mail</th></tr>";

                                while($row = mysqli_fetch_array($result))
                                {
                                    $id=$row['ID'];
                                    $company=$row['COMPANY'];
                                    $dateStart=$row['DATE_START'];
                                    $frame_number=$row['FRAME_NUMBER'];
                                    $email=$row['EMAIL'];
                                    $part2=$part2."<tr class=\"tableResume\"><td class=\"tableResume\">".$id."</td><td class=\"tableResume\">".$company."</td><td class=\"tableResume\">".$frame_number."</td><td class=\"tableResume\">".$dateStart."</td><td class=\"tableResume\">".$email."</td></tr>";


                                }

                                $part2=$part2."</table>";
                                echo $part2;


                                echo "<br>------------------<br />";
                                echo "Fin du script: ".date("H:m:s");
                                echo "<br>------------------<br />";

                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END: CONTENT -->
        

<!-- FOOTER -->
<footer class="background-dark text-grey" id="footer">
    <div class="footer-content">
        <div class="container">
        
        <br><br>
        
            <div class="row text-center">
                <div class="copyright-text text-center"> &copy; 2019 KAMEO Bikes</div>
                <div class="social-icons center">
							<ul>
								<li class="social-facebook"><a href="https://www.facebook.com/Kameo-Bikes-123406464990910/" target="_blank"><i class="fa fa-facebook"></i></a></li>
								
								<li class="social-instagram"><a href="https://www.instagram.com/kameobikes/" target="_blank"><i class="fa fa-instagram"></i></a></li>
							</ul>
				</div>
				
				<br>
				<br>
				
            </div>
        </div>
    </div>
</footer>
		<!-- END: FOOTER -->
	<!-- END: WRAPPER -->


	<!-- Theme Base, Components and Settings -->
	<script src="js/theme-functions.js"></script>

	<!-- Custom js file -->
	<script src="js/language.js"></script>


</div></body>


</html>