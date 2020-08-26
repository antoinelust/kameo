document.addEventListener("DOMContentLoaded", () => {
    const consentBar = document.querySelector(".consent-bar");
    const consentBtn = document.querySelector(".consent-btn");
    const refuseBtn = document.querySelector(".refuse-btn");

    consentBtn.addEventListener("click", () => {
        consentBar.classList.remove("active");
        localStorage.setItem("ConsentAuthorization", "accepted");
    })

    refuseBtn.addEventListener("click", () => {
        consentBar.classList.remove("active");
        localStorage.setItem("ConsentAuthorization", "refused");
    })

    setTimeout(() => {
        if (!localStorage.getItem("ConsentAuthorization")) {
            consentBar.classList.add("active");
        }
    }, 2000);
});