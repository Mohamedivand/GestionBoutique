<?php
ob_start();
require("../../script/connexion_bd.php");
require("../../class/User.php");
require("../../class/Boutique.php");

if (!isset($_COOKIE['idUser'], $_COOKIE['idBoutique'])) {
    header('Location : boutique.php');
}
$userTmp = new User($bdd, $_COOKIE['idUser']);
if (!$userTmp->getExiste()) {
    header("location:../../index.php");
}

if (!in_array($userTmp->getRole()->getRole(), array("admin", "proprietaire"))) {
    header("location:../../index.php");
}

$idBoutique = $_COOKIE['idBoutique'];
$boutique = new Boutique($bdd, $idBoutique);
if (!$boutique->getExiste()) {
    header("location:boutique.php");
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../lib/css/fontawesome/css/all.css">
    <link rel="stylesheet" href="../../lib/bootstrap-5.0.2/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="../../style/css/style.css">
    <link rel="stylesheet" href="../../style/css/dashboard/boutique.css">
    <link rel="stylesheet" href="../../style/loader/loader.css">
    <link rel="stylesheet" href="../../style/include/toolBar/toolBar.css">
    <link rel="stylesheet" href="../../style/css/navbar.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>


    <script src="../../lib/js/jquery-3.6.1.min.js"></script>

    <title>Vos utilisateurs de <?php echo ($boutique->getNomBoutique()) ?></title>
</head>

<body>
    <?php
    include("../../include/navbar.php");
    ?>

    <section class="dashboard">
        <div class="top">
            <i class='bx bx-menu'></i>
            <div class="search-box">
                <input type="text" placeholder="Entrer une date a rechercher" class="" id="searchInput">
                <i class="fa-solid fa-magnifying-glass"></i>
                <span class="searchBtn" id="searchBtn">Rechercher</span>
            </div>
        </div>

        <div class="dash-content">
            <div class="overview">
                <div class="title">
                    <i class="fa fa-user"></i>
                    <span class="text">Gerez vos fournisseurs ici</span>
                </div>


                <div class="col-md-10 ">
                    <div class="row ">
                        <div class="col-xl-3 col-lg-6">
                            <div class="card l-bg-blue-dark">
                                <div class="card-statistic-3 p-4">
                                    <div class="card-icon card-icon-large"><i class="fas fa-user-group"></i></div>
                                    <div class="mb-4">
                                        <h5 class="card-title mb-0">Nombre de fournisseurs</h5>
                                    </div>
                                    <div class="row align-items-center mb-2 d-flex">
                                        <div class="col-8">
                                            <h2 class="d-flex align-items-center mb-0 nbrUser">
                                                --
                                            </h2>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="btnZone">
                    <a href="../form/user.php" title="Ajouter un utilisateur">Ajouter</a>
                    <a href="../../print/printFournisseur.php" title="imprimer la liste de vos fournisseurs">imprimer</a>
                </div>

                <div class="table-responsive table-data">
                    <table id="example" style="width:100%" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Prenom</th>
                                <th>Téléphone</th>
                                <th>Rôle</th>
                                <th>Modifier</th>
                                <th>Supprimer</th>
                            </tr>
                        </thead>
                        <tbody class="userTable">
                            
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="popup ">
                <div class="popupZone">
                    <?php
                    include("../../include/loader.php");
                    ?>
                </div>
            </div>

            <?php
            include("../../include/toolBar.php");
            ?>
    </section>

    <script src="../../style/js/script.js"></script>
    <script src="../../style/js/navbar.js"></script>
    <script src="../../style/js/dashboard/user.js"></script>
    <script src="../../style/include/toolBar/toolBar.js"></script>

</body>

</html>