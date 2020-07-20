function initializeUpdate(){
	document.getElementById('widget-update-form-password-text').innerHTML="";
	document.getElementById('widget-update-form-password').readOnly = true;
	document.getElementById('widget-update-form-password').value="********";
	document.getElementById('widget-update-form-password-confirmation-text').innerHTML="";
	document.getElementById('widget-update-form-password-confirmation').type='hidden';
	document.getElementById('widget-update-form-password-switch').value="false";
}
function updatePassword(){
	document.getElementById('widget-update-form-password-text').innerHTML="<span class=\"fr\">Votre Nouveau mot de passe :</span><span class=\"nl\">Your new password :</span><span class=\"en\">Your new password:</span>";
	document.getElementById('widget-update-form-password').removeAttribute('readonly');
	document.getElementById('widget-update-form-password').value="";
	document.getElementById('widget-update-form-password-confirmation-text').innerHTML="<span class=\"fr\">Veuillez confirmer :</span><span class=\"nl\">Please confirm :</span><span class=\"en\">Please confirm:</span>";
	document.getElementById('widget-update-form-password-confirmation').type='password';
	document.getElementById('widget-update-form-password-switch').value="true";
	displayLanguage();
	var langue = getLanguage();
}
jQuery("#widget-updateInfo").validate({
	submitHandler: function(form) {
		jQuery(form).ajaxSubmit({
			success: function(text) {
				if (text.response == 'success') {
					$.notify({
						message: text.message
					}, {
						type: 'success'
					});
					$('#update').modal('toggle');
					var timestamp=Date.now().toString();
					addressDomicile = user_data['ADRESS'] + ", " + user_data['POSTAL_CODE'] + ", " + user_data['CITY'];
					get_meteo(timestamp.substring(0,10), addressDomicile);
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