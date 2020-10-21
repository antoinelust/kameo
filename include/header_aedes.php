<!-- HEADER -->
<header id="header" class="header-light">
  <div id="header-wrap">
	<div class="container">
	  <!--LOGO-->
	  <div id="logo">
		<img src="/images/aedes_logo.png" alt="Aedes Logo">
	  </div>
	  <!--END: LOGO-->
	  <!--MOBILE MENU -->
	  <div class="nav-main-menu-responsive">
		<button class="lines-button x" aria-label="menu">
		  <span class="lines"></span>
		</button>
	  </div>
	  <!--END: MOBILE MENU -->
	</div>
  </div>
</header>
<!-- END: HEADER -->

<div class="modal fade" id="mykameo" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<h3><?=L::header_mykameo;?></h3>
						<form id="user_management" class="form-transparent-grey" action="/apis/Kameo/access_management.php" role="form" method="post">
							<div class="form-group">
								<label class="sr-only"><?=L::header_mail;?></label>
								<input type="email" name="userID" class="form-control" id="userID" placeholder="Adresse mail" autocomplete="username">
							</div>
							<div class="form-group m-b-5">
								<label class="sr-only"><?=L::header_password;?></label>
								<input type="password" name="password" id="user_password" class="form-control" placeholder="Mot de passe" autocomplete="current-password">
							</div>
							<div class="form-group form-inline text-left ">
								<a data-target="#lostPassword" data-toggle="modal" data-dismiss="modal" href="#" class="right"><small><?=L::header_forgottenpass;?></small></a>
							</div>
							<div class="text-left form-group">
								<button class="button effect fill" type="submit"><?=L::header_goTo;?></button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--<script type="text/javascript" src="js/addons/tweetnacl/nacl-fast.min.js"></script>
<script type="text/javascript" src="js/addons/tweetnacl-util/nacl-util.min.js"></script>-->
<script type="text/javascript">
	jQuery("#user_management").validate({
		submitHandler: function(form) {
			var url = form.action;
			$.ajax({
			 type: "POST",
			 url: url,
			 data: { userID: document.getElementById('userID').value, password: /*nacl.util.encodeBase64(nacl.hash(nacl.util.decodeUTF8(*/document.getElementById('user_password').value/*)))*/ },
			 success: function(text) {
						if (text.response == 'success') {
									  window.location.href = "/mykameo";
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

<div class="modal fade" id="lostPassword" tabindex="-1" role="modal" aria-labelledby="modal-label" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<h3><?=L::header_forgottenpass;?></h3>
						<form id="widget-lostPassword" class="form-transparent-grey" action="apis/Kameo/lost_password.php" role="form" method="post">
							<div class="form-group">
								<label for="widget-update-form-email"><?=L::header_mail;?></label>
								<input type="text" name="widget-update-form-email" class="form-control required" autocomplete="username">
							</div>
							<div class="text-left form-group">
								<button  class="button effect fill" type="submit"><i class="fa fa-paper-plane"></i><?=L::header_send;?></button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	jQuery("#widget-lostPassword").validate({
		submitHandler: function(form) {
			jQuery(form).ajaxSubmit({
				success: function(text) {
					if (text.response == 'success') {
						$.notify({
							message: text.message
						}, {
							type: 'success'
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
