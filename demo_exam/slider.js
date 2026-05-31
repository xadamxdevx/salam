let slides = document.querySelectorAll(".slide");
let currentSlide = 0;

function showSlide(index) {
    slides[currentSlide].classList.remove("active");
    currentSlide = index;
    slides[currentSlide].classList.add("active");
}

document.querySelector(".next").onclick = function () {
    let nextSlide = currentSlide + 1;

    if (nextSlide >= slides.length) {
        nextSlide = 0;
    }

    showSlide(nextSlide);
};

document.querySelector(".prev").onclick = function () {
    let prevSlide = currentSlide - 1;

    if (prevSlide < 0) {
        prevSlide = slides.length - 1;
    }

    showSlide(prevSlide);
};

setInterval(function () {
    let nextSlide = currentSlide + 1;

    if (nextSlide >= slides.length) {
        nextSlide = 0;
    }

    showSlide(nextSlide);
}, 3000);