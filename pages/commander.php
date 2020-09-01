<!DOCTYPE html>
<html lang="fr">
<?php
include 'include/head.php';
header_remove("Set-Cookie");
header_remove("X-Powered-By");
?>

<body class="wide">
    <!-- WRAPPER -->
    <div class="wrapper">
        <?php include 'include/topbar.php'; ?>
        <?php include 'include/header.php'; ?>
        <!-- CONTENT -->
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="text-green"><?= L::commander_title; ?></h1>
                        <p><?= L::commander_subtext; ?></p>

                        <div class="m-t-30">
                            <form id="widget-contact-form" action="apis/Kameo/order-form.php" role="form" method="post">
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="name"><?= L::commander_nom; ?></label>
                                        <input type="text" aria-required="true" name="widget-contact-form-name" class="form-control required name">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="firstName"><?= L::commander_prenom; ?></label>
                                        <input type="text" aria-required="true" name="widget-contact-form-firstName" class="form-control required name">

                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="email"><?= L::commander_mail; ?></label>
                                        <input type="email" aria-required="true" name="widget-contact-form-email" class="form-control required email">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="phone"><?= L::commander_phone; ?></label>
                                        <input type="phone" aria-required="true" name="widget-contact-form-phone" class="form-control required phone" placeholder="+32">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="entreprise"><?= L::commander_entreprise; ?></label>
                                        <input type="entreprise" aria-required="true" name="widget-contact-form-entreprise" class="form-control required entreprise" placeholder="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="message"><?= L::commander_message; ?></label>
                                    <textarea type="text" name="widget-contact-form-message" rows="5" class="form-control required" placeholder="Votre message"></textarea>
                                </div>

                                <div class="g-recaptcha" data-sitekey="6LfqMFgUAAAAADlCo3L6lqhdnmmkNvoS-kx00BMi"></div>

                                <input type="text" class="hidden" id="widget-contact-form-antispam" name="widget-contact-form-antispam" value="" />
                                <button class="button effect fill" type="submit" id="form-submit"><i class="fa fa-paper-plane"></i>&nbsp;<?= L::commander_send; ?></button>
                            </form>
                            <script type="text/javascript">
                                jQuery("#widget-contact-form").validate({
                                    submitHandler: function(form) {

                                        jQuery(form).ajaxSubmit({
                                            success: function(text) {
                                                if (text.response == 'success') {
                                                    $.notify({
                                                        message: "Nous avons <strong>bien</strong> reçu votre message et nous reviendrons vers vous dès que possible."
                                                    }, {
                                                        type: 'success'
                                                    });
                                                    $(form)[0].reset();

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
    <script src="js/theme-functions.js"></script>

    <!-- Custom js file -->
    <script src="js/language.js"></script>



</body>

</html>