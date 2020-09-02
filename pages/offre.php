<!DOCTYPE html>
<html lang="fr">
<?php
include 'include/head.php';
header_remove("Set-Cookie");
header_remove("X-Powered-By");
?>
<?php
$brand = isset($_GET['brand']) ? $_GET['brand'] : NULL;
$model = isset($_GET['model']) ? $_GET['model'] : NULL;
$frameType = isset($_GET['frameType']) ? $_GET['frameType'] : NULL;
include 'apis/Kameo/connexion.php';
$brandUPPER = strtoupper($brand);
$modelUPPER = strtoupper($model);
$frameTypeUPPER = strtoupper($frameType);

$sql = "SELECT * FROM bike_catalog WHERE UPPER(BRAND)='$brandUPPER' AND UPPER(MODEL)='$modelUPPER' AND UPPER(FRAME_TYPE)='$frameTypeUPPER'";

if ($conn->query($sql) === FALSE) {
    echo $conn->error;
}
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
?>

<body class="wide">
    <!-- WRAPPER -->
    <div class="wrapper">
        <?php include 'include/topbar.php'; ?>
        <?php include 'include/header.php'; ?>
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-6">

                        <img src="images_bikes/<?php echo str_replace(' ', '-', $brand) . '_' . str_replace(' ', '-', $model) . '_' . $frameType; ?>.jpg" class="img-responsive img-rounded" alt="">
                        <br>
                        <dl class="dl">
                            <dt><?= L::offre_technical_characs; ?></dt>
                            <dd><?= L::offre_see_brand; ?><ins><a href="<?php echo $row['LINK']; ?>" target="_blank"><?= L::offre_brand_site; ?></a></ins>.</dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <div class="heading heading text-left m-b-20">
                            <h2><?php echo $brand . ' ' . $model; ?></h2>
                        </div>

                        <dl class="dl col-md-6">
                            <dt><?= L::offre_usage; ?></dt>
                            <dd><?php echo $row['UTILISATION']; ?></dd>
                            <br>
                            <dt><?= L::offre_cadre_type; ?></dt>
                            <dd><?php if ($row['FRAME_TYPE'] == "H") {
                                    echo "Homme";
                                } else if ($row['FRAME_TYPE'] == "M") {
                                    echo "Mixte";
                                } else if ($row['FRAME_TYPE'] == "F") {
                                    echo "Femme";
                                } else {
                                    echo "undefined";
                                } ?></dd>
                        </dl>

                        <dl class="dl col-md-6">
                            <dt><?= L::offre_electric_assist; ?></dt>
                            <dd><?php if ($row['ELECTRIC'] == "Y") {
                                    echo "Oui";
                                } else if ($row['ELECTRIC'] == "N") {
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
                            $retailPrice = $row['PRICE_HTVA'];
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
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <div class="plan">
                                            <div class="plan-header">
                                                <h4><?= L::offre_buyprice_htva; ?></h4>
                                                <h2 class="text-green"><sup>€</sup><?php echo round($row['PRICE_HTVA']); ?></h2>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <div class="plan">
                                            <div class="plan-header">
                                                <h4><?= L::offre_buyprice_tvac; ?></h4>
                                                <h2 class="text-green"><sup>€</sup><?php echo round($row['PRICE_HTVA'] * 1.21); ?></h2>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <div class="plan">
                                            <div class="plan-header">
                                                <h4><?= L::offre_price_leasing_htva; ?></h4>
                                                <h2 class="text-green"><sup>€</sup><?php echo $leasingPrice; ?><span><small><?= L::offre_permonth; ?></small></span></h2>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- END: Pricing Table Colored -->



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

                                <input type="text" class="hidden" id="widget-offer-brand" name="widget-offer-brand" value="<?php echo $brand; ?>" />
                                <input type="text" class="hidden" id="widget-offer-model" name="widget-offer-model" value="<?php echo $model; ?>" />
                                <input type="text" class="hidden" id="widget-offer-model" name="widget-offer-frame-type" value="<?php echo $frameType; ?>" />
                                <input type="text" class="hidden" id="widget-offer-antispam" name="widget-offer-antispam" value="" />
                                <button class="button green button-3d rounded effect" type="submit" id="form-submit"><?= L::offre_askoffer_btn; ?></button>
                            </form>
                            <script type="text/javascript">
                                jQuery("#widget-offer").validate({
                                    submitHandler: function(form) {
                                        console.log("test");
                                        jQuery(form).ajaxSubmit({
                                            success: function(text) {
                                                if (text.response == 'success') {
                                                    $.notify({
                                                        message: "Nous avons bien reçu votre message et nous reviendrons vers vous dès que possible."
                                                    }, {
                                                        type: 'success'
                                                    });
                                                    $(form)[0].reset();

                                                    gtag('event', 'send', {
                                                        'event_category': 'mail',
                                                        'event_label': 'offre.php'
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