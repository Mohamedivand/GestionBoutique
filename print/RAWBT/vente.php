<?php
ob_start();
require_once("../../script/connexion_bd.php");
require_once("../../class/Boutique.php");
require_once("../../class/Vente.php");

if (!isset($_GET['idVente'], $_COOKIE['idBoutique'])) {
    header("location:../pages/dashboard/vente.php");
    exit();
}
$idVente = $_GET['idVente'];
$vente = new Vente($bdd, $idVente);
$idBoutique = $_COOKIE['idBoutique'];
$boutique = new Boutique($bdd, $idBoutique);
if (!$vente->getExiste()) {
    header("location:../pages/dashboard/vente.php");
    exit();
}
if (!$boutique->getExiste()) {
    header("location:../pages/dashboard/vente.php");
    exit();
}
if ($vente->getIdBoutique() != $boutique->getId()) {
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
    <title>Recu de vente</title>

    <style>
        body{
            max-width: calc(8cm - 8px);
            margin: 0;
            padding: 8px;
            font-family: Arial, Helvetica, sans-serif;
        }

        .borderBotton{
            border-bottom: 2px dotted;
        }

        .duo{
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .duo > *{
            max-width: 50%;
        }

        .title{
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <p class="borderBotton">
        Achat numero
        <?php echo ($vente->getId()) ?>
    </p>

    <div>
        <?php
            // $produits = new Produit($bdd, $t);
            if (!is_null($vente->getTableauProduit())) {
                foreach ($vente->getTableauProduit() as $produit) {
        ?>
                    <p class="duo">
                        <span>
                            <?php echo ($produit['produit']->getNomProduit()) ?>
                        </span>

                        <span>
                            <?php echo ($prixUnit = ($vente->getTypeVente() == "det") ? $produit['produit']->getPrixVenteDetail() : $produit['produit']->getPrixVenteEngros()) ?>
                            FCFA *
                            <?php echo ($produit['quantite']) ?>
                        </span>
                    </p>
        <?php
                }
            }
        ?>
    </div>

    <hr>
    
    <div>
        <p>
            <b>Nombre total de produit: </b>
            
            <?php echo (is_array($vente->getTableauProduit()) ? sizeof($vente->getTableauProduit()) : "--") ?>
        </p>

        <p>
            <b>Reduction: </b>

            <?php echo ($vente->getReduction()) ?> FCFA
        </p>
        
        <p>
            <b>Montant total a payer (reduction inclue): </b>

            <?php echo ($vente->getTotal_a_payer()) ?> FCFA
        </p>
    </div>

    <hr>

    <div align="right">
        <h5>Achat effectuee le:
            <?php echo ($vente->getDateVente()) ?>
        </h5>
    </div>

    <p style="border: 1px solid; padding: 10px; background: black; color: white">
        <span class="title">
            <?php echo ($boutique->getNomBoutique()) ?>
        </span>
         Vous remercie pour votre achat
    </p>


</body>

</html>