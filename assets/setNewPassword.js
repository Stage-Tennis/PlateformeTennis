document.addEventListener("DOMContentLoaded", function () {
  const newPassword = document.getElementById("newPassword");
  const repeatNewPassword = document.getElementById("repeatNewPassword");

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

  newPassword.addEventListener("input", function () {
    updatePasswordConditions(newPassword.value);
  });

  repeatNewPassword.addEventListener("input", function () {
    // Vérifier uniquement la correspondance des mots de passe lors de la modification de repeatNewPassword
    checkPasswordMatch(newPassword.value, repeatNewPassword.value);
  });

  function updatePasswordConditions(newPassword) {
    // Mise à jour des conditions basées sur newPassword
    document.getElementById("condition-length").style.color =
      newPassword.length >= 12 && newPassword.length <= 255 ? "green" : "red";
    document.getElementById("condition-lowercase").style.color = /[a-z]/.test(
      newPassword
    )
      ? "green"
      : "red";
    document.getElementById("condition-uppercase").style.color = /[A-Z]/.test(
      newPassword
    )
      ? "green"
      : "red";
    document.getElementById("condition-digit").style.color = /\d/.test(
      newPassword
    )
      ? "green"
      : "red";
    document.getElementById("condition-specialchar").style.color =
      /[@$!%*?&]/.test(newPassword) ? "green" : "red";

    // Vérification de la correspondance des mots de passe ici pour s'assurer qu'elle est vérifiée à chaque fois que newPassword est modifié
    checkPasswordMatch(newPassword, repeatNewPassword.value);
  }

  updatePasswordConditions(newPassword.value);

  function checkPasswordMatch(newPassword, repeatNewPassword) {
    // Mise à jour de la condition de correspondance des mots de passe
    // Assurez-vous que les deux champs ne sont pas vides et contiennent le même contenu
    if (newPassword.length > 0 && newPassword === repeatNewPassword) {
      document.getElementById("condition-match").style.color = "green";
    } else {
      document.getElementById("condition-match").style.color = "red";
    }
  }

  // Ajoutez cet écouteur d'événements au champ repeatNewPassword
  repeatNewPassword.addEventListener("input", function () {
    checkPasswordMatch(newPassword.value, repeatNewPassword.value);
  });

  // N'oubliez pas d'initialiser correctement lors du chargement de la page
  document.addEventListener("DOMContentLoaded", function () {
    checkPasswordMatch(newPassword.value, repeatNewPassword.value);
  });
});
