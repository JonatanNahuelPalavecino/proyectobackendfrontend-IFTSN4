const slides = document.querySelectorAll(".slide");
const dots = document.querySelectorAll(".dot");
const previousButton = document.querySelector(".previous");
const nextButton = document.querySelector(".next");

let currentSlide = 0;
let interval;

function showSlide(index) {
    if (index >= slides.length) {
        currentSlide = 0;
    } else if (index < 0) {
        currentSlide = slides.length - 1;
    } else {
        currentSlide = index;
    }

    slides.forEach((slide, index) => {
        slide.classList.toggle("active", index === currentSlide);
    });

    dots.forEach((dot, index) => {
        dot.classList.toggle("active", index === currentSlide);
    });
}

function nextSlide() {
    showSlide(currentSlide + 1);
}

function previousSlide() {
    showSlide(currentSlide - 1);
}

function startSlider() {
    interval = setInterval(nextSlide, 5000);
}

function restartSlider() {
    clearInterval(interval);
    startSlider();
}

nextButton.addEventListener("click", () => {
    nextSlide();
    restartSlider();
});

previousButton.addEventListener("click", () => {
    previousSlide();
    restartSlider();
});

dots.forEach((dot) => {
    dot.addEventListener("click", () => {
        showSlide(Number(dot.dataset.slide));
        restartSlider();
    });
});

showSlide(0);
startSlider();