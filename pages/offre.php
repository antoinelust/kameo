<!DOCTYPE html>
<html lang="fr">
<?php
include 'include/head.php';
?>
<?php
$ID = isset($_GET['ID']) ? $_GET['ID'] : NULL;
include 'apis/Kameo/connexion.php';

$sql = "SELECT * FROM bike_catalog WHERE ID='$ID'";

if ($conn->query($sql) === FALSE) {
    echo $conn->error;
}
$result = mysqli_query($conn, $sql);
$resultat = mysqli_fetch_assoc($result);
?>

<body class="wide">

  <?
  	require_once $_SERVER['DOCUMENT_ROOT'].'/apis/Kameo/environment.php';
  	if(constant('ENVIRONMENT')=="production"){
  		include $_SERVER['DOCUMENT_ROOT'].'/include/googleTagManagerBody.php';
  	}
  ?>

    <!-- WRAPPER -->
    <div class="wrapper">
        <?php include 'include/topbar.php'; ?>
        <?php include 'include/header.php'; ?>
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <img src="images_bikes/<?php echo $ID; ?>.jpg" class="img-responsive img-rounded" alt="">
                        <br>
                        <dl class="dl">
                            <dt><?= L::offre_technical_characs; ?></dt><br>
                            <div class="container-fluid">
                              <div class="row equal">
                                <div class="col-xs-12 col-sm-4" style='border: 1px solid grey; text-align: center; padding-top: 1em;'>
                                    <div class="plan">
                                        <div class="plan-header" style="cursor : default">
                                            <svg width="2.5em" viewBox="0 0 16 16" class="bi bi-power" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M5.578 4.437a5 5 0 1 0 4.922.044l.5-.866a6 6 0 1 1-5.908-.053l.486.875z"/>
                                                <path fill-rule="evenodd" d="M7.5 8V1h1v7h-1z"/>
                                            </svg>
                                            <p class="text-green"><?php
                                              echo "<strong>".$resultat['MOTOR']."</strong>";
                                              if($resultat['MOTOR']=='')
                                              {
                                                  echo 'N/A';
                                              }
                                           ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4" style='border: 1px solid grey; text-align: center; padding-top: 1em;'>
                                    <div class="plan">
                                        <div class="plan-header" style="cursor : default">
                                            <svg width="2.5em" viewBox="0 0 16 16" class="bi bi-battery-full" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M12 5H2a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1zM2 4a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H2z"/>
                                                <path d="M2 6h10v4H2V6zm12.5 3.5a1.5 1.5 0 0 0 0-3v3z"/>
                                            </svg>
                                            <p class="text-green"><?php
                                              echo "<strong>".$resultat['BATTERY']."</strong>";
                                              if($resultat['BATTERY']=='')
                                              {
                                                  echo 'N/A';
                                              }
                                           ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4" style='border: 1px solid grey; text-align: center; padding-top: 1em;'>
                                    <div class="plan">
                                        <div class="plan-header" style="cursor : default">
                                            <svg width="2.5em" viewBox="0 0 16 16" class="bi bi-gear-wide-connected" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M8.932.727c-.243-.97-1.62-.97-1.864 0l-.071.286a.96.96 0 0 1-1.622.434l-.205-.211c-.695-.719-1.888-.03-1.613.931l.08.284a.96.96 0 0 1-1.186 1.187l-.284-.081c-.96-.275-1.65.918-.931 1.613l.211.205a.96.96 0 0 1-.434 1.622l-.286.071c-.97.243-.97 1.62 0 1.864l.286.071a.96.96 0 0 1 .434 1.622l-.211.205c-.719.695-.03 1.888.931 1.613l.284-.08a.96.96 0 0 1 1.187 1.187l-.081.283c-.275.96.918 1.65 1.613.931l.205-.211a.96.96 0 0 1 1.622.434l.071.286c.243.97 1.62.97 1.864 0l.071-.286a.96.96 0 0 1 1.622-.434l.205.211c.695.719 1.888.03 1.613-.931l-.08-.284a.96.96 0 0 1 1.187-1.187l.283.081c.96.275 1.65-.918.931-1.613l-.211-.205a.96.96 0 0 1 .434-1.622l.286-.071c.97-.243.97-1.62 0-1.864l-.286-.071a.96.96 0 0 1-.434-1.622l.211-.205c.719-.695.03-1.888-.931-1.613l-.284.08a.96.96 0 0 1-1.187-1.186l.081-.284c.275-.96-.918-1.65-1.613-.931l-.205.211a.96.96 0 0 1-1.622-.434L8.932.727zM8 12.997a4.998 4.998 0 1 0 0-9.995 4.998 4.998 0 0 0 0 9.996z"/>
                                                <path fill-rule="evenodd" d="M7.375 8L4.602 4.302l.8-.6L8.25 7.5h4.748v1H8.25L5.4 12.298l-.8-.6L7.376 8z"/>
                                            </svg>
                                            <p class="text-green"><?php
                                                echo "<strong>".$resultat['TRANSMISSION']."</strong>";
                                                if($resultat['TRANSMISSION']=='')
                                                {
                                                    echo 'N/A';
                                                }
                                            ?></p>
                                        </div>
                                    </div>
                                </div>
                              </div>
                            </div>

                            <dt>
                                <div class="pricing-table col-no-margin row">
                                    <div class="col-md-4">
                                        <div class="plan">
                                            <div class="plan-header" style="cursor : default">
                                                <svg width="2.5em" viewBox="0 0 16 16" class="bi bi-power" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M5.578 4.437a5 5 0 1 0 4.922.044l.5-.866a6 6 0 1 1-5.908-.053l.486.875z"/>
                                                    <path fill-rule="evenodd" d="M7.5 8V1h1v7h-1z"/>
                                                </svg>
                                                <p class="text-green"><?php
                                                  echo $resultat['MOTOR'];
                                                  if($resultat['MOTOR']=='')
                                                  {
                                                      echo 'N/A';
                                                  }
                                               ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="plan">
                                            <div class="plan-header" style="cursor : default">
                                                <svg width="2.5em" viewBox="0 0 16 16" class="bi bi-battery-full" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M12 5H2a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1zM2 4a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H2z"/>
                                                    <path d="M2 6h10v4H2V6zm12.5 3.5a1.5 1.5 0 0 0 0-3v3z"/>
                                                </svg>
                                                <p class="text-green"><?php
                                                  echo $resultat['BATTERY'];
                                                  if($resultat['BATTERY']=='')
                                                  {
                                                      echo 'N/A';
                                                  }
                                               ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="plan">
                                            <div class="plan-header" style="cursor : default">
                                                <svg width="2.5em" viewBox="0 0 16 16" class="bi bi-gear-wide-connected" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M8.932.727c-.243-.97-1.62-.97-1.864 0l-.071.286a.96.96 0 0 1-1.622.434l-.205-.211c-.695-.719-1.888-.03-1.613.931l.08.284a.96.96 0 0 1-1.186 1.187l-.284-.081c-.96-.275-1.65.918-.931 1.613l.211.205a.96.96 0 0 1-.434 1.622l-.286.071c-.97.243-.97 1.62 0 1.864l.286.071a.96.96 0 0 1 .434 1.622l-.211.205c-.719.695-.03 1.888.931 1.613l.284-.08a.96.96 0 0 1 1.187 1.187l-.081.283c-.275.96.918 1.65 1.613.931l.205-.211a.96.96 0 0 1 1.622.434l.071.286c.243.97 1.62.97 1.864 0l.071-.286a.96.96 0 0 1 1.622-.434l.205.211c.695.719 1.888.03 1.613-.931l-.08-.284a.96.96 0 0 1 1.187-1.187l.283.081c.96.275 1.65-.918.931-1.613l-.211-.205a.96.96 0 0 1 .434-1.622l.286-.071c.97-.243.97-1.62 0-1.864l-.286-.071a.96.96 0 0 1-.434-1.622l.211-.205c.719-.695.03-1.888-.931-1.613l-.284.08a.96.96 0 0 1-1.187-1.186l.081-.284c.275-.96-.918-1.65-1.613-.931l-.205.211a.96.96 0 0 1-1.622-.434L8.932.727zM8 12.997a4.998 4.998 0 1 0 0-9.995 4.998 4.998 0 0 0 0 9.996z"/>
                                                    <path fill-rule="evenodd" d="M7.375 8L4.602 4.302l.8-.6L8.25 7.5h4.748v1H8.25L5.4 12.298l-.8-.6L7.376 8z"/>
                                                </svg>
                                                <p class="text-green"><?php
                                                    echo $resultat['TRANSMISSION'];
                                                    if($resultat['TRANSMISSION']=='')
                                                    {
                                                        echo 'N/A';
                                                    }
                                                ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </dt>
                        </dl>
                    </div>

                    <div class="col-md-6">
                        <div class="heading heading text-left m-b-20">
                            <h2><?php echo $resultat['BRAND'] . ' ' . $resultat['MODEL']; ?></h2>
                        </div>

                        <dl class="dl col-md-6">
                            <dt><?= L::offre_usage; ?></dt>
                            <dd><?php echo $resultat['UTILISATION']; ?></dd>
                            <br>
                            <dt><?= L::offre_cadre_type; ?></dt>
                            <dd><?php if ($resultat['FRAME_TYPE'] == "H") {
                                    echo "Homme";
                                } else if ($resultat['FRAME_TYPE'] == "M") {
                                    echo "Mixte";
                                } else if ($resultat['FRAME_TYPE'] == "F") {
                                    echo "Femme";
                                } else {
                                    echo "undefined";
                                } ?></dd>
                        </dl>

                        <dl class="dl col-md-6">
                            <dt><?= L::offre_electric_assist; ?></dt>
                            <dd><?php if ($resultat['ELECTRIC'] == "Y") {
                                    echo "Oui";
                                } else if ($resultat['ELECTRIC'] == "N") {
                                    echo "Non";
                                } else {
                                    echo "undefined";
                                } ?></dd>
                            <br>
                        </dl>

                        <div class="col-md-12">
                            <?php

                            $marginBike = 0.7;
                            $marginOther = 0.3;
                            $leasingDuration = 36;

                            // Form Fields
                            $retailPrice = $resultat['PRICE_HTVA'];
                            $priceRetailer = $retailPrice * (1 - 0.27);
                            $debtCost = $priceRetailer * 0.09;

                            $otherCost = 3 * 84 + 4 * 100;

                            $totalCost = ($priceRetailer + $debtCost + $otherCost);
                            $leasingPrice = round(($priceRetailer * (1 + $marginBike) + $otherCost * (1 + $marginOther)) / $leasingDuration);
                            ?>

                            <!--<h3>Prix Leasing (HTVA): <b class="text-green"><?php echo $leasingPrice; ?></b> <small>€/mois HTVA</small></h3>-->


                            <!-- Pricing Table Colored -->
                            <div class="row">
                                <div class="pricing-table col-no-margin">
                                    <div class="col-md-4 col-sm-12 col-xs-4">
                                        <div class="plan">
                                            <div class="plan-header" style="min-height:182px !important ; cursor : default">
                                                <h4><?= L::offre_buyprice_htva; ?></h4>
                                                <h2 class="text-green"><sup>€</sup><?php echo round($resultat['PRICE_HTVA']); ?></h2>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-12 col-xs-4">
                                        <div class="plan">
                                            <div class="plan-header" style="min-height:182px !important ; cursor : default">
                                                <h4><?= L::offre_buyprice_tvac; ?></h4>
                                                <h2 class="text-green"><sup>€</sup><?php echo round($resultat['PRICE_HTVA'] * 1.21); ?></h2>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-12 col-xs-4">
                                        <div class="plan">
                                            <div class="plan-header" style="min-height:182px !important ; cursor : default">
                                                <h4><?= L::offre_price_leasing_htva; ?></h4>
                                                <h2 class="text-green"><sup>€</sup><?php echo $leasingPrice; ?><span><small><?= L::offre_permonth; ?></small></span></h2>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- END: Pricing Table Colored -->



                            <!-- ---------- -->

                        </div>

                        <div class="col-md-12">
                            <?= L::offre_leasing_description; ?>
                        </div>

                        <div class="separator"></div>

                        <div class="m-t-30">
                            <form id="widget-offer" action="apis/Kameo/offer_form.php" role="form" method="post">
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="name"><?= L::offre_nom; ?></label>
                                        <input type="text" aria-required="true" name="widget-offer-name" class="form-control required name">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="firstName"><?= L::offre_prenom; ?></label>
                                        <input type="text" aria-required="true" name="widget-offer-firstName" class="form-control required name">

                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="email"><?= L::offre_mail; ?></label>
                                        <input type="email" aria-required="true" name="widget-offer-email" class="form-control required email">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="phone"><?= L::offre_phone; ?></label>
                                        <input type="phone" aria-required="true" name="widget-offer-phone" class="form-control required phone" placeholder="+32">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="widget-offer-leasing"><?= L::offre_aquisition_type; ?></label>
                                        <select name="widget-offer-leasing">
                                            <option value="achat"><?= L::offre_aquisition_buy; ?></option>
                                            <option value="leasing"><?= L::offre_aquisition_leasing; ?></option>
                                        </select>
                                    </div>
                                </div>

                                <input type="text" class="hidden" id="widget-offer-brand" name="widget-offer-brand" value="<?php echo $resultat['BRAND']; ?>" />
                                <input type="text" class="hidden" id="widget-offer-model" name="widget-offer-model" value="<?php echo $resultat['MODEL']; ?>" />
                                <input type="text" class="hidden" id="widget-offer-frame-type" name="widget-offer-frame-type" value="<?php echo $resultat['FRAME_TYPE']; ?>" />
                                <input type="text" class="hidden" id="widget-offer-antispam" name="widget-offer-antispam" value="" />
                                <button class="button green button-3d rounded effect" type="submit" id="form-submit"><?= L::offre_askoffer_btn; ?></button>
                            </form>
                            <script type="text/javascript">
                                jQuery("#widget-offer").validate({
                                    submitHandler: function(form) {
                                        jQuery(form).ajaxSubmit({
                                            success: function(text) {
                                                if (text.response == 'success') {
                                                    $.notify({
                                                        message: text.message
                                                    }, {
                                                        type: 'success'
                                                    });
                                                    $(form)[0].reset();

                                                    gtag('event', 'send', {
                                                        'event_category': 'mail',
                                                        'event_label': 'offre.php',
                                                        'config': 'UA-108429655-1'
                                                    });

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
            </div>
        </section>
        <!-- END: CONTENT -->
        <?php include 'include/footer.php'; ?>
    </div>
    <!-- END: WRAPPER -->


    <!-- Theme Base, Components and Settings -->
    <script src="/js/theme-functions.js"></script>

    <!-- Custom js file -->
    <script src="/js/language.js"></script>



</body>

</html>
