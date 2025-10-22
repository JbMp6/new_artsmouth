document.addEventListener('DOMContentLoaded', () => {
  const slides = document.querySelectorAll('.slide');
  const dots = document.querySelectorAll('.dot');
  let index = 0;

  if (slides.length === 0) return;

  function showSlide(newIndex) {
    slides[index].classList.remove('active');
    dots[index].classList.remove('active');
    index = newIndex;
    slides[index].classList.add('active');
    dots[index].classList.add('active');
  }

  function showNextSlide() {
    const nextIndex = (index + 1) % slides.length;
    showSlide(nextIndex);
    console.log('Slide changed to index:', nextIndex);
  }

  // Clic sur un dot
  dots.forEach(dot => {
    dot.addEventListener('click', () => {
      const newIndex = parseInt(dot.dataset.index);
      showSlide(newIndex);
    });
  });

  // Auto défilement

  setInterval(showNextSlide, 7000);
});
