document.addEventListener("DOMContentLoaded", function () {
  // Accéder aux champs de texte
  var firstnameField = document.getElementById("firstname");
  var lastnameField = document.getElementById("lastname");
  var emailField = document.getElementById("email");
  var messageField = document.getElementById("message");

  // Accéder à l'élément qui affiche le nombre de caractères pour le message
  var characterCount = document.getElementById("characterCount");

  // Écouteur pour le champ 'firstname'
  firstnameField.addEventListener("input", function () {
    if (firstnameField.value.length > 55) {
      firstnameField.value = firstnameField.value.substring(0, 55); // Limiter à 55 caractères
      alert("La limite de 55 caractères pour le nom a été atteinte.");
    }
  });

  // Écouteur pour le champ 'lastname'
  lastnameField.addEventListener("input", function () {
    if (lastnameField.value.length > 55) {
      lastnameField.value = lastnameField.value.substring(0, 55); // Limiter à 55 caractères
      alert("La limite de 55 caractères pour le prénom a été atteinte.");
    }
  });

  // Écouteur pour le champ 'email'
  emailField.addEventListener("input", function () {
    if (emailField.value.length > 180) {
      emailField.value = emailField.value.substring(0, 180); // Limiter à 180 caractères
      alert("La limite de 180 caractères pour l'email a été atteinte.");
    }
  });

  // Écouteur pour le champ 'message'
  messageField.addEventListener("input", function () {
    var currentLength = messageField.value.length; // Obtenir la longueur actuelle du texte
    characterCount.textContent = currentLength; // Mettre à jour le compteur de caractères

    if (currentLength > 1024) {
      messageField.value = messageField.value.substring(0, 1024); // Limiter à 1024 caractères
      alert("La limite de 1024 caractères a été atteinte.");
    }
  });
});
