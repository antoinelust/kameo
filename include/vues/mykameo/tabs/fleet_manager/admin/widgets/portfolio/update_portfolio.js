jQuery("#widget-updateCatalog-form").validate({
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
          listPortfolioBikes();
          document.getElementById("widget-updateCatalog-form").reset();
          $("#updatePortfolioBike").modal("toggle");
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
jQuery("#widget-deletePortfolioBike-form").validate({
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
          listPortfolioBikes();
          document.getElementById("widget-updateCatalog-form").reset();
          $("#updatePortfolioBike").modal("toggle");
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
