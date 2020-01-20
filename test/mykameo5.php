<?php 
include 'include/header.php';
// checkAccess();
$user=$_SESSION['userID'];
?>
<script>
function ValidationEvent() {
    // Storing Field Values In Variables
    var name = document.getElementById("name").value;
    var email = document.getElementById("email").value;
    var contact = document.getElementById("contact").value;
    // Regular Expression For Email
    var emailReg = /^([w-.]+@([w-]+.)+[w-]{2,4})?$/;
    // Conditions
    if (name != '' && email != '' && contact != '') {
    if (email.match(emailReg)) {
    if (document.getElementById("male").checked || document.getElementById("female").checked) {
    if (contact.length == 10) {
    alert("All type of validation has done on OnSubmit event.");
    return true;
    } else {
    alert("The Contact No. must be at least 10 digit long!");
    return false;
    }
    } else {
    alert("You must select gender.....!");
    return false;
    }
    } else {
    alert("Invalid Email Address...!!!");
    return false;
    }
    } else {
    alert("All fields are required.....!");
    return false;
    }
}

    
</script>
<?php

if($user==NULL){
    ?>
    <script>window.location.href = "http://www.kameobikes.com/index.php?deconnexion=true";</script>
    <?php
}



include 'include/connexion.php';
/* $sql = "select aa.EMAIL, aa.FRAME_NUMBER, aa.NOM, aa.PRENOM, aa.PHONE, aa.ADRESS, aa.POSTAL_CODE, aa.CITY, aa.WORK_ADRESS, aa.WORK_POSTAL_CODE, aa.WORK_CITY,
cc.MODEL_FR \"bike_Model_FR\", cc.MODEL_EN \"bike_Model_EN\", cc.MODEL_NL \"bike_Model_NL\", 
dd.MODEL_FR \"tires_Model_FR\", dd.MODEL_EN \"tires_Model_EN\", dd.MODEL_NL \"tires_Model_NL\",
ee.MODEL_FR \"saddle_Model_FR\", ee.MODEL_EN \"saddle_Model_EN\", ee.MODEL_NL \"saddle_Model_NL\",
ff.MODEL_FR \"handle_Model_FR\", ff.MODEL_EN \"handle_Model_EN\", ff.MODEL_NL \"handle_Model_NL\",
jj.TRANSMISSION_TYPE_FR \"transmission_type_FR\", jj.TRANSMISSION_TYPE_EN \"transmission_type_EN\", jj.TRANSMISSION_TYPE_NL \"transmission_type_NL\",
kk.ANTIVOL_FR \"antivol_FR\", kk.ANTIVOL_EN \"antivol_EN\", kk.ANTIVOL_NL \"antivol_NL\",
gg.COLOR_FR \"pedal_Color_FR\", gg.COLOR_EN \"pedal_Color_EN\", gg.COLOR_NL \"pedal_Color_NL\",
hh.COLOR_FR \"handle_Color_FR\", hh.COLOR_EN \"handle_Color_EN\", hh.COLOR_NL \"handle_Color_NL\",
ii.COLOR_FR \"wires_Color_FR\", ii.COLOR_EN \"wires_Color_EN\", ii.COLOR_NL \"wires_Color_NL\"
from customer_referential aa, customer_bikes bb, bike_models cc, tires_model dd, saddle_model ee, handle_model ff, transmission_type jj, antivol kk, color_proposed gg, color_proposed hh, color_proposed ii 
where aa.EMAIL='$user' and aa.FRAME_NUMBER=bb.FRAME_NUMBER and bb.TYPE=cc.ID and bb.ANTIVOL=kk.ID
and bb.tires_MODEL=dd.ID and bb.SADDLE_MODEL=ee.ID and bb.handle_MODEL=ff.ID and bb.transmission_type=jj.ID
and bb.PEDAL_COLOR=gg.COLOR_ID and bb.HANDLE_COLOR=hh.COLOR_ID and bb.WIRES_COLOR=ii.COLOR_ID"; */
	
$sql = "select aa.EMAIL, aa.FRAME_NUMBER, aa.NOM, aa.PRENOM, aa.PHONE, aa.ADRESS, aa.POSTAL_CODE, aa.CITY, aa.WORK_ADRESS, aa.WORK_POSTAL_CODE, aa.WORK_CITY from customer_referential aa where aa.EMAIL='$user'";
	
	
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);

	
	
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
                            <h2>MY KAMEO</h2>
                        </div>
						<?php
						//include 'include/meteo.php';
						?>
						<br />
                        <!--<img src="images/meteo_300px.jpg" class="img-responsive img-rounded" alt="meteo du jour">-->
                        
						 <div class="form-group col-sm-6"> 
                        <a class="button small red-dark button-3d rounded icon-right" data-target="#probleme" data-toggle="modal" href="#">
						<span id="fr">Signaler un problème</span>
						<span id="en">Report an issue</span>
						<span id="nl">Een probleem melden</span>
						</a>	
						</div>
						
                        
                        <br>
                        <?php 
						if (ctype_alpha(substr($row["FRAME_NUMBER"],0,3))){
                            
                            // Afficher toutes les réservations actives du client avant de pouvoir faire la recherche. Demander à Thibault ! 
                            // => faire les passées, les futures, etc...
                            
                            
                            //new db to construct : bike_reservations: doit contenir
                            //ID
                            //FRAME_NUMBER
                            //DATE_START
                            //BUILDING_START
                            //DATE_END
                            //BUILDING_END
                            //ID_CLIENT
                            //index sur FRAME_NUMBER, DATE_START, DATE_END
                            //index sur ID_CLIENT pour la recherche
                            
?>
                          
                          
               <!--ce form ci permet de ne p"as avoir un bug.-->
               <form action="#" method="post">
               </form>

                <div class="col-md-12">  		
						<!--Horizontal tabs radius-->
				<div id="tabs-05c" class="tabs color tabs radius">
					<ul class="tabs-navigation">
						<li class="active"><a href="#administration"><i class="fa fa-book"></i>Administration</a> </li>
						<li class="active"><a href="#reserver"><i class="fa fa-calendar-plus-o"></i>Réserver un vélo</a> </li>
						<li><a href="#reservations"><i class="fa fa-check-square-o"></i>Vos réservations</a> </li>
						
					</ul>
					
					<div class="tabs-content">
					
					<div class="tab-pane" id="administration">
							
							<h4>Utilisation des vélos</h4>
						 	
						 	<div class="form-group col-sm-12">
						 	
						 	<!-- Graphique -->
						 	<!--
							<script src="https://code.highcharts.com/highcharts.js"></script>
							<script src="https://code.highcharts.com/modules/exporting.js"></script>
							<script src="https://code.highcharts.com/modules/export-data.js"></script>
							
							<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

						 	
						 	<script type="text/javascript">
							
								Highcharts.chart('container', {
								  chart: {
								    type: 'line'
								  },
								  title: {
								    text: 'Utilisation des vélos'
								  },
								 
								  xAxis: {
								    categories: ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jui', 'Jui', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec']
								  },
								  yAxis: {
								    title: {
								      text: 'Nombre de kilomètres parcourus'
								    }
								  },
								  plotOptions: {
								    line: {
								      dataLabels: {
								        enabled: true
								      },
								      enableMouseTracking: false
								    }
								  },
								  series: [{
								    name: 'Année 2018',
								    data: [1753, 864, 1763, 3862, 6253, 13243, 4234, 152 , 9823, 25343, 1625, 837]
								  }]
								});
							</script>
							-->
							
							<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
						    <script type="text/javascript">
						      google.charts.load('current', {'packages':['corechart']});
						      google.charts.setOnLoadCallback(drawChart);
						
						      function drawChart() {
						        var data = google.visualization.arrayToDataTable([
						          ['Month', '2018'],
						          ['Jan',  1000],
						          ['Fev',  1170],
						          ['Mar',  660],
						          ['Avr',  1030],
						          ['Mai',  635],
						          ['Jui',  2987],
						          ['Jui',  2543],
						          ['Aou',  3987],
						          ['Sep',  1392],
						          ['Oct',  752],
						          ['Nov',  200],
						          ['Dec',  150]
						        ]);
						
						        var options = {
						          title: 'Nombre de kilomètres parcourus',
						          hAxis: {},
						          vAxis: {minValue: 0}
						        };
						
						        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
						        chart.draw(data, options);
						        
						        
						      }
						    </script>
						    
						    <div id="chart_div"></div>
						 	
						 	</div>
							
						</div>
						<div class="tab-pane active" id="reserver">
                           <form id="search-bikes-form" action="include/search-bikes.php" method="post">                    
                               <div class="form-group col-sm-12">
                               		
                                    <label for="date" class="special">À quelle date voulez-vous prendre le vélo?</label><br />	
                                    <div class="form-group col-sm-6"> 
                                    								      
                                        <select name="search-bikes-form-day" id="date_jour">									           
                                            <option value="1">1</option>									       
                                            <option value="2">2</option>									        
                                            <option value="3">3</option>								
                                            <option value="4">4</option>
                                            <option value="5">5</option>									       
                                            <option value="6">6</option>									        
                                            <option value="7">7</option>								
                                            <option value="8">8</option>
                                            <option value="9">9</option>	
                                            <option value="10">10</option>
                                            <option value="11">11</option>									       
                                            <option value="12">12</option>									        
                                            <option value="13">13</option>								
                                            <option value="14">14</option>
                                            <option value="15">15</option>									       
                                            <option value="16">16</option>									        
                                            <option value="17">17</option>								
                                            <option value="18">18</option>
                                            <option value="19">19</option>
                                            <option value="20">20</option>
                                            <option value="21">21</option>									       
                                            <option value="22">22</option>									        
                                            <option value="23">23</option>								
                                            <option value="24">24</option>
                                            <option value="25">25</option>									       
                                            <option value="26">26</option>									        
                                            <option value="27">27</option>								
                                            <option value="28">28</option>
                                            <option value="29">29</option>
                                            <option value="30">30</option>
                                            <option value="31">31</option>									      
                                        </select>
                                    </div>
                                    
                                    <div class="form-group col-sm-6"> 
                                        <select name="search-bikes-form-month" id="date_jour">									           
                                            <option value="1">janvier</option>									       
                                            <option value="2">février</option>									        
                                            <option value="3">mars</option>								
                                            <option value="4">avril</option>
                                            <option value="5">mai</option>									       
                                            <option value="6">juin</option>									        
                                            <option value="7">juillet</option>								
                                            <option value="8">août</option>
                                            <option value="9">septembre</option>	
                                            <option value="10">octobre</option>	
                                            <option value="11">novembre</option>
                                            <option value="12">décembre</option>								      
                                        </select>    
                                    </div>  
                               
                                                         

                                <div class="form-group col-sm-6">                                       
                                     <label for="search-bikes-form-intake-building">Où voulez-vous prendre le vélo?</label><br />									     
                                     <select name="search-bikes-form-deposit-building">									           
                                            <option value="marcellis">Quai Marcellis 24</option>									       
                                        	<option value="sauveniere">Boulevard de la Sauvenière 118</option>		
                                        	<option value="stemarie">Rue Ste-Marie 22</option>									      
                                      </select>                                  
                                </div>                                                                         
                                <div class="form-group col-sm-6">                                      
                                     <label for="search-bikes-form-deposit-building">Où voulez-vous rendre le vélo?</label><br />									      
                                     <select name="search-bikes-form-deposit-building">									           
                                            <option value="marcellis">Quai Marcellis 24</option>									       
                                        	<option value="sauveniere">Boulevard de la Sauvenière 118</option>		
                                        	<option value="stemarie">Rue Ste-Marie 22</option>									      
                                      </select>                                  
                                 </div>  
                                 <div class="form-group col-sm-6">                                       
                                     <label for="search-bikes-form-intake-hour">À quelle heure voulez-vous prendre le vélo?</label><br />									     
                                     <select name="search-bikes-form-intake-hour">
                                        <option value="8h00">8h00</option>									       
                                        <option value="8h15">8h15</option>									        
                                        <option value="8h30">8h30</option>								
                                        <option value="8h45">8h45</option>
                                        <option value="9h00">9h00</option>									       
                                        <option value="9h15">9h15</option>									        
                                        <option value="9h30">9h30</option>								
                                        <option value="9h45">9h45</option>
                                        <option value="10h00">10h00</option>									       
                                        <option value="10h15">10h15</option>									        
                                        <option value="10h30">10h30</option>								
                                        <option value="10h45">10h45</option>
                                        <option value="11h00">11h00</option>									       
                                        <option value="11h15">11h15</option>									        
                                        <option value="11h30">11h30</option>								
                                        <option value="11h45">11h45</option>
                                        <option value="12h00">12h00</option>									       
                                        <option value="12h15">12h15</option>									        
                                        <option value="12h30">12h30</option>								
                                        <option value="12h45">12h45</option>
                                        <option value="13h00">13h00</option>									       
                                        <option value="13h15">13h15</option>									        
                                        <option value="13h30">13h30</option>								
                                        <option value="13h45">13h45</option>
                                        <option value="14h00">14h00</option>									       
                                        <option value="14h15">14h15</option>									        
                                        <option value="14h30">14h30</option>								
                                        <option value="14h45">14h45</option>
                                        <option value="15h00">15h00</option>									       
                                        <option value="15h15">15h15</option>									        
                                        <option value="15h30">15h30</option>								
                                        <option value="15h45">15h45</option>
                                        <option value="16h00">16h00</option>									       
                                        <option value="16h15">16h15</option>									        
                                        <option value="16h30">16h30</option>								
                                        <option value="16h45">16h45</option>
                                        <option value="17h00">17h00</option>									       
                                        <option value="17h15">17h15</option>									        
                                        <option value="17h30">17h30</option>								
                                        <option value="17h45">17h45</option>
                                        <option value="18h00">18h00</option>									       
                                        <option value="18h15">18h15</option>									        
                                        <option value="18h30">18h30</option>								
                                        <option value="18h45">18h45</option>									    
                                      </select>                                   
                                </div>                                                                         
                                <div class="form-group col-sm-6">                                      
                                     <label for="search-bikes-form-deposit-hour">À quelle heure voulez-vous rendre le vélo?</label><br />									      
                                     <select name="search-bikes-form-deposit-hour">									           
                                        <option value="8h00">8h00</option>									       
                                        <option value="8h15">8h15</option>									        
                                        <option value="8h30">8h30</option>								
                                        <option value="8h45">8h45</option>
                                        <option value="9h00">9h00</option>									       
                                        <option value="9h15">9h15</option>									        
                                        <option value="9h30">9h30</option>								
                                        <option value="9h45">9h45</option>
                                        <option value="10h00">10h00</option>									       
                                        <option value="10h15">10h15</option>									        
                                        <option value="10h30">10h30</option>								
                                        <option value="10h45">10h45</option>
                                        <option value="11h00">11h00</option>									       
                                        <option value="11h15">11h15</option>									        
                                        <option value="11h30">11h30</option>								
                                        <option value="11h45">11h45</option>
                                        <option value="12h00">12h00</option>									       
                                        <option value="12h15">12h15</option>									        
                                        <option value="12h30">12h30</option>								
                                        <option value="12h45">12h45</option>
                                        <option value="13h00">13h00</option>									       
                                        <option value="13h15">13h15</option>									        
                                        <option value="13h30">13h30</option>								
                                        <option value="13h45">13h45</option>
                                        <option value="14h00">14h00</option>									       
                                        <option value="14h15">14h15</option>									        
                                        <option value="14h30">14h30</option>								
                                        <option value="14h45">14h45</option>
                                        <option value="15h00">15h00</option>									       
                                        <option value="15h15">15h15</option>									        
                                        <option value="15h30">15h30</option>								
                                        <option value="15h45">15h45</option>
                                        <option value="16h00">16h00</option>									       
                                        <option value="16h15">16h15</option>									        
                                        <option value="16h30">16h30</option>								
                                        <option value="16h45">16h45</option>
                                        <option value="17h00">17h00</option>									       
                                        <option value="17h15">17h15</option>									        
                                        <option value="17h30">17h30</option>								
                                        <option value="17h45">17h45</option>
                                        <option value="18h00">18h00</option>									       
                                        <option value="18h15">18h15</option>									        
                                        <option value="18h30">18h30</option>								
                                        <option value="18h45">18h45</option>									      
                                      </select>   
                                      </div>
                                      <input type="text" class="hidden" id="widget-lostPassword-form-hash" name="search-bikes-form-frame-number" value="<?php echo $row['FRAME_NUMBER'] ?>" />                               
                                 </div> 
                                
<br />
								<div class="form-group col-sm-6">  
									<button id="fr" class="button effect fill" type="submit" id="form-submit">Rechercher</button>
									<button id="en" class="button effect fill" type="submit" id="form-submit">Search</button>
									<button id="nl" class="button effect fill" type="submit" id="form-submit">Zoeken</button>
								</div>
                            </form>
                            <script type="text/javascript">            
                                jQuery("#search-bikes-form").validate({
                                    submitHandler: function(form) {

                                        jQuery(form).ajaxSubmit({
                                            success: function(text) {
                                                if (text.response == 'error') {
                                                    $.notify({
                                                        message: text.message
                                                    }, {
                                                        type: 'danger'
                                                    });

                                                } else {
                                                    $.notify({
                                                        message: text.message
                                                    }, {
                                                        type: 'success'
                                                    });

                                                }
                                            }
                                        });
                                    }
                                });
                                

                            </script> 
                            </div>   
						
						<!-- Pour un écran large -->
						<div class="visible-lg">
							<div class="col-lg-12 backgroundgreen">
								<p class="text-white">Le 03/07/2018 à 08h00</p> 
							</div>
						</div>
						
						<div class="visible-lg">
						<div class="col-lg-12 backgroundgreen">
                            
                            	<div class="col-lg-3">
                            		<img src="images/meteo/pluie.png" alt="image" class="center" />
                            	</div>     
                            	
                            	<div class="col-lg-3">
									<ul>
										<li class="temperature  text-center">  7°</li>
										<li class="humidite  text-center">  75%</li>
										<li class="vent  text-center">  8,7 m/s</li>
									</ul>
								</div>
                            	
                            	<div class="col-lg-3">
                            		<ul class="bords">
										<li class="marche grid-col-demo text-center">  37 min</li>
										<li class="voiture grid-col-demo text-center">  13 min</li>
										<li class="bike grid-col-demo text-center">  11 min</li>
									</ul>
                            	</div>
                            	
                            	<div class="col-lg-3">
                            	<img src="images/meteo/2_10.png" alt="image" />
                            	</div>
                          </div>  
						</div>
						
						
						<!-- Pour un écran médium -->
						<div class="visible-md">
							<div class="col-md-12 backgroundgreen">
								<p class="text-white">Le 03/07/2018 à 08h00</p> 
							</div>
						</div>
						
						<div class="visible-md">
						<div class="col-md-12 backgroundgreen">
                            
                            	<div class="col-md-3">
                            		<img src="images/meteo/pluie.png" alt="image" class="center" />
                            	</div>     
                            	
                            	<div class="col-md-3">
									<ul>
										<li class="temperature  text-center">  7°</li>
										<li class="humidite  text-center">  75%</li>
										<li class="vent  text-center">  8,7 m/s</li>
									</ul>
								</div>
                            	
                            	<div class="col-md-3">
                            		<ul class="bords">
										<li class="marche grid-col-demo text-center">  37 min</li>
										<li class="voiture grid-col-demo text-center">  13 min</li>
										<li class="bike grid-col-demo text-center">  11 min</li>
									</ul>
                            	</div>
                            	
                            	<div class="col-md-3">
                            	<img src="images/meteo/2_10.png" alt="image" />
                            	</div>
                          </div>          
						</div>
						
						<!-- Pour une tablette -->
						<div class="visible-sm">
							<div class="col-sm-12 backgroundgreen">
								<p class="text-white">Le 03/07/2018 à 08h00</p> 
							</div>
						</div>
						
						<div class="visible-sm">
						<div class="col-sm-12 backgroundgreen">
                            
                            	<div class="col-sm-12">
                            		<img src="images/meteo/pluie.png" alt="image" class="centerimg" />
                            	</div>     
                            	
                            	<div class="seperator"></div>
                            	
                            	<div class="col-sm-6">
									<ul>
										<li class="temperature2  text-center">  7°</li>
										<li class="humidite2  text-center">  75%</li>
										<li class="vent2  text-center">  8,7 m/s</li>
									</ul>
								</div>
                            	
                            	<div class="col-sm-6">
                            		<ul class="bords">
										<li class="marche2 grid-col-demo text-center">  37 min</li>
										<li class="voiture2 grid-col-demo text-center">  13 min</li>
										<li class="bike2 grid-col-demo text-center">  11 min</li>
									</ul>
                            	</div>
                            	
                            	<div class="seperator"></div>
                            	
                            	<div class="col-sm-12">
                            	<img src="images/meteo/2_10.png" alt="image" class="centerimg"/>
                            	</div>
                          </div>          
						</div>
						
						<!-- Pour un smartphone -->
						<div class="visible-xs">
							<div class="col-xs-12 backgroundgreen">
								<p class="text-white">Le 03/07/2018 à 08h00</p> 
							</div>
						</div>
						
						<div class="visible-xs">
						<div class="col-xs-12 backgroundgreen">
                            
                            	<div class="col-xs-12">
                            		<img src="images/meteo/pluie.png" alt="image" class="centerimg" />
                            	</div> 
                            	
                            	<div class="seperator"></div>    
                            	
                            	<div class="col-xs-12">
									<ul>
										<li class="temperature3  text-center">  7°</li>
										<li class="humidite3  text-center">  75%</li>
										<li class="vent3  text-center">  8,7 m/s</li>
									</ul>
								</div>
								
								<div class="seperator"></div>
                            	
                            	<div class="col-xs-12">
                            		<ul class="bords">
										<li class="marche3 grid-col-demo text-center">  37 min</li>
										<li class="voiture3 grid-col-demo text-center">  13 min</li>
										<li class="bike3 grid-col-demo text-center">  11 min</li>
									</ul>
                            	</div>
                            	
                            	<div class="seperator"></div>
                            	
                            	<div class="col-xs-12">
                            	<img src="images/meteo/2_10.png" alt="image" class="centerimg" />
                            	</div>
                          </div>          
						</div>
						
					
						
						
						<div class="tab-pane" id="reservations">
							
							<div data-example-id="contextual-table" class="bs-example">
						      <table class="table table-condensed">
						      <h4>Vos anciennes réservations:</h4>
						      
						      <div class="col-md-3">  
						      <p>Nombre par page:
						      	<select>
								  <option value="2">2</option>
								  <option value="5">5</option>
								  <option value="10">10</option>
								  <option value="25">25</option>
								</select>
								</p>
								</div>
								
						        <thead>
						          <tr>
						            <th>Date</th>
						            <th>Départ</th>
						            <th>Arrivée</th>
						            <th>Vélo</th>
						          </tr>
						        </thead>
						        <tbody>
						          <tr>
						            <td>3 avril 2018</td>
						            <td>Rue Ste-Marie à 1Oh30</td>
						            <td>Boulevard de la Sauvenière à 12h45</td>
						            <td>Romeo ABC01</td>
						          </tr>
						          <tr>
						            <td>3 avril 2018</td>
						            <td>Boulevard de la sauvenière à 16h30</td>
						            <td>Quai Marcellis à 16h15</td>
						            <td>Romeo ABC01</td>
						          </tr>
						        </tbody>
						      </table>
						      
						       <div class="form-group col-sm-6"> 
		                        <a class="button small green button-3d rounded icon-right" href="#">
								<span id="fr">Montrer plus</span>
								</a>	
								</div>
						      
						    </div>
						    
						    <div class="seperator"></div>
						    
						    <div data-example-id="contextual-table" class="bs-example">
						      <table class="table table-condensed">
						      <h4>Vos prochaines réservations:</h4>
						        <thead>
						          <tr>
						            <th>Date</th>
						            <th>Départ</th>
						            <th>Arrivée</th>
						            <th>Vélo</th>
						            <th></th>
						          </tr>
						        </thead>
						        <tbody>
						          <tr>
						            <td>12 avril 2018</td>
						            <td>Rue Ste-Marie à 1Oh30</td>
						            <td>BQuai Marcellis à 12h45</td>
						            <td>Romeo ABC03</td>
						            <td><a class="button small green rounded effect" data-target="#1" data-toggle="modal" href="#"><span>+</span></a></td></td>
						          </tr>
						          <tr>
						            <td>12 avril 2018</td>
						            <td>Quai Marcellis à 16h30</td>
						            <td>Rue Ste-Marie à 16h15</td>
						            <td>Romeo ABC03</td>
						            <td><a class="button small green rounded effect" data-target="#2" data-toggle="modal" href="#"><span>+</span></a></td>
						          </tr>
						        </tbody>
						      </table>
						    </div>
						    
							
							
						</div>
						
						
					</div>
				</div>
				
				
				<div class="modal fade" id="1" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-sm-12">
									<h4>Personne avant vous:</h4>
										<ul>
											<li>Nom et prénom: Antoine Lust</li>
											<li>Numéro de téléphone: 0487 65 44 39</li>
											<li>Adresse mail: antoine.lust@kameobikes.com</li>
										</ul>
									<h4>Personne après vous:</h4>
										<p>Aucun</p>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<div class="pull-left">
									<button data-dismiss="modal" class="btn btn-b" type="button">Fermer</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="modal fade" id="2" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-sm-12">
									<h4>Personne avant vous:</h4>
										<ul>
											<li>Nom et prénom: Pierre-Yves Adant</li>
											<li>Numéro de téléphone: 0487 65 44 83</li>
											<li>Adresse mail: pierre-yves.adant@kameobikes.com</li>
										</ul>
									<h4>Personne après vous:</h4>
										<p>Aucun</p>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<div class="pull-left">
									<button data-dismiss="modal" class="btn btn-b" type="button">Fermer</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="modal fade" id="3" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-sm-12">
									<img src="images/velo1_mini.jpg" alt="image" />
									<h4>Nombre de réservations faites:</h4><p> 5</p>
									<h4>Kilomètres parcourus:</h4><p> 542</p>
									<h4>Date du dernier entretien:</h4><p> 15 mai 2018</p>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<div class="pull-left">
									<button data-dismiss="modal" class="btn btn-b" type="button">Fermer</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="modal fade" id="4" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-sm-12">
									<img src="images/velo2_mini.jpg" alt="image" />
									<h4>Nombre de réservations faites:</h4><p> 18</p>
									<h4>Kilomètres parcourus:</h4><p> 1298</p>
									<h4>Date du dernier entretien:</h4><p> 15 mai 2018</p>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<div class="pull-left">
									<button data-dismiss="modal" class="btn btn-b" type="button">Fermer</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-md-4">
				<div class="featured-box">
					<div class="effect social-links"> <img src="images/velo1_big.jpg" alt="image" />
						<div class="image-box-content">
							<p> <a href="images/velo1_big.jpg" data-lightbox-type="image" title=""><i class="fa fa-expand"></i></a> </p>
						</div>
					</div>
				</div>
				</div>
				<div class="col-md-4">
				<h4>Pameo</h4>
				<p class="subtitle">ABC123</p>
				</div>
				<div class="col-md-2">
					<a class="button large green button-3d rounded icon-left" id="fr" data-target="#resume" data-toggle="modal" href="#"><span>Réserver</span></a>
				</div>
				
				<div class="seperator"></div>
				
				<div class="col-md-4">
				<div class="featured-box">
					<div class="effect social-links"> <img src="images/velo2_big.jpg" alt="image" />
						<div class="image-box-content">
							<p> <a href="images/velo2_big.jpg" data-lightbox-type="image" title=""><i class="fa fa-expand"></i></a> </p>
						</div>
					</div>
				</div>
				</div>
				<div class="col-md-4">
				<h4>Romeo</h4>
				<p class="subtitle">ABC123</p>
				</div>
				<div class="col-md-2">
					<a class="button large green button-3d rounded icon-left" id="fr" data-target="#resume" data-toggle="modal" href="#"><span>Réserver</span></a>
				</div>
				
				<div class="seperator"></div>
				
				<div class="col-md-4">
				<div class="featured-box">
					<div class="effect social-links"> <img src="images/velo3_big.jpg" alt="image" />
						<div class="image-box-content">
							<p> <a href="images/velo3_big.jpg" data-lightbox-type="image" title=""><i class="fa fa-expand"></i></a> </p>
						</div>
					</div>
				</div>
				</div>
				<div class="col-md-4">
				<h4>Zimeo</h4>
				<p class="subtitle">ABC123</p>
				</div>
				<div class="col-md-2">
					<a class="button large green button-3d rounded icon-left" id="fr" data-target="#resume" data-toggle="modal" href="#"><span>Réserver</span></a>
				</div>
				
				<div class="seperator"></div>
				
				<div class="col-md-4">
				<div class="featured-box">
					<div class="effect social-links"> <img src="images/velo1_big.jpg" alt="image" />
						<div class="image-box-content">
							<p> <a href="images/velo1_big.jpg" data-lightbox-type="image" title=""><i class="fa fa-expand"></i></a> </p>
						</div>
					</div>
				</div>
				</div>
				<div class="col-md-4">
				<h4>Pameo</h4>
				<p class="subtitle">ABC123</p>
				</div>
				<div class="col-md-2">
					<a class="button large green button-3d rounded icon-left" id="fr" data-target="#resume" data-toggle="modal" href="#"><span>Réserver</span></a>
				</div>
				
				<div class="seperator"></div>
				
				<div class="col-md-4">
				<div class="featured-box">
					<div class="effect social-links"> <img src="images/velo3_big.jpg" alt="image" />
						<div class="image-box-content">
							<p> <a href="images/velo3_big.jpg" data-lightbox-type="image" title=""><i class="fa fa-expand"></i></a> </p>
						</div>
					</div>
				</div>
				</div>
				<div class="col-md-4">
				<h4>Zimeo</h4>
				<p class="subtitle">ABC123</p>
				</div>
				<div class="col-md-2">
					<a class="button large green button-3d rounded icon-left" id="fr" data-target="#resume" data-toggle="modal" href="#"><span>Réserver</span></a>
				</div>
				
				<div class="row">
				<hr>
			        <nav>
			          <ul class="pager pager-fancy">
			            <li class="previous"><a href="#"><i class="fa fa-angle-left"></i> Avant</a> </li>
			            <li class="next"><a href="#">Suite <i class="fa fa-angle-right"></i></a> </li>
			          </ul>
			        </nav>
				</div>
				
				
				</div>
<?php
						}
						else
						{
							$sql = "select aa.EMAIL, aa.FRAME_NUMBER, aa.NOM, aa.PRENOM, aa.PHONE, aa.ADRESS, aa.POSTAL_CODE, aa.CITY, aa.WORK_ADRESS, aa.WORK_POSTAL_CODE, aa.WORK_CITY,
							cc.MODEL_FR \"bike_Model_FR\", cc.MODEL_EN \"bike_Model_EN\", cc.MODEL_NL \"bike_Model_NL\", 
							dd.MODEL_FR \"tires_Model_FR\", dd.MODEL_EN \"tires_Model_EN\", dd.MODEL_NL \"tires_Model_NL\",
							ee.MODEL_FR \"saddle_Model_FR\", ee.MODEL_EN \"saddle_Model_EN\", ee.MODEL_NL \"saddle_Model_NL\",
							ff.MODEL_FR \"handle_Model_FR\", ff.MODEL_EN \"handle_Model_EN\", ff.MODEL_NL \"handle_Model_NL\",
							jj.TRANSMISSION_TYPE_FR \"transmission_type_FR\", jj.TRANSMISSION_TYPE_EN \"transmission_type_EN\", jj.TRANSMISSION_TYPE_NL \"transmission_type_NL\",
							kk.ANTIVOL_FR \"antivol_FR\", kk.ANTIVOL_EN \"antivol_EN\", kk.ANTIVOL_NL \"antivol_NL\",
							gg.COLOR_FR \"pedal_Color_FR\", gg.COLOR_EN \"pedal_Color_EN\", gg.COLOR_NL \"pedal_Color_NL\",
							hh.COLOR_FR \"handle_Color_FR\", hh.COLOR_EN \"handle_Color_EN\", hh.COLOR_NL \"handle_Color_NL\",
							ii.COLOR_FR \"wires_Color_FR\", ii.COLOR_EN \"wires_Color_EN\", ii.COLOR_NL \"wires_Color_NL\"
							from customer_referential aa, customer_bikes bb, bike_models cc, tires_model dd, saddle_model ee, handle_model ff, transmission_type jj, antivol kk, color_proposed gg, color_proposed hh, color_proposed ii 
							where aa.EMAIL='$user' and aa.FRAME_NUMBER=bb.FRAME_NUMBER and bb.TYPE=cc.ID and bb.ANTIVOL=kk.ID
							and bb.tires_MODEL=dd.ID and bb.SADDLE_MODEL=ee.ID and bb.handle_MODEL=ff.ID and bb.transmission_type=jj.ID
							and bb.PEDAL_COLOR=gg.COLOR_ID and bb.HANDLE_COLOR=hh.COLOR_ID and bb.WIRES_COLOR=ii.COLOR_ID";
							$result = mysqli_query($conn, $sql);
							$row = mysqli_fetch_assoc($result);
							?>
	                        <img src="images/romeo_big.jpg" class="img-responsive img-rounded" alt="Infographie">
						    <div class="table-responsive">
						      <table class="table table-striped">
						        <tbody>
								  <tr>
									<td id="fr">Type de cadre</td>
									<td id="en">Bike model</td>
									<td id="nl">Fietsmodel</td>
									<td id="fr"><?php echo $row["bike_Model_FR"] ?></td>
									<td id="en"><?php echo $row["bike_Model_EN"] ?></td>
									<td id="nl"><?php echo $row["bike_Model_NL"] ?></td>
								  </tr>
								  <tr>
									<td id="fr">Numéro de châssis</td>
									<td id="en">Frame number</td>
									<td id="nl">Fietsnummer</td>
										<td><?php echo $row["FRAME_NUMBER"] ?></td>
								  </tr>
								  <tr>
									<td id="fr">Type de selle</td>
									<td id="en">Saddle model</td>
									<td id="nl">Zadel model</td>
									<td id="fr"><?php echo $row["saddle_Model_FR"] ?></td>
									<td id="en"><?php echo $row["saddle_Model_EN"] ?></td>
									<td id="nl"><?php echo $row["saddle_Model_NL"] ?></td>
								  </tr>
								  <tr>
									<td id="fr">Type de poignées</td>
									<td id="en">Handle model</td>
									<td id="nl">Handvat model</td>
									<td id="fr"><?php echo $row["handle_Model_FR"] ?></td>
									<td id="en"><?php echo $row["handle_Model_EN"] ?></td>
									<td id="nl"><?php echo $row["handle_Model_NL"] ?></td>
								  </tr>
								  <tr>
									<td id="fr">Type de pneu</td>
									<td id="en">Tires model</td>
									<td id="nl">Banden model</td>
									<td id="fr"><?php echo $row["tires_Model_FR"] ?></td>
									<td id="en"><?php echo $row["tires_Model_EN"] ?></td>
									<td id="nl"><?php echo $row["tires_Model_NL"] ?></td>
								  </tr>
								  <tr>
									<td id="fr">Type de transmission</td>
									<td id="en">Transmission type</td>
									<td id="nl">Transmissietype</td>
									<td id="fr"><?php echo $row["transmission_type_FR"] ?></td>
									<td id="en"><?php echo $row["transmission_type_EN"] ?></td>
									<td id="nl"><?php echo $row["transmission_type_NL"] ?></td>
								  </tr>
								  <tr>
									<td id="fr">Couleur des pédales</td>
									<td id="en">Pedals color</td>
									<td id="nl">Pedalen kleur</td>
									<td id="fr"><?php echo $row["pedal_Color_FR"] ?></td>
									<td id="en"><?php echo $row["pedal_Color_EN"] ?></td>
									<td id="nl"><?php echo $row["pedal_Color_NL"] ?></td>
								  </tr>				
								  <tr>
									<td id="fr">Couleur des cables de frein</td>
									<td id="en">Wires color</td>
									<td id="nl">Remkabels kleur</td>
									<td id="fr"><?php echo $row["wires_Color_FR"] ?></td>
									<td id="en"><?php echo $row["wires_Color_EN"] ?></td>
									<td id="nl"><?php echo $row["wires_Color_NL"] ?></td>
								  </tr>			
								  <tr>
									<td id="fr">Couleur des poignées</td>
									<td id="en">Handle color</td>
									<td id="nl">Handvat kleur</td>
									<td id="fr"><?php echo $row["handle_Color_FR"] ?></td>
									<td id="en"><?php echo $row["handle_Color_EN"] ?></td>
									<td id="nl"><?php echo $row["handle_Color_NL"] ?></td>
								  </tr>										  
								  <tr>
									<td id="fr">Antivol</td>
									<td id="en">Locker</td>
									<td id="nl">Kastje</td>
									<td id="fr"><?php echo $row["antivol_FR"] ?></td>
									<td id="en"><?php echo $row["antivol_EN"] ?></td>
									<td id="nl"><?php echo $row["antivol_NL"] ?></td>
								  </tr>
								  <tr>
									<td id="fr">Phares</td>
									<td id="en">Lights</td>
									<td id="nl">Licht</td>
									<td id="fr">Avant et arrière </td>
									<td id="en">Front and back lights </td>
									<td id="nl">Voor- en achterlicht </td>
								  </tr>

						        </tbody>
						      </table>
						    </div>
                        <?php
						}
						?>
						
                    </div>
                    
                </div>

            </div>
            <!-- END: post content -->
            
              <!-- Sidebar-->
            <div class="col-md-3 sidebar">
            
                <!--widget blog articles-->
                <div class="widget clearfix widget-blog-articles">
                    <h4 class="widget-title" id="fr">Vos informations</h4>
                    <h4 class="widget-title" id="en">Your data</h4>
                    <h4 class="widget-title" id="nl">Uw gegevens</h4>

                    <ul class="list-posts list-medium">
                         <li id="fr">Nom
                            <small><?php echo $row["NOM"] ?></small>
                        </li>
                        <li id="en">Name
                            <small><?php echo $row["NOM"] ?></small>
                        </li>
                        <li id="nl">Naam
                            <small><?php echo $row["NOM"] ?></small>
                        </li>
						
                        <li id="fr">Prénom
                            <small><?php echo $row["PRENOM"] ?></small>
                        </li>
						<li id="en">First Name
                            <small><?php echo $row["PRENOM"] ?></small>
                        </li>
						<li id="nl">Voornaam
                            <small><?php echo $row["PRENOM"] ?></small>
                        </li>
						
						
                        <li id="fr">Numéro de téléphone
                            <small class="phone"><?php echo $row["PHONE"] ?></small>
                        </li>
						<li id="en">Phone number
                            <small id="phone"><?php echo $row["PHONE"] ?></small>
                        </li>
						<li id="nl">Telefoonnummer
                            <small id="phone"><?php echo $row["PHONE"] ?></small>
                        </li>
						
                        <li id="fr">Adresse du domicile
                            <small><?php echo $row['ADRESS'].", ".$row['POSTAL_CODE'].", ".$row['CITY'] ?></small>
                        </li>

						<li id="en">Home adress
                            <small><?php echo $row['ADRESS'].", ".$row['POSTAL_CODE'].", ".$row['CITY'] ?></small>
                        </li>
						
						<li id="nl">Adress
                            <small><?php echo $row['ADRESS'].", ".$row['POSTAL_CODE'].", ".$row['CITY'] ?></small>
                        </li>
						
                        <li id="fr">Lieu de travail
                            <small><?php echo $row['WORK_ADRESS'].", ".$row['WORK_POSTAL_CODE'].", ".$row['WORK_CITY'] ?></small>
                        </li>

						<li id="en">Work place
                            <small><?php echo $row['WORK_ADRESS'].", ".$row['WORK_POSTAL_CODE'].", ".$row['WORK_CITY'] ?></small>
                        </li>
						
						<li id="nl">Werk adress
                            <small><?php echo $row['WORK_ADRESS'].", ".$row['WORK_POSTAL_CODE'].", ".$row['WORK_CITY'] ?></small>
                        </li>
						
<!--                         <li id="fr">Mot de passe
                            <small><?php echo str_repeat("*", strlen($_SESSION['UserPassword'])); ?></small>
                        </li>			
                        <li id="en">Password
                            <small><?php echo str_repeat("*", strlen($_SESSION['UserPassword'])); ?></small>
                        </li>			
                        <li id="nl">Wachtwoord
                            <small><?php echo str_repeat("*", strlen($_SESSION['UserPassword'])); ?></small>
                        </li>		 -->							
                        <li id="fr">Mot de passe
                            <small>********</small>
                        </li>			
                        <li id="en">Password
                            <small>********</small>
                        </li>			
                        <li id="nl">Wachtwoord
                            <small>********</small>
                        </li>									
						
                    </ul>
                    <a class="button small green button-3d rounded icon-left" data-target="#update" data-toggle="modal" href="#">
						<span id="fr">ACTUALISER</span>
						<span id="en">UPDATE</span>
						<span id="nl">UPDATE</span>
					</a>
                    <br>
                    <br>
                    <a href="docs/test.pdf" target="_blank" title="Pdf" id="=fr">Conditions générales</a>
                    <a href="docs/test.pdf" target="_blank" title="Pdf" id="en">Terms and Conditions</a>
                    <a href="docs/test.pdf" target="_blank" title="Pdf" id="nl">Algemene voorwaarden</a>
                    <br>
                    <br>
                    <a href="docs/test.pdf" target="_blank" title="Pdf">Bike policy</a>
                    <br>
                    <br>                    
                    <a class="button small green button-3d rounded icon-left" data-target="#tellus" data-toggle="modal" href="#">
						<span id="fr">Partagez vos impressions</span>
						<span id="en">Tell us what you feel</span>
						<span id="nl">Vertel ons wat je voelt</span>
					</a>
					 <a class="button small green button-3d rounded icon-left" data-target="#assurance" data-toggle="modal" href="#">
					 <span id="fr">Mon assurance</span>
					 </a>
                </div>
                <!--end: widget blog articles-->
            </div>
            <!-- END: Sidebar-->
			
			
        </div>
    </div>
</section>
<!-- END: SECTION -->

<div class="modal fade" id="resume" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-6">
						<h4>Resumé de votre réservation:</h4>
						<p>Prise en charge: Bloc 1 à 8h45</p>
						<p>Remise du vélo: bloc 4 à 16h30</p>
						<p>Votre vélo: 
							<div class="col-md-4">
							<img src="images/velo_mini.jpg" alt="image" />
							</div>
						</p>
					</div>
				</div>
			</div>
			<button class="btn btn-primary" type="submit" id="form-submit"><i class="fa fa-check"></i>&nbsp;Valider</button> 
		</div>
	</div>
</div>



<div class="modal fade" id="tellus" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<form id="widget-tellus-form" action="include/tellus-form.php" role="form" method="post">
                                
                                <div class="row">
                                    <div class="form-group col-sm-12">
                                        <label for="subject"  id="fr">Votre sujet</label>
										<label for="subject"  id="en">Subject</label>
										<label for="subject"  id="nl">Onderwerp</label>
                                        <input type="text" name="widget-tellus-form-subject" class="form-control required">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="message"  id="fr">Message</label>
									<label for="message"  id="en">Message</label>
									<label for="message"  id="nl">Bericht</label>
                                    <textarea type="text" name="widget-tellus-form-message" rows="5" class="form-control required"></textarea>
                                </div>
                                <input type="text" class="hidden" id="widget-contact-form-antispam" name="widget-contact-form-antispam" value="" />
                                <button  id="fr" class="button effect fill" type="submit" id="form-submit"><i class="fa fa-paper-plane"></i>&nbsp;Envoyer</button>
								<button  id="en" class="button effect fill" type="submit" id="form-submit"><i class="fa fa-paper-plane"></i>&nbsp;Send</button>
								<button  id="nl" class="button effect fill" type="submit" id="form-submit"><i class="fa fa-paper-plane"></i>&nbsp;Verzenden</button>
                            </form>
							<script type="text/javascript">
                                jQuery("#widget-tellus-form").validate({

                                    submitHandler: function(form) {

                                        jQuery(form).ajaxSubmit({
                                            success: function(text) {
                                                if (text.response == 'success') {
                                                    $.notify({
                                                        message: text.message
                                                    }, {
                                                        type: 'success'
                                                    });
													$('#tellus').modal('toggle');

                                                } else {
                                                    $.notify({
                                                        message: text.message
                                                    }, {
                                                        type: 'danger'
                                                    });
                                                }
                                            }
                                        });
                                    }
                                });

                            </script>
					</div>
				</div>
			</div>
			<div id="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div id="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div id="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="update" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<form id="widget-updateInfo" action="include/updateInfos.php" role="form" method="post">                     
                                <div class="row">
									<span class="col-md-3 fr">Informations générales</span>
									<span class="col-md-3 en">General information</span>
									<span class="col-md-3 nl">Algemene informatie</span>
                                    <div class="form-group col-sm-12">
                                        <label for="telephone"  id="fr">Numéro de téléphone</label>
										<label for="telephone"  id="en">Phone number</label>
										<label for="telephone"  id="nl">Telefoonnumber</label>
                                        <input type="text" name="widget-update-form-phone" class="form-control required" value="<?php echo $row["PHONE"] ?>">
                                    </div>
									<span class="col-md-3 fr">Domicile</span>
									<span class="col-md-3 en">Home</span>
									<span class="col-md-3 nl">Thuis</span>
										 <div class="form-group col-sm-12">
											<label for="email"  id="fr">Adresse</label>
											<label for="email"  id="en">Adress</label>
											<label for="email"  id="nl">Adres</label>
											<input type="text" name="widget-update-form-adress" class="form-control required" value="<?php echo $row['ADRESS'] ?>">
										</div>
										<div class="form-group col-sm-12">
											<label for="velo"  id="fr">Code Postal</label>
											<label for="velo"  id="en">Postal Code</label>
											<label for="velo"  id="nl">Postcode</label>
											<input type="text" name="widget-update-form-post-code" class="form-control required" value="<?php echo $row['POSTAL_CODE'] ?>">
										</div>
										<div class="form-group col-sm-12">
											<label for="chassis"  id="fr">Commune</label>
											<label for="chassis"  id="en">City</label>
											<label for="chassis"  id="nl">Gemeente</label>
											<input type="text" name="widget-update-form-city" class="form-control required" value="<?php echo $row['CITY'] ?>">
										</div>
									<span class="col-md-3 fr">Lieu de travail</span>
									<span class="col-md-3 nl">Werk</span>
									<span class="col-md-3 en">Work place</span>
										<div class="form-group col-sm-12">
											<label for="email"  id="fr">Adresse</label>
											<label for="email"  id="en">Adress</label>
											<label for="email"  id="nl">Adres</label>
											<input type="text" name="widget-update-form-work-adress" class="form-control required" value="<?php echo $row['WORK_ADRESS'] ?>">
										</div>
										<div class="form-group col-sm-12">
											<label for="velo"  id="fr">Code Postal</label>
											<label for="velo"  id="en">Postal Code</label>
											<label for="velo"  id="nl">Postcode</label>
											<input type="text" name="widget-update-form-work-post-code" class="form-control required" value="<?php echo $row['WORK_POSTAL_CODE'] ?>">
										</div>
										<div class="form-group col-sm-12">
											<label for="chassis"  id="fr">Commune</label>
											<label for="chassis"  id="en">City</label>
											<label for="chassis"  id="nl">Gemeente</label>
											<input type="text" name="widget-update-form-work-city" class="form-control required" value="<?php echo $row['WORK_CITY'] ?>">
										</div>											
										<div class="form-group col-sm-12">
											<label for="chassis"  id="fr">Mot de passe</label>
											<label for="chassis"  id="en">Password</label>
											<label for="chassis"  id="nl">Wachtwoord</label>
<!-- 											<input type="password" name="widget-update-form-password" class="form-control required" value="<?php echo $_SESSION['UserPassword'] ?>">-->
											<input type="password" name="widget-update-form-password" class="form-control required" value="********">
										</div>	
										
									<input type="text" class="hidden" id="widget-contact-form-antispam" name="widget-contact-form-antispam" value="" />
								</div>
								<button  id="fr" class="button effect fill" type="submit" id="form-submit"><i class="fa fa-paper-plane"></i>&nbsp;Envoyer</button>
								<button  id="en" class="button effect fill" type="submit" id="form-submit"><i class="fa fa-paper-plane"></i>&nbsp;Send</button>
								<button  id="nl" class="button effect fill" type="submit" id="form-submit"><i class="fa fa-paper-plane"></i>&nbsp;Verzenden</button>
                                
                            </form>
							<script type="text/javascript">
                                jQuery("#widget-updateInfo").validate({

                                    submitHandler: function(form) {

                                        jQuery(form).ajaxSubmit({
                                            success: function(text) {
                                                if (text.response == 'success') {
                                                    $.notify({
                                                        message: text.message
                                                    }, {
                                                        type: 'success'
                                                    });
													$('#update').modal('toggle');
                                                } else {
                                                    $.notify({
                                                        message: text.message
                                                    }, {
                                                        type: 'danger'
                                                    });
                                                }
                                            }
                                        });
                                    }
                                });

                            </script>
					</div>
				</div>
			</div>
			<div id="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div id="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div id="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="probleme" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<form id="widget-issue-form" action="include/issue-form.php" role="form" method="post">
                                
                                <div class="row">
                                    <div class="form-group col-sm-12">
                                        <label for="subject"  id="fr">Pièce présentant un problème</label>
										<label for="subject"  id="en">Subject</label>
										<label for="subject"  id="nl">Onderwerp</label>
                                        <select name="widget-issue-form-bikePart">
                                           <option value="...">...</option>
								           <option value="Cadre" id="fr">Cadre</option>
								           <option value="Cadre" id="en">Frame</option>
								           <option value="Cadre" id="nl">Geraamte</option>
								           <option value="Guidon" id="fr">Guidon</option>
								           <option value="Guidon" id="en">Handle</option>
								           <option value="Guidon" id="nl">Handvat</option>
								           <option value="Selle" id="fr">Selle</option>
								           <option value="Selle" id="nl">Saddle</option>
								           <option value="Selle" id="nl">Zadel</option>
								           <option value="Roue" id="fr">Roue</option>
								           <option value="Roue" id="en">Wheel</option>
								           <option value="Roue" id="nl">Wiel</option>
								           <option value="Pédalier" id="fr">Pédalier</option>
								           <option value="Pédalier" id="en">Drive</option>
								           <option value="Pédalier" id="nl">Aandrijving</option>
								           <option value="Freins" id="fr">Freins</option>
								           <option value="Freins" id="en">Brake</option>
								           <option value="Freins" id="nl">Handrem</option>
								           <option value="Chaine" id="fr">Chaine</option>
								           <option value="Chaine" id="en">Chain</option>
								           <option value="Chaine" id="nl">Ketting</option>
								           <option value="Lampe" id="fr">Phare</option>
								           <option value="Lampe" id="fr">Lights</option>
								           <option value="Lampe" id="fr">Lamp</option>
								           <option value="Autre" id="fr">Autre</option>
								           <option value="Autre" id="en">Other</option>
								           <option value="Autre" id="nl">Ander</option>
								       </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="message"  id="fr">Décrivez le problème</label>
									<label for="message"  id="en">Message</label>
									<label for="message"  id="nl">Bericht</label>
                                    <textarea type="text" name="widget-issue-form-message" rows="5" class="form-control required"></textarea>
                                </div>
                                <input type="text" class="hidden" id="widget-contact-form-antispam" name="widget-contact-form-antispam" value="" />
                                <button  id="fr" class="button effect fill" type="submit" id="form-submit"><i class="fa fa-paper-plane"></i>&nbsp;Envoyer</button>
								<button  id="en" class="button effect fill" type="submit" id="form-submit"><i class="fa fa-paper-plane"></i>&nbsp;Send</button>
								<button  id="nl" class="button effect fill" type="submit" id="form-submit"><i class="fa fa-paper-plane"></i>&nbsp;Verzenden</button>
                            </form>
                            <script type="text/javascript">
                                jQuery("#widget-issue-form").validate({

                                    submitHandler: function(form) {

                                        jQuery(form).ajaxSubmit({
                                            success: function(text) {
                                                if (text.response == 'success') {
                                                    $.notify({
                                                        message: text.message
                                                    }, {
                                                        type: 'success'
                                                    });
													$('#probleme').modal('toggle');

                                                } else {
                                                    $.notify({
                                                        message: text.message
                                                    }, {
                                                        type: 'danger'
                                                    });
                                                }
                                            }
                                        });
                                    }
                                });

                            </script>
					</div>
				</div>
			</div>
			<div id="fr" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
			<div id="en" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
			</div>
			<div id="nl" class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Fermer</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="assurance" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-6">
						<h4>À propos de mon assurance</h4>
						<p>Résumé de ce que contient l'assurance</p>
					</div>
				</div>
			</div>
			<button class="btn btn-primary" type="submit" id="form-submit"><i class="fa fa-check"></i>&nbsp;Valider</button> 
		</div>
	</div>
</div>
		


		<!-- FOOTER -->
		<footer class="background-dark text-grey" id="footer">
    <div class="footer-content">
        <div class="container">
            <div class="row text-center">
                <div class="copyright-text text-center"> &copy; 2017 KAMEO Bikes
                </div>
                <div class="social-icons center">
							<ul>
								<li class="social-facebook"><a href="https://www.facebook.com/Kameo-Bikes-123406464990910/" target="_blank"><i class="fa fa-facebook"></i></a></li>
								
								<li class="social-instagram"><a href="https://www.instagram.com/kameobikes/" target="_blank"><i class="fa fa-instagram"></i></a></li>
							</ul>
						</div>
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
