function googleTranslateElementInit() {
    new google.translate.TranslateElement(
        {
            pageLanguage: "en",
            autoDisplay: false,
        },
        "google_translate_element",
    );

    // Restore previously selected language
    setTimeout(function () {
        const selectedLanguage = localStorage.getItem("selectedLanguage");
        const selectedLanguageName = localStorage.getItem(
            "selectedLanguageName",
        );

        if (selectedLanguage) {
            changeLanguage(selectedLanguage);
        }
        if (selectedLanguageName) {
            updateCurrentLanguage(selectedLanguageName);
        }
    }, 1000);
}

function changeLanguage(lang) {
    const googleSelect = document.querySelector(".goog-te-combo");

    if (!googleSelect) {
        console.log("Waiting for Google Translate...");

        // Try again after Google Translate loads
        setTimeout(function () {
            changeLanguage(lang);
        }, 500);

        return;
    }

    googleSelect.value = lang;

    googleSelect.dispatchEvent(
        new Event("change", {
            bubbles: true,
        }),
    );
}

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".language-switcher").forEach(function (link) {
        link.addEventListener("click", function (e) {
            e.preventDefault();

            const language = this.dataset.language;
            const languageName = this.dataset.languageName;

            // Save language
            localStorage.setItem("selectedLanguage", language);
            localStorage.setItem("selectedLanguageName", languageName);

            // Change language
            changeLanguage(language);
            updateCurrentLanguage(languageName);
        });
    });
});

function updateCurrentLanguage(languageName) {
    const currentLang = document.getElementById("current-lang");

    if (currentLang) {
        currentLang.textContent = languageName;
    }
}

document
    .getElementById("languageSwitcher")
    ?.addEventListener("change", function () {
        changeLanguage(this.value);
    });

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("form").forEach(function (form) {
        form.addEventListener("submit", function () {
            const button = form.querySelector("[data-loading-button]");

            if (!button) {
                return;
            }

            const spinner = button.querySelector("[data-spinner]");
            const buttonText = button.querySelector("[data-button-text]");
            const loaderText = button.dataset.loaderText;

            // Disable button
            button.disabled = true;

            // Show spinner
            if (spinner) {
                spinner.classList.remove("d-none");
            }

            // Change button text
            if (buttonText) {
                buttonText.textContent = loaderText;
            }
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const countrySelect = document.getElementById("country");
    const stateSelect = document.getElementById("state");
    const citySelect = document.getElementById("city");

    // Agar dropdowns current page par nahi hain
    if (!countrySelect || !stateSelect || !citySelect) {
        return;
    }

    // Agar URLs defined nahi hain
    if (
        typeof stateListsUrl === "undefined" ||
        typeof citiesListsUrl === "undefined"
    ) {
        return;
    }

    const selectedState = stateSelect.dataset.selected || "";
    const selectedCity = citySelect.dataset.selected || "";

    /**
     * Load States
     */
    async function loadStates(countryId, selectedId = null) {
        stateSelect.disabled = true;
        stateSelect.innerHTML = '<option value="">Loading...</option>';

        citySelect.disabled = true;
        citySelect.innerHTML = '<option value="">Choose One</option>';

        if (!countryId) {
            stateSelect.innerHTML = '<option value="">Choose One</option>';
            stateSelect.disabled = false;
            citySelect.disabled = false;
            return;
        }

        try {
            const response = await fetch(
                `${stateListsUrl}?country=${encodeURIComponent(countryId)}`,
            );

            if (!response.ok) {
                throw new Error("Failed to load states");
            }

            const states = await response.json();

            stateSelect.innerHTML = '<option value="">Choose One</option>';

            states.forEach(function (state) {
                const option = document.createElement("option");

                option.value = state.id;
                option.textContent = state.name;

                if (String(state.id) === String(selectedId)) {
                    option.selected = true;
                }

                stateSelect.appendChild(option);
            });
        } catch (error) {
            console.error("State loading error:", error);

            stateSelect.innerHTML =
                '<option value="">Unable to load states</option>';
        } finally {
            stateSelect.disabled = false;
            citySelect.disabled = false;
        }
    }

    /**
     * Load Cities
     */
    async function loadCities(stateId, selectedId = null) {
        citySelect.disabled = true;
        citySelect.innerHTML = '<option value="">Loading...</option>';

        if (!stateId) {
            citySelect.innerHTML = '<option value="">Choose One</option>';

            citySelect.disabled = false;
            return;
        }

        try {
            const response = await fetch(
                `${citiesListsUrl}?state=${encodeURIComponent(stateId)}`,
            );

            if (!response.ok) {
                throw new Error("Failed to load cities");
            }

            const cities = await response.json();

            citySelect.innerHTML = '<option value="">Choose One</option>';

            cities.forEach(function (city) {
                const option = document.createElement("option");

                option.value = city.id;
                option.textContent = city.name;

                if (String(city.id) === String(selectedId)) {
                    option.selected = true;
                }

                citySelect.appendChild(option);
            });
        } catch (error) {
            console.error("City loading error:", error);

            citySelect.innerHTML =
                '<option value="">Unable to load cities</option>';
        } finally {
            citySelect.disabled = false;
        }
    }

    /**
     * Country Change
     */
    countrySelect.addEventListener("change", function () {
        loadStates(this.value);

        citySelect.innerHTML = '<option value="">Choose One</option>';
    });

    /**
     * State Change
     */
    stateSelect.addEventListener("change", function () {
        loadCities(this.value);
    });

    /**
     * Edit Page
     * Load Preselected State & City
     */
    if (countrySelect.value) {
        loadStates(countrySelect.value, selectedState).then(function () {
            if (selectedState) {
                loadCities(selectedState, selectedCity);
            }
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    if (typeof imageInput !== "undefined") {
        imageInput?.addEventListener("change", function (event) {
            const file = event.target.files[0];
            if (file) {
                imagePreview.src = URL.createObjectURL(file);
            }
        });
    }
});


// Only String
document.querySelectorAll('.only-string').forEach(input => {
    input.addEventListener('input', function () {
        this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
    });
});

// Only Number
document.querySelectorAll('.only-number').forEach(input => {
    input.addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '');
    });
});

// Email Validation
document.querySelectorAll('.only-email').forEach(input => {
    input.addEventListener('input', function () {

        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const error = this.nextElementSibling;

        if (this.value === '' || regex.test(this.value)) {
            error.innerText = '';
        } else {
            error.innerText = 'Invalid email address';
        }
    });
});