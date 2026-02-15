document.addEventListener('DOMContentLoaded', () => {
  const slides = document.querySelectorAll('.slide');
  let index = 0;

  if(slides.length === 0) return;

  // Initialisation : première slide visible
  slides.forEach(slide => slide.classList.remove('active'));
  slides[0].classList.add('active');

  // Fonction pour passer à la slide suivante
  function showNextSlide() {
    slides[index].classList.remove('active');   // cache la slide actuelle
    index = (index + 1) % slides.length;        // incrémente l’index
    slides[index].classList.add('active');      // affiche la suivante
  }

  // Démarrage du slider toutes les 5 secondes
  setInterval(showNextSlide, 5000);
});
