<?php 
include 'include/header.php';
// checkAccess();
$user=$_SESSION['userID'];
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
                        
                        <br>
                        <?php 
						if (ctype_alpha(substr($row["FRAME_NUMBER"],0,3))){
							$frame_number=$row["FRAME_NUMBER"];
							$sql = "select bb.TYPE, cc.MODEL_FR from customer_referential aa, customer_bikes bb, bike_models cc where aa.EMAIL='$user' and bb.FRAME_NUMBER like '$frame_number%' and bb.TYPE=cc.ID";
							echo $sql;
							echo "<br />";
							echo "<br />";

							$result = mysqli_query($conn, $sql);
							echo "<form>";							
							while($resultat = mysqli_fetch_assoc($result)){
								echo "<input type=\"checkbox\" name \"bike".$resultat['TYPE']."\" value=\"".$resultat['TYPE']."\">";
								echo "<label for \"bike".$resultat['TYPE'].">".$resultat['MODEL_FR']."</label></br/>";
								}
							echo "</from>";
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
						                            <?php
						}
						?>
						
<div class="col-md-12">  

	<div data-example-id="contextual-table" class="bs-example">
      <table class="table table-condensed">
      <h4>Vos anciennes réservations:</h4>
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
            <td>Bloc 1 à 1Oh30</td>
            <td>Bloc 3 à 12h45</td>
            <td>Romeo ABC01</td>
          </tr>
          <tr>
            <td>3 avril 2018</td>
            <td>Bloc 3 à 16h30</td>
            <td>Bloc 1 à 16h15</td>
            <td>Romeo ABC01</td>
          </tr>
        </tbody>
      </table>
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
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>12 avril 2018</td>
            <td>Bloc 1 à 1Oh30</td>
            <td>Bloc 3 à 12h45</td>
            <td>Romeo ABC01</td>
          </tr>
          <tr>
            <td>12 avril 2018</td>
            <td>Bloc 3 à 16h30</td>
            <td>Bloc 1 à 16h15</td>
            <td>Romeo ABC01</td>
          </tr>
        </tbody>
      </table>
    </div>
    
    <div class="seperator"></div>
                    
  <form id="widget-contact-form" action="include/reservation-form.php" role="form" method="post">                           
   <div class="row">                            	
    <div class="form-group col-sm-6">                                       
  		 <label for="batiments_prendre">Où voulez-vous prendre le vélo?</label><br />									     
	     <select name="batiments_prendre" id="batiments_prendre">
	     	<option value="bloc1">Bloc 1</option>									       
	      	<option value="bloc2">Bloc 2</option>									        
	      	<option value="bloc3">Bloc 3</option>								
	      	<option value="bloc4">Bloc 4</option>									    
	      </select>                                   
	</div>                                                                         
	<div class="form-group col-sm-6">                                      
		 <label for="batiments_rendre">Où voulez-vous rendre le vélo?</label><br />									      
		 <select name="batiments_rendre" id="batiments_rendre">									           
		 		<option value="bloc1">Bloc 1</option>									           
		 		<option value="bloc2">Bloc 2</option>									           
		 		<option value="bloc3">Bloc 3</option>									           
		 		<option value="bloc4">Bloc 4</option>									      
		  </select>                                   
	 </div>  
	 <div class="form-group col-sm-6">                                       
  		 <label for="heure_prendre">À quelle heure voulez-vous prendre le vélo?</label><br />									     
	     <select name="heure_prendre" id="heure_prendre">
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
		 <label for="heure_rendre">À quelle heure voulez-vous rendre le vélo?</label><br />									      
		 <select name="heure_rendre" id="heure_rendre">									           
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
	 
	 <div class="form-group col-sm-12">                                      
		 <label for="date">À quelle date voulez-vous prendre le vélo?</label><br />	
		  <div class="form-group col-sm-4"> 								      
			 <select name="date_jour" id="date_jour">									           
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
		   <div class="form-group col-sm-4"> 
			  <select name="date_jour" id="date_jour">									           
			 	<option value="janvier">janvier</option>									       
		      	<option value="février">février</option>									        
		      	<option value="mars">mars</option>								
		      	<option value="avril">avril</option>
		      	<option value="mai">mai</option>									       
		      	<option value="juin">juin</option>									        
		      	<option value="juillet7">juillet</option>								
		      	<option value="août">août</option>
		      	<option value="septembre">septembre</option>	
		      	<option value="novembre">novembre</option>
		      	<option value="décembre">décembre</option>								      
			  </select>    
		  </div>                               
	 </div> 
	 
	 <button class="btn btn-primary" type="submit" id="form-submit"><i class="fa fa-search"></i>&nbsp;Chercher</button> 
	  
	  
	  <div class="seperator"></div>
	  
	 <div class="col-md-10"> 
	  <div class="col-md-4">
        <div class="featured-box">
          <div class="effect social-links"> <img src="images/velo_big.jpg" alt="image" />
            <div class="image-box-content">
              <p> <a href="images/velo_big.jpg" data-lightbox-type="image" title=""><i class="fa fa-expand"></i></a> </p>
            </div>
          </div>
        </div>
      </div> 
      <div class="col-md-5">
          	<ul>
				<li>Type: Romeo</li>
				<li>Poids: 14,7 Kg</li>
				<li>Autonomie: <i class="fa fa-circle-o"></i><i class="fa fa-circle-o"></i><i class="fa fa-circle"></i><i class="fa fa-circle"></i><i class="fa fa-circle"></i> </li> 
				<li>Couleur: Blanc</li>
			</ul>
	 </div>
	 <div class="col-md-2">
			<a class="button large green button-3d rounded icon-left" id="fr" data-target="#resume" data-toggle="modal" href="#"><span>Réserver</span></a>
	 </div>
    </div>
    
    <div class="seperator"></div>
    
    
    <div class="col-md-10"> 
	  <div class="col-md-4">
        <div class="featured-box">
          <div class="effect social-links"> <img src="images/velo_big.jpg" alt="image" />
            <div class="image-box-content">
              <p> <a href="images/velo_big.jpg" data-lightbox-type="image" title=""><i class="fa fa-expand"></i></a> </p>
            </div>
          </div>
        </div>
      </div> 
      <div class="col-md-5">
          	<ul>
				<li>Type: Romeo</li>
				<li>Poids: 14,7 Kg</li>
				<li>Autonomie: <i class="fa fa-circle-o"></i><i class="fa fa-circle-o"></i><i class="fa fa-circle"></i><i class="fa fa-circle"></i><i class="fa fa-circle"></i> </li> 
				<li>Couleur: Blanc</li>
			</ul>
	 </div>
	 <div class="col-md-2">
			<a class="button large green button-3d rounded icon-left" id="fr" data-target="#resume" data-toggle="modal" href="#"><span>Réserver</span></a>
	 </div>
    </div>
    
    <div class="seperator"></div>
    
    <div class="col-md-10"> 
	  <div class="col-md-4">
        <div class="featured-box">
          <div class="effect social-links"> <img src="images/velo_big.jpg" alt="image" />
            <div class="image-box-content">
              <p> <a href="images/velo_big.jpg" data-lightbox-type="image" title=""><i class="fa fa-expand"></i></a> </p>
            </div>
          </div>
        </div>
      </div> 
      <div class="col-md-5">
          	<ul>
				<li>Type: Romeo</li>
				<li>Poids: 14,7 Kg</li>
				<li>Autonomie: <i class="fa fa-circle-o"></i><i class="fa fa-circle-o"></i><i class="fa fa-circle"></i><i class="fa fa-circle"></i><i class="fa fa-circle"></i> </li> 
				<li>Couleur: Blanc</li>
			</ul>
	 </div>
	 <div class="col-md-2">
			<a class="button large green button-3d rounded icon-left" id="fr" data-target="#resume" data-toggle="modal" href="#"><span>Réserver</span></a>
	 </div>
    </div>
    
    <div class="seperator"></div>
                    
	                 
	 </div>	
	 </div>
						
     </div>
                    
     </div>

     </div>
     
<div class="modal fade" id="resume" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
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
					<br>
					<iframe src="https://calendar.google.com/calendar/embed?showTitle=0&amp;showNav=0&amp;showPrint=0&amp;showTabs=0&amp;showCalendars=0&amp;showTz=0&amp;height=200&amp;wkst=2&amp;hl=fr&amp;bgcolor=%23FFFFFF&amp;src=thibaut.mativa%40gmail.com&amp;color=%232952A3&amp;ctz=Europe%2FBrussels" style="border-width:0" width="200" height="200" frameborder="0" scrolling="no"></iframe>
                </div>
                <!--end: widget blog articles-->
            </div>
            <!-- END: Sidebar-->
			
			
        </div>
    </div>
</section>
<!-- END: SECTION -->




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
