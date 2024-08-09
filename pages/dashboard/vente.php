<?php
ob_start();
require_once("../../script/connexion_bd.php");
require_once("../../class/User.php");
require_once("../../class/Boutique.php");

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
    <link rel="stylesheet" href="../../style/css/dashboard/commande.css">
    <link rel="stylesheet" href="../../style/css/dashboard/vente.css">
    <link rel="stylesheet" href="../../style/include/loader/loader.css">
    <link rel="stylesheet" href="../../style/include/toolBar/toolBar.css">
    <link rel="stylesheet" href="../../style/css/navbar.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>


    <script src="../../lib/js/jquery-3.6.1.min.js"></script>

    <title>Vos Ventes</title>
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
                    <i class="fa fa-chart-line"></i>
                    <span class="text">Gerez vos Vente ici</span>
                </div>

                <div class="col-md-10 ">
                    <div class="row ">
                        <!-- <div class="col-xl-3 col-lg-6">
                            <div class="card l-bg-blue-dark">
                                <div class="card-statistic-3 p-4">
                                    <div class="card-icon card-icon-large"><i class="fas fa-share-nodes"></i></div>
                                    <div class="mb-4">
                                        <h5 class="card-title mb-0">Total Qte Vendue</h5>
                                    </div>
                                    <div class="row align-items-center mb-2 d-flex">
                                        <div class="col-8">
                                            <h2 class="d-flex align-items-center mb-0 totalQteVendue">
                                                --
                                            </h2>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <div class="col-xl-3 col-lg-6">
                            <div class="card l-bg-cherry">
                                <div class="card-statistic-3 p-4">
                                    <div class="card-icon card-icon-large"><i class="fas fa-circle-dollar-to-slot"></i></div>
                                    <div class="mb-4">
                                        <h5 class="card-title mb-0">Montant total de vente</h5>
                                    </div>
                                    <div class="row align-items-center mb-2 d-flex">
                                        <div class="col-8">
                                            <h2 class="d-flex align-items-center mb-0 totalMontantVendue">
                                                --
                                            </h2>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6">
                            <div class="card l-bg-green-dark">
                                <div class="card-statistic-3 p-4">
                                    <div class="card-icon card-icon-large"><i class="fas fa-money-bill-alt"></i></div>
                                    <div class="mb-4">
                                        <h5 class="card-title mb-0">Montant total des Reduction</h5>
                                    </div>
                                    <div class="row align-items-center mb-2 d-flex">
                                        <div class="col-8">
                                            <h2 class="d-flex align-items-center mb-0 totalReduction">
                                                --
                                            </h2>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6">
                            <div class="card l-blue-dark">
                                <div class="card-statistic-3 p-4">
                                    <div class="card-icon card-icon-large"><i class="fas fa-money-bill-alt"></i></div>
                                    <div class="mb-4">
                                        <h5 class="card-title mb-0">Montant apres reduction</h5>
                                    </div>
                                    <div class="row align-items-center mb-2 d-flex">
                                        <div class="col-8">
                                            <h2 class="d-flex align-items-center mb-0 totalMontantAfterReduction">
                                                --
                                            </h2>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6">
                            <div class="card l-blue-dark">
                                <div class="card-statistic-3 p-4">
                                    <div class="card-icon card-icon-large"><i class="fas fa-money-bill-alt"></i></div>
                                    <div class="mb-4">
                                        <h5 class="card-title mb-0">Vente Du jour</h5>
                                    </div>
                                    <div class="row align-items-center mb-2 d-flex">
                                        <div class="col-8">
                                            <h2 class="d-flex align-items-center mb-0 venteJournalier">
                                                --
                                            </h2>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="btnZone">
                <a href="../form/ajouter_vente.php" title="Ajouter une vente">Ajouter une vente</a>
                <a href="../../print/printVenteJour.php" title="vente du jour">Imprimer vente du jour</a>
            </div>


            <div class="table-responsive table-data">
                <table id="example" style="width:100%" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="titleTab1">Date</th>
                            <th class="titleTab2">Nom Client</th>
                            <th class="titleTab3">Num Client</th>
                            <th class="titleTab4">Prix des articles</th>
                            <th class="titleTab5">Reduction</th>
                            <th class="titleTab6">Montant apres reduction</th>
                            <th class="titleTab7">Type de Vente</th>
                            <th class="titleTab7">Liste Produits</th>
                            <th class="titleTab8">Recu de vente</th>
                            <th class="titleTab9 ">Action</th>
                        </tr>
                    </thead>
                    <tbody class="venteTable">
                        <!-- <tr>
                  <td>Tiger Nixon</td>
                  <td>System Architect</td>
                  <td>Edinburgh</td>
                  <td>61</td>
                  <td>2011/04/25</td>
                  <td>i</td>
                </tr>-->
                    </tbody>
                </table>
            </div>

            <div class="voirPlus">
                <button id="voirPlus">Voir plus</button>
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
                            <h3>historique</h3>
                        </div>

                        <div class="table-responsive table-data">
                            <table id="example" style="width:100%" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th class="titleTab13">Produit</th>
                                        <th class="titleTab14">Quantiter</th>
                                        <th class="titleTab14">Montant</th>
                                    </tr>
                                </thead>
                                <tbody class="histoTable">
                                    <!-- <tr>
                            <td>Tiger Nixon</td>
                            <td>System Architect</td>
                            </tr>   
                         -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        include("../../include/toolBar.php");
        ?>
    </section>



    <script src="../../style/js/script.js"></script>
    <script src="../../style/js/navbar.js"></script>
    <script src="../../style/js/dashboard/vente.js"></script>
    <script src="../../style/include/toolBar/toolBar.js"></script>

</body>

</html>