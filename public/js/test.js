var currentSlide = 0;
var slides = document.querySelectorAll('.slide');
var slider = document.querySelector('.slider');

function showSlide(slideIndex) {
  if (slideIndex < 0) {
    slideIndex = slides.length - 1;
  } else if (slideIndex >= slides.length) {
    slideIndex = 0;
  }

  slider.style.transform = 'translateX(-' + slideIndex * 100 + '%)';
  currentSlide = slideIndex;
  // Ajoute la classe 'active' à la diapositive actuelle et supprime la classe 'active' des autres diapositives
  slides.forEach(function(slide) {
    slide.classList.remove('active');
  });
  slides[slideIndex].classList.add('active');

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
  }, 5000); // Défilement automatique toutes les 3 secondes (ajustez la valeur selon vos besoins)
}

document.addEventListener('DOMContentLoaded', function() {
  showSlide(0); // Affiche la première image dès le chargement
  startSlider(); // Démarre le défilement automatique
});
