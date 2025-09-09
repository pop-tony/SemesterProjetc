const texts = document.getElementsByClassName("texts");
const dropdown = document.getElementsByClassName("dropdown");

const slides = document.querySelectorAll(".slide");
let slideIndex = 0;
let intervalId = null;

document.addEventListener('DOMContentLoaded', initializeSlider);

function initializeSlider(){
    slides[slideIndex].classList.add("showSlide");
    intervalId = setInterval(showSlide, 5000);
    
}

function showSlide(){
    
    slideIndex ++;

    if(slideIndex == slides.length){
        slides[slideIndex - 1].classList.remove("showSlide");
        slideIndex = 0;
    }

    if(slideIndex == 0){
        slides[slideIndex].classList.add("showSlide");
    }
    else if(slideIndex > 0){
        slides[slideIndex - 1].classList.remove("showSlide");
        slides[slideIndex].classList.add("showSlide");
    }

}

function prevSlide(){
    clearInterval(intervalId);
    if(slideIndex > 0){
        slides[slideIndex].classList.remove("showSlide");
        slides[slideIndex - 1].classList.add("showSlide");
        slideIndex -= 1;
    }
    intervalId = setInterval(showSlide, 5000);
}

function nextSlide(){
    clearInterval(intervalId);
    if(slideIndex < slides.length - 1){
        slides[slideIndex].classList.remove("showSlide");
        slides[slideIndex + 1].classList.add("showSlide");
        slideIndex += 1;
    }
    intervalId = setInterval(showSlide, 5000);
}
/* if(slideIndex == 0){
        slides[slideIndex].classList.add("showSlide");
    }
    else if(slideIndex > 0){
        slides[slideIndex - 1].classList.remove("showSlide");
        slides[slideIndex].classList.add("showSlide");
    }
*/