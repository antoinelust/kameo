const consentBar = document.querySelector(".consent-bar");
const consentBtn = document.querySelector(".consent-btn");

consentBtn.addEventListener("click", () => {
    consentBar.classList.remove("active");
    localStorage.setItem("consentBarDisplayed", "true");
});

setTimeout(() => {
    if (!localStorage.getItem("consentBarDisplayed")) {
        consentBar.classList.add("active");
    }
}, 2000);