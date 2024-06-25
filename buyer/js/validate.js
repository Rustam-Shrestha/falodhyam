
document.getElementById("username").addEventListener("input", validateUsername);
document.getElementById("email").addEventListener("input", validateEmail);
document.getElementById("phone").addEventListener("input", validatePhone);
document.getElementById("password").addEventListener("input", validatePassword);

function validateUsername() {
    var usernameField = document.getElementById("username");
    var errorUsername = document.getElementById("errorname");

    var username = usernameField.value.trim();

    if (username.length < 4) {
        errorUsername.textContent = "Username must be at least 4 characters long";
        errorUsername.style.color = "red";
        return false;
    } else {
        errorUsername.textContent = "";
        return true;
    }
}

function validateEmail() {
    var emailField = document.getElementById("email");
    var errorEmail = document.getElementById("erroremail");

    var email = emailField.value.trim();

    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailRegex.test(email)) {
        errorEmail.textContent = "Enter a valid email address";
        errorEmail.style.color = "red";
        return false;
    } else {
        errorEmail.textContent = "";
        return true;
    }
}

function validatePhone() {
    var phoneField = document.getElementById("phone");
    var errorPhone = document.getElementById("errorphone");

    var phoneNumber = phoneField.value.trim();

    var phoneRegex = /^\d{10}$/;

    if (!phoneRegex.test(phoneNumber)) {
        errorPhone.textContent = "Enter a valid 10-digit phone number";
        errorPhone.style.color = "red";
        return false;
    } else {
        errorPhone.textContent = "";
        return true;
    }
}

function validatePassword() {
    var passwordField = document.getElementById("password");
    var errorText = document.getElementById("errortext");

    var password = passwordField.value;

    var minLength = /.{8,}/;
    var hasLowerCase = /[a-z]/;
    var hasUpperCase = /[A-Z]/;
    var hasNumber = /\d/;
    var hasSpecialChar = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;

    var isValid = minLength.test(password) &&
        hasLowerCase.test(password) &&
        hasUpperCase.test(password) &&
        hasNumber.test(password) &&
        hasSpecialChar.test(password);

    if (isValid) {
        errorText.textContent = "Good to go";
        errorText.style.color = "green";
    } else {
        errorText.textContent = "Password requirements:\n" +
            " - At least 8 characters\n" +
            " - One lowercase letter\n" +
            " - One uppercase letter\n" +
            " - One number\n" +
            " - One special character";
        errorText.style.color = "red";
    }

    return isValid;
}

document.getElementById('signup-form').addEventListener('submit', function (event) {
    var isUsernameValid = validateUsername();
    var isEmailValid = validateEmail();
    var isPhoneValid = validatePhone();
    var isPasswordValid = validatePassword();
    
    if (!(isUsernameValid && isEmailValid && isPhoneValid && isPasswordValid)) {
        event.preventDefault();
        alert('Please fix the errors in the form before submitting.');
    }
});
