<!DOCTYPE html>
<html lang="fr">
<?php
ob_start();
if(!isset($_SESSION))
	session_start();

$token=isset($_SESSION['userID']) ? $_SESSION['userID'] : NULL; //@TODO: replaced by a token to check if connected
$user_ID=isset($_SESSION['ID']) ? $_SESSION['ID'] : NULL; //Used by: notifications.js
$feedback=isset($_GET['feedback']) ? $_GET['feedback'] : NULL; //Used by: login_form.js
$langue=isset($_SESSION['langue']) ? $_SESSION['langue'] : 'fr';

require_once 'include/i18n/i18n.php';
include 'apis/Kameo/connexion.php';

$i18n = new i18n('lang/lang_{LANGUAGE}.ini'); //french by defaut
$i18n->init();

include 'include/head.php';
echo '<body class="wide">
	<!-- WRAPPER -->
	<div class="wrapper">';
		include 'include/topbar.php';
		include 'include/header.php';

echo '<style media="screen">
    .tableFixed {
      table-layout: fixed;
    }
    .separator-small{
      padding-top:20px;
      width:60%;
      opacity: 0.5;
    }
</style>
<script type="text/javascript">
  const feedback = "'.$feedback.'";
</script>';

if($token==NULL){ //Not connected
  include 'include/vues/login_form/main.php'; //@TODO: REFACTOR
}else{ //Connected
  //@TODO: Replace email chech with authentication token
  $sql = "SELECT NOM, PRENOM, PHONE, ADRESS, CITY, POSTAL_CODE, WORK_ADRESS, WORK_POSTAL_CODE, WORK_CITY, EMAIL from customer_referential WHERE EMAIL='$token' LIMIT 1";
  if ($conn->query($sql) === FALSE)
    die;
  $user_data = mysqli_fetch_assoc(mysqli_query($conn, $sql));

  echo '
  <script type="text/javascript">
    const user_ID = "'.$user_ID.'";
    const user_data = JSON.parse(\''.json_encode($user_data).'\');
  </script>
  <script type="text/javascript" src="js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
  <!-- <script type="text/javascript" src="./js/locales/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script> -->
  <script type="text/javascript" src="js/addons/chart.js/dist/Chart.min.js" charset="UTF-8"></script>
  <script src="js/addons/openlayers/OpenLayers.js"></script>
  <script type="text/javascript" src="js/global_functions.js"></script>
  <script type="text/javascript" src="js/addons/datatables/datatables.min.js"></script>
  <script type="text/javascript" src="js/load_client_conditions.js"></script>
  <script type="text/javascript" src="js/search_module.js"></script>
  <script type="text/javascript" src="js/notifications.js"></script>
  <script type="text/javascript" src="js/addresses.js"></script>
  <script type="text/javascript" src="js/weather.js"></script>
  <script type="text/javascript" src="js/cafetaria.js"></script>
  ';

  $sql="select * from customer_referential aa, customer_bike_access bb where aa.EMAIL='".$user_data['EMAIL']."' and aa.EMAIL=bb.EMAIL and bb.TYPE='personnel' LIMIT 1";
  $result=mysqli_query($conn, $sql);
  $company=($result->num_rows==0); //Used by: mykameo/main.php
  /** TEST VARIABLE @TODO: REMOVE **/
  //$company = '';
?>
  <section class="content">
  <div class="container">
    <div class="row">
      <!-- MAIN CONTENT -->
      <div class="post-content float-right col-md-9">
        <div class="post-item">
          <div class="post-content-details">
            <div class="heading heading text-left m-b-20">
              <div class="row" style="position: relative;">
                <h2 class="col-sm-8">MY KAMEO</h2>
					      <?php include 'include/vues/mykameo/notifications.html'; ?>
              </div>
            </div>
            <br/>
            <div class="col-md-12">
              <span id="assistanceSpan"></span>
              <?php if(!$company)
				  /** CALENDAR **/
				  include 'include/vues/mykameo/calendar.html';
				  include 'include/vues/mykameo/widgets/calendar/main.php';?>
            </div>
            <br/>
            <?php if($company): ?>
              <div class="col-md-12">
                <div id="tabs-05c" class="tabs color tabs radius">
                  <ul id="mainTab" class="tabs-navigation">
                  	<li class="hidden orderBike"><a href="#orderBike" class="orderBike"><i class="fa fa-user"></i><?=L::tabs_order_title;?></a></li>
                  	<li class="reserver active"><a href="#reserver"><i class="fa fa-calendar-plus-o"></i><?=L::tabs_book_title;?></a> </li>
                  	<li><a href="#reservations" class="reservations"><i class="fa fa-check-square-o"></i><?=L::tabs_reservations_title;?></a> </li>
                  	<li class="hidden fleetmanager"><a href="#fleetmanager" class="fleetmanager"><i class="fa fa-user"></i><?=L::tabs_fleet_title;?></a> </li>
                  	<?php /**@TODO: <li><a href="#routes" class="routes"><i class="fa fa-road"></i>Itinéraires</a></li>-->**/ ?>
                  </ul>
                  <div class="tabs-content">
                    <?php
                      include 'include/vues/mykameo/tabs/order/order_tab.html';	//TAB 1 @TODO: REFACTOR
                      /** @TODO: REPARE THE FACT THAT THE BOOK TAB SCRIPT DISPLAYS THE CONTACT ASSISTANCE BUTTON BECAUSE IT'S NOT USED WHEN PERSONNAL BIKE ONLY **/
          						include 'include/vues/mykameo/tabs/book/main.php'; //TAB 2 @TODO: REFACTOR
                      include 'include/vues/mykameo/tabs/reservations/main.php';	//TAB 3 @TODO: REFACTOR
          						include 'include/vues/mykameo/tabs/fleet_manager/main.php';	//TAB 4 @TODO: REFACTOR ?>
                  </div>
                  <?php include 'include/vues/mykameo/tabs/order/widgets/order.html'; ?>
                </div>
                <?php include 'include/vues/mykameo/widgets/future_booking/future_booking.html'; ?>
                <div id="velos" style="display: none;"></div>
              </div>
            <?php else: ?>
              <?php
                /** @TODO: REPLACE BY API CALL **/
                $sql = "select aa.EMAIL, aa.FRAME_NUMBER, aa.NOM, aa.PRENOM, aa.PHONE, aa.ADRESS, aa.POSTAL_CODE, aa.CITY, aa.WORK_ADRESS, aa.WORK_POSTAL_CODE, aa.WORK_CITY, bb.CONTRACT_START, bb.CONTRACT_END, dd.BRAND, dd.MODEL, dd.FRAME_TYPE, cc.BIKE_NUMBER from customer_referential aa, customer_bikes bb, customer_bike_access cc, bike_catalog dd where aa.EMAIL='".$user_data['EMAIL']."' and aa.EMAIL=cc.EMAIL and cc.BIKE_NUMBER=bb.FRAME_NUMBER and bb.TYPE=dd.ID";
                if ($conn->query($sql) === FALSE)
                    die;
                $row = mysqli_fetch_assoc(mysqli_query($conn, $sql));
                $contractNumber='KAMEO BIKES'; //@TODO: Remove var
                $contractStart=$row['CONTRACT_START'];
                $contractEnd=$row['CONTRACT_END'];
              ?>
              <?php /** TRAVEL INFORMATIONS @TODO: NEED REFACTORISATION TO REMOVE BAD DEFINED RESPONSIVITY AND LOAD DATA DYNAMICALLY **/ ?>
              <div id="travel_information_2" class="hidden">
                <!-- Pour un écran large -->
                <div class="visible-lg">
                  <div class="col-lg-12 backgroundgreen down">
                    <p class="text-white down">
                      <span class="fr-inline text-white">Votre trajet domicile - travail le </span>
                      <span class="en-inline text-white">Your trip home - work on </span>
                      <span class="nl-inline text-white">Uw reis naar huis - werk op </span>
                      <span class="text-white" id="meteoDate1"></span>
                      <span class="fr-inline text-white"> à </span>
                      <span class="en-inline text-white"> at </span>
                      <span class="nl-inline text-white"> om </span>
                      <span class="text-white" id="meteoHour1"></span>
                    </p>
                  </div>
                </div>
                <div class="visible-lg">
                  <div class="col-lg-12 backgroundgreen" style="margin-bottom: 20px; margin-top: 0px;">

                    <div class="col-lg-3">
                      <img id="logo_meteo1" alt="image" class="centerimg" />
                    </div>

                    <div class="col-lg-3">
                      <ul>
                        <li id="temperature_widget1" class="temperature text-center"></li>
                        <li id="precipitation_widget1" class="humidite text-center"></li>
                        <li id="wind_widget1" class="vent text-center"></li>
                      </ul>
                    </div>

                    <div class="col-lg-3">
                      <ul class="bords">
                        <li id="walking_duration_widget1" class="marche grid-col-demo text-center"></li>
                        <li id="car_duration_widget1" class="voiture grid-col-demo text-center"></li>
                        <li id="bike_duration_widget1" class="bike grid-col-demo text-center"></li>
                      </ul>
                    </div>

                    <div class="col-lg-3">
                      <img id="score_kameo1" alt="image" class="centerimg" />
                    </div>
                  </div>
                </div>

                <!-- Pour un écran médium -->
                <div class="visible-md">
                  <div class="col-md-12 backgroundgreen">
                    <p class="text-white down">
                      <span class="fr-inline text-white">Votre trajet domicile - travail le </span>
                      <span class="en-inline text-white">Your trip home - work on </span>
                      <span class="nl-inline text-white">Uw reis naar huis - werk op </span>
                      <span class="text-white" id="meteoDate2"></span>
                      <span class="fr-inline text-white"> à </span>
                      <span class="en-inline text-white"> at </span>
                      <span class="nl-inline text-white"> om </span>
                      <span class="text-white" id="meteoHour2"></span>
                    </p>
                  </div>
                </div>

                <div class="visible-md">
                  <div class="col-md-12 backgroundgreen" style="margin-bottom: 20px; margin-top: 0px;">

                    <div class="col-md-3">
                      <img id="logo_meteo2" alt="image" class="centerimg" />
                    </div>
                    <div class="col-md-3">
                      <ul>
                        <li id="temperature_widget2" class="temperature text-center"></li>
                        <li id="precipitation_widget2" class="humidite text-center"></li>
                        <li id="wind_widget2" class="vent text-center"></li>
                      </ul>
                    </div>
                    <div class="col-md-3">
                      <ul class="bords">
                        <li id="walking_duration_widget2" class="marche grid-col-demo text-center"></li>
                        <li id="car_duration_widget2" class="voiture grid-col-demo text-center"></li>
                        <li id="bike_duration_widget2" class="bike grid-col-demo text-center"></li>
                      </ul>
                    </div>
                    <div class="col-md-3">
                      <img id="score_kameo2" alt="image" class="centerimg" data-toggle="tooltip" data-placement="top" title="L'indice mykameo est une combinaison de la météo et du temps de trajet en vélo par rapport à la voiture"/>
                    </div>
                  </div>
                </div>

                <!-- Pour une tablette -->
                <div class="visible-sm">
                  <div class="col-sm-12 backgroundgreen">
                    <p class="text-white down">
                      <span class="fr-inline text-white">Votre trajet domicile - travail le </span>
                      <span class="en-inline text-white">Your trip home - work on </span>
                      <span class="nl-inline text-white">Uw reis naar huis - werk op </span>
                      <span class="text-white" id="meteoDate3"></span>
                      <span class="fr-inline text-white"> à </span>
                      <span class="en-inline text-white"> at </span>
                      <span class="nl-inline text-white"> om </span>
                      <span class="text-white" id="meteoHour3"></span>
                    </p>
                  </div>
                </div>
                <div class="visible-sm">
                  <div class="col-sm-12 backgroundgreen" style="margin-bottom: 20px; margin-top: 0px;">

                    <div class="col-sm-12">
                      <img id="logo_meteo3" alt="image" class="centerimg" />
                    </div>
                    <div class="seperator"></div>
                    <div class="col-sm-6">
                      <ul>
                        <li id="temperature_widget3" class="temperature2 text-center"></li>
                        <li id="precipitation_widget3" class="humidite2 text-center"></li>
                        <li id="wind_widget3" class="vent2 text-center"></li>
                      </ul>
                    </div>
                    <div class="col-sm-6">
                      <ul class="bords">
                        <li id="walking_duration_widget3" class="marche2 grid-col-demo text-center"></li>
                        <li id="car_duration_widget3" class="voiture2 grid-col-demo text-center"></li>
                        <li id="bike_duration_widget3" class="bike2 grid-col-demo text-center"></li>
                      </ul>
                    </div>
                    <div class="seperator"></div>
                    <div class="col-sm-12">
                      <img id="score_kameo3" alt="image" class="centerimg" />
                    </div>
                  </div>
                </div>

                <!-- Pour un smartphone -->
                <div class="visible-xs">
                  <div class="col-xs-12 backgroundgreen">
                    <p class="text-white down">
                      <span class="fr-inline text-white">Votre trajet domicile - travail le </span>
                      <span class="en-inline text-white">Your trip home - work on </span>
                      <span class="nl-inline text-white">Uw reis naar huis - werk op </span>
                      <span class="text-white" id="meteoDate4"></span>
                      <span class="fr-inline text-white"> à </span>
                      <span class="en-inline text-white"> at </span>
                      <span class="nl-inline text-white"> om </span>
                      <span class="text-white" id="meteoHour4"></span>
                    </p>
                  </div>
                </div>
                <div class="visible-xs">
                  <div class="col-xs-12 backgroundgreen" style="margin-bottom: 20px; margin-top: 0px;">
                    <div class="col-xs-12">
                      <img id="logo_meteo4" alt="image" class="centerimg" />
                    </div>
                    <div class="seperator"></div>
                    <div class="col-xs-12">
                      <ul>
                        <li id="temperature_widget4" class="temperature3 text-center"></li>
                        <li id="precipitation_widget4" class="humidite3 text-center"></li>
                        <li id="wind_widget4" class="vent3 text-center"></li>
                      </ul>
                    </div>
                    <div class="seperator"></div>
                    <div class="col-xs-12">
                      <ul class="bords">
                        <li id="walking_duration_widget4" class="marche3 grid-col-demo text-center"></li>
                        <li id="car_duration_widget4" class="voiture3 grid-col-demo text-center"></li>
                        <li id="bike_duration_widget4" class="bike3 grid-col-demo text-center"></li>
                      </ul>
                    </div>
                    <div class="seperator"></div>
                    <div class="col-xs-12">
                      <img id="score_kameo4" alt="image" class="centerimg" />
                    </div>
                  </div>
                </div>
              </div>
              <div id="travel_information_2_error" class="hidden">
                <!-- Pour un écran large -->
                <div class="visible-lg">
                  <div class="col-lg-12 backgroundgreen down">
                    <p class="text-white down">
                      <span class="fr-inline text-white">Votre trajet domicile - travail à </span>
                      <span class="en-inline text-white">Your trip home - work at </span>
                      <span class="nl-inline text-white">Uw reis naar huis - werk bij </span>
                      <span class="text-white" id="meteoHour1"></span>
                    </p>
                  </div>
                </div>

                <div class="visible-lg">
                  <div class="col-lg-12 backgroundgreen" style="margin-bottom: 20px; margin-top: 0px;">

                    <h2 class="text-white text-center">ERROR</h2>
                    <p class="text-white text-center fr">Erreur dans le chargement de vos données. Veuillez vérifier votre adresse de domicile et lieu de travail</p>
                    <p class="text-white text-center en">Error when loading travel information. Please check your work place and house address information.</p>
                    <p class="text-white text-center nl">Fout bij het laden van reisinformatie. Controleer uw werkplaats en huisadresgegevens.</p>

                  </div>
                </div>

                <!-- Pour un écran médium -->
                <div class="visible-md">
                  <div class="col-md-12 backgroundgreen">
                    <p class="text-white down">
                      <span class="fr-inline">Votre trajet domicile - travail à </span>
                      <span class="en-inline">Your trip home - work at </span>
                      <span class="nl-inline">Uw reis naar huis - werk bij </span>
                      <span id="meteoHour2"></span>
                    </p>
                  </div>
                </div>
                <div class="visible-md">
                  <div class="col-md-12 backgroundgreen" style="margin-bottom: 20px; margin-top: 0px;">
                    <h2 class="text-white text-center">ERROR</h2>
                    <p class="text-white text-center fr">Erreur dans le chargement de vos données. Veuillez vérifier votre adresse de domicile et lieu de travail</p>
                    <p class="text-white text-center en">Error when loading travel information. Please check your work place and house address information.</p>
                    <p class="text-white text-center nl">Fout bij het laden van reisinformatie. Controleer uw werkplaats en huisadresgegevens.</p>

                  </div>
                </div>

                <!-- Pour une tablette -->
                <div class="visible-sm">
                  <div class="col-sm-12 backgroundgreen">
                    <p class="text-white down">
                      <span class="fr-inline text-white">Votre trajet domicile - travail à </span>
                      <span class="en-inline text-white">Your trip home - work at </span>
                      <span class="nl-inline text-white">Uw reis naar huis - werk bij </span>
                      <span class="text-white" id="meteoHour3"></span>
                    </p>
                  </div>
                </div>
                <div class="visible-sm">
                  <div class="col-sm-12 backgroundgreen" style="margin-bottom: 20px; margin-top: 0px;">
                    <h2 class="text-white text-center">ERROR</h2>
                    <p class="text-white text-center fr">Erreur dans le chargement de vos données. Veuillez vérifier votre adresse de domicile et lieu de travail</p>
                    <p class="text-white text-center en">Error when loading travel information. Please check your work place and house address information.</p>
                    <p class="text-white text-center nl">Fout bij het laden van reisinformatie. Controleer uw werkplaats en huisadresgegevens.</p>
                  </div>
                </div>

                <!-- Pour un smartphone -->
                <div class="visible-xs">
                  <div class="col-xs-12 backgroundgreen">
                    <p class="text-white down">
                      <span class="fr-inline text-white">Votre trajet domicile - travail à </span>
                      <span class="en-inline text-white">Your trip home - work at </span>
                      <span class="nl-inline text-white">Uw reis naar huis - werk bij </span>
                      <span class="text-white" id="meteoHour4"></span>
                    </p>
                  </div>
                </div>
                <div class="visible-xs">
                  <div class="col-xs-12 backgroundgreen" style="margin-bottom: 20px; margin-top: 0px;">
                    <h2 class="text-white text-center">ERROR</h2>
                    <p class="text-white text-center fr">Erreur dans le chargement de vos données. Veuillez vérifier votre adresse de domicile et lieu de travail</p>
                    <p class="text-white text-center en">Error when loading travel information. Please check your work place and house address information.</p>
                    <p class="text-white text-center nl">Fout bij het laden van reisinformatie. Controleer uw werkplaats en huisadresgegevens.</p>
                  </div>
                </div>
              </div>
              <div id="travel_information_2_loading">
                <!-- Pour un écran large -->
                <div class="visible-lg">
                  <div class="col-lg-12 backgroundgreen down">
                    <p class="text-white down">
                      <span class="fr-inline text-white">Votre trajet domicile - travail à </span>
                      <span class="en-inline text-white">Your trip home - work at </span>
                      <span class="nl-inline text-white">Uw reis naar huis - werk bij </span>
                      <span class="text-white" id="meteoHour1"></span>
                    </p>
                  </div>
                </div>
                <div class="visible-lg">
                  <div class="col-lg-12 backgroundgreen" style="margin-bottom: 20px; margin-top: 0px;">
                    <h2 class="text-white text-center">LOADING</h2>
                    <p class="text-white text-center fr">Chargement des informations entre votre domicile et votre lieu de travail</p>
                    <p class="text-white text-center en">Loading of travel time between your house and work place</p>
                    <p class="text-white text-center nl">Laden van reistijd tussen uw huis en uw werkplek</p>
                  </div>
                </div>

                <!-- Pour un écran médium -->
                <div class="visible-md">
                  <div class="col-md-12 backgroundgreen">
                    <p class="text-white down">
                      <span class="fr-inline">Votre trajet domicile - travail à </span>
                      <span class="en-inline">Your trip home - work at </span>
                      <span class="nl-inline">Uw reis naar huis - werk bij </span>
                      <span id="meteoHour2"></span>
                    </p>
                  </div>
                </div>
                <div class="visible-md">
                  <div class="col-md-12 backgroundgreen" style="margin-bottom: 20px; margin-top: 0px;">
                    <h2 class="text-white text-center">LOADING</h2>
                    <p class="text-white text-center fr">Chargement des informations entre votre domicile et votre lieu de travail</p>
                    <p class="text-white text-center en">Loading of travel time between your house and work place</p>
                    <p class="text-white text-center nl">Laden van reistijd tussen uw huis en uw werkplek</p>
                  </div>
                </div>
                <!-- Pour une tablette -->
                <div class="visible-sm">
                  <div class="col-sm-12 backgroundgreen">
                    <p class="text-white down">
                      <span class="fr-inline text-white">Votre trajet domicile - travail à </span>
                      <span class="en-inline text-white">Your trip home - work at </span>
                      <span class="nl-inline text-white">Uw reis naar huis - werk bij </span>
                      <span class="text-white" id="meteoHour3"></span>
                    </p>
                  </div>
                </div>
                <div class="visible-sm">
                  <div class="col-sm-12 backgroundgreen" style="margin-bottom: 20px; margin-top: 0px;">
                    <h2 class="text-white text-center">LOADING</h2>
                    <p class="text-white text-center fr">Chargement des informations entre votre domicile et votre lieu de travail</p>
                    <p class="text-white text-center en">Loading of travel time between your house and work place</p>
                    <p class="text-white text-center nl">Laden van reistijd tussen uw huis en uw werkplek</p>
                  </div>
                </div>
                <!-- Pour un smartphone -->
                <div class="visible-xs">
                  <div class="col-xs-12 backgroundgreen">
                    <p class="text-white down">
                      <span class="fr-inline text-white">Votre trajet domicile - travail à </span>
                      <span class="en-inline text-white">Your trip home - work at </span>
                      <span class="nl-inline text-white">Uw reis naar huis - werk bij </span>
                      <span class="text-white" id="meteoHour4"></span>
                    </p>
                  </div>
                </div>
                <div class="visible-xs">
                  <div class="col-xs-12 backgroundgreen" style="margin-bottom: 20px; margin-top: 0px;">
                    <h2 class="text-white text-center">LOADING</h2>
                    <p class="text-white text-center fr">Chargement des informations entre votre domicile et votre lieu de travail</p>
                    <p class="text-white text-center en">Loading of travel time between your house and work place</p>
                    <p class="text-white text-center nl">Laden van reistijd tussen uw huis en uw werkplek</p>
                  </div>
                </div>
              </div>

              <img src="images_bikes/<?php echo $row['BIKE_NUMBER']; ?>.jpg" class="img-responsive img-rounded center" alt="Image of Bike">
              <br/>
              <!-- BIKE DESCRIPTION -->
			        <div class="table-responsive">
                <table class="table table-striped">
                  <caption> <?=L::bike_description_title;?> </caption>
                  <tbody>
                    <tr>
                      <td><?=L::bike_description_model;?></td>
                      <td><?php echo $row["BRAND"]." ".$row["MODEL"] ?></td>
                    </tr>
                    <tr>
                      <td><?=L::bike_description_contract_start;?></td>
                      <td><?php echo $row["CONTRACT_START"]; ?></td>
                    </tr>
                    <tr>
                      <td><?=L::bike_description_contract_end;?></td>
                      <td><?php echo $row["CONTRACT_END"]; ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- METEO -->
              <script type="text/javascript" src="include/vues/mykameo/weather.js"></script>
              <?php endif;?>
          </div>
        </div>
      </div>
      <!-- END: MAIN CONTENT -->
      <!-- SIDEBAR -->
      <div class="col-md-3 sidebar">
        <div class="widget clearfix widget-blog-articles">
          <h4 class="widget-title"><?=L::sidebar_title;?></h4>
          <ul class="list-posts list-medium">
            <li><?=L::sidebar_last_name;?>
              <small><?= $user_data["NOM"] ?></small>
            </li>
            <?php if($user_data["PRENOM"]): ?>
              <li><?=L::sidebar_first_name;?>
                <small><?= $user_data["PRENOM"] ?></small>
              </li>
            <?php endif;?>
            <?php if($user_data["PHONE"]): ?>
              <li><?=L::sidebar_phone;?>
                <small class="phone"><?= $user_data["PHONE"] ?></small>
              </li>
            <?php endif;?>
            <?php if($user_data["ADRESS"]): ?>
              <li><?=L::sidebar_home_address;?>
                <small><?= $user_data['ADRESS'].", ".$user_data['POSTAL_CODE'].", ".$user_data['CITY'] ?></small>
              </li>
            <?php endif;?>
            <?php if($user_data["WORK_ADRESS"]): ?>
              <li><?=L::sidebar_work_address;?>
                <small><?= $user_data['WORK_ADRESS'].", ".$user_data['WORK_POSTAL_CODE'].", ".$user_data['WORK_CITY'] ?></small>
              </li>
            <?php endif;?>
            <li class="fr"><?=L::sidebar_password;?>
              <small>********</small>
            </li>
          </ul>
          <?php /**@TODO: REMOVE ONCLICK REFACTOR **/ ?>
          <a class="button small green button-3d rounded icon-left" data-target="#update" data-toggle="modal" href="#" onclick="initializeUpdate()">
            <span><?=L::sidebar_refresh_button;?></span>
          </a>
          <br>
          <?php if(!$company): ?>
            <br><br>
            <h4 class="widget-title">
              <span><?=L::sidebar_statistics_title;?> </span>
              <span id="year"></span>
            </h4>
            <ul class="list-posts list-medium">
              <li> <span> <?=L::sidebar_trips_number;?></span>
                <small id="count_trips"></small>
              </li>
              <li> <span> <?=L::sidebar_km_number;?></span>
                <small id="total_trips"></small>
              </li>
            </ul>
			      <!-- Statistics calculation -->
            <script type="text/javascript" src="include/vues/mykameo/statistics.js"></script>
          <?php endif;?>
          <br>
          <a href="docs/cgvfr.pdf" target="_blank" title="Pdf"><?=L::sidebar_terms;?></a><br><br>
          <a href="docs/KAMEO-BikePolicy.pdf" target="_blank" title="Pdf"><?=L::sidebar_policy;?></a><br><br>
          <a href="docs/manueldutilisationmykameo.pdf" target="_blank" title="Pdf" class="fr"><?=L::sidebar_manual;?></a><br>
          <a class="button small green button-3d rounded icon-left" data-target="#tellus" data-toggle="modal" href="#" onclick="initializeTellUs()">
            <span><?=L::sidebar_feedback_button;?></span>
          </a><br>
          <a class="button small red button-3d rounded icon-left" onclick="logout()">
            <span><?=L::sidebar_disconnect_button;?></span>
          </a>
          <script type="text/javascript" src="js/logout.js"></script>
        </div>
      </div>
      <!-- END: SIDEBAR -->
  </div>
</div>
</section>

<!-- INFORMATIONS WIDGETS -->
<?php include 'include/vues/mykameo/widgets/informations/update_informations.html';?>
<script type="text/javascript" src="include/vues/mykameo/widgets/informations/update_informations.js"></script>
<!-- SUPPORT WIDGET -->
<?php
  //php's $contractNumber var is needed here
  include 'include/vues/mykameo/widgets/support/support.html';
  include 'include/vues/mykameo/widgets/support/contact_support.html';  /**@TODO: FIX THE API TO SEND MAIL **/
  include 'include/vues/mykameo/widgets/support/contact_maintenance.html'; /**@TODO: FIX THE API TO SEND MAIL **/
?>

<!-- FEEDBACK WIDGET -->
<?php include 'include/vues/mykameo/widgets/feedback/feedback.html'; /**@TODO: FIX THE API TO SEND MAIL **/
?>

<script type="text/javascript" src="js/initialize_counters.js"></script>
<script type="text/javascript" src="js/maintenance_management.js"></script>
<script type="text/javascript">
window.addEventListener("DOMContentLoaded", function(event) {
	$( ".fleetmanager" ).click(function() {
    /** DASHBOARD **/
    list_errors();
    initialize_task_owner_sales_selection();

		initializeFields();

		hideResearch();
		var date=new Date();
		if ($( ".form_date_end_client" ).length)
			$(".form_date_end_client").data("datetimepicker").setDate(date);
		if ($( ".form_date_start_client" ).length)
		{
			date.setMonth(date.getMonth()-6);
			$(".form_date_start_client").data("datetimepicker").setDate(date);
		}
	});
	$( ".reservations" ).click(function() {
		hideResearch();
		getHistoricBookings(email);
	});
  	var date=new Date();
		if ($( ".form_date_end" ).length)
    	$(".form_date_end").data("datetimepicker").setDate(date);
		if ($( ".form_date_start" ).length)
		{
	    date.setMonth(date.getMonth()-1);
    	$(".form_date_start").data("datetimepicker").setDate(date);
		}
});
<?php //@TODO: REFACTOR 'CAUSE USED IN MULTIPLE PART OF THE CODE' ?>
/** Reservation & fleetManager tabs **/
function hideResearch(){
  document.getElementById('velos').innerHTML = "";
  document.getElementById("velos").style.display = "none";
  document.getElementById("travel_information").style.display = "none";
}

//Vélo perso + quand tu commande un vélo
function get_travel_time(date, address_start, address_end){

  return $.ajax({
    url: 'apis/Kameo/get_directions.php',
    type: 'post',
    data: {"date": date, "address_start": address_start, "address_end": address_end},
    success: function(response){
    }
  });
}
//Vélo perso + quand tu commande un vélo
function get_kameo_score(weather, precipitation, temperature, wind_speed, travel_time_bike, travel_time_car){
  var weather_score={clearday:10, rain:4, snow:0, sleet:2, wind:6, fog:6, cloudy:8, partlycloudyday:9, clearnight:10, partlycloudynight:9};
  var difference_travel_time= ( travel_time_car - travel_time_bike ) / (travel_time_bike);

  if (difference_travel_time > 0.2){
    travel_score=2;
  } else if (difference_travel_time > 0.1){
    travel_score=1;
  } else if (difference_travel_time < -0.2){
    travel_score=-2
  } else if (difference_travel_time < -0.1){
    travel_score=-1
  } else
  {
    travel_score=0;
  }

  if (travel_time_bike<10){
    travel_score=travel_score+3;
  }


  if (temperature > 30 || temperature < 5){
    temperature_score=-2;
  } else if (temperature > 25 || temperature < 10){
    temperature_score=-1;
  } else{
    temperature_score=0;
  }

  if (wind_speed > 20){
    wind_score=-3;
  } else if (wind_speed > 20){
    wind_score=-2;
  } else if (wind_speed>10){
    wind_score=-1;
  } else{
    wind_score=0;
  }


  kameo_score= (weather_score[weather]+travel_score+temperature_score+wind_score);
  if (kameo_score>10){
    kameo_score=10;
  } else if(kameo_score<0 || travel_time_bike > 120){
    kameo_score=0;
  }
  document.getElementById("score_kameo1").src="images/meteo/"+kameo_score+"_10.png";
  document.getElementById("score_kameo2").src="images/meteo/"+kameo_score+"_10.png";
  document.getElementById("score_kameo3").src="images/meteo/"+kameo_score+"_10.png";
  document.getElementById("score_kameo4").src="images/meteo/"+kameo_score+"_10.png";

  var image="images/meteo/"+kameo_score+"_10.png";

  return image;

}
function list_kameobikes_member(){
    $('#widget-addActionCompany-form select[name=owner]')
    .find('option')
    .remove()
    .end()
    ;

    $.ajax({
      url: 'apis/Kameo/get_kameobikes_members.php',
      type: 'get',
      success: function(response){
        if(response.response == 'error')
          console.log(response.message);
        else if(response.response == 'success'){
          for (var i = 0; i < response.membersNumber; i++)
            $('#widget-addActionCompany-form select[name=owner]').append("<option value="+response.member[i].email+">"+response.member[i].firstName+" "+response.member[i].name+"<br>");
          $('#widget-addActionCompany-form select[name=owner]').val('julien@kameobikes.com');
        }
      }
});
}
function listPortfolioBikes(){
  $.ajax({
    url: 'apis/Kameo/load_portfolio.php',
    type: 'get',
    data: {"action": "list"},
    success: function(response){
      if (response.response == 'error') {
        console.log(response.message);
      } else{
            var i=0;
            var dest="";
            var temp="<table class=\"table table-condensed\" id=\"portfolioBikeListing\"><h4 class=\"fr-inline text-green\">Vélos du catalogue:</h4><h4 class=\"en-inline text-green\">Portfolio bikes:</h4><h4 class=\"nl-inline text-green\">Portfolio bikes:</h4><br/><a class=\"button small green button-3d rounded icon-right\" data-target=\"#addPortfolioBike\" data-toggle=\"modal\" onclick=\"initializeCreatePortfolioBike()\" href=\"#\"><span class=\"fr-inline\"><i class=\"fa fa-plus\"></i> Ajouter un vélo</span></a><thead><tr><th>ID</th><th><span class=\"fr-inline\">Marque</span><span class=\"en-inline\">Brand</span><span class=\"nl-inline\">Brand</span></th><th><span class=\"fr-inline\">Modèle</span><span class=\"en-inline\">Model</span><span class=\"nl-inline\">Model</span></th><th><span class=\"fr-inline\">Utilisation</span><span class=\"en-inline\">Use</span><span class=\"nl-inline\">Use</span></th><th><span class=\"fr-inline\">Electrique ?</span><span class=\"en-inline\">Electric</span><span class=\"nl-inline\">Electric</span></th><th><span class=\"fr-inline\">Cadre</span><span class=\"en-inline\">Frame</span><span class=\"nl-inline\">Frame</span></th><th><span class=\"fr-inline\">Prix</span><span class=\"en-inline\">Price</span><span class=\"nl-inline\">Price</span></th><th>Afficher</th><th></th></tr></thead><tbody>";
            dest=dest.concat(temp);
            while(i<response.bikeNumber){
                var temp="<tr><td>"+response.bike[i].ID+"</td><td>"+response.bike[i].brand+"</td><td>"+response.bike[i].model+"</td><td>"+response.bike[i].utilisation+"</td><td>"+response.bike[i].electric+"</td><td>"+response.bike[i].frameType+"</td><td>"+Math.round(response.bike[i].price)+" €</td><td>"+response.bike[i].display+"<td><a href=\"#\" class=\"text-green updatePortfolioClick\" onclick=\"initializeUpdatePortfolioBike('"+response.bike[i].ID+"')\" data-target=\"#updatePortfolioBike\" data-toggle=\"modal\">Mettre à jour </a></td></tr>";
                dest=dest.concat(temp);
                i++;
            }
            document.getElementById('portfolioBikesListing').innerHTML=dest.concat("</tbody>");
            displayLanguage();
            $('#portfolioBikeListing').DataTable({
                "paging": false
            });
      }
    }
  })
}
</script>

<!-- FLEET MANAGER WIDGETS -->
<?php
  /** FLEET **/
  //BIKES
  include 'include/vues/mykameo/tabs/fleet_manager/fleet/widgets/bikes/main.php';
  //USERS
  include 'include/vues/mykameo/tabs/fleet_manager/fleet/widgets/users/main.php';
  //RESERVATIONS
  include 'include/vues/mykameo/tabs/fleet_manager/fleet/widgets/reservations/main.php';
  /** @TODO: CREATE SCRIPT & include 'include/vues/tabs/fleet_manager/fleet/widgets/reservations/update_reservation.html'; **/

  /** SETTINGS **/
  //CONDITIONS
  include 'include/vues/mykameo/tabs/fleet_manager/settings/widgets/conditions/main.php';

  /** ADMIN **/
  //CUSTOMERS
  include 'include/vues/mykameo/tabs/fleet_manager/admin/widgets/customers/main.php';
  //MANAGE BIKES
  include 'include/vues/mykameo/tabs/fleet_manager/admin/widgets/bikes/main.php';
  //BOXES
  include 'include/vues/mykameo/tabs/fleet_manager/admin/widgets/boxes/main.php';
  //TASKS
  include 'include/vues/mykameo/tabs/fleet_manager/admin/widgets/tasks/main.php';
  //CASHFLOW
  include 'include/vues/mykameo/tabs/fleet_manager/admin/widgets/cashflow/cashflow.html';
  //FEEDBACKS
  include 'include/vues/mykameo/tabs/fleet_manager/admin/widgets/feedbacks/main.php';
  //MAINTENANCES
  include 'include/vues/mykameo/tabs/fleet_manager/admin/widgets/maintenances/main.php';
  //ORDERS
  include 'include/vues/mykameo/tabs/fleet_manager/admin/widgets/orders/main.php';
  //PORTFOLIO
  /** @TODO: Add a delete confirmation widget **/
  include 'include/vues/mykameo/tabs/fleet_manager/admin/widgets/portfolio/main.php';
  //BILLS
  include 'include/vues/mykameo/tabs/fleet_manager/bills/widgets/bills/main.php';
  //DASHBOARD
  include 'include/vues/mykameo/tabs/fleet_manager/admin/widgets/dashboard/main.php';
?>

<?php } ?>

<div class="loader"><!-- Place at bottom of page --></div>

<?php include 'include/footer.php'; ?>

</div>
<!-- END: WRAPPER -->

<!-- Theme Base, Components and Settings -->
<script src="js/theme-functions.js"></script>

<?php //@TODO: TRANSLATE USING i18n AND GET RID OF THAT ?>
<script type="text/javascript" src="js/language.js">
  displayLanguage();
</script>

</body>
<?php
$conn->close();
ob_end_flush();
?>
</html>
