var currentSlide = 0;
var slides = document.querySelectorAll('.slide');
var slider = document.querySelector('.slider');

function showSlide(slideIndex) {
  if (slideIndex < 0) {
    slideIndex = slides.length - 1;
  } else if (slideIndex >= slides.length) {
    slideIndex = 0;
  }

  slider.style.transform = 'translateX(-' + slideIndex * (100 / 3) + '%)'; /* Ajustement de la valeur du décalage */
  currentSlide = slideIndex;
}

function previousSlide() {
  showSlide(currentSlide - 1);
}

function nextSlide() {
  showSlide(currentSlide + 1);
}

function startSlider() {
  setInterval(function() {
    nextSlide();
  }, 3000); // Défilement automatique toutes les 3 secondes (ajustez la valeur selon vos besoins)
}

// Exécute startSlider() après le chargement du document
document.addEventListener('DOMContentLoaded', function() {
  showSlide(0); // Affiche la première image dès le chargement
  startSlider();
});
