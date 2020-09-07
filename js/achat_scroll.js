$(document).ready(function () {
  $(window).scroll(function () {
    if ($(window).scrollTop() > 100 && document.documentElement.scrollHeight > 1920) {
      $("#achat_sidebar").css("position", "fixed");
      $("#achat_sidebar").css("top", "0");
    
    } else if ($(window).scrollTop() <= 100) {
      $("#achat_sidebar").css("position", "");
      $("#achat_sidebar").css("top", "");
    }
    
    if (
      $("#achat_sidebar").offset().top + $("#achat_sidebar").height() >
      $("#footer").offset().top
    ) {
      $("#achat_sidebar").css(
        "top",
        -(
          $("#achat_sidebar").offset().top +
          $("#achat_sidebar").height() -
          $("#footer").offset().top
        )
      );
    }
  });
});
