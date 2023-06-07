var currentSlide = 0;
var slides = document.querySelectorAll('.slide');
var slider = document.querySelector('.slider');
var slideshowInterval; // Variable pour stocker l'intervalle du défilement automatique

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
}

function previousSlide() {
  showSlide(currentSlide - 1);
}

function nextSlide() {
  showSlide(currentSlide + 1);
}

function startSlider() {
  slideshowInterval = setInterval(function() {
    nextSlide();
  }, 3000); // Défilement automatique toutes les 3 secondes (ajustez la valeur selon vos besoins)

  var sliderContainer = document.querySelector('.slide');

slides.forEach(slide =>slide.addEventListener('mouseOver',pauseSlider))

  sliderContainer.addEventListener('mouseover', () => {
    pauseSlider()
    // console.log('test');
  });

  sliderContainer.addEventListener('mouseleave', resumeSlider);
}

function pauseSlider() {
  clearInterval(slideshowInterval);
}

function resumeSlider() {
  slideshowInterval = setInterval(function() {
    nextSlide();
  }, 1000);
}


////////////////////////////////////////////



var currentSlide2 = 0;

var slides2 = document.querySelectorAll('.slide2');
var slider2 = document.querySelector('.slider2');
var prevButton2 = document.querySelector('.prevButton2');
var nextButton2 = document.querySelector('.nextButton2');

function showSlide2(slideIndex) {
  if (slideIndex < 0) {
    slideIndex = slides2.length - 1;
  } else if (slideIndex >= slides2.length) {
    slideIndex = 0;
  }

  slider2.style.transform = 'translateX(-' + slideIndex * 100 + '%)';
  currentSlide2 = slideIndex;

  // Ajoute la classe 'active2' à la diapositive actuelle et supprime la classe 'active2' des autres diapositives
  slides2.forEach(function(slide) {
    slide.classList.remove('active');
  });
  slides2[slideIndex].classList.add('active');
}

function previousSlide2() {
  showSlide2(currentSlide2 - 1);
}

function nextSlide2() {
  showSlide2(currentSlide2 + 1);
}

function startSlider2() {
    setInterval(function() {
      nextSlide2();
    }, 5000); // Défilement automatique toutes les 3 secondes (ajustez la valeur selon vos besoins)
  }
  

document.addEventListener('DOMContentLoaded', function() {
    showSlide(0); // Affiche la première image dès le chargement
  startSlider(); // Démarre le défilement automatique

  showSlide2(0); // Affiche la première image dès le chargement

  pauseSlider();


  prevButton2.addEventListener('click', function() {
    previousSlide2();
  });

  nextButton2.addEventListener('click', function() {
    nextSlide2();
  });
});

