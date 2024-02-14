document.addEventListener("DOMContentLoaded", function () {
  const newPassword = document.getElementById(
    "change_password_form_plainPassword_first"
  );
  const repeatNewPassword = document.getElementById(
    "change_password_form_plainPassword_second"
  );

  const errorDiv = document.createElement("div");
  errorDiv.className = "text-red-600 mt-2";
  errorDiv.id = "passwordError";

  const successDiv = document.createElement("div");
  successDiv.className = "text-green-600 mt-2";
  successDiv.id = "passwordSuccess";

  function isValidPassword(newPassword) {
    const minLength = 12;
    const maxLength = 255;
    const regex =
      /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,255}$/;

    return (
      regex.test(newPassword) &&
      newPassword.length >= minLength &&
      newPassword.length <= maxLength
    );
  }

  document
    .getElementById("togglePassword")
    .addEventListener("change", function (e) {
      if (e.target.checked) {
        newPassword.type = "text";
        repeatNewPassword.type = "text";
      } else {
        newPassword.type = "password";
        repeatNewPassword.type = "password";
      }
    });

  newPassword.addEventListener("input", () =>
    updatePasswordConditions(newPassword.value, repeatNewPassword.value)
  );

  repeatNewPassword.addEventListener("input", () =>
    updatePasswordConditions(newPassword.value, repeatNewPassword.value)
  );

  updatePasswordConditions("", "");
  function updatePasswordConditions(newPassword, repeatNewPassword) {
    // Initialisation de toutes les conditions sur rouge
    document.getElementById("condition-length").style.color = "red";
    document.getElementById("condition-lowercase").style.color = "red";
    document.getElementById("condition-uppercase").style.color = "red";
    document.getElementById("condition-digit").style.color = "red";
    document.getElementById("condition-specialchar").style.color = "red";
    document.getElementById("condition-match").style.color = "red";

    // Mise à jour des conditions si elles sont remplies
    if (newPassword.length >= 12 && newPassword.length <= 255) {
      document.getElementById("condition-length").style.color = "green";
    }

    if (/[a-z]/.test(newPassword)) {
      document.getElementById("condition-lowercase").style.color = "green";
    }
    if (/[A-Z]/.test(newPassword)) {
      document.getElementById("condition-uppercase").style.color = "green";
    }
    if (/\d/.test(newPassword)) {
      document.getElementById("condition-digit").style.color = "green";
    }
    if (/[@$!%*?&]/.test(newPassword)) {
      document.getElementById("condition-specialchar").style.color = "green";
    }

    const a = newPassword === repeatNewPassword;
    console.log(
      `newPassword.value = ${newPassword}, repeatNewPassword.value = ${repeatNewPassword.value}`
    );
    const b = newPassword !== "";
    const c = repeatNewPassword !== "";

    console.log(`a = ${a}, b = ${b}, c = ${c}, a && b && c = ${a && b && c}`);
    if (a && b && c) {
      document.getElementById("condition-match").style.color = "green";
    }
  }
});
