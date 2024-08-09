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
    <link rel="stylesheet" href="../../style/css/dashboard/statistique.css">
    <link rel="stylesheet" href="../../style/css/dashboard/boutique.css">
    <link rel="stylesheet" href="../../style/loader/loader.css">
    <link rel="stylesheet" href="../../style/include/toolBar/toolBar.css">
    <link rel="stylesheet" href="../../style/css/navbar.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>



    <script src="../../lib/js/jquery-3.6.1.min.js"></script>

    <title>Vos Statistiques</title>
    <script src="../../lib/js/chart.js"></script>
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
                    <span class="text">Gerez vos statistiques Hebdomadaires</span>
                </div>


                <div class="col-md-10 ">
                    <div class="row ">
                        <div class="col-xl-3 col-lg-6">
                            <div class="card l-bg-blue-dark">
                                <div class="card-statistic-3 p-4">
                                    <div class="card-icon card-icon-large"><i class="fas fa-coins"></i></div>
                                    <div class="mb-4">
                                        <h5 class="card-title mb-0">Vente de la semaine</h5>
                                    </div>
                                    <div class="row align-items-center mb-2 d-flex">
                                        <div class="col-8">
                                            <h2 class="d-flex align-items-center mb-0 totalVente">
                                                --
                                            </h2>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6">
                            <div class="card l-bg-cherry">
                                <div class="card-statistic-3 p-4">
                                    <div class="card-icon card-icon-large"><i class="fas fa-money-bill-1-wave"></i></div>
                                    <div class="mb-4">
                                        <h5 class="card-title mb-0">Dette de la Semaine</h5>
                                    </div>
                                    <div class="row align-items-center mb-2 d-flex">
                                        <div class="col-8">
                                            <h2 class="d-flex align-items-center mb-0 totalDette">
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
                                    <div class="card-icon card-icon-large"><i class="fas fa-money-bill-1"></i></div>
                                    <div class="mb-4">
                                        <h5 class="card-title mb-0">Bénéfice de la semaine</h5>
                                    </div>
                                    <div class="row align-items-center mb-2 d-flex">
                                        <div class="col-8">
                                            <h2 class="d-flex align-items-center mb-0 totalBenefice">
                                                --
                                            </h2>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6">
                            <div class="card l-bg-orange-dark">
                                <div class="card-statistic-3 p-4">
                                    <div class="card-icon card-icon-large"><i class="fas fa-circle-dollar-to-slot"></i></div>
                                    <div class="mb-4">
                                        <h5 class="card-title mb-0">Depense de la semaine</h5>
                                    </div>
                                    <div class="row align-items-center mb-2 d-flex">
                                        <div class="col-8">
                                            <h2 class="d-flex align-items-center mb-0 totalDepense">
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
                                    <div class="card-icon card-icon-large"><i class="fab fa-stack-overflow"></i></div>
                                    <div class="mb-4">
                                        <h5 class="card-title mb-0">Stock de la semaine</h5>
                                    </div>
                                    <div class="row align-items-center mb-2 d-flex">
                                        <div class="col-8">
                                            <h2 class="d-flex align-items-center mb-0 totalQte">
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
                <a style="color: white;">Vos derniere ventes</a>
                <a href="printStatistique.php" target="blank" title="imprimer vos produits">imprimer</a>
            </div>

            <div class="table-responsive table-data">
                <table id="example" style="width:100%" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Nom Client</th>
                            <th>Montant à payer</th>
                            <th>Réduction</th>
                            <th>Reste à payer</th>
                            <th>Type de vente</th>
                        </tr>
                    </thead>
                    <tbody class="venteTable">
                        <!-- <tr data-idVente="" >
                        <td>4</td>
                        <td>1</td>
                        <td>1</td>
                        <td>1</td>
                    </tr>   -->
                    </tbody>
                </table>
            </div>

            <div class="btnZone">
                <a style="color: white;margin-bottom: 20px;">Vos meilleurs Ventes</a>
            </div>

            <div class="statZone">
                <div class="graphe polarZone">
                    <canvas id="polar"></canvas>
                </div>


                <div class="table-responsive table-data table2">
                    <table id="example" style="width:100%" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Quantité Vendue</th>
                                <th>Montant Vendue</th>
                                <th>Bénéfice</th>

                            </tr>
                        </thead>
                        <tbody class="produitTable">
                            <!-- <tr data-idBoutique="" >
                                <td>4</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                            </tr>   -->
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="statZone">
                <div class="graphe2 barZone">
                    <canvas id="bar"></canvas>
                </div>
                <div class="graphe3 lineZone">
                    <canvas id="line"></canvas>
                </div>
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
    <script src="../../style/js/dashboard/statistique.js"></script>
    <script src="../../style/include/toolBar/toolBar.js"></script>

</body>

</html>