document.addEventListener("DOMContentLoaded", function () {
  // Sélection des éléments du formulaire et des éléments pour afficher les messages d'erreur
  const licenseSerial = document.querySelector("input[name='license_serial']");
  const licenseSerialError = document.getElementById("licenseSerialError");
  const name = document.querySelector("input[name='name']");
  const nameError = document.getElementById("nameError");
  const surname = document.querySelector("input[name='surname']");
  const surnameError = document.getElementById("surnameError");
  const civility = document.querySelector("select[name='civility']");
  const civilityError = document.getElementById("civilityError");
  const birthdate = document.querySelector("input[name='birthdate']");
  const birthdateError = document.getElementById("birthdateError");
  const email = document.querySelector("input[name='email']");
  const emailError = document.getElementById("emailError");
  const phone = document.querySelector("input[name='phone']");
  const phoneError = document.getElementById("phoneError");
  const address = document.querySelector("input[name='address']");
  const addressError = document.getElementById("addressError");
  const zipcode = document.querySelector("input[name='zipcode']");
  const zipcodeError = document.getElementById("zipcodeError");
  const city = document.querySelector("input[name='city']");
  const cityError = document.getElementById("cityError");
  const startTennis = document.querySelector("input[name='startTennis']");
  const startTennisError = document.getElementById("startTennisError");

  if (startTennis.length > 4) {
    e.preventDefault();
    startTennis = startTennis.slice(0, 4);
  }

  // Fonctions de validation
  function validateField(field, validator, errorMessage, errorElement) {
    const isValid = validator(field.value);
    errorElement.textContent = isValid ? "" : errorMessage;
    errorElement.style.display = isValid ? "none" : "block";
    return isValid;
  }

  function isValidLicenseSerial(serial) {
    return /^[0-9]{7}[A-Za-z]$/.test(serial);
  }

  function isValidNameOrSurname(value) {
    return value.length >= 1 && value.length <= 55;
  }

  function isValidCivility(value) {
    return !isNaN(value) && value.trim() !== "";
  }

  function isValidBirthdate(value) {
    return value.trim() !== "";
  }

  function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  function isValidPhone(phone) {
    return phone.length === 10 && /^[0-9]+$/.test(phone);
  }

  function isValidAddress(address) {
    return address.length >= 1 && address.length <= 255;
  }

  function isValidZipcode(zipcode) {
    return zipcode.length === 5 && /^[0-9]+$/.test(zipcode);
  }

  function isValidCity(city) {
    return city.length >= 1 && city.length <= 128;
  }

  function isValidStartTennis(year) {
    return (
      year >= 1940 &&
      year <= 2099 &&
      !isNaN(year) &&
      year.toString().length === 4
    );
  }

  // Gestionnaires d'événements pour la validation
  licenseSerial.addEventListener("input", () =>
    validateField(
      licenseSerial,
      isValidLicenseSerial,
      "Le numéro de licence doit comporter 7 chiffres suivis d'une lettre.",
      licenseSerialError
    )
  );
  name.addEventListener("input", () =>
    validateField(
      name,
      isValidNameOrSurname,
      "Le prénom doit contenir entre 1 et 55 caractères.",
      nameError
    )
  );
  surname.addEventListener("input", () =>
    validateField(
      surname,
      isValidNameOrSurname,
      "Le nom doit contenir entre 1 et 55 caractères.",
      surnameError
    )
  );
  civility.addEventListener("change", () =>
    validateField(
      civility,
      isValidCivility,
      "La civilité doit être sélectionnée.",
      civilityError
    )
  );
  birthdate.addEventListener("change", () =>
    validateField(
      birthdate,
      isValidBirthdate,
      "La date d'anniversaire doit être remplie.",
      birthdateError
    )
  );
  email.addEventListener("input", () =>
    validateField(
      email,
      isValidEmail,
      "L'adresse email doit avoir un format valide.",
      emailError
    )
  );
  phone.addEventListener("input", () =>
    validateField(
      phone,
      isValidPhone,
      "Le numéro de téléphone doit contenir 10 chiffres.",
      phoneError
    )
  );
  address.addEventListener("input", () =>
    validateField(
      address,
      isValidAddress,
      "L'adresse doit contenir entre 1 et 255 caractères.",
      addressError
    )
  );
  zipcode.addEventListener("input", () =>
    validateField(
      zipcode,
      isValidZipcode,
      "Le code postal doit contenir 5 chiffres.",
      zipcodeError
    )
  );
  city.addEventListener("input", () =>
    validateField(
      city,
      isValidCity,
      "Le nom de la ville doit contenir entre 1 et 128 caractères.",
      cityError
    )
  );
  startTennis.addEventListener("input", () =>
    validateField(
      startTennis,
      isValidStartTennis,
      "L'année de début du tennis doit être comprise entre 1940 et 2099.",
      startTennisError
    )
  );

  // Fonction de validation globale du formulaire (appelée avant la soumission)
  function validateForm() {
    const fieldsAreValid = [
      validateField(
        licenseSerial,
        isValidLicenseSerial,
        "Le numéro de licence doit comporter 7 chiffres suivis d'une lettre.",
        licenseSerialError
      ),
      validateField(
        name,
        isValidNameOrSurname,
        "Le prénom doit contenir entre 1 et 55 caractères.",
        nameError
      ),
      validateField(
        surname,
        isValidNameOrSurname,
        "Le nom doit contenir entre 1 et 55 caractères.",
        surnameError
      ),
      validateField(
        civility,
        isValidCivility,
        "La civilité doit être sélectionnée.",
        civilityError
      ),
      validateField(
        birthdate,
        isValidBirthdate,
        "La date d'anniversaire doit être remplie.",
        birthdateError
      ),
      validateField(
        email,
        isValidEmail,
        "L'adresse email doit avoir un format valide.",
        emailError
      ),
      validateField(
        phone,
        isValidPhone,
        "Le numéro de téléphone doit contenir 10 chiffres.",
        phoneError
      ),
      validateField(
        address,
        isValidAddress,
        "L'adresse doit contenir entre 1 et 255 caractères.",
        addressError
      ),
      validateField(
        zipcode,
        isValidZipcode,
        "Le code postal doit contenir 5 chiffres.",
        zipcodeError
      ),
      validateField(
        city,
        isValidCity,
        "Le nom de la ville doit contenir entre 1 et 128 caractères.",
        cityError
      ),
      validateField(
        startTennis,
        isValidStartTennis,
        "L'année de début du tennis doit être comprise entre 1940 et 2099.",
        startTennisError
      ),
    ].every((isValid) => isValid);

    // Active ou désactive le bouton de soumission en fonction de la validité du formulaire
  }
});
