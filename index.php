<?php
    ob_start();

    require('script/connexion_bd.php');
    require('class/User.php');
    if(isset($_GET['deconnexion']) || isset($_POST['deconnexion'])){
        setcookie('idUser', 1, 1, '/');
        setcookie('idBoutique', 1, 1, '/');
        setcookie('mdpUser', 1, 1, '/');
        unset($_COOKIE['mdpUser']);
        unset($_COOKIE['idUser']);
        unset($_COOKIE['idBoutiqe']);
        setcookie('chart1', 1, 1, '/');
        unset($_COOKIE['chart1']);
        setcookie('chart2', 1, 1, '/');
        unset($_COOKIE['chart2']);
        setcookie('chart3', 1, 1, '/');
        unset($_COOKIE['chart3']);
        setcookie('montantDette', 1, 1, '/');
        unset($_COOKIE['montantDette']);
        setcookie('montantDepense', 1, 1, '/');
        unset($_COOKIE['montantDepense']);
        setcookie('quantiteProduitVendu', 1, 1, '/');
        unset($_COOKIE['quantiteProduitVendu']);
    }

    if(isset($_COOKIE['idUser'])){
        $idUser = $_COOKIE['idUser'];

        $user = new User($bdd, $idUser);

        if($user->getExiste()){
            if(in_array($user->getRole()->getRole(), array('admin', 'proprietaire'))){
                header('location:pages/dashboard/boutique.php');
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="lib/css/fontawesome/css/all.css">

    <link rel="stylesheet" href="style/css/index.css">
    <script src="lib/js/jquery-3.6.1.min.js"></script>
</head>

<body>
    <div class="main">
        <div class="connexionZone">
            <div class="connexionBorder border-1"></div>

            <form id="connexionForm">
                <input type="hidden" name="token" value="djessyaroma1234">
                <div class="logoZone">
                    <img src="res/images/logoDjessyGroup.svg" alt="">
                </div>

                <div class="inputZone">
                    <span>
                        <i class="fa fa-user"></i>
                    </span>

                    <input type="text" placeholder="Votre login" name="login" required>
                </div>

                <div class="inputZone">
                    <span>
                        <i class="fa fa-lock"></i>
                    </span>

                    <input type="password" id="password" name="mdp" placeholder="Votre mot de passe" required>

                    <b class="pass-eyes" id="passwordToggler">
                        <i class="fa fa-eye"></i>
                    </b>
                </div>

                <div class="btnZone">
                    <button>
                        <span>Connexion</span>
                    </button>
                </div>

                <div align="center">
                    <h5 style="border-top: 2px solid white; padding-top: 20px;">
                        Merci de nous avoir confier la gestion de votre business. 
                        Pour tout besoins, contactez nous
                    </h5>
                    
                    <div class="contactZone">
                        <a href="tel:66035300" class="phone-icone">
                            <i class=" fa fa-phone"></i>
                        </a>
                        <a href="mailto:bore.younous59@gmail.com" class="envelope-icone">
                            <i class=" fa fa-envelope"></i>
                        </a>
                        <a class="fb-icone">
                        <!-- <a href="https://m.facebook.com/profile.php?id=100063855260762" class="fb-icone"> -->
                            <i class=" fab fa-facebook-f"></i>
                        </a>
                        <a href="https://wa.me/+22366035300" class="whatsapp-icone">
                            <i class=" fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>

                <div class="mention">
                    <span>Powered By <a href="https://groupedjessy.com">Groupe Djessy</a></span>
                </div>

            </form>

            <div class="connexionBorder border-2"></div>
        </div>
    </div>

    <script src="style/js/index.js"></script>
</body>

</html>

<?php
    ob_end_flush();
?>