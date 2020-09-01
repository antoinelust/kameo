<!DOCTYPE html>
<html lang="fr">
<?php
include 'include/head.php';
?>

<body class="wide">
  <!-- WRAPPER -->
  <div class="wrapper">
    <?php include 'include/topbar.php'; ?>
    <?php include 'include/header.php'; ?>
    <!--Square icons-->
    <section>

      <div class="container">
        <div class="row">
          <h1 class="text-green"><?= L::location_allin_title; ?></h1>
          <br>
          <p><?= L::location_allin_subtitle; ?></p>
          <!-- Pricing Table -->
          <div class="col-md-6">
            <div class="form-group">
              <label class="valeur" for="phone"><?= L::location_allin_pricehtva; ?></label>
              <input type="number" class="form-control required" name="prix" value="2000" id="prix" aria-required="true" onChange="updatePrices(this)">
            </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="pricing-table">
            <div class="col-md-6 col-sm-12 col-xs-12">
              <div class="plan">
                <div class="plan-header">
                  <?= L::location_allin_sellhtva; ?>
                  <div class="plan-price" id="retailPrice"></div>
                </div>
                <div class="plan-list">
                  <ul style="display: block;">
                    <li><i class="fa fa-globe"></i><?= L::location_allin_sell_list1; ?></li>
                    <li><i class="fa fa-thumbs-up"></i><?= L::location_allin_sell_list2; ?></li>
                    <li><i class="fa fa-cogs"></i><?= L::location_allin_sell_list3; ?><i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Cette formule ne prévoit pas d'entretien inclus dans le prix initial. Il est néanmoins possible d'en demander un via la plateforme MyKameo. Une facture séparée sera alors envoyée."></i></li>
                    <li><i class="fa fa-lock"></i><?= L::location_allin_sell_list4; ?></li>
                    <li><i class="fa fa-user"></i><?= L::location_allin_sell_list5; ?></li>
                    <br>
                    <a class="button small green button-3d rounded effect icon-left" data-target="#avantageRetailPrice" data-toggle="modal" href="#" style="display: block;"><span><i class="fa fa-eye"></i><?= L::location_allin_btn_sell; ?></span></a>
                  </ul>
                </div>
              </div>
            </div>

            <div class="col-md-6 col-sm-12 col-xs-12">
              <div class="plan">
                <div class="plan-header">
                <?= L::location_allin_leasehtva; ?>
                  <div class="plan-price" id="leasingPriceFR"></div>
                </div>
                <div class="plan-list">
                  <ul style="display: block;">
                    <li><i class="fa fa-globe"></i><?= L::location_allin_lease_list1; ?><i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Le nombre de kilomètres est cumulable. Sur une location de 36 mois, il suffit de ne pas dépasser 36 * 500 = 18.000 kms au total."></i></li>
                    <li><i class="fa fa-thumbs-up"></i><?= L::location_allin_lease_list2; ?></li>
                    <li><i class="fa fa-lock"></i><?= L::location_allin_lease_list3; ?></li>
                    <li><i class="fa fa-cogs"></i><?= L::location_allin_lease_list4; ?><i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="2 entretiens la première année puis un entretien par an. La planification exacte des entretiens se fait via la plateforme mykameo."></i></li>
                    <li><i class="fa fa-user"></i><?= L::location_allin_lease_list5; ?></li>
                    <li><i class="fa fa-money"></i><?= L::location_allin_lease_list6; ?><i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="A la fin de la location, le vélo peut être racheté à hauteur de 15% du prix d'achat du vélo."></i></li>
                    <br>
                    <a class="button small green button-3d rounded effect icon-left" data-target="#avantageLeasingPrice" data-toggle="modal" href="#"  style="display: block;"><span><i class="fa fa-eye"></i><?= L::location_allin_btn_lease; ?></span></a>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- END: Pricing Table -->

        <div class="modal fade" id="avantageRetailPrice" tabindex="-1" role="modal" aria-labelledby="modal-label-3" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 id="modal-label-3" class="modal-title"><?= L::location_allin_sellmodal_title; ?></h4>
              </div>
              <div class="modal-body">
                <div class="row mb20">
                  <div class="col-sm-12">

                    <p>Toutes les dépenses liées aux vélos et vélos électriques sont déductibles à <strong>100%</strong> fiscalement, profitez-en ! <br />
                      Sur base d'un achat de vélo d'une valeur de <span class="text-green" id="spanRetailPriceFR"></span>, voici l'avantage fiscal: <br /><br />
                      <strong>Prix du vélo :</strong> <span id="spanRetailPriceFR2"></span><br />
                      <strong>TVA :</strong> <span id="spanTVARetailPriceFR"></span><br />
                      <strong>Prix HTVA :</strong> <span id="spanHTVARetailPriceFR"></span><br />
                      <strong>Avantage fiscal :</strong> 100 % x <span id="spanHTVARetailPriceFR2"></span> x 34 % = <span id="spanAvantageFiscalRetailPriceFR" class="text-green"></span><br /><br />
                      Grâce à l'avantage fiscal, le vélo d'une valeur de <span id="spanHTVARetailPriceFR3"></span> HTVA ne coûte réellement à la société que <strong><span id="spanFinalPriceRetailPriceFR" class="text-green"></span></strong> (<span id="spanHTVARetailPriceFR4"></span> - <span id="spanAvantageFiscalRetailPriceFR2"></span>).
                    </p>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <a class="button button-3d rounded effect icon-left" style="display: block;" data-dismiss="modal"><span><i class="fa fa-close"></i><?= L::location_allin_sellmodal_close; ?></span></a>
              </div>
            </div>
          </div>
        </div>
        <div class="modal fade" id="avantageLeasingPrice" tabindex="-1" role="modal" aria-labelledby="modal-label-3" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 id="modal-label-3" class="modal-title"><?= L::location_allin_locationmodal_title; ?></h4>
              </div>
              <div class="modal-body">
                <div class="row mb20">
                  <div class="col-sm-12">

                    <p>Toutes les dépenses liées aux vélos et vélos électriques sont déductibles à <strong>100%</strong> fiscalement, profitez-en ! <br />
                      Sur base d'un achat de vélo d'une valeur de <span class="text-green" id="spanLeasingPriceFR"></span>, voici l'avantage fiscal: <br /><br />
                      <strong>Montant de la location :</strong> <span id="spanLeasingPriceFR2"></span><br />
                      <strong>Avantage fiscal :</strong> 100 % x <span id="spanHTVALeasingPriceFR2"></span> x 34 % = <span id="spanAvantageFiscalLeasingPriceFR" class="text-green"></span><br /><br />


                      Grâce à l'avantage fiscal, la location d'une valeur de <span id="spanHTVALeasingPriceFR3"></span> HTVA ne coûte réellement à la société que <strong><span id="spanFinalPriceLeasingPriceFR" class="text-green"></span></strong> (<span id="spanHTVALeasingPriceFR4"></span> - <span id="spanAvantageFiscalLeasingPriceFR2"></span>).
                    </p>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <a class="button button-3d rounded effect icon-left" style="display: block;" data-dismiss="modal"><span><i class="fa fa-close"></i><?= L::location_allin_locationmodal_close; ?></span></a>
              </div>
            </div>
          </div>
        </div>


        <script type="text/javascript">
          updatePrices(document.getElementById('prix'));

          function updatePrices(ele) {
            var price = (ele.value);

            $.ajax({
              url: 'apis/Kameo/get_prices.php',
              type: 'post',
              data: {
                "retailPrice": price
              },
              success: function(response) {
                document.getElementById('retailPrice').innerHTML = "<sup>€</sup>" + response.HTVARetailPrice + "<span></span>";
                document.getElementById('leasingPriceFR').innerHTML = "<sup>€</sup>" + response.leasingPrice + "<span>/mois</span>";
                //document.getElementById('rentingPriceFR').innerHTML = "<sup>€</sup>"+response.rentingPrice+"<span>/mois</span>";  

                document.getElementById('spanRetailPriceFR').innerHTML = response.retailPrice + " € ";
                document.getElementById('spanRetailPriceFR2').innerHTML = response.retailPrice + " € ";
                document.getElementById('spanHTVARetailPriceFR').innerHTML = response.HTVARetailPrice + " € ";
                document.getElementById('spanHTVARetailPriceFR2').innerHTML = response.HTVARetailPrice + " € ";
                document.getElementById('spanHTVARetailPriceFR3').innerHTML = response.HTVARetailPrice + " € ";
                document.getElementById('spanHTVARetailPriceFR4').innerHTML = response.HTVARetailPrice + " € ";
                document.getElementById('spanTVARetailPriceFR').innerHTML = response.TVARetailPrice + " € ";
                document.getElementById('spanAvantageFiscalRetailPriceFR').innerHTML = response.avantageFiscalRetailPrice + " € ";
                document.getElementById('spanAvantageFiscalRetailPriceFR2').innerHTML = response.avantageFiscalRetailPrice + " € ";
                document.getElementById('spanFinalPriceRetailPriceFR').innerHTML = response.finalPriceRetailPrice + " € ";
                document.getElementById('spanRetailPriceFR').innerHTML = response.retailPrice + " € ";


                document.getElementById('spanLeasingPriceFR').innerHTML = response.retailPrice + " € ";
                document.getElementById('spanLeasingPriceFR2').innerHTML = response.leasingPrice + " €/mois ";
                document.getElementById('spanHTVALeasingPriceFR2').innerHTML = response.HTVALeasingPrice + " € ";
                document.getElementById('spanHTVALeasingPriceFR3').innerHTML = response.HTVALeasingPrice + " €/mois ";
                document.getElementById('spanHTVALeasingPriceFR4').innerHTML = response.HTVALeasingPrice + " € ";
                document.getElementById('spanAvantageFiscalLeasingPriceFR').innerHTML = response.avantageFiscalLeasingPrice + " €/mois ";
                document.getElementById('spanAvantageFiscalLeasingPriceFR2').innerHTML = response.avantageFiscalLeasingPrice + " € ";
                document.getElementById('spanFinalPriceLeasingPriceFR').innerHTML = response.finalPriceLeasingPrice + " €/mois ";
              }
            });
          }
        </script>



      </div>
    </section>

    <?php include 'include/footer.php'; ?>

  </div>
  <!-- END: WRAPPER -->

  <!-- Theme Base, Components and Settings -->
  <script src="js/theme-functions.js"></script>

  <!-- Language management -->
  <script type="text/javascript" src="js/language.js"></script>



</body>

</html>