jQuery("#re-connexion").validate({
	submitHandler: function(form) {
		jQuery(form).ajaxSubmit({
			success: function(text) {
				if (text.response == 'success') {
					if (feedback != '') {
						window.location.href = "mykameo.php?feedback=" + feedback;
					} else {
						window.location.href = "mykameo.php";
					}
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
