let toggler = document.getElementById("toggler");
let menus = document.getElementsByClassName("navbar");
toggler.addEventListener("click", () => {
    // sliding all the available menus
    for (let num = 0; num < menus.length; i++) {
        menus[num].classList.toggle("show");
    }
});



let slideIndex = 0;
const slides = document.querySelectorAll(".carousel img");
const totalSlides = slides.length;

function slideShower() {
    for (let i = 0; i < totalSlides; i++) {
        slides[i].style.display = "none";
    }
    slides[slideIndex].style.display = "block";
}

function nextSlide(){
    slideIndex++;
    if(slideIndex===totalSlides){
        slideIndex = 0;
    }
    slideShower();
}

function prevSlide(){
    slideIndex--;
    if(slideIndex<0){
        slideIndex=totalSlides-1;
    }
    slideShower();
}
setInterval(nextSlide, 5000);
showSlides();

function closeLabel() {
    var label = document.querySelector('.label-container');
    label.style.display = 'none';
}