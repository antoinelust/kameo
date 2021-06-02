<!DOCTYPE html>
<html lang="fr">
<?php
include 'include/head.php';
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
                <img src="images_accessories/<?php echo $_GET['ID']; ?>.jpg" class="img-responsive img-rounded" alt="">
                <br>
            </div>
            <div class="col-md-6">
              <div class="heading heading text-left m-b-20">
                  <h2 name='model'></h2>
              </div>
              <div class="col-md-12">
                <div class="equal">
                  <div class="col-md-6 col-sm-12 col-xs-4 box_grey">
                      <div class="plan">
                          <div class="plan-header" style="cursor : default">
                              <h4><?= L::offre_buyprice_htva; ?></h4>
                              <h2 class="text-green" name='retailPrice'><sup>€</sup></h2>
                          </div>
                      </div>
                  </div>

                  <div class="col-md-6 col-sm-12 col-xs-4  box_grey">
                      <div class="plan">
                          <div class="plan-header" style="cursor : default">
                              <h4><?= L::offre_buyprice_tvac; ?></h4>
                              <h2 class="text-green" name='retailPriceTVAC'><sup>€</sup></h2>
                          </div>
                      </div>
                  </div>
                </div>
                <div class="equal">
                  <div class="col-md-6 col-sm-12 col-xs-4 box_grey">
                      <div class="plan">
                          <div class="plan-header" style="cursor : default">
                              <h4><?= L::offre_price_leasing_htva; ?></h4>
                              <h2 class="text-green" name='leasingPrice'><sup>€</sup></h2>
                          </div>
                      </div>
                  </div>

                  <div class="col-md-6 col-sm-12 col-xs-4  box_grey">
                      <div class="plan">
                          <div class="plan-header" style="cursor : default">
                              <h4><?= L::offre_price_leasing_tvac; ?></h4>
                              <h2 class="text-green" name='leasingPriceTVAC'><sup>€</sup></h2>
                          </div>
                      </div>
                  </div>
                </div>
	              <div class="space"></div>
              </div>

              <div class="separator"></div>

              <div class="m-t-30">
                <form id="widget-offer" action="apis/Kameo/offer_form.php" role="form" method="post">
                    <div class="row">
                      <p><?= L::offre_markInterstedAccessory; ?></p>
                        <div class="form-group col-md-6">
                          <label for="name"><?= L::offre_nom; ?></label>
                          <input type="text" aria-required="true" name="name" class="form-control required name">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="firstName"><?= L::offre_prenom; ?></label>
                        <input type="text" aria-required="true" name="firstName" class="form-control required name">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="email"><?= L::offre_mail; ?></label>
                          <input type="email" aria-required="true" name="email" class="form-control required email">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="phone"><?= L::offre_phone; ?></label>
                          <input type="phone" aria-required="true" name="phone" class="form-control required phone" placeholder="+32">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="billingType"><?= L::offre_aquisition_type; ?></label>
                          <select name="billingType">
                              <option value="achat"><?= L::offre_aquisition_buy; ?></option>
                              <option value="leasing"><?= L::offre_aquisition_leasing; ?></option>
                          </select>
                        </div>
                    </div>
                    <input type="text" class="hidden" name="type" value="accessory" >
                    <input type="text" class="hidden" name="catalogID" value="<?= $_GET['ID']; ?>">
                    <button class="button green button-3d rounded effect" type="submit" id="form-submit"><?= L::action_btncontact; ?></button>
                </form>

                <script type="text/javascript">
                var ID="<?php echo $_GET['ID']; ?>";
                $.ajax({
                  url: 'apis/Kameo/load_portfolio_accessories.php',
                  type: 'get',
                  data: {
                      "action" : "retrieve",
                      "catalogID" : ID
                  },
                  success: function(response){
                    $('h2[name=model]').append(response.accessory.BRAND+" "+response.accessory.MODEL);
                    $('h2[name=retailPrice]').append(response.accessory.PRICE_HTVA);
                    $('h2[name=retailPriceTVAC]').append(Math.round(response.accessory.PRICE_HTVA*1.21*100)/100);
                    $('h2[name=leasingPrice]').append(Math.round(response.accessory.leasingPrice*100)/100+"<small>"+traduction.offre_permonth+"</small>");
                    $('h2[name=leasingPriceTVAC]').append(Math.round(response.accessory.leasingPrice*1.21*100)/100+"<small>"+traduction.offre_permonth+"</small>");
                  }
                })


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
  <script src="/js/language.js"></script>
</body>

</html>
