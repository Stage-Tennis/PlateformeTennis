document.addEventListener("DOMContentLoaded", function () {
  var validateBtn = document.getElementById("validateBtn");
  var confirmBtn = document.getElementById("confirmBtn");

  validateBtn.addEventListener("click", function () {
    validateBtn.style.display = "none";
    confirmBtn.style.display = "inline-block";
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("form");
  form.addEventListener("submit", function (event) {
    event.preventDefault();

    const email = document.querySelector('input[name="email"]').value;
    const name = document.querySelector('input[name="name"]').value;
    const surname = document.querySelector('input[name="surname"]').value;
    const address = document.querySelector('input[name="address"]').value;
    const city = document.querySelector('input[name="city"]').value;
    const zipcode = document.querySelector('input[name="zipcode"]').value;
    const phone = document.querySelector('input[name="phone"]').value;

    // Validation des champs
    if (email.length > 180) {
      alert("L'adresse mail doit contenir au maximum 180 caractères.");
      return;
    }
    if (name.length > 55) {
      alert("Le nom doit contenir au maximum 55 caractères.");
      return;
    }
    if (surname.length > 55) {
      alert("Le prénom doit contenir au maximum 55 caractères.");
      return;
    }
    if (address.length > 255) {
      alert("L'adresse doit contenir au maximum 255 caractères.");
      return;
    }
    if (city.length > 128) {
      alert("La ville doit contenir au maximum 128 caractères.");
      return;
    }
    if (!/^\d{5}$/.test(zipcode)) {
      alert("Le code postal doit contenir exactement 5 chiffres.");
      return;
    }
    if (!/^\d{10}$/.test(phone)) {
      alert("Le numéro de téléphone doit contenir exactement 10 chiffres.");
      return;
    }

    form.submit();
  });

  function addInputListener(selector, maxLength) {
    const inputElement = document.querySelector(selector);
    inputElement.addEventListener("input", function () {
      if (inputElement.value.length > maxLength) {
        alert(`La valeur saisie dépasse la limite de ${maxLength} caractères.`);
        inputElement.value = inputElement.value.substring(0, maxLength);
      }
    });
  }

  addInputListener('input[name="email"]', 180);
  addInputListener('input[name="name"]', 55);
  addInputListener('input[name="surname"]', 55);
  addInputListener('input[name="address"]', 255);
  addInputListener('input[name="city"]', 128);

  ['input[name="zipcode"]', 'input[name="phone"]'].forEach((selector) => {
    const inputElement = document.querySelector(selector);
    inputElement.addEventListener("keypress", (event) => {
      if (!(event.charCode >= 48 && event.charCode <= 57)) {
        event.preventDefault();
        alert("Veuillez saisir des chiffres uniquement.");
      }
    });

    if (selector === 'input[name="zipcode"]') {
      addInputListener(selector, 5);
    } else {
      addInputListener(selector, 10);
    }
  });
});
