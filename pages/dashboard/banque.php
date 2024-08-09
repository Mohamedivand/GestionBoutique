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
    <link rel="stylesheet" href="../../style/css/dashboard/vente.css">
    <link rel="stylesheet" href="../../style/include/loader/loader.css">
    <link rel="stylesheet" href="../../style/css/form/ajouterCarte.css" />
    <link rel="stylesheet" href="../../style/include/toolBar/toolBar.css">
    <link rel="stylesheet" href="../../style/css/navbar.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>


    <script src="../../lib/js/jquery-3.6.1.min.js"></script>

    <title>Gestion Bancaire</title>
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
                    <span class="text">Gestion Bancaire</span>
                </div>
                <div class="creditZone">
                    <!-- carteListe -->
                </div>


                <div class="btnZone">
                    <a href="#" class="addCard" title="ajouter un compte">Ajouter un nouveau compte</a>
                    <a href="#" class="depotBtn" title="Faire un depot">Depot</a>
                    <a href="#" class="retraitbtn" title="Faire un retrait">Retrait</a>
                    <a href="#" class="pritBtn" title="imprimer votre Historique">Imprimer</a>
                </div>

                <div class="table-responsive table-data">
                    <table id="example" style="width:100%" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="titleTab1">Date</th>
                                <th class="titleTab1">Nom Employer</th>
                                <th class="titleTab3">Numero Employer</th>
                                <th class="titleTab3">Numero compte</th>
                                <th class="titleTab2">Montant</th>
                                <th class="titleTab2">Operation</th>
                                <th class="titleTab2">Motif</th>
                                <th class="titleTab2">Supprimer</th>
                            </tr>
                        </thead>
                        <tbody class="banqueZone">
                            <!-- transactionListe  -->
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

            <div class="historique historiqueZoneInactive">
                <div class="popupZone">
                    <i class="fa-solid fa-xmark closeHisto"></i>
                    <div class="historiqueZone">
                        <div class="container">
                            <header>
                                <span class="logo">
                                    <img src="../../res/images/creditCard3.png" alt="" />
                                    <h5>Nom Boutique</h5>
                                </span>
                                <img src="../../res/images/creditCard2.png" alt="" class="chip" />
                            </header>
                            <div class="card-details">
                                <div class="name-number">
                                    <h6>Numero compte (obligatoire)*</h6>
                                    <input type="number" class="number" id="numCard" placeholder="Votre numero de compte" required>
                                </div>
                                <div class="name-number">
                                    <h6>Nom de la banque</h6>
                                    <input type="text" placeholder="Le nom de la banque" class="number" id="nomBanque">
                                </div>
                                <div class="name-number">
                                    <h6>somme (obligatoire)*</h6>
                                    <input type="number" class="number" placeholder="Le solde de votre compte" id="soldeCard" value="0" required>
                                </div>
                                <input type="submit" value="envoyer" class="btnEnvoyer" id="btnCreateCard">
                            </div>
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
    <script src="../../style/js/dashboard/banque.js"></script>
    <script src="../../style/include/toolBar/toolBar.js"></script>

</body>

</html>