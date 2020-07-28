jQuery("#re-connexion").validate({
	submitHandler: function(form) {
		var url = form.action;
		$.ajax({
         type: "POST",
         url: url,
		 data: { userID: document.getElementById('user_email2').value, password: /*nacl.util.encodeBase64(nacl.hash(nacl.util.decodeUTF8(*/document.getElementById('user_password2').value/*)))*/ },
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
			