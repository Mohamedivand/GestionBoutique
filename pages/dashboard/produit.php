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
    <link rel="stylesheet" href="../../style/css/dashboard/commande.css">
    <link rel="stylesheet" href="../../style/css/dashboard/vente.css">
    <link rel="stylesheet" href="../../style/loader/loader.css">
    <link rel="stylesheet" href="../../style/include/toolBar/toolBar.css">
    <link rel="stylesheet" href="../../style/css/navbar.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>



    <script src="../../lib/js/jquery-3.6.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <title>Vos produits de <?php echo ($boutique->getNomBoutique()) ?></title>
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
                    <i class="fa fa-paperclip"></i>
                    <span class="text">Gerez vos produits ici</span>
                </div>

                <div class="col-md-10 ">
                    <div class="row ">
                        <div class="col-xl-3 col-lg-6">
                            <div class="card l-bg-blue-dark">
                                <div class="card-statistic-3 p-4">
                                    <div class="card-icon card-icon-large"><i class="fab fa-stack-overflow"></i></div>
                                    <div class="mb-4">
                                        <h5 class="card-title mb-0">Produits disponible au stock</h5>
                                    </div>
                                    <div class="row align-items-center mb-2 d-flex">
                                        <div class="col-8">
                                            <h2 class="d-flex align-items-center mb-0 produit_number">
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
                                    <div class="card-icon card-icon-large"><i class="fas fa-cart-shopping"></i></div>
                                    <div class="mb-4">
                                        <h5 class="card-title mb-0">Total prix d'achat</h5>
                                    </div>
                                    <div class="row align-items-center mb-2 d-flex">
                                        <div class="col-8">
                                            <h2 class="d-flex align-items-center mb-0 totalP_achat">
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
                                    <div class="card-icon card-icon-large"><i class="fas fa-money-bill"></i></div>
                                    <div class="mb-4">
                                        <h5 class="card-title mb-0">Total prix de vente en details</h5>
                                    </div>
                                    <div class="row align-items-center mb-2 d-flex">
                                        <div class="col-8">
                                            <h2 class="d-flex align-items-center mb-0 totalP_venteDetails">
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
                                    <div class="card-icon card-icon-large"><i class="fas fa-money-bill"></i></div>
                                    <div class="mb-4">
                                        <h5 class="card-title mb-0">Total prix de vente en gros</h5>
                                    </div>
                                    <div class="row align-items-center mb-2 d-flex">
                                        <div class="col-8">
                                            <h2 class="d-flex align-items-center mb-0 totalP_venteEngros">
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
                                    <div class="card-icon card-icon-large"><i class="fas fa-user-group"></i></div>
                                    <div class="mb-4">
                                        <h5 class="card-title mb-0">Total des fournisseurs</h5>
                                    </div>
                                    <div class="row align-items-center mb-2 d-flex">
                                        <div class="col-8">
                                            <h2 class="d-flex align-items-center mb-0 fournisseur_number">
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
                    <a href="../form/ajouter_produit.php" title="Ajouter un produit">Ajouter</a>
                    <a href="../../print/printProduit.php" target="blank" title="imprimer vos produits">Imprimer</a>
                </div>

                <div class="table-responsive table-data">
                    <table id="example" style="width:100%" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="titleTab1">Image</th>
                                <th class="titleTab1">Produit</th>
                                <th class="titleTab2">P.Achat</th>
                                <th class="titleTab3">Quantité</th>
                                <th class="titleTab4">P.Vente.Detail</th>
                                <th class="titleTab5">P.Vente.Engros</th>
                                <th class="titleTab6">Marque</th>
                                <th class="titleTab6">Type</th>
                                <th class="titleTab6">Collection</th>
                                <th class="titleTab6">Catégorie</th>
                                <th class="titleTab6">code Barre</th>
                                <th class="titleTab6">Fournisseur</th>
                                <th class="titleTab6">Modifier</th>
                                <th class="titleTab6">Supprimer</th>
                            </tr>
                        </thead>
                        <tbody class="produitTable">
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

            <a id="downloadImg" style="display: none;" download="Le code bar de votre article"></a>

            <div class="historique historiqueZoneInactive" style="background: none;">
                <div class="popupZone" style="background: none; width: 300px">
                    <i class="fa-solid fa-xmark closeHisto"></i>
                    <div class="historiqueZone" id="contenuBar" style="background: none;width: 100% !important;height: 150px !important;box-shadow: none !important;font-size: x-large !important;">
                    </div>
                </div>
            </div>

            <!-- <div class="voirPlus">
                <button id="voirPlus">Voir plus</button>
            </div> -->
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

   
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script> -->
    <!-- <script src="https://unpkg.com/jspdf-invoice-template@1.4.0/dist/index.js"></script>
    <script src="../../print/test.js"></script> -->
    <script src="../../style/js/script.js"></script>
    <script src="../../style/js/navbar.js"></script>
    <script src="../../style/js/dashboard/produit.js"></script>
    <script src="../../style/include/toolBar/toolBar.js"></script>
</body>

</html>