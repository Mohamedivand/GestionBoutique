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
    <link rel="stylesheet" href="../../style/css/dashboard/boutique.css">
    <link rel="stylesheet" href="../../style/css/style.css">
    <link rel="stylesheet" href="../../style/css/dashboard/commande.css">
    <link rel="stylesheet" href="../../style/css/dashboard/vente.css">
    <link rel="stylesheet" href="../../style/include/loader/loader.css">
    <link rel="stylesheet" href="../../style/css/form/ajouterCarte.css" />
    <link rel="stylesheet" href="../../style/include/toolBar/toolBar.css">
    <link rel="stylesheet" href="../../style/css/navbar.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>



    <script src="../../lib/js/jquery-3.6.1.min.js"></script>
    <title>Vos Achats Extern de <?php echo ($boutique->getNomBoutique()) ?></title>
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
                    <i class="fa-solid fa-warehouse"></i>
                    <span class="text">Gerez vos achats extern ici</span>
                </div>

                <div class="col-md-10 ">
                    <div class="row ">
                        <div class="col-xl-3 col-lg-6">
                            <div class="card l-bg-blue-dark">
                                <div class="card-statistic-3 p-4">
                                    <div class="card-icon card-icon-large"><i class="fab fa-stack-overflow"></i></div>
                                    <div class="mb-4">
                                        <h5 class="card-title mb-0">Total de Commande effectuer</h5>
                                    </div>
                                    <div class="row align-items-center mb-2 d-flex">
                                        <div class="col-8">
                                            <h2 class="d-flex align-items-center mb-0 comandeNumber">
                                                --
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6">
                            <div class="card l-bg-blue-dark">
                                <div class="card-statistic-3 p-4">
                                    <div class="card-icon card-icon-large"><i class="fab fa-stack-overflow"></i></div>
                                    <div class="mb-4">
                                        <h5 class="card-title mb-0">Total Qte de produit Commander</h5>
                                    </div>
                                    <div class="row align-items-center mb-2 d-flex">
                                        <div class="col-8">
                                            <h2 class="d-flex align-items-center mb-0 commandeQte">
                                                --
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="inputZone">
                    <div class="inputs">
                        <input type="text" placeholder="recherche..." class="searchInput">
                        <select name="" id="dateSelect">
                            <option value="1">Toutes les commandes</option>
                            <option value="2"> Aujourd'hui</option>
                            <option value="3">Ce Mois ci</option>
                            <option value="4">Cette Annee</option>
                        </select>

                    </div>
                </div>

                <div class="table-responsive table-data">
                    <table id="example" style="width:100%" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="titleTab1">Date</th>
                                <th class="titleTab6">total_Qte_Commander</th>
                                <th class="titleTab6">fournisseur</th>
                                <th class="titleTab6">Montant Total</th>
                                <th class="titleTab6">Voir</th>
                                <th class="titleTab6">valider</th>
                                <th class="titleTab6">Action</th>
                            </tr>
                        </thead>
                        <tbody class="sousTraitanceTable">
                            <!--<tr data-idBoutique="" >
                   <td>Tiger Nixon</td>
                  <td>System Architect</td>
                  <td>Edinburgh</td>
                  <td>61</td>
                  <td>2011/04/25</td>
                  <td>$320,800</td>
                </tr> -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="historique  historiqueZoneInactive" id="produit">
                <div class="popupZone">
                    <i class="fa-solid fa-xmark closeHisto"></i>
                    <div class="historiqueZone">
                        <div class="histTitle">
                            <h3 class="titleHistorique">Produits Commander</h3>
                        </div>

                        <div class="table-responsive table-data">
                            <table id="example" style="width:100%" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th class="titleTab1">nom produit</th>
                                        <th class="titleTab2">qte produit</th>
                                        <th class="titleTab2">prix unitaire</th>
                                    </tr>
                                </thead>
                                <tbody class="infoAchat">
                                    <!-- <tr>
                                        <td>Tiger Nixon</td>
                                        <td>System Architect</td>
                                        <td>System Architect</td>
                                    </tr>    -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="historique  historiqueZoneInactive" id="produitZone">
                <div class="popupZone">
                    <i class="fa-solid fa-xmark closeHisto"></i>
                    <div class="historiqueZone">
                        <div class="histTitle">
                            <h3 class="titleHistorique">Produits Commander</h3>
                        </div>

                        <div class="table-responsive table-data">
                            <table id="example" style="width:100%" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th class="titleTab1">nom produit</th>
                                        <th class="titleTab2">qte produit</th>
                                        <th class="">prix</th>
                                    </tr>
                                </thead>
                                <tbody class="infoProduit">
                                    <!-- <tr>
                                        <td>Tiger Nixon</td>
                                        <td>System Architect</td>
                                        <td>System Architect</td>
                                    </tr>    -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="historique historique2 historiqueZoneInactive" id="qte">
                <div class="popupZone">
                    <i class="fa-solid fa-xmark closeHisto"></i>
                    <div class="historiqueZone">
                        <div class="histTitle">
                            <h3 class="titleHistorique">Modification</h3>
                        </div>

                        <div class="table-responsive table-data">
                            <table id="example" style="width:100%" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th class="titleTab1">nom produit</th>
                                        <th class="titleTab2">qte Demander</th>
                                        <th class="titleTab2">prix</th>
                                    </tr>
                                </thead>
                                <tbody class="updateProduit">
                                    <!-- <tr>
                                    <td>Tiger Nixon</td>
                                    <td>System Architect</td>
                                    <td>System Architect</td>
                                </tr>     -->
                                </tbody>
                            </table>
                        </div>
                    </div>
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
    <script src="../../style/js/dashboard/sousTraitance.js"></script>
    <script src="../../style/include/toolBar/toolBar.js"></script>

</body>

</html>