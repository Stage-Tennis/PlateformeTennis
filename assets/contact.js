document.addEventListener("DOMContentLoaded", function () {
  var firstnameField = document.getElementById("firstname");
  var lastnameField = document.getElementById("lastname");
  var emailField = document.getElementById("email");
  var messageField = document.getElementById("message");

  var characterCount = document.getElementById("characterCount");

  firstnameField.addEventListener("input", function () {
    if (firstnameField.value.length > 55) {
      firstnameField.value = firstnameField.value.substring(0, 55);
      alert("La limite de 55 caractères pour le nom a été atteinte.");
    }
  });

  lastnameField.addEventListener("input", function () {
    if (lastnameField.value.length > 55) {
      lastnameField.value = lastnameField.value.substring(0, 55);
      alert("La limite de 55 caractères pour le prénom a été atteinte.");
    }
  });

  emailField.addEventListener("input", function () {
    if (emailField.value.length > 180) {
      emailField.value = emailField.value.substring(0, 180);
      alert("La limite de 180 caractères pour l'email a été atteinte.");
    }
  });

  messageField.addEventListener("input", function () {
    var currentLength = messageField.value.length;
    characterCount.textContent = currentLength;

    if (currentLength > 1024) {
      messageField.value = messageField.value.substring(0, 1024);
      alert("La limite de 1024 caractères a été atteinte.");
    }
  });
});
