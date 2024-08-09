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
    <link rel="stylesheet" href="../../style/include/loader/loader.css">
    <link rel="stylesheet" href="../../style/include/toolBar/toolBar.css">
    <link rel="stylesheet" href="../../style/css/navbar.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>

    <script src="../../lib/js/jquery-3.6.1.min.js"></script>

    <title>Caisse Temporaire</title>
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
                    <i class="fa fa-shop"></i>
                    <span class="text">Gestion Caisse Temporaire</span>
                </div>


                <div class="col-md-10 ">
                    <div class="row ">
                        <div class="col-xl-3 col-lg-6">
                            <div class="card l-bg-blue-dark">
                                <div class="card-statistic-3 p-4">
                                    <div class="card-icon card-icon-large"><i class="fas fa-receipt"></i></div>
                                    <div class="mb-4">
                                        <h5 class="card-title mb-0">Total en caisse</h5>
                                    </div>
                                    <div class="row align-items-center mb-2 d-flex">
                                        <div class="col-8">
                                            <h2 class="d-flex align-items-center mb-0 sommeTotal">
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
                                    <div class="card-icon card-icon-large"><i class="fas fa-money-bill"></i></div>
                                    <div class="mb-4">
                                        <h5 class="card-title mb-0">Total Depot Effectuer</h5>
                                    </div>
                                    <div class="row align-items-center mb-2 d-flex">
                                        <div class="col-8">
                                            <h2 class="d-flex align-items-center mb-0 totalDepot">
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
                                    <div class="card-icon card-icon-large"><i class="fas fa-money-bill"></i></div>
                                    <div class="mb-4">
                                        <h5 class="card-title mb-0">Total Retrait Effectuer</h5>
                                    </div>
                                    <div class="row align-items-center mb-2 d-flex">
                                        <div class="col-8">
                                            <h2 class="d-flex align-items-center mb-0 totalRetrait">
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
                <a href="../form/depot_caisse.php" class="depotCaisse" title="Faire un depot">Depot</a>
                <a href="../form/retrait_caisse.php" class="retraitCaisse" title="Faire un retrait">Retrait</a>
                <a href="../../print/printCaisse.php" target="blank" title="imprimer vos  Proformat">Imprimer Liste</a>
            </div>

            <div class="table-responsive table-data">
                <table id="example" style="width:100%" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="titleTab1">Date</th>
                            <th class="titleTab1">Nom Employer</th>
                            <th class="titleTab3">Numero Emploer</th>
                            <th class="titleTab2">Montant</th>
                            <th class="titleTab2">Type Transaction</th>
                            <th class="titleTab2">Motif </th>
                            <th class="titleTab2">supprimer </th>
                        </tr>
                    </thead>
                    <tbody class="caisseZone">
                        <!-- <tr>
                        <td>2</td>
                        <td>4</td>
                        <td>5</td>
                        <td>2</td>
                        <td>4</td>
                        <td>5</td>
                        <td><i class="fa-solid fa-pen"></i></td>
                        <td><i class="fa-solid fa-trash"></i></td>
                    </tr>  -->
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
    <script src="../../style/js/dashboard/caisse.js"></script>
    <script src="../../style/include/toolBar/toolBar.js"></script>

</body>

</html>