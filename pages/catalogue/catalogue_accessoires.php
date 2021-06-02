<!DOCTYPE html>
<html lang="fr">
<?php
	include 'include/head.php';

  switch ($_GET['category']) {
    case 'casques':
      $category=L::accessoryCategories_casque;
      break;
    case 'antivol':
      $category=L::accessoryCategories_cadenas;
      break;
    case 'textiles':
      $category=L::accessoryCategories_textiles;
      break;
    case 'sacoche':
      $category=L::accessoryCategories_sacoche;
      break;
    case 'phare':
      $category=L::accessoryCategories_phare_avant;
      break;
    case 'siege_enfant':
      $category=L::accessoryCategories_siege_enfant;
      break;
    case 'remorques':
      $category=L::accessoryCategories_remorques;
      break;
		case 'gourde':
      $category=L::accessoryCategories_gourde;
		case 'outils':
      $category=L::accessoryCategories_tools;
		case 'GPS':
      $category=L::accessoryCategories_GPS;
		case 'pompe_a_velo':
			$category=L::accessoryCategories_pompe_a_velo;
		case 'Produitsentretien':
			$category=L::accessoryCategories_produit_entretien;
		case 'selle':
			$category=L::accessoryCategories_selle;

    break;
  }

?>
<body class="wide">

	<!-- WRAPPER -->
	<div class="wrapper">
		<?php include 'include/topbar.php'; ?>
		<?php include 'include/header.php'; ?>
		<!--Square icons-->
  <section>
    <div class="container">
      <div class="container-fullwidth">

              <div class="col-md-12 center catalog">
                <h1 class="text-green">
                <?= L::accessories_title_listing.' - '.$category; ?></h1>
                <div class="grid"></div>
                <div class="no_results col-md-12 text-center" style='display: none'>
                  <img src='<?= L::achat_img_no_results; ?>' type="image/svg+xml" />
                </div>

                  <!-- END: Portfolio Items -->
              </div>
          </div>

          <button onclick="topFunction()" id="btn_goto_top_catalog" title="Go to top"><i class="fas fa-arrow-circle-up"></i></button>
      </div>
  </section>
    <!-- END: CONTENT -->
  <div class="modal fade" id="accessoryPicture" tabindex="-1" role="modal" aria-labelledby="modal-label-3" aria-hidden="true">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                  <h4 id="accessoryPicturetitle" class="modal-title"></h4>
              </div>
              <div class="modal-body">
                  <div class="row mb20">
                      <img id="accessoryPictureImage" class="img-responsive img-rounded center" alt="" />
                  </div>
              </div>
              <div class="modal-footer">
                  <button data-dismiss="modal" class="btn btn-b" type="button"><?= L::achat_modal_close; ?></button>
              </div>
          </div>
      </div>
  </div>

  <script type="text/javascript">

    var accessories;
    var category="<?php echo $_GET['category']; ?>";
    function loadPortfolioAccessories(){
      $('.grid').html("");
      var $grid = $('.grid').isotope({});
      $grid.isotope('destroy');
      $.ajax({
        url: 'apis/Kameo/load_portfolio_accessories.php',
        type: 'get',
        data: {
            "action" : "list",
            "category" : category
        },
        success: function(response) {
          if (response.response == 'error') {
              $.notify({
                  message: response.message
              }, {
                  type: 'error'
              });
          }
          if (response.response == 'success') {
            var $grid = $('.grid').isotope({
                itemSelector: '.grid-item',
            });
            var i = 0;
            while (i < response.accessories.length) {

              if(response.accessories[i].DISPLAY=='Y'){
								var temp = "\
                <div style='padding:10px' class='grid-item col-md-3'><div style='display: block; border: 2px solid #3cb395; padding: 0px;'>\
                  <a href=\"offre_accessoire?ID="+response.accessories[i].catalogID+"\"><div class='portfolio-image text-center effect social-links' style='margin-bottom: 1em; margin-top:1em'>\
                      <img src=\"images_accessories/" + response.accessories[i].catalogID + ".jpg\" style='width: auto; height: 200px' alt=\"image_" + response.accessories[i].BRAND.toLowerCase().replace(/ /g, '-') + "_" + response.accessories[i].MODEL + "\">\
                  </div></a>\
                  <div class=\"portfolio-description background-green text-light\" style='padding-left: 1em; padding-top: 1em'>\
                    <a href=\"offre_accessoire?ID="+response.accessories[i].catalogID+"\"><h4 class=\"title\">" + traduction['accessoryCategories_'+response.accessories[i].CATEGORY] + "</h4></a>\
										<p style='margin-bottom: 0px'><strong>"+traduction.generic_brand+"</strong> : "+response.accessories[i].BRAND+"\
                    <br><strong>"+traduction.generic_model+"</strong> : "+response.accessories[i].MODEL+"\
										<br><strong>"+traduction.achat_achat+"</strong> : "+Math.round(response.accessories[i].PRICE_HTVA) + "  € " + traduction.generic_VATExc +
                    "<br><strong>Leasing</strong> : "+Math.round(response.accessories[i].leasingPrice*100)/100 + "  €/" + traduction.generic_mois + " " + traduction.generic_VATExc +
                    "<br><a class='button small black-light button-3d rounded' href=\"offre_accessoire?ID="+response.accessories[i].catalogID+"\">"+traduction.tabs_order_title+"</a></p>"+
                  "</div>"+
                "</div></div>";
                var $item = $(temp);
                $grid.append($item)
                    // add and lay out newly appended elements
                    .isotope('appended', $item);
              }
              i++;
            }
            var $elems = $('.grid-item img');
            var elemsCount = $elems.length;
            var loadedCount = 0;
            $elems.on('load', function () {
              // increase the loaded count
              loadedCount++;
              // if loaded count flag is equal to elements count
              if (loadedCount == elemsCount) {
                setTimeout(function(){
                  $('.grid').isotope();
                }, 500);
                $('[data-toggle="tooltip"]').tooltip({
                  container: "body",
                })
              }
            });
          }
        }
      })
    }
    loadPortfolioAccessories();
  </script>

  <!--END: CALL TO ACTION -->

  <?php include 'include/footer.php'; ?>

  </div>
  <!-- END: WRAPPER -->

  <!-- Theme Base, Components and Settings -->
  <script src="js/theme-functions.js"></script>

  <!-- Language management -->
  <script type="text/javascript" src="js/language.js"></script>

</body>

</html>
