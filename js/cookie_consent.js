document.addEventListener("DOMContentLoaded", () => {
  if (!localStorage.getItem("ConsentAuthorization")) {
    setTimeout(() => {
      const consentBar = document.querySelector(".consent-bar");
      const consentBtn = document.querySelector(".consent-btn");
      const refuseBtn = document.querySelector(".refuse-btn");

      consentBar.classList.add("active");

      consentBtn.addEventListener("click", () => {
        consentBar.classList.remove("active");
        localStorage.setItem("ConsentAuthorization", "accepted");
      });

      refuseBtn.addEventListener("click", () => {
        consentBar.classList.remove("active");
        localStorage.setItem("ConsentAuthorization", "refused");
      });
    }, 2000);
  }
});
