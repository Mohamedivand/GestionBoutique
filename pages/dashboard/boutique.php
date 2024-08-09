<?php
ob_start();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../lib/bootstrap-5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../lib/css/fontawesome/css/all.css">
    <link rel="stylesheet" href="../../style/css/style.css">
    <link rel="stylesheet" href="../../style/css/dashboard/boutique.css">
    <link rel="stylesheet" href="../../style/loader/loader.css">
    <link rel="stylesheet" href="../../style/css/dashboard/vente.css">
    <link rel="stylesheet" href="../../style/css/navbar.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>


    <script src="../../lib/js/jquery-3.6.1.min.js"></script>

    <title>Vos boutiques</title>
</head>

<body>
    <?php
    require("../../script/connexion_bd.php");
    require("../../class/User.php");

    setcookie('idBoutique', 1, 1, '/');
    unset($_COOKIE['idBoutique']);
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

    if (!isset($_COOKIE['idUser'])) {
        header("location:../../index.php?deconnexion=1");
        exit();
    }

    $userTmp = new User($bdd, $_COOKIE['idUser']);
    if (!$userTmp->getExiste()) {
        header("location:../../index.php?deconnexion=1");
        exit();
    }

    if (!isset($_COOKIE['mdpUser'])) {
        setcookie('mdpUser', $userTmp->getMdp(), time() + 3600 * 24 * 365, '/');
    }

    if ($userTmp->getMdp() != $_COOKIE['mdpUser']) {
        header("location:../../index.php?deconnexion=1");
        exit();
    }

    if (!in_array($userTmp->getRole()->getRole(), array("admin", "proprietaire"))) {
        header("location:../../index.php?deconnexion=1");
        exit();
    }


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

                <div class="col-md-10 ">
                    <div class="row ">
                        <div class="col-xl-3 col-lg-6">
                            <div class="card l-blue-dark">
                                <div class="card-statistic-3 p-4">
                                    <div class="card-icon card-icon-large"><i class="fas fa-shop"></i></div>
                                    <div class="mb-4">
                                        <h5 class="card-title mb-0">Total les boutiques</h5>
                                    </div>
                                    <div class="row align-items-center mb-2 d-flex">
                                        <div class="col-8">
                                            <h2 class="d-flex align-items-center mb-0 nbrBoutique">
                                                --
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="btnZone">
                        <a href="../form/boutique.php" title="Ajouter une boutique" id="addBtn">Ajouter</a>
                        <a href="../../print/printBoutique.php" title="imprimer la liste de vos boutique" id="printBtn">imprimers</a>
                    </div>

                    <div class="table-responsive table-data">
                        <table id="example" style="width:100%" class="table table-striped table-bordered">
                            <thead>
                                <tr class="tableTitle">
                                    <th class="titleTab1"></th>
                                    <th class="titleTab2">Nom</th>
                                    <th class="titleTab3">Debut d'abonnement</th>
                                    <th class="titleTab4">Fin d'abonnement</th>
                                    <th class="titleTab6">Modifier</th>
                                    <th class="titleTab7">Ajouter_Info</th>
                                    <th class="titleTab5">Supprimer</th>
                                    <?php
                                    if (in_array($userTmp->getRole()->getRole(), array("admin"))) {
                                    ?>
                                        <th class="titleTab6">Action</th>
                                    <?php
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody class="boutiqueTable">
                                <!--listes des boutiques -->
                            </tbody>
                        </table>
                    </div>

                    <div class="popup ">
                        <div class="popupZone">
                            <?php
                            include("../../include/loader.php");
                            ?>
                        </div>
                    </div>

                    <div class="historique historiqueZoneInactive">
                        <div class="popupZone">
                            <i class="fa-solid fa-xmark closeHisto"></i>
                            <div class="historiqueZone">
                                <div class="histTitle">
                                    <h3>Boutique a Resilier</h3>
                                </div>

                                <div class="table-responsive table-data">
                                    <table id="example" style="width:100%" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="titleTab13">Nom Boutique</th>
                                            </tr>
                                        </thead>
                                        <tbody class="zoneResilier">
                                            <!--listes des boutiques a resilier -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

    </section>



    <script src="../../style/js/script.js"></script>
    <script src="../../style/js/navbar.js"></script>
    <script src="../../style/js/dashboard/boutique.js"></script>
</body>

</html>