<?php
    ob_start();
    require_once("../script/connexion_bd.php");
    require_once("../class/Boutique.php");
    require_once("../class/Vente.php");

    if(!isset($_GET['idVente'],$_COOKIE['idBoutique'])){
        header("location:../pages/dashboard/vente.php");
        exit();
    }
    $idVente = $_GET['idVente'];
    $vente = new Vente($bdd, $idVente);
    $idBoutique = $_COOKIE['idBoutique'];
    $boutique = new Boutique($bdd, $idBoutique);
    if(!$vente->getExiste()){
        header("location:../pages/dashboard/vente.php");
        exit();
    }
    if(!$boutique->getExiste()){
        header("location:../pages/dashboard/vente.php");
        exit();
    }
    if($vente->getIdBoutique() != $boutique->getId() ){
        header("location:../pages/dashboard/vente.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../lib/js/jquery-3.6.1.min.js"></script>
    <script src="../lib/js/jspdf/jspdf.umd.min.js"></script>
    <script src="../lib/js/jspdf/html2canvas.min.js"></script>
    <link rel="stylesheet" href="../lib/css/fontawesome/css/all.css">
    <title>Recu de vente</title>
    <link rel="stylesheet" href="../style/css/print/content.css">
</head>
<body>
    <div id="printTable" class="main">
        <div class="header">
            <div style='font-weight: bold;'>
                <span>
                    Easy Managment
                </span>
                <br>
                <span>
                    Baco djicoronie ACI
                </span>
            </div>
            <div class="contactZone">
                <div>
                    <span>Tel:</span>
                    <a href="tel:66035300">
                        66035300
                    </a>
                </div>
                <div>
                    <span>Email:</span>
                    <a href="mailto:groupedjessy@gmail.com">
                        groupedjessy@gmail.com
                    </a>
                </div>
            </div>
        </div>

        <div>
            <p align="right">
                Achat numero <?php echo($vente->getId()) ?>
            </p>
            <h1 align="center">
                Reçu de vente chez <?php echo($boutique->getNomBoutique()) ?>
            </h1>
            <h4 align="center">
                Nom Client: <?php echo($vente->getClient()->getNom()) ?>
            </h4>
            <h4 align="center">
                Numéro Client: <?php echo($vente->getClient()->getContact()->getTel()) ?>
            </h4>
        </div>

        <div style="display: flex; justify-content: center;">
            <table>
                <thead>
                    <th>Produit</th>
                    <th>P.Unitaire</th>
                    <th>Quantite</th>
                    <th>Montant</th>
                </thead>
                <tbody>
                    <?php
                        // $produits = new Produit($bdd, $t);
                        if(!is_null($vente->getTableauProduit())){
                            $br = 0;
                            for($i = 0; $i<30; $i++){
                                foreach ($vente->getTableauProduit() as $produit) {
                                    $br++;
                    ?>
                                    <tr >
                                        <td>
                                            <?php echo($produit['produit']->getNomProduit()) ?>
                                        </td>
                                        <td>
                                            <?php echo($prixUnit = ($vente->getTypeVente()=="det") ? $produit['produit']->getPrixVenteDetail() : $produit['produit']->getPrixVenteEngros()) ?>
                                        </td>
                                        <td>
                                            <?php echo($produit['quantite']) ?>
                                        </td>
                                        <td>
                                            <?php echo($produit['quantite'] * $prixUnit) ?>
                                        </td>
                                    </tr>
                    <?php
                                    if($br == 35){
                    ?>
                                        <tr class="tableBr">
                                            <td></td>
                                            <td>
                                                <?php echo($boutique->getNomBoutique()) ?>
                                            </td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                    <?php
                                        $br=0;
                                    }
                                }
                            }
                        }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="infoZone">
            <b>Nombre total de produit: </b>
            <span>
                <?php echo(is_array($vente->getTableauProduit()) ? sizeof($vente->getTableauProduit()) : "--") ?> FCFA
            </span>
            <hr>

            <b>Reduction: </b>
            <span>
                <?php echo($vente->getReduction()) ?> FCFA
            </span>
            <hr>

            <b>Montant total a payer (reduction inclue): </b>
            <span>
                <?php echo($vente->getTotal_a_payer()) ?> FCFA
            </span>
            <hr>
        </div>

        <div class="signature" align="right">
            <h5>Achat effectuee le: <?php echo($vente->getDateVente()) ?></h5>
            <h3>Signature:</h3>
        </div>
    </div>

    <div class="downloadZone">
        <button id="printButton" onclick="print()">
            <span class="downloadIcone">
                <i class="fa fa-download"></i>
            </span>
            Print
        </button>
    </div>

    <script src="../style/js/print/content.js"></script>
    
</body>
</html>