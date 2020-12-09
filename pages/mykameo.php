<!DOCTYPE html>
<html lang="fr">
<?php
ob_start();
if (!isset($_SESSION))
  session_start();


echo '
<!-- For IE <= 9 -->
<!--[if IE]>
<script type="text/javascript">
    window.location = "navigateur.php";
</script>
<![endif]-->

<!-- For IE > 9 -->
<script type="text/javascript">
    if (window.navigator.msPointerEnabled) {
        window.location = "navigateur.php";
    }
</script>
';

$token = isset($_SESSION['userID']) ? $_SESSION['userID'] : NULL; //@TODO: replaced by a token to check if connected
$user_ID = isset($_SESSION['ID']) ? $_SESSION['ID'] : NULL; //Used by: notifications.js
$langue = isset($_SESSION['langue']) ? $_SESSION['langue'] : 'fr';

include 'apis/Kameo/connexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/apis/Kameo/authentication.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/apis/Kameo/environment.php';

$token = getBearerToken();

include 'include/head.php';
echo '<body class="wide">';

if (constant('ENVIRONMENT') == "production") {
  include $_SERVER['DOCUMENT_ROOT'] . '/include/googleTagManagerBody.php';
}

echo '<!-- WRAPPER -->
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
</style>';

echo '<script type="text/javascript" src="js/language2.js">
  displayLanguage();
</script>';

// Traduction notifications
require_once $_SERVER['DOCUMENT_ROOT'] . '/apis/Kameo/notifications/notifications_lang.php';



if ($token == NULL) { //Not connected
  include 'include/vues/login_form/main.php'; //@TODO: REFACTOR
} else { //Connected
  //@TODO: Replace email chech with authentication token
  include 'apis/Kameo/connexion.php';

  $sql = "SELECT NOM, PRENOM, PHONE, ADRESS, CITY, POSTAL_CODE, WORK_ADRESS, WORK_POSTAL_CODE, WORK_CITY, aa.COMPANY, aa.EMAIL, bb.* from customer_referential aa, (SELECT CASE WHEN COUNT(1) =1 THEN 'TRUE' ELSE 'FALSE' END as personnalBike FROM customer_bike_access WHERE customer_bike_access.EMAIL=(SELECT EMAIL FROM customer_referential WHERE TOKEN='$token') AND TYPE='personnel') bb WHERE aa.TOKEN='$token' LIMIT 1";
  if ($conn->query($sql) === FALSE)
    die;
  $user_data = mysqli_fetch_assoc(mysqli_query($conn, $sql));
  $user_data = array_merge($user_data, getCondition()['conditions']);
  echo '
  <script type="text/javascript">
    const user_ID = "' . $user_ID . '";
    const user_data = JSON.parse(`' . json_encode($user_data) . '`);
    var email=user_data["EMAIL"];
    var feedback_start = "' .L::notifications_feedback_start. '";
    var feedback_middle = "' .L::notifications_feedback_middle. '";
    var feedback_end = "' .L::notifications_feedback_end. '";
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
    <script type="text/javascript">

      window.addEventListener("DOMContentLoaded", (event) => {';
  if (get_user_permissions("search", $token)) {
    echo '$("#reserver").addClass("active"); ';
  } else if (get_user_permissions("order", $token)) {
    echo '$("#orderBike").addClass("active"); ';
    echo '$("#orderBikeID").addClass("active"); ';
    echo 'get_command_user(email);';
  } else if (get_user_permissions(["fleetManager", "admin"], $token)) {
    echo '$("#fleetmanager").addClass("active"); ';
    echo '$("#fleetmanagerID").addClass("active"); ';
    echo '$( ".fleetmanager" ).trigger( "click" );';
    echo 'displayLanguage();';
  } else if ($user_data["personnalBike"]=="TRUE"){
    echo '$("#personnalBike").addClass("active"); ';
    echo '$("#personnalBikeID").addClass("active"); ';
  }

  echo 'initializeFields();';


  echo '});
    </script>';


  $sql = "select * from customer_referential aa, customer_bike_access bb where aa.EMAIL='" . $user_data['EMAIL'] . "' and aa.EMAIL=bb.EMAIL and bb.TYPE='personnel' LIMIT 1";
  $result = mysqli_query($conn, $sql);
  $company = ($result->num_rows == 0); //Used by: mykameo/main.php
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
              <br />
              <div class="col-md-12">
                <span id="assistanceSpan"></span>
                <?php if (!$company)
                  /** CALENDAR **/
                  include 'include/vues/mykameo/calendar.html';
                include 'include/vues/mykameo/widgets/calendar/main.php'; ?>
              </div>
              <br />
                <div class="col-md-12">
                  <div id="tabs-05c" class="tabs color tabs radius">
                    <ul id="mainTab" class="tabs-navigation">
                      <?php
                      if (get_user_permissions("order", $token)) {
                        if($user_data['CAFETARIA']=='Y'){
                          echo '<li class="orderBike" id="orderBikeID"><a href="#orderBike" class="orderBike"><i class="fa fa-user"></i>' . L::tabs_order_title . '</a></li>';
                        }
                      }
                      if (get_user_permissions("search", $token)) {
                        echo '<li class="reserver active"><a href="#reserver"><i class="fa fa-calendar-plus-o"></i>' . L::tabs_book_title . '</a> </li>
                            <li><a href="#reservations" class="reservations"><i class="fa fa-check-square-o"></i>' . L::tabs_reservations_title . '</a> </li>';
                      }
                      if (get_user_permissions(["fleetManager", "admin"], $token)) {

                        echo '<li id="fleetmanagerID"><a href="#fleetmanager" class="fleetmanager"><i class="fa fa-user"></i>' . L::tabs_fleet_title . '</a> </li>';
                      }
                      if($user_data['personnalBike']=="TRUE"){
                        echo '<li id="personnalBikeID"><a href="#personnalBike" class="personnalBike"><i class="fa fa-user"></i>' . L::tabs_personnal_title . '</a> </li>';
                      }
                      ?>
                    </ul>
                    <div class="tabs-content">
                      <?php
                      if (get_user_permissions("order", $token)) {
                        include 'include/vues/mykameo/tabs/order/order_tab.html';  //TAB 1 @TODO: REFACTOR
                      }
                      /** @TODO: REPARE THE FACT THAT THE BOOK TAB SCRIPT DISPLAYS THE CONTACT ASSISTANCE BUTTON BECAUSE IT'S NOT USED WHEN PERSONNAL BIKE ONLY **/
                      if (get_user_permissions("search", $token)) {
                        include 'include/vues/mykameo/tabs/book/main.php'; //TAB 2 @TODO: REFACTOR
                        include 'include/vues/mykameo/tabs/reservations/main.php';  //TAB 3 @TODO: REFACTOR
                      }
                      if($user_data['personnalBike']=="TRUE"){
                        include 'include/vues/mykameo/tabs/personnal_bike/main.php';  //TAB 4 @TODO: REFACTOR
                      }
                      if(get_user_permissions(["fleetManager", "admin"] , $token)){
                        include 'include/vues/mykameo/tabs/fleet_manager/main.php';  //TAB 4 @TODO: REFACTOR
                      }
                      ?>

                    </div>
                    <?php include 'include/vues/mykameo/tabs/order/widgets/order.html'; ?>
                  </div>
                  <?php include 'include/vues/mykameo/widgets/future_booking/future_booking.html'; ?>
                </div>
            </div>
          </div>
        </div>
        <!-- END: MAIN CONTENT -->
        <!-- SIDEBAR -->
        <div class="col-md-3 sidebar">
          <div class="widget clearfix widget-blog-articles">
            <h4 class="widget-title"><?= L::sidebar_title; ?></h4>
            <ul class="list-posts list-medium">
              <li><?= L::sidebar_last_name; ?>
                <small><?= $user_data["NOM"] ?></small>
              </li>
              <?php if ($user_data["PRENOM"]) : ?>
                <li><?= L::sidebar_first_name; ?>
                  <small><?= $user_data["PRENOM"] ?></small>
                </li>
              <?php endif; ?>
              <?php if ($user_data["PHONE"]) : ?>
                <li><?= L::sidebar_phone; ?>
                  <small class="phone"><?= $user_data["PHONE"] ?></small>
                </li>
              <?php endif; ?>
              <?php if ($user_data["ADRESS"]) : ?>
                <li><?= L::sidebar_home_address; ?>
                  <small><?= $user_data['ADRESS'] . ", " . $user_data['POSTAL_CODE'] . ", " . $user_data['CITY'] ?></small>
                </li>
              <?php endif; ?>
              <?php if ($user_data["WORK_ADRESS"]) : ?>
                <li><?= L::sidebar_work_address; ?>
                  <small><?= $user_data['WORK_ADRESS'] . ", " . $user_data['WORK_POSTAL_CODE'] . ", " . $user_data['WORK_CITY'] ?></small>
                </li>
              <?php endif; ?>
              <li><?= L::sidebar_password; ?>
                <small>********</small>
              </li>
            </ul>
            <?php /**@TODO: REMOVE ONCLICK REFACTOR **/ ?>
            <a class="button small green button-3d rounded icon-left" data-target="#update" data-toggle="modal" href="#" onclick="initializeUpdate()">
              <span><?= L::sidebar_refresh_button; ?></span>
            </a>
            <br>
            <?php if (false) : ?>
              <br><br>
              <h4 class="widget-title">
                <span><?= L::sidebar_statistics_title; ?> </span>
                <span id="year"></span>
              </h4>
              <ul class="list-posts list-medium">
                <li> <span> <?= L::sidebar_trips_number; ?></span>
                  <small id="count_trips"></small>
                </li>
                <li> <span> <?= L::sidebar_km_number; ?></span>
                  <small id="total_trips"></small>
                </li>
              </ul>
              <!-- Statistics calculation -->
              <script type="text/javascript" src="include/vues/mykameo/statistics.js"></script>
            <?php endif; ?>
            <br>
            <?php
            include_once 'apis/Kameo/companies/get_company_details.php';
            $contactDetails = get_company();
            if ($contactDetails['contact']['company'] != "Actiris"){
              echo '<a href="docs/cgvfr.pdf" target="_blank" title="Pdf">' . L::sidebar_terms . '</a><br><br>';
            }

            if ($contactDetails['contact']['company'] == "Actiris"){
              echo '<a href="docs/'.L::sidebar_bike_policy_link_actiris.'.pdf" target="_blank" title="Pdf">'.L::sidebar_bike_policy.'</a><br><br>';
            }elseif($contactDetails['contact']['company'] == "AZZANA"){
              echo '<a href="docs/'.L::sidebar_bike_policy_link_azzana.'.pdf" target="_blank" title="Pdf">'.L::sidebar_bike_policy.'</a><br><br>';
            }else{
              echo '<a href="docs/KAMEO-BikePolicy.pdf" target="_blank" title="Pdf">'.L::sidebar_bike_policy.'</a><br><br>';
            }

            if ($contactDetails['contact']['company'] == "Actiris"){
              echo '<a href="docs/'.L::sidebar_manualActiris.'.pdf" target="_blank" title="Pdf">'.L::sidebar_manual.'</a><br><br>';
            }else{
              echo '<a href="docs/manueldutilisationmykameo.pdf" target="_blank" title="Pdf">'.L::sidebar_manual.'</a><br><br>';
            }
            ?>
            <a class="button small green button-3d rounded icon-left" data-target="#tellus" data-toggle="modal" href="#" onclick="initializeTellUs()">
              <span><?= L::sidebar_feedback_button; ?></span>
            </a><br>
            <a class="button small red button-3d rounded icon-left" onclick="logout()">
              <span><?= L::sidebar_disconnect_button; ?></span>
            </a>
            <script type="text/javascript" src="js/logout.js"></script>
          </div>
        </div>
        <!-- END: SIDEBAR -->
      </div>
    </div>
  </section>
  <!-- BOOK TAB RESUME WIDGET -->
  <?php include 'include/vues/mykameo/tabs/book/widgets/book.html'; ?>
  <!-- Feedback WIDGET -->
  <?php include 'include/vues/mykameo/tabs/book/widgets/feedback.html'; ?>
  <!-- INFORMATIONS WIDGETS -->
  <?php include 'include/vues/mykameo/widgets/informations/update_informations.html'; ?>

  <?php if ($user_data["personnalBike"]=="TRUE"){
    include 'include/vues/mykameo/tabs/personnal_bike/widget/rachatBike.html';
  }?>

  <script type="text/javascript" src="include/vues/mykameo/widgets/informations/update_informations.js"></script>
  <!-- SUPPORT WIDGET -->
  <?php
  //php's $contractNumber var is needed here
  include 'include/vues/mykameo/widgets/support/support.html';
  include 'include/vues/mykameo/widgets/support/contact_support.html';
  /**@TODO: FIX THE API TO SEND MAIL **/
  ?>

  <!-- FEEDBACK WIDGET -->
  <?php include 'include/vues/mykameo/widgets/feedback/feedback.html';
  /**@TODO: FIX THE API TO SEND MAIL **/
  ?>

  <script type="text/javascript">
    window.addEventListener("DOMContentLoaded", function(event) {
      $(".fleetmanager").click(function() {

        hideResearch();
        var date = new Date();
        if ($(".form_date_end_client").length)
          $(".form_date_end_client").data("datetimepicker").setDate(date);
        if ($(".form_date_start_client").length) {
          date.setMonth(date.getMonth() - 6);
          $(".form_date_start_client").data("datetimepicker").setDate(date);
        }
      });
      $(".reservations").click(function() {
        hideResearch();
        getHistoricBookings(email);
      });
      var date = new Date();
      if ($(".form_date_end").length)
        $(".form_date_end").data("datetimepicker").setDate(date);
      if ($(".form_date_start").length) {
        date.setMonth(date.getMonth() - 1);
        $(".form_date_start").data("datetimepicker").setDate(date);
      }
    });
    <?php //@TODO: REFACTOR 'CAUSE USED IN MULTIPLE PART OF THE CODE'
    ?>
    /** Reservation & fleetManager tabs **/
    function hideResearch() {
      var myEle = document.getElementById("velos");
      if(myEle){
        document.getElementById('velos').innerHTML = "";
        document.getElementById("velos").style.display = "none";
      }
      var myEle = document.getElementById("travel_information");
      if(myEle){
        document.getElementById("travel_information").style.display = "none";
      }
    }

    //Vélo perso + quand tu commande un vélo
    function get_travel_time(date, address_start, address_end) {

      return $.ajax({
        url: 'apis/Kameo/get_directions.php',
        type: 'post',
        data: {
          "date": date,
          "address_start": address_start,
          "address_end": address_end
        },
        success: function(response) {}
      });
    }
    //Vélo perso + quand tu commande un vélo
    function get_kameo_score(weather, precipitation, temperature, wind_speed, travel_time_bike, travel_time_car) {
      var weather_score = {
        clearday: 10,
        rain: 4,
        snow: 0,
        sleet: 2,
        wind: 6,
        fog: 6,
        cloudy: 8,
        partlycloudyday: 9,
        clearnight: 10,
        partlycloudynight: 9
      };
      var difference_travel_time = (travel_time_car - travel_time_bike) / (travel_time_bike);

      if (difference_travel_time > 0.2) {
        travel_score = 2;
      } else if (difference_travel_time > 0.1) {
        travel_score = 1;
      } else if (difference_travel_time < -0.2) {
        travel_score = -2
      } else if (difference_travel_time < -0.1) {
        travel_score = -1
      } else {
        travel_score = 0;
      }

      if (travel_time_bike < 10) {
        travel_score = travel_score + 3;
      }


      if (temperature > 30 || temperature < 5) {
        temperature_score = -2;
      } else if (temperature > 25 || temperature < 10) {
        temperature_score = -1;
      } else {
        temperature_score = 0;
      }

      if (wind_speed > 20) {
        wind_score = -3;
      } else if (wind_speed > 20) {
        wind_score = -2;
      } else if (wind_speed > 10) {
        wind_score = -1;
      } else {
        wind_score = 0;
      }


      kameo_score = (weather_score[weather] + travel_score + temperature_score + wind_score);
      if (kameo_score > 10) {
        kameo_score = 10;
      } else if (kameo_score < 0 || travel_time_bike > 120) {
        kameo_score = 0;
      }
      document.getElementById("score_kameo1").src = "images/meteo/" + kameo_score + "_10.png";
      document.getElementById("score_kameo2").src = "images/meteo/" + kameo_score + "_10.png";
      document.getElementById("score_kameo3").src = "images/meteo/" + kameo_score + "_10.png";
      document.getElementById("score_kameo4").src = "images/meteo/" + kameo_score + "_10.png";

      var image = "images/meteo/" + kameo_score + "_10.png";

      return image;

    }

  </script>

  <!-- FLEET MANAGER WIDGETS -->
  <?php
  /** FLEET **/

  //BIKES
  if (get_user_permissions("fleetManager", $token)) {
    include 'include/vues/mykameo/tabs/fleet_manager/fleet/widgets/bikes/main.php';
  }
  //USERS
  if (get_user_permissions("fleetManager", $token)) {
    include 'include/vues/mykameo/tabs/fleet_manager/fleet/widgets/users/main.php';
  }
  //ORDERS
  if (get_user_permissions("fleetManager", $token)) {
    if($user_data['CAFETARIA']=='Y'){
      include 'include/vues/mykameo/tabs/fleet_manager/fleet/widgets/orders/main.php';
    }
  }
  //RESERVATIONS
  if (get_user_permissions("fleetManager", $token)) {
    if($user_data['BOOKING']=="Y"){
      include 'include/vues/mykameo/tabs/fleet_manager/fleet/widgets/reservations/main.php';
    }
  }
  //LOCKING
  if (get_user_permissions("fleetManager", $token)) {
    if($user_data['LOCKING']=="Y"){
      include 'include/vues/mykameo/tabs/fleet_manager/fleet/widgets/boxes/main.php';
    }
  }

  /** SETTINGS **/
  //CONDITIONS
  if (get_user_permissions("fleetManager", $token)) {
    include 'include/vues/mykameo/tabs/fleet_manager/settings/widgets/conditions/main.php';
  }

  /** ADMIN **/
  if (get_user_permissions("admin", $token)) {

    //CUSTOMERS
    include 'include/vues/mykameo/tabs/fleet_manager/admin/widgets/customers/main.php';
    //ORDERS
    include 'include/vues/mykameo/tabs/fleet_manager/admin/widgets/orders/main.php';
    //PORTFOLIO BIKES
    /** @TODO: Add a delete confirmation widget **/
    include 'include/vues/mykameo/tabs/fleet_manager/admin/widgets/portfolio/main.php';
  }
  if (get_user_permissions("admin", $token)) {

    //PORTFOLIO ACCESSORIES
    include 'include/vues/mykameo/tabs/fleet_manager/admin/widgets/portfolioAccessories/main.php';
  }
  if (get_user_permissions("admin", $token)) {
    //MANAGE BIKES
    include 'include/vues/mykameo/tabs/fleet_manager/admin/widgets/bikes/main.php';
    //CHATS
    include 'include/vues/mykameo/tabs/fleet_manager/admin/widgets/chats/main.php';
    //FEEDBACKS
    include 'include/vues/mykameo/tabs/fleet_manager/admin/widgets/feedbacks/main.php';
    //MAINTENANCES
    include 'include/vues/mykameo/tabs/fleet_manager/admin/widgets/maintenances/main.php';
    //BOXES
    include 'include/vues/mykameo/tabs/fleet_manager/admin/widgets/boxes/main.php';
    //TASKS
    include 'include/vues/mykameo/tabs/fleet_manager/admin/widgets/tasks/main.php';
    //MAINTENANCE
    include 'include/vues/mykameo/widgets/support/contact_maintenance.html';

  }

  if(get_user_permissions("cashflow", $token)){
    //CASHFLOW
    include 'include/vues/mykameo/tabs/fleet_manager/admin/widgets/cashflow/main.php';
  }

  if(get_user_permissions("dashboard", $token)){
    //DASHBOARD
    include 'include/vues/mykameo/tabs/fleet_manager/admin/widgets/dashboard/main.php';
  }

  if (get_user_permissions("bills", $token)) {
    //BILLS
    include 'include/vues/mykameo/tabs/fleet_manager/bills/widgets/bills/main.php';
  }
}
  ?>

<div class="loader">
  <!-- Place at bottom of page -->
</div>

<?php include 'include/footer.php'; ?>

</div>
<!-- END: WRAPPER -->

<!-- Theme Base, Components and Settings -->
<script src="js/theme-functions.js"></script>

</body>
<?php
$conn->close();
ob_end_flush();
?>

</html>
