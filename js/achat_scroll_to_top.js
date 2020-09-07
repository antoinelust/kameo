scrollBtn = document.getElementById("btn_goto_top_catalog");

window.onscroll = function () {
  scrollFunction();
};

function scrollFunction() {
  if (document.body.scrollTop > 500 || document.documentElement.scrollTop > 500) {
    scrollBtn.classList.add("active");

  } else {
    scrollBtn.classList.remove("active");
  }
}

function topFunction() {
  window.scrollTo({ top: 100, behavior: 'smooth' });
}
