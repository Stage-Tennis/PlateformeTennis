document.addEventListener("DOMContentLoaded", function () {
  const togglePasswordVisibility = () => {
    const passwordInput = document.getElementById("password");
    const passwordIcon = document.getElementById(
      "toggle-password-visibility"
    ).firstElementChild;
    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      passwordIcon.classList.replace("fa-eye", "fa-eye-slash");
    } else {
      passwordInput.type = "password";
      passwordIcon.classList.replace("fa-eye-slash", "fa-eye");
    }
  };

  document
    .getElementById("toggle-password-visibility")
    .addEventListener("click", togglePasswordVisibility);
});
