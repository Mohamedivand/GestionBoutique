<?php
ob_start();
try {
    // On verifie lutilisateur et la boutique
    require_once("../../script/connexion_bd.php");
    require_once("../../class/User.php");
    require_once("../../class/Boutique.php");

    if (!isset($_COOKIE['idUser'], $_GET['idBoutique'])) {
        header('location: ../dashboard/boutique.php?popUpText=3');
        exit();
    }

    $idUser = $_COOKIE['idUser'];
    $idBoutique = $_GET['idBoutique'];

    $user = new User($bdd, $idUser);
    $boutique = new Boutique($bdd, $idBoutique);

    if (!$user->getExiste() || !in_array($user->getRole()->getRole(), array("admin", "proprietaire"))) {
        header('location: ../dashboard/boutique.php?popUpText=5');
        exit();
    }

    if (!$boutique->getExiste()) {
        header('location: ../dashboard/boutique.php?popUpText=4');
        exit();
    }
} catch (Exception $e) {
    header('location: ../dashboard/boutique.php?popUpText=7');
}
?>

<!DOCTYPE html>

<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Ajoutez des produits a votre site Djessy Parfuma (Parfumerie malienne)">
    <meta name="author" content="Djessy Parfuma">


    <meta property="og:title" content="Ajoue de produit">
    <meta property="og:description" content="Ajoutez un produit">


    <link rel="stylesheet" href="../../style/css/form/ajouter_image_boutique.css">
    <link rel="stylesheet" href="../../style/include/pop_up/pop_up.css">
    <link rel="stylesheet" href="../../lib/css/fontawesome/css/all.css">


    <script src="../../lib/js/jquery-3.6.1.min.js"></script>
    <title>informations pdf</title>
</head>

<body>

    <div id="container">
        <section class="sec_form">
            <div class="title">
                <h1>Les informations pour votre pdf</h1>
            </div>

            <form action="traitement/ajouter_image_boutique.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="idBoutique" value="<?php echo ($boutique->getId()) ?>">

                <label class="image-class" id="imageBanderolePicker" for="imageBanderoleInput">
                    <i class="fas fa-regular fa-camera"></i>
                    <span style="color:white">
                        Selectionnez l'image de votre banderole
                    </span>
                </label>
                <input name="imageBanderole" type="file" accept="image/png, image/gif, image/jpeg, image/webp, image/jpg, image/heivc, image/svg" class="inp_image" id="imageBanderoleInput">

                <div class="imageSliderZone" id="banderoleImageZone">
                    <?php
                        if(!is_null($boutique->getImageBanderole())){
                    ?>
                            <div class="productImagesZone">
                                <img src="../../res/images/banderole/<?php echo($boutique->getImageBanderole()) ?>" alt="">
                            </div>
                    <?php
                        }
                    ?>

                    <!-- on listera les imagess ici -->

                </div>
                <label class="image-class" id="imageTamponPicker" for="imageTamponInput">
                    <i class="fas fa-regular fa-camera"></i>
                    <span style="color:white">
                        Selectionnez l'image de votre tampon
                    </span>
                </label>
                <input name="imageTampon" type="file" accept="image/png, image/gif, image/jpeg, image/webp, image/jpg, image/heivc, image/svg" class="inp_image" id="imageTamponInput">

                <div class="imageSliderZone" id="tamponImageZone">
                    <?php
                        if(!is_null($boutique->getImageTampon())){
                    ?>
                            <div class="productImagesZone">
                                <img src="../../res/images/tampon/<?php echo($boutique->getImageTampon()) ?>" alt="">
                            </div>
                    <?php
                        }
                    ?>

                    <!-- on listera les imagess ici -->

                </div>

                <label for="">Le texte a mentionner sur votre pied de page</label>
                <input
                    name="footerTexte" 
                    type="text" 
                    placeholder="Saisissez le texte a mentionner sur votre pied de page" 
                    value="<?php echo ($boutique->getTextFooterPDF()) ?>"
                    class="inp_text"
                >



                <div class="options_form">
                    <button type="submit" name="modifier"><b>Ajouter</b></button>
                    <button type="reset" name="annuler" onclick="cancel()"><b>Annuler</b></button>
                </div>

            </form>

            <?php
            include('../../include/pop_up.php');
            ?>

        </section>
    </div>

    <script src="../../style/js/Form/ajouter_image_boutique.js"></script>
    <script src="../../style/include/pop_up/pop_up.js"></script>
</body>

</html>

<?php
// ob_end_flush();
?>