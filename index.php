<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artsmouth</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="https://kit.fontawesome.com/154242561e.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php include __DIR__ . '/includes/header.php'; ?>
    <main>
        <div class="full_container center_container" id="home_slider">
            <div class="full_container center_container" style= "height: 600px">
                <?php include __DIR__ . '/includes/home_slider.php'; ?>
            </div>
            <div class="fluid_full_container center_container" style="height: 200px; width: 750px;">
                <div class="ltl_container center_container home_text" style="height: 200px; width: 750px;">
                    <p>
                        La découverte de la paire de défenses devint alors le point de départ d'une fabuleuse aventure. <b>Artsmouth est là,</b> un clin d'œil au mammouth Jarkov maintenu dans un bloc de permafrost, ce sol gelé en permanence qui est son linceul depuis plus de 20 000 ans… 
                    </p>
                </div>
            </div>
            <div class="fluid_full_container center_container" style="height: 150px; width: 750px;">
                <a class="arrow_down" href="#featured">
                    <i class="fa-solid fa-angles-down"></i>
                </a>
            </div>
        </div>


        <div class="full_container" id="featured">
            <div class="full_container featured">
                <div class="h1_container ">
                    <h1>///FEATURED</h1>
                </div>
            </div>
        </div>
    </main>
    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>