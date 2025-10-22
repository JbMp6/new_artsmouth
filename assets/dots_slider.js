document.addEventListener('DOMContentLoaded', () => {
  const slides = document.querySelectorAll('.slide');
  if (slides.length === 0) return;

  let index = 0;
  let autoSlideInterval;
  let isAutoSliding = true;

  // Fonction pour afficher une slide
  function showSlide(newIndex) {
    slides[index].classList.remove('active');
    slides[newIndex].classList.add('active');
    index = newIndex;
    updateDots();
  }

  // Met à jour les dots
  function updateDots() {
    document.querySelectorAll('.dot').forEach(dot => dot.classList.remove('active'));
    const activeSlide = slides[index];
    const dots = activeSlide.querySelectorAll('.dot');
    dots.forEach(dot => {
      if (parseInt(dot.dataset.index) === index) {
        dot.classList.add('active');
      }
    });
  }

  // Slide suivante
  function showNextSlide() {
    const nextIndex = (index + 1) % slides.length;
    showSlide(nextIndex);
  }

  // Démarrer et arrêter l’auto-slide
  function startAutoSlide() {
    stopAutoSlide();
    autoSlideInterval = setInterval(showNextSlide, 7000);
  }

  function stopAutoSlide() {
    clearInterval(autoSlideInterval);
  }

  // Click sur un dot
  document.querySelectorAll('.dot').forEach(dot => {
    dot.addEventListener('click', () => {
      const newIndex = parseInt(dot.dataset.index);
      showSlide(newIndex);
      stopAutoSlide();
      isAutoSliding = false;
    });
  });

  // Stop auto-slide si une vidéo démarre
  const iframes = document.querySelectorAll('.slide iframe');
  iframes.forEach(iframe => {
    const player = new Vimeo.Player(iframe);
    player.on('play', () => {
      stopAutoSlide();
      isAutoSliding = false;
      console.log('Lecture vidéo détectée → slider arrêté');
    });
  });

  // Démarrage automatique
  startAutoSlide();

  // ✅ Activer la slide ciblée si paramètre ?article= présent
  const urlParams = new URLSearchParams(window.location.search);
  const targetArticle = urlParams.get('article');
  if (targetArticle) {
    // Chercher la slide correspondante
    slides.forEach((slide, i) => {
      const slideTitleElem = slide.querySelector('.slide-content h2');
      if (slideTitleElem && slideTitleElem.textContent.trim().toLowerCase() === targetArticle.toLowerCase()) {
        // Activer la slide ciblée
        showSlide(i);
      }
    });
  }
});
