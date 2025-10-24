// Fonction pour le défilement fluide vers les sections
function smoothScroll() {
    // Sélectionner tous les liens d'ancrage (y compris ceux du header)
    const anchorLinks = document.querySelectorAll('a[href*="#"]');
    
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            // Vérifier si c'est un lien vers une section de la même page (format #section)
            if (href.startsWith('#')) {
                e.preventDefault(); // Empêcher le comportement par défaut
                
                const targetId = href.substring(1); // Enlever le #
                const targetElement = document.getElementById(targetId);
                
                if (targetElement) {
                    // Calculer la position de l'élément cible
                    const targetPosition = targetElement.offsetTop;
                    
                    // Effectuer le défilement fluide
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            }
            // Pour les liens du header vers la même page avec ancres (comme index.php#featured)
            else if (href.includes('.php') && href.includes('#')) {
                // Vérifier si c'est un lien vers la page actuelle
                const currentPage = window.location.pathname.split('/').pop();
                const linkPage = href.split('.php')[0] + '.php';
                
                if (linkPage === currentPage || (currentPage === '' && linkPage === 'index.php')) {
                    e.preventDefault(); // Empêcher le comportement par défaut
                    
                    const targetId = href.split('#')[1]; // Extraire l'ID après #
                    const targetElement = document.getElementById(targetId);
                    
                    if (targetElement) {
                        const targetPosition = targetElement.offsetTop;
                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }
                }
                // Sinon, laisser le comportement par défaut pour naviguer vers une autre page
            }
        });
    });
}

// Fonction pour gérer le défilement fluide au chargement de la page
function handlePageLoadScroll() {
    // Vérifier s'il y a une ancre dans l'URL
    const hash = window.location.hash;
    if (hash) {
        // Attendre un peu que la page soit complètement chargée
        setTimeout(() => {
            const targetElement = document.querySelector(hash);
            if (targetElement) {
                const targetPosition = targetElement.offsetTop;
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        }, 100);
    }
}

// Initialiser les fonctions quand le DOM est chargé
document.addEventListener('DOMContentLoaded', function() {
    smoothScroll();
    handlePageLoadScroll();
});

// Alternative avec une fonction plus avancée si le navigateur ne supporte pas 'smooth'
function smoothScrollAdvanced() {
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                const startPosition = window.pageYOffset;
                const targetPosition = targetElement.offsetTop;
                const distance = targetPosition - startPosition;
                const duration = 1000; // Durée en millisecondes
                let start = null;
                
                function animation(currentTime) {
                    if (start === null) start = currentTime;
                    const timeElapsed = currentTime - start;
                    const run = easeInOutQuad(timeElapsed, startPosition, distance, duration);
                    window.scrollTo(0, run);
                    if (timeElapsed < duration) requestAnimationFrame(animation);
                }
                
                // Fonction d'easing pour un mouvement plus naturel
                function easeInOutQuad(t, b, c, d) {
                    t /= d / 2;
                    if (t < 1) return c / 2 * t * t + b;
                    t--;
                    return -c / 2 * (t * (t - 2) - 1) + b;
                }
                
                requestAnimationFrame(animation);
            }
        });
    });
}

// Utiliser la version avancée si nécessaire
// document.addEventListener('DOMContentLoaded', function() {
//     smoothScrollAdvanced();
// });
