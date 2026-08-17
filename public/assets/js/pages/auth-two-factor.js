class TwoFactorAuth {
    constructor(t = ".two-factor") {
        this.container = document.querySelector(t);

        if (this.container) {
            this.inputs = Array.from(
                this.container.querySelectorAll("input")
            );

            if (this.inputs.length === 0) {
                console.error(
                    "TwoFactorAuth: No input fields found in the container."
                );
            } else {
                this.confirmBtn = this.container
                    .closest("form")
                    ?.querySelector('button[type="submit"]');

                this.init();
            }
        } else {
            console.error(
                `TwoFactorAuth: Container "${t}" not found.`
            );
        }
    }

    init() {
        this.inputs[0].focus();

        this.inputs.forEach((input, index) => {
            input.setAttribute("inputmode", "numeric");
            input.setAttribute("maxlength", "1");

            input.addEventListener("input", (event) =>
                this.handleInput(event, index)
            );

            input.addEventListener("keydown", (event) =>
                this.handleKeyDown(event, index)
            );

            // Paste event
            input.addEventListener("paste", (event) =>
                this.handlePaste(event, index)
            );
        });

        if (this.confirmBtn) {
            this.confirmBtn.addEventListener("click", (event) =>
                this.handleSubmit(event)
            );
        } else {
            console.warn(
                "TwoFactorAuth: Submit button not found."
            );
        }
    }

    handleInput(event, index) {
        let value = event.target.value.replace(/\D/g, "");

        if (value.length > 1) {
            value = value.charAt(0);
        }

        this.inputs[index].value = value;

        if (
            value &&
            index < this.inputs.length - 1
        ) {
            this.inputs[index + 1].focus();
        }
    }

    handleKeyDown(event, index) {
        if (
            event.key === "Backspace" &&
            !this.inputs[index].value &&
            index > 0
        ) {
            this.inputs[index - 1].focus();
        }
    }

    handlePaste(event, startIndex) {
        event.preventDefault();

        const pastedData = event.clipboardData
            .getData("text")
            .replace(/\D/g, "");

        if (!pastedData) {
            return;
        }

        // Paste starting from selected OTP box
        const digits = pastedData.split("");

        digits.forEach((digit, offset) => {
            const inputIndex = startIndex + offset;

            if (inputIndex < this.inputs.length) {
                this.inputs[inputIndex].value = digit;
            }
        });

        // Calculate last filled input
        const lastIndex = Math.min(
            startIndex + digits.length,
            this.inputs.length
        ) - 1;

        // Focus last filled input
        if (this.inputs[lastIndex]) {
            this.inputs[lastIndex].focus();
        }
    }

    handleSubmit(event) {
        event.preventDefault();

        const code = this.inputs
            .map((input) => input.value)
            .join("");

        if (
            /^\d+$/.test(code) &&
            code.length === this.inputs.length
        ) {
            verifyFormSubmit();
        } else {
            this.showError(
                "Please enter a valid verification code."
            );

            setTimeout(
                () => this.clearError(),
                3000
            );
        }
    }

    showError(message) {
        if (!this.errorSpan) {
            this.errorSpan =
                document.createElement("span");

            this.errorSpan.className =
                "text-danger d-block mb-3";

            this.inputs[0]
                .parentElement
                .insertAdjacentElement(
                    "afterend",
                    this.errorSpan
                );
        }

        this.errorSpan.textContent = message;
    }

    clearError() {
        if (this.errorSpan) {
            this.errorSpan.remove();
            this.errorSpan = null;
        }
    }
}

document.addEventListener(
    "DOMContentLoaded",
    () => {
        new TwoFactorAuth();
    }
);

function verifyFormSubmit() {
    const verifyForm =
        document.getElementById("verifyForm");

    if (verifyForm) {
        verifyForm.submit();
    }
}


/// RESEND OTP
document.addEventListener("DOMContentLoaded", function () {

    const resendBtn = document.getElementById("resendOtp");
    const resendText = document.getElementById("resendText");
    const resendLoader = document.getElementById("resendLoader");
    const countdown = document.getElementById("countdown");

    const STORAGE_KEY = "otp_resend_expiry";
    const TIMER_SECONDS = 60;

    let timer = null;

    // Start countdown
    function startCountdown(expiryTime) {

        clearInterval(timer);

        resendBtn.style.pointerEvents = "none";
        resendBtn.classList.add("text-muted");

        countdown.classList.remove("d-none");

        function updateTimer() {

            const currentTime = Date.now();

            const remainingSeconds = Math.ceil(
                (expiryTime - currentTime) / 1000
            );

            if (remainingSeconds <= 0) {

                clearInterval(timer);

                localStorage.removeItem(STORAGE_KEY);

                countdown.classList.add("d-none");

                resendBtn.style.pointerEvents = "auto";
                resendBtn.classList.remove("text-muted");

                resendText.textContent = "Resend";

                return;
            }

            countdown.textContent = `(${remainingSeconds}s)`;
        }

        updateTimer();

        timer = setInterval(updateTimer, 1000);
    }


    // Check existing timer on page load
    const savedExpiry = localStorage.getItem(STORAGE_KEY);

    if (savedExpiry) {

        const expiryTime = parseInt(savedExpiry);

        if (expiryTime > Date.now()) {

            startCountdown(expiryTime);

        } else {

            localStorage.removeItem(STORAGE_KEY);

        }
    }


    // Resend OTP click
    resendBtn.addEventListener("click", function () {

        // Show loader
        resendLoader.classList.remove("d-none");

        resendText.textContent = "Sending...";

        resendBtn.style.pointerEvents = "none";

        // Set expiry time
        const expiryTime =
            Date.now() + (TIMER_SECONDS * 1000);

        localStorage.setItem(
            STORAGE_KEY,
            expiryTime
        );

        // Browser Laravel resend route par jayega.
        // Page reload ke baad saved expiry se
        // countdown automatically continue hoga.

    });

});