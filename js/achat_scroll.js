$(document).ready(function () {
  $(window).scroll(function () {
    sidebar = document.getElementById("achat_sidebar");
    btnPromo = document.getElementById("promo-btn-block");
    if (
      $(window).scrollTop() > 100 &&
      document.documentElement.scrollHeight > 1920
      && window.matchMedia("(min-width: 1200px)").matches
    ) {
      sidebar.classList.add("scroll");

      $(btnPromo).css({
        "position": "fixed",
        "top": sidebar.offsetHeight
      });

    } else if ($(window).scrollTop() <= 100) {
      sidebar.classList.remove("scroll");

      $(btnPromo).css({
        "position": "",
        "top": ""
      });

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

var resizeTimeout;
window.addEventListener('resize', function(event) {
  clearTimeout(resizeTimeout);
  resizeTimeout = setTimeout(function(){
    window.location.reload();
  }, 500);
});