import Swiper from "swiper";
import "swiper/css";

console.log("Enculé");

console.log(document.querySelector(".swiper"));
const swiper = new Swiper("#swiper-container", {
    direction: 'vertical',
    loop: true,

    // Navigation arrows
    navigation: {
        nextEl: '.swiper-next',
        prevEl: '.swiper-prev',
    },
});