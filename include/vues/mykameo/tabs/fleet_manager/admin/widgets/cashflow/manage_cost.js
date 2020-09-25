jQuery("#widget-costsManagement-form").validate({
  submitHandler: function (form) {
    jQuery(form).ajaxSubmit({
      success: function (response) {
        if (response.response == "success") {
          $.notify(
            {
              message: response.message,
            },
            {
              type: "success",
            }
          );
          list_contracts_offers("*");
          document.getElementById("widget-costsManagement-form").reset();
          $("#costsManagement").modal("toggle");
        } else {
          $.notify(
            {
              message: response.message,
            },
            {
              type: "danger",
            }
          );
        }
      },
    });
  },
});

$("#widget-costsManagement-form select[name=type]").change(function () {
  if ($("#widget-costsManagement-form select[name=type]").val() == "one-shot") {
    $("#widget-costsManagement-form input[name=end]").val("");
    $("#widget-costsManagement-form input[name=end]").attr("readonly", true);
  }
  if ($("#widget-costsManagement-form select[name=type]").val() == "monthly") {
    $("#widget-costsManagement-form input[name=end]").attr("readonly", false);
  }
});
