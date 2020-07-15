<section class="content">
	<div class="container">
		<div class="row">
			<div class="post-content float-right col-md-9">
				<div class="post-item">
					<div class="post-content-details">
						<div class="heading heading text-left m-b-20">
							<h2 class="fr">Connexion à MyKameo</h2>
							<h2 class="en">Log-in to MyKameo</h2>
							<h2 class="nl">Log in op MyKameo</h2>
							<form id="re-connexion" class="form-transparent-grey" action="include/access_management.php" role="form" method="post">
								<div class="form-group">
									<label class="sr-only fr">Adresse mail</label>
									<label class="sr-only en">E-mail</label>
									<label class="sr-only nl">Mail</label>
									<input type="email" name="userID" class="form-control" placeholder="Adresse mail" autocomplete="username">
								</div>
								<div class="form-group m-b-5">
									<label class="sr-only fr">Mot de passe</label>
									<label class="sr-only en">Password</label>
									<label class="sr-only nl">Wachtwoord</label>
									<input type="password" name="password" class="form-control" placeholder="Mot de passe" autocomplete="current-password">
								</div>
								<div class="form-group form-inline text-left ">
									<a data-target="#lostPassword" data-toggle="modal" data-dismiss="modal" href="#" class="right fr"><small>Mot de passe oublié?</small></a>
									<a data-target="#lostPassword" data-toggle="modal" data-dismiss="modal" href="#" class="right nl"><small>Wachtwoord kwijt?</small></a>
									<a data-target="#lostPassword" data-toggle="modal" data-dismiss="modal" href="#" class="right en"><small>Password lost?</small></a>
								</div>
								<div class="text-left form-group">
									<button class="button effect fill fr" type="submit">Accéder</button>
									<button class="button effect fill en" type="submit">Confirm</button>
									<button class="button effect fill nl" type="submit">Bevestingen</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<script type="text/javascript">
	jQuery("#re-connexion").validate({
		submitHandler: function(form) {
			jQuery(form).ajaxSubmit({
				success: function(text) {
					if (text.response == 'success') {
						<?php
						if (isset($_GET['feedback'])) {
							?> window.location.href = "<?php echo "
							mykameo.php?feedback=".$_GET['feedback']; ?>"; <?php
						} else {
							?> window.location.href = "mykameo.php"; <?php
						} ?>
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