document.addEventListener("DOMContentLoaded", function () {
  let slideIndex = 0;
  const intervalTime = 3500;
  let slideInterval;

  function changeSlide(step) {
    const slides = document.querySelector(".carousel-slide").children;
    const totalSlides = slides.length;

    // Masquer la div correspondante de l'image actuelle
    hideCorrespondingContent();

    slideIndex += step;

    if (slideIndex >= totalSlides) {
      slideIndex = 0;
    } else if (slideIndex < 0) {
      slideIndex = totalSlides - 1;
    }

    document.querySelector(".carousel-slide").style.transform = `translateX(-${
      slideIndex * 100
    }%)`;

    // Afficher la div correspondante de la nouvelle image
    displayCorrespondingContent();
  }

  function displayCorrespondingContent() {
    const slides = document.querySelector(".carousel-slide").children;
    const currentSlide = slides[slideIndex];
    const contentId = currentSlide.getAttribute("data-content-id");

    // Afficher la div correspondante
    const correspondingDiv = document.getElementById(contentId);
    if (correspondingDiv) {
      correspondingDiv.style.display = "block";
    }
  }

  function hideCorrespondingContent() {
    const slides = document.querySelector(".carousel-slide").children;
    const currentSlide = slides[slideIndex];
    const contentId = currentSlide.getAttribute("data-content-id");

    // Masquer la div correspondante de l'image actuelle
    const correspondingDiv = document.getElementById(contentId);
    if (correspondingDiv) {
      correspondingDiv.style.display = "none";
    }
  }

  function startAutoplay() {
    slideInterval = setInterval(() => {
      changeSlide(1);
    }, 3500);
  }

  function stopAutoplay() {
    clearInterval(slideInterval);
  }

  function resetAutoplay() {
    stopAutoplay();
    startAutoplay();
  }

  document.querySelector(".prev").addEventListener("click", () => {
    changeSlide(-1);
    resetAutoplay();
  });

  document.querySelector(".next").addEventListener("click", () => {
    changeSlide(1);
    resetAutoplay();
  });

  document
    .querySelector(".carousel-container")
    .addEventListener("mouseenter", stopAutoplay);
  document
    .querySelector(".carousel-container")
    .addEventListener("mouseleave", startAutoplay);

  // Initialiser la div de contenu correspondante lors du chargement de la page
  displayCorrespondingContent();

  startAutoplay();
});
