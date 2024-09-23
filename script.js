// File path: /mnt/data/script.js

// Function to set a cookie
function setCookie(name, value, days) {
  // Create a new Date object
  const date = new Date();
  // Set the expiration time for the cookie
  date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
  // Convert the expiration date to UTC string
  const expires = "expires=" + date.toUTCString();
  // Set the cookie with name, value, expiration, and path
  document.cookie = name + "=" + value + ";" + expires + ";path=/";
}

// Function to get a cookie
function getCookie(name) {
  // Prepare the cookie name
  const cname = name + "=";
  // Decode the cookie to handle special characters
  const decodedCookie = decodeURIComponent(document.cookie);
  // Split the decoded cookie string into an array of cookies
  const ca = decodedCookie.split(";");
  // Iterate through each cookie in the array
  for (let i = 0; i < ca.length; i++) {
    let c = ca[i];
    // Remove leading spaces in cookie string
    while (c.charAt(0) === " ") {
      c = c.substring(1);
    }
    // If the cookie name matches, return its value
    if (c.indexOf(cname) === 0) {
      return c.substring(cname.length, c.length);
    }
  }
  // Return an empty string if the cookie is not found
  return "";
}

// Apply theme based on cookie
document.addEventListener("DOMContentLoaded", function () {
  // Get the theme toggle button and body element
  const themeToggle = document.getElementById("theme-toggle");
  const body = document.body;
  // Get the saved theme from the cookie
  const savedTheme = getCookie("theme");

  // If a theme is saved in the cookie, apply it
  if (savedTheme) {
    body.classList.add(savedTheme);
    // Update the theme toggle button text based on the current theme
    themeToggle.textContent =
      savedTheme === "light-theme" ? "Change to Dark" : "Change to Light";
  } else {
    // Apply the default light theme if no theme is saved
    body.classList.add("light-theme");
  }

  // Toggle between light and dark themes on button click
  themeToggle.addEventListener("click", function () {
    if (body.classList.contains("light-theme")) {
      // Switch to dark theme
      body.classList.replace("light-theme", "dark-theme");
      themeToggle.textContent = "Change to Light";
      // Set dark theme in the cookie with 30 days expiration
      setCookie("theme", "dark-theme", 30);
    } else {
      // Switch to light theme
      body.classList.replace("dark-theme", "light-theme");
      themeToggle.textContent = "Change to Dark";
      // Set light theme in the cookie with 30 days expiration
      setCookie("theme", "light-theme", 30);
    }
  });

  // Accordion functionality
  const accordionHeaders = document.querySelectorAll(".accordion-header");
  accordionHeaders.forEach((header) => {
    header.addEventListener("click", function () {
      const accordionContent = header.nextElementSibling;
      const isVisible = accordionContent.style.display === "block";
      accordionContent.style.display = isVisible ? "none" : "block";
    });
  });

  // Close cookie consent on button click
  const cookieConsentClose = document.getElementById("cookie-consent-close");
  const cookieConsent = document.querySelector(".cookie-consent");

  if (cookieConsentClose) {
    cookieConsentClose.addEventListener("click", function () {
      cookieConsent.style.display = "none";
    });
  }

  // Validate password input
  const passwordInput = document.getElementById("password");
  const passwordError = document.getElementById("password-error");

  passwordInput.addEventListener("input", function () {
    // Regular expression pattern for password validation
    const pattern = /^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$/;
    // Check if the input matches the pattern
    if (!pattern.test(passwordInput.value)) {
      passwordError.style.display = "block";
    } else {
      passwordError.style.display = "none";
    }
  });

  // Validate password on form submission
  const signupForm = document.getElementById("signup-form");
  signupForm.addEventListener("submit", function (event) {
    if (
      !passwordInput.value.match(
        /^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$/
      )
    ) {
      // Prevent form submission and display password error
      event.preventDefault();
      passwordError.style.display = "block";
    }
  });
});
