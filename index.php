<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artsmouth</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="assets/style-mobile.css">
    <script src="assets/script.js"></script>
    <script src="https://kit.fontawesome.com/154242561e.js" crossorigin="anonymous"></script>

</head>
<body>
    <?php include __DIR__ . '/includes/header.php'; ?>
    <div id="home"></div>
    <main id="home">
        <div class="full_container" style="height: 1000px">
            <div class="full_container center_container" style= "height: 600px">
                <?php include __DIR__ . '/includes/home_slider.php'; ?>
            </div>
            <div class="fluid_full_container center_container" style="height: 200px; width: 700px;">
                <div class="ltl_container center_container home_text" style="height: 200px; width: 100%;">
                    <p>
                        La découverte de la paire de défenses devint alors le point de départ d'une fabuleuse aventure. <b>Artsmouth est là,</b> un clin d'œil au mammouth Jarkov maintenu dans un bloc de permafrost, ce sol gelé en permanence qui est son linceul depuis plus de 20 000 ans… 
                    </p>
                </div>
            </div>
            <div class="fluid_full_container center_container" style="height: 150px; width: 750px;">
                <a class="arrow_down" href="#featured">
                    <svg class="arrow-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
                        <path d="M26.32,28.52c.53,0,1.07.19,1.5.57l21.88,19.22,21.89-19.23c.94-.83,2.38-.73,3.21.21.83.94.73,2.38-.21,3.21l-23.39,20.54c-.86.76-2.14.76-3,0l-23.38-20.54c-.94-.83-1.04-2.27-.21-3.21.44-.51,1.08-.77,1.71-.77Z"/>
                        <path d="M26.32,42.13c.53,0,1.07.19,1.5.57l21.88,19.22,21.89-19.22c.94-.83,2.38-.73,3.21.21.83.94.73,2.38-.21,3.21l-23.38,20.55c-.86.76-2.14.76-3,0l-23.39-20.55c-.94-.83-1.04-2.27-.21-3.21.44-.52,1.08-.78,1.71-.78Z"/>
                    </svg>
                </a>
            </div>
        </div>


        <div class="full_container center_container" id="featured" style="height: 1000px">
            <div class="background_image featured" style="background-image: url('assets/images/fond_enter_featured-scaled.jpg');">
                <div class="h1_container ">
                    <h1 class="h1_featured">/// FEATURED</h1>
                </div>
                <?php include __DIR__ . '/includes/featured.php'; ?>
            </div>
        </div>
        <div class="full_container center_container" id="contact" style="height: 1000px">
            <div class="background_image featured" style="background-image: url('assets/images/fond_enter_contact-scaled.jpg');">
                <div class="h1_container ">
                    <h1 class="h1_contact">/// CONTACT</h1>
                </div>
                <div class="form-container">
                    <form method="POST" action="contact-handler.php">
                        <input type="text" name="name" placeholder="Name" required>
                        <input type="email" name="email" placeholder="Email" required>
                        <textarea name="message" placeholder="Message" required></textarea>
                        <button type="submit">NOW ! GO TO ARTSMOUTH…</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="full_container center_container" id="why" style="height: 1000px">
            <div class="background_image featured" style="background-image: url('assets/images/fond_enter_citation-scaled.jpg');">
                <div class="h1_container ">
                    <h1 class="h1_why">/// WHY?</h1>
                </div>
                <div class="center container text_why" >
                    <p>
                        “C’est ce que je fals qui m’apprend ce que je cherche. Mon travail est un espace de questionnement où les sens qu’on lul prête peuvent se falre et défalre. Parce qu’au bout du compte, l’oeuvre vit du regard qu’on lul porte. Elle ne se lImite ni à ce qu’elle est, ni à celui qui l’a produite, elle est falte aussi de celul qui la regarde. Je ne demande rien au spectateur, je lul propose un projet : il en est le libre et nécessaire interprète.”
                    </p>
                    <p id="citation_author">
                        Pierre Soulages
                    </p>
                </div>
                <a class="arrow_down up" href="#home">
                    <svg class="arrow-svg up" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
                        <path d="M26.32,28.52c.53,0,1.07.19,1.5.57l21.88,19.22,21.89-19.23c.94-.83,2.38-.73,3.21.21.83.94.73,2.38-.21,3.21l-23.39,20.54c-.86.76-2.14.76-3,0l-23.38-20.54c-.94-.83-1.04-2.27-.21-3.21.44-.51,1.08-.77,1.71-.77Z"/>
                        <path d="M26.32,42.13c.53,0,1.07.19,1.5.57l21.88,19.22,21.89-19.22c.94-.83,2.38-.73,3.21.21.83.94.73,2.38-.21,3.21l-23.38,20.55c-.86.76-2.14.76-3,0l-23.39-20.55c-.94-.83-1.04-2.27-.21-3.21.44-.52,1.08-.78,1.71-.78Z"/>
                    </svg>
                </a>
            </div>
            
        </div>

    </main>
    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>