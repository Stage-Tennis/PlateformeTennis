document.addEventListener("DOMContentLoaded", function () {
  const cards = document.querySelectorAll(".card");
  const modal = document.getElementById("modal");
  const modalImg1 = document.getElementById("modalImg1");
  const modalImg2 = document.getElementById("modalImg2");
  const closeModal = document.getElementById("closeModal");

  cards.forEach((card) => {
    card.addEventListener("click", function () {
      const img1 = card.getAttribute("modalimg1");
      const img2 = card.getAttribute("modalimg2");
      modalImg1.src = img1;
      modalImg2.src = img2;
      modal.classList.remove("hidden");
    });
  });

  closeModal.addEventListener("click", function () {
    modal.classList.add("hidden");
  });

  window.addEventListener("click", function (event) {
    if (event.target === modal) {
      modal.classList.add("hidden");
    }
  });
});
