document.addEventListener("DOMContentLoaded", () => {
  checkCookie();
});

function setCookie(cname, cvalue, exdays) {
  let d = new Date();
  d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
  let expires = "expires=" + d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
  let name = cname + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(";");
  for (let i = 0; i < ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == " ") {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

function checkCookie() {
  tbcookie = getCookie("tb-popup");
  if (tbcookie === "") {
    setTimeout(() => {
      const popup = document.querySelector(".tb-popup");
      popup.classList.add("active");

      closeBtn = document.getElementById("popup-closeBtn");
      closeBtn.addEventListener("click", () => {
        popup.classList.remove("active");
        setCookie("tb-popup", "active", 1); // la 3ème valeure integer correspond au délais entre chaque affichage du popup après fermeture
      });
    }, 5000);
  }
}
