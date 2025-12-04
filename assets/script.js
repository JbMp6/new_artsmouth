// Fonction pour gérer le menu hamburger
function hamburgerMenu() {
    const hamburger = document.getElementById('hamburger');
    const verticalMenu = document.getElementById('verticalMenu');
    const body = document.body;
    
    console.log('Initialisation du menu hamburger...', { hamburger, verticalMenu });
    
    if (hamburger && verticalMenu) {
        console.log('Éléments trouvés, ajout des event listeners...');
        hamburger.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Clic sur hamburger détecté!');
            // Toggle les classes active
            hamburger.classList.toggle('active');
            verticalMenu.classList.toggle('active');
            body.classList.toggle('menu-open');
        });
        
        // Fermer le menu quand on clique sur un lien
        const menuLinks = verticalMenu.querySelectorAll('a');
        menuLinks.forEach(link => {
            link.addEventListener('click', function() {
                hamburger.classList.remove('active');
                verticalMenu.classList.remove('active');
                body.classList.remove('menu-open');
            });
        });
        
        // Fermer le menu en cliquant en dehors
        document.addEventListener('click', function(event) {
            if (!verticalMenu.contains(event.target) && !hamburger.contains(event.target)) {
                if (verticalMenu.classList.contains('active')) {
                    hamburger.classList.remove('active');
                    verticalMenu.classList.remove('active');
                    body.classList.remove('menu-open');
                }
            }
        });
    }
}

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

// Fonction d'initialisation
function initScripts() {
    try {
        popUp();
    } catch (error) {
        console.log('Erreur popup:', error);
    }
    
    try {
        hamburgerMenu();
    } catch (error) {
        console.error('Erreur menu hamburger:', error);
    }
    
    try {
        smoothScroll();
    } catch (error) {
        console.log('Erreur smooth scroll:', error);
    }
    
    try {
        handlePageLoadScroll();
    } catch (error) {
        console.log('Erreur page load scroll:', error);
    }
}

// Initialiser les fonctions quand le DOM est chargé
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initScripts);
} else {
    // Le DOM est déjà chargé, initialiser immédiatement
    initScripts();
}

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

function popUp() {
    const popUp_page = document.querySelector(".popup");
    
    // Vérifier si la popup existe avant de la manipuler
    if (!popUp_page) {
        return;
    }

    // Vérifier si l'utilisateur a déjà vu le popup dans cette session
    const hasSeenPopup = sessionStorage.getItem('artsmouth_popup_seen');
    
    // Si l'utilisateur a déjà vu le popup dans cette session, ne pas l'afficher
    if (hasSeenPopup === 'true') {
        popUp_page.style.display = "none";
        return;
    }

    // Afficher la popup
    popUp_page.style.display = "block";

    // Petite pause pour que le navigateur applique display:block avant d'augmenter l'opacité
    setTimeout(() => {
        popUp_page.style.opacity = "1"; // fondu visible
    }, 10);

    // Bloquer le scroll
    document.body.style.overflow = "hidden";

    function preventScroll(e) {
        e.preventDefault();
    }
    window.addEventListener("wheel", preventScroll, { passive: false });
    window.addEventListener("touchmove", preventScroll, { passive: false });

    // Après 3 secondes, cacher la popup avec fondu
    setTimeout(() => {
        popUp_page.style.opacity = "0"; // fondu vers invisible

        // attendre la fin de la transition avant de mettre display:none
        setTimeout(() => {
            popUp_page.style.display = "none";
            document.body.style.overflow = "auto";
            window.removeEventListener("wheel", preventScroll);
            window.removeEventListener("touchmove", preventScroll);
            
            // Enregistrer que l'utilisateur a vu le popup dans cette session
            sessionStorage.setItem('artsmouth_popup_seen', 'true');
        }, 3000); // correspond à la durée de la transition
    }, 5000);
}
