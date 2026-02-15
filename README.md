# Artsmouth – Portfolio créatif

Site vitrine en PHP pour présenter les créations Artsmouth : sélection mise en avant, sliders thématiques et back-office léger pour gérer les contenus et les messages de contact.

## Fonctions clés
- Accueil immersif avec hero-slider illustré et popup d’intro désactivé après première vue par session ([index.php](index.php), [assets/home_slider.js](assets/home_slider.js)).
- Section featured automatique qui affiche l’article le plus récent et propose un lien vers la page concernée ([includes/featured.php](includes/featured.php)).
- Pages thématiques avec sliders pleine largeur et version mobile : [work.php](work.php), [crush.php](crush.php), [video.php](video.php) (support Vimeo), pilotés par le JSON des articles.
- Formulaire de contact avec validation, stockage des messages dans [data/contacts.json](data/contacts.json) et redirection vers l’ancre Contact ([contact-handler.php](contact-handler.php)).
- Navigation responsive avec menu hamburger et défilement fluide vers les ancres ([includes/header.php](includes/header.php), [assets/script.js](assets/script.js)).

## Back-office (admin/)
- Authentification simple avec CSRF et mot de passe haché ([admin/index.php](admin/index.php)).
- Tableau de bord pour gérer les contenus :
	- Ajout d’articles avec uploads d’images de fond et vignettes featured, visibilité on/off, type de page, lien vidéo Vimeo ([admin/add-article.php](admin/add-article.php)).
	- Edition/suppression d’articles avec prévisualisation des fichiers en cours ([admin/edit_article.php](admin/edit_article.php), [admin/view.php](admin/view.php)).
	- Gestion des messages de contact (lecture + suppression) ([admin/view-contact.php](admin/view-contact.php)).
	- Remplacement de l’image du popup d’accueil avec conversion auto en JPG ([admin/manage-popup.php](admin/manage-popup.php)).
- Fichiers médias uploadés dans [admin/uploads/](admin/uploads/) ; les données structurées restent dans [data/articles.json](data/articles.json).

## Données et structure
- Articles : [data/articles.json](data/articles.json) (champ `page` = work | crush | video | featured, `visible`, `featured_image`, `image_bgrd`, `video`).
- Messages de contact : [data/contacts.json](data/contacts.json) (créé à la volée si absent).
- Assets CSS/JS : dossier [assets/](assets) (styles desktop/mobile, sliders, menu, popup, favicon et médias).

## Accès en ligne
- Site public : https://artsmouth.fr


## Personnalisation rapide
- Modifier les images du hero dans [assets/images/home_slider/](assets/images/home_slider/).
- Changer l’image du popup via le back-office ou en remplaçant [assets/images/popup.jpg](assets/images/popup.jpg).
- Adapter les styles responsive dans [assets/style.css](assets/style.css) et [assets/style-mobile.css](assets/style-mobile.css) ainsi que les feuilles dédiées aux sliders.

