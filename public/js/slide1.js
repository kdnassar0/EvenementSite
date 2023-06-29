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





  document.querySelector('.navOpenBtn').addEventListener('click',function(){

    document.querySelector('.espaceNav').classList.add('active');
  })
  document.querySelector('.navCloseBtn').addEventListener('click',function(){

    document.querySelector('.espaceNav').classList.remove('active');
  })
