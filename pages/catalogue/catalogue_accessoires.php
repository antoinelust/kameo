<!DOCTYPE html>
<html lang="fr">
<?php
include 'include/head.php';


$category = isset($_GET['category']) ? $_GET['category'] : NULL;

echo "<script type='text/javascript'>
var category ='".$category."';
</script>'";


?>

<body class="wide">
    <!-- WRAPPER -->
    <div class="wrapper">
        <?php include 'include/topbar.php'; ?>
        <?php include 'include/header.php'; ?>
        <?php include 'include/tb_popup.php'; ?>
        <script src="https://cdn.jsdelivr.net/npm/vanilla-lazyload@13.0.1/dist/lazyload.min.js"></script>
        <script src="js/language.js"></script>
        <script src="js/global_functions.js"></script>

        <style>
            * {
                box-sizing: border-box;
            }

            body {
                font-family: sans-serif;
            }

        </style>

        <!-- CONTENT -->

        <section>
            <div class="container-fullwidth">

                    <div class="col-md-9 center catalog">
                      <h1 class="text-green">
                      <?php
                      switch ($_GET['category']) {
                        case 'Ville et Chemin':
                          $category=L::achat_villeetchemin;
                          break;
                        case 'Ville':
                          $category=L::achat_ville;
                          break;
                        case 'Pliant':
                          $category=L::achat_pliant;
                          break;
                        case 'Speedpedelec':
                          $category=L::achat_speedpedelec;
                          break;
                        case 'Gravel':
                          $category=L::achat_gravel;
                          break;
                        case 'VTT':
                          $category=L::achat_vtt;
                          break;
                        case 'Cargo':
                          $category=L::achat_cargo;
                          break;
                        case 'Enfants':
                          $category=L::achat_enfants;
                          break;
                      }

                      echo L::achat_bikes_title.' - '.$category; ?></h1>
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
            function loadPortfolioAccessories(){
              $('.grid').html("");
              var $grid = $('.grid').isotope({});
              $grid.isotope('destroy');
              $.ajax({
                url: 'apis/Kameo/load_portfolio_accessories.php',
                type: 'get',
                data: {
                    "action" : "list"
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
                        <div style='display: block' class='grid-item col-md-3'>\
                          <div class='portfolio-image effect social-links'>\
                              <img src=\"images_accessories/" + response.accessories[i].catalogID + ".jpg\" style='width: auto; height: 200px' alt=\"image_" + response.accessories[i].BRAND.toLowerCase().replace(/ /g, '-') + "_" + response.accessories[i].MODEL + "\">\
                              <div class=\"image-box-content\">\
                                  <p>\
                                      <a data-target=\"#accessoryPicture\" data-toggle=\"modal\" href=\"#\" onclick=\"updateAccessoryPicture('" + response.accessories[i].catalogID + "', '" + response.accessories[i].BRAND + "', '" + response.accessories[i].MODEL + "')\"><i class=\"fa fa-expand\"></i></a>\
                                      <a href=\"offre_accessoire.php?ID=" + response.accessories[i].catalogID + "\"><i class=\"fa fa-link\"></i></a>\
                                  </p>\
                              </div>\
                          </div>\
                          <div class=\"portfolio-description\">\
                            <a href=\"offre.php?ID="+response.accessories[i].ID+"\"><h4 class=\"title\">" + response.accessories[i].BRAND + "</h4></a>\
                            <p>"+response.accessories[i].MODEL+"\
                            <br>"+traduction.generic_frame+" : <br>"+traduction.achat_achat+": " + Math.round(response.accessories[i].PRICE_HTVA) + "  € " +
                            "</p>"+
                          "</div>"+
                        "</div>";
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

            function updateAccessoryPicture(ID, brand, model) {

                document.getElementById('accessoryPicturetitle').innerHTML = brand + " " + model;
                document.getElementById('accessoryPictureImage').src = "images_accessories/" + ID + ".jpg";

            }

        </script>
        <?php include 'include/footer.php'; ?>
    </div>
    <!-- END: WRAPPER -->

    <!-- Theme Base, Components and Settings -->
    <script src="js/theme-functions.js"></script>
    <!-- Scroll to top button -->
    <script src="js/achat_scroll_to_top.js"></script>
    <!-- TB Popup Redirection -->
    <script src="js/tb_popup.js"></script>
</body>

</html>
