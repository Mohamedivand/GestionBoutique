<?php
ob_start();
require("../../script/connexion_bd.php");
require("../../class/User.php");

if (!isset($_COOKIE['idUser'])) {
    header("location:../../index.php");
}
$userTmp = new User($bdd, $_COOKIE['idUser']);
if (!$userTmp->getExiste() || !in_array($userTmp->getRole()->getRole(), array("admin"))) {
    header("location:../../index.php");
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

    <title>Vos sites autorises</title>
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
                    <i class="fa fa-globe"></i>
                    <span class="text">Gerez vos sites autoriser ici</span>
                </div>

                <!-- <div class="boxes">
                    <div class="box box1">
                        <i class="fa fa-globe"></i>
                        <span class="text">Sites autoriser</span>
                        <?php
                        $query = $bdd->query('SELECT COUNT(*) AS nbrSite FROM `site`');
                        $res = $query->fetch();
                        ?>
                        <span class="number nbrUser"><?php echo ($res['nbrSite']) ?></span>
                    </div>
                </div>  -->

                <div class="col-md-10 ">
                    <div class="row ">
                        <div class="col-xl-3 col-lg-6">
                            <div class="card l-bg-blue-dark">
                                <div class="card-statistic-3 p-4">
                                    <div class="card-icon card-icon-large"><i class="fas fa-globe"></i></div>
                                    <div class="mb-4">
                                        <h5 class="card-title mb-0">Sites autoriser</h5>
                                    </div>
                                    <div class="row align-items-center mb-2 d-flex">
                                        <div class="col-8">
                                            <?php
                                            $query = $bdd->query('SELECT COUNT(*) AS nbrSite FROM `site`');
                                            $res = $query->fetch();
                                            ?>
                                            <h2 class="d-flex align-items-center mb-0 totalClient">
                                                <?php echo ($res['nbrSite']) ?>
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
                <a href="../form/ajouterSite.php" title="Ajouter un site">Ajouter</a>
            </div>

            <div class="table-responsive table-data">
                <table id="example" style="width:100%" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Nom de domaine</th>
                            <th>token</th>
                            <th>Supprimer</th>
                        </tr>
                    </thead>
                    <tbody class="userTable">
                        <?php
                        $query = $bdd->query('SELECT * FROM `site` ORDER BY nomDomaine');
                        while ($res = $query->fetch()) {
                        ?>
                            <tr data-idSite="<?php echo ($res['idSite']) ?>">
                                <td><?php echo ($res['nomDomaine'] ? $res['nomDomaine'] : "--") ?></td>
                                <td><?php echo ($res['token'] ? $res['token'] : "--") ?></td>
                                <td>
                                    <a>
                                        <i class="fa fa-trash actionDeleteMagasin"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!--  <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Vos sites</h3>
                        <a href="../form/ajouterSite.php"  id="ajouter" title="Ajouter un site">
                            <i class="fa fa-plus fa-3x btnAdd"></i>
                        </a>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Nom de domaine</th>
                                <th>token</th>
                                <th>Supprimer</th>
                            </tr>
                        </thead>

                        <tbody class="produitTable">
                            <?php
                            $query = $bdd->query('SELECT * FROM `site` ORDER BY nomDomaine');
                            while ($res = $query->fetch()) {
                            ?>
                                    <tr data-idSite="<?php echo ($res['idSite']) ?>" >
                                        <td><?php echo ($res['nomDomaine'] ? $res['nomDomaine'] : "--") ?></td>
                                        <td><?php echo ($res['token'] ? $res['token'] : "--") ?></td>
                                        <td>
                                            <a>
                                                <i class="fa fa-trash actionDeleteMagasin"></i>
                                            </a>
                                        </td>
                                    </tr>
                            <?php
                            }
                            ?>
                            
                        </tbody>
                    </table>
                </div>
            </div> -->
        </div>

        <div class="popup popupInactive">
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
    <script src="../../style/js/dashboard/site_autorise.js"></script>
    <script src="../../style/include/toolBar/toolBar.js"></script>


</body>

</html>