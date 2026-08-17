const loginForm = document.getElementById("loginForm");
const loginBtn = document.getElementById("loginBtn");
const loaderBtn = document.getElementById("loaderBtn");


const resendLink = document.getElementById("resendLink");
const resendText = document.getElementById("resendText");
const resendLoader = document.getElementById("resendLoader");
const countdownEl = document.getElementById("countdown");

function startCountdown(seconds) {
    resendLink.style.pointerEvents = "none";
    resendLink.style.opacity = "0.5";
    countdownEl.classList.remove("d-none");

    let timeLeft = seconds;

    countdownEl.innerHTML = `(${timeLeft}s)`;

    const interval = setInterval(() => {
        timeLeft--;

        countdownEl.innerHTML = `(${timeLeft}s)`;

        if (timeLeft <= 0) {
            clearInterval(interval);

            resendLink.style.pointerEvents = "auto";
            resendLink.style.opacity = "1";

            countdownEl.classList.add("d-none");
        }
    }, 1000);
}

function resendOtp() {
    resendText.classList.add("d-none");
    resendLoader.classList.remove("d-none");

    return true;
}


loginForm?.addEventListener("submit", function () {
    loginBtn.style.display = "none";
    loaderBtn.style.display = "block";
});