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

				<h1 class="text-green"><?=L::blog_title;?></h1>
				<br>


				<!-- CONTENT -->
				        <!-- Blog post-->
				        <div class="post-content post-3-columns">

									<!-- Post pénurie vélo-->

										<div class="post-item">
				                <div class="post-image">
				                    <a href="blog_Choisir-son-velo-electrique.html">
				                        <img alt="" src="images/blog/assemblage_velo_Cover.jpg">
				                    </a>
				                </div>
				                <div class="post-content-details">
				                    <div class="post-title">
				                        <h3><a href="blog_penurie_velo">Pénurie de vélos en 2021</a></h3>
				                    </div>
				                    <div class="post-description">
				                        <p>On vous éclaire sur la situation en 2021 !</p>

				                        <div class="post-info">
				                            <a class="read-more" href="blog_penurie_velo">Lire plus <i class="fa fa-long-arrow-right"></i></a>
				                        </div>
				                    </div>
				                </div>
				            </div>

										<!-- Post Amélioration d'infrastuctures cyclables-->

										<div class="post-item">
				                <div class="post-image">
				                    <a href="blog_Choisir-son-velo-electrique.html">
				                        <img alt="" src="images/blog/ChoixVelo_Cover.jpg">
				                    </a>
				                </div>
				                <div class="post-content-details">
				                    <div class="post-title">
				                        <h3><a href="blog_Choisir-son-velo-electrique.html">Comment choisir son nouveau vélo électrique</a></h3>
				                    </div>
				                    <div class="post-description">
				                        <p>Suivez notre guide pour faire votre meilleur choix.</p>

				                        <div class="post-info">
				                            <a class="read-more" href="blog_Choisir-son-velo-electrique.html">Lire plus <i class="fa fa-long-arrow-right"></i></a>
				                        </div>
				                    </div>
				                </div>
				            </div>


				            <!-- Post Amélioration d'infrastuctures cyclables-->
				            <div class="post-item">
				                <div class="post-image">
				                    <a href="blog_Infrastructures-cyclables-a-Liege-et-a-Bruxelles-pendant-le-deconfinement-et-apres.html">
				                        <img alt="" src="images/blog/infra_velo.jpg">
				                    </a>
				                </div>
				                <div class="post-content-details">
				                    <div class="post-title">
				                        <h3><a href="blog_Infrastructures-cyclables-a-Liege-et-a-Bruxelles-pendant-le-deconfinement-et-apres.html">Infrastructures cyclables</a></h3>
				                    </div>
				                    <div class="post-description">
				                        <p>à Liège et à Bruxelles, pendant le déconfinement et après.</p>

				                        <div class="post-info">
				                            <a class="read-more" href="blog_Infrastructures-cyclables-a-Liege-et-a-Bruxelles-pendant-le-deconfinement-et-apres.html">Lire plus <i class="fa fa-long-arrow-right"></i></a>
				                        </div>
				                    </div>
				                </div>
				            </div>


				            <!-- pagination nav -->
				            <!--
				            <div class="text-center">
									<div class="pagination-wrap">
										<ul class="pagination">
											<li>
												<a aria-label="Previous" href="#">
													<span aria-hidden="true"><i class="fa fa-angle-left"></i></span>
												</a>
											</li>
											<li><a href="#">1</a>
											</li>
											<li><a href="#">2</a>
											</li>
											<li class="active"><a href="#">3</a>
											</li>
											<li><a href="#">4</a>
											</li>
											<li><a href="#">5</a>
											</li>
											<li>
												<a aria-label="Next" href="#">
													<span aria-hidden="true"><i class="fa fa-angle-right"></i></span>
												</a>
											</li>
										</ul>
									</div>
								</div>
				        </div>
				        -->
				        <!-- END: Blog post-->
				<!-- END: SECTION -->
		</div>
	</div>
	</section>

	<?php include 'include/footer.php'; ?>
	</div>
	<!-- END: WRAPPER -->

	<!-- Theme Base, Components and Settings -->
	<script src="js/theme-functions.js"></script>

	<!-- Language management -->
	<script type="text/javascript" src="js/language.js"></script>

	<!-- CONTACT -->
<a class="gototop gototop-button" href="#" data-target="#contact" data-toggle="modal"><i class="fa fa-envelope-o"></i></a>

<div class="modal fade" id="contact" tabindex="-1" role="modal" aria-labelledby="modal-label-2" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h2 class="modal-title" id="modal-label"><?=L::blog_contact_title;?></h2>
			</div>
			<div class="modal-body">
				<div class="row text-left">
					<div class="col-md-12">
						<form id="widget-contact-form" action="include/contact-form.php" role="form" method="post">
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="name"><?=L::blog_contact_name;?></label>
                                        <input type="text" aria-required="true" name="name" class="form-control required name">
                                    </div>
                                     <div class="form-group col-sm-6">
                                        <label for="firstName"><?=L::blog_contact_prenom;?></label>
                                        <input type="text" aria-required="true" name="firstName" class="form-control required name">

										</div>
                                    <div class="form-group col-sm-6">
                                        <label for="email"><?=L::blog_contact_mail;?></label>
                                        <input type="email" aria-required="true" name="email" class="form-control required email">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="phone"><?=L::blog_contact_phone;?></label>
                                        <input type="phone" aria-required="true" name="phone" class="form-control required phone" placeholder="+32">
                                    </div>
                                    <div class="form-group col-sm-6">
		                                <div class="particulier">
											<label><input type="radio" name="type" value="particulier" checked><?=L::blog_contact_particulier;?></label>
										</div>
										<div class="professionnel">
											<label><input type="radio" name="type" value="professionnel"><?=L::blog_contact_pro;?></label>
										</div>
									</div>
									<div class="form-group col-sm-12 entreprise hidden">
	                                	<label for="entreprise"><?=L::blog_contact_societyname;?></label>
	                                	<input type="text" aria-required="true" name="entreprise" class="form-control">
	                                </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-12">
                                        <label for="subject"><?=L::blog_contact_subject;?></label>
                                        <input type="text" name="subject" class="form-control required">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="message"><?=L::blog_contact_message;?></label>
                                    <textarea type="text" name="message" rows="5" class="form-control required" placeholder="Votre message"></textarea>
                                </div>

                                <div class="g-recaptcha" data-sitekey="6LfqMFgUAAAAADlCo3L6lqhdnmmkNvoS-kx00BMi"></div>

                                <input type="text" class="hidden" name="antispam" value="" />

                                <button class="button green button-3d effect fill-vertical" type="submit" id="form-submit"><i class="fa fa-paper-plane"></i>&nbsp;<?=L::blog_contact_send_btn;?></button>
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

                                                    gtag('event', 'send', {
                                                      'event_category': 'mail',
                                                      'event_label': 'contact.php'
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
			<!--
			<div class="modal-footer">
				<button type="button" class="btn btn-b" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-b">Save Changes</button>
			</div>
			-->
		</div>
	</div>
</div>

</body>

</html>
