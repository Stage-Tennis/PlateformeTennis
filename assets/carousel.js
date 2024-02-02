import Swiper from "swiper";
import "swiper/css";

document.addEventListener("DOMContentLoaded", function () {
  new Swiper(".swiper-container", {
    loop: true,
    speed: 300,
    effect: "fade",
    autoplay: {
      delay: 2500,
    },
    pagination: {
      el: ".swiper-pagination",
    },
    navigation: {
      nextEl: "#swiper-button-next",
      prevEl: "#swiper-button-prev",
    },
  });
});
