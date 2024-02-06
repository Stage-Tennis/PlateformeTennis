document.addEventListener("DOMContentLoaded", function () {
  const carrouselInner = document.getElementById("carrouselInner");
  const imageContainers = carrouselInner.querySelectorAll("div.flex-none"); // Sélectionne toutes les divs, y compris les images dupliquées
  let currentImageIndex = 0;
  const imageWidth = imageContainers[0].offsetWidth; // La largeur d'une image

  function resetCarrouselPosition() {
    carrouselInner.style.transition = "none"; // Désactive la transition pour le saut instantané
    carrouselInner.style.transform = "translateX(0)"; // Réinitialise à la position de départ
    currentImageIndex = 0; // Réinitialise l'index à la première image

    // Force le navigateur à reconnaître la suppression de la transition avant de la réactiver
    setTimeout(() => {
      carrouselInner.style.transition = "transform ease-out 700ms"; // Réactive la transition
    }, 50); // Un petit délai pour s'assurer que la transition est bien désactivée avant de la réactiver
  }

  setInterval(function () {
    currentImageIndex++;

    // Vérifie si le carrousel a atteint les images dupliquées
    if (currentImageIndex >= imageContainers.length - 3) {
      // -3 pour les images dupliquées à la fin
      // Utilise une fonction séparée pour la clarté et la réutilisabilité
      resetCarrouselPosition();
    } else {
      carrouselInner.style.transform = `translateX(-${
        currentImageIndex * imageWidth
      }px)`;
    }
  }, 3000); // Change l'image toutes les 3 secondes
});
