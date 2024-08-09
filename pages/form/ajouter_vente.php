<?php
ob_start();
require("../../script/connexion_bd.php");
require("../../class/User.php");
require("../../class/Boutique.php");

if (!isset($_COOKIE['idUser'], $_COOKIE['idBoutique'])) {
    header("location:../dashboard/boutique.php");
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
    header("location:../dashboard/boutique.php");
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout de vente</title>
    <script src="../../lib/js/jquery-3.6.1.min.js"></script>
    <link rel="stylesheet" href="../../lib/css/fontawesome/css/all.css">
    <link rel="stylesheet" href="../../style/css/form/ajouter_vente.css">
</head>

<body>
    <div class="main">
        <header>
            <h6>Effectuez une vente</h6>

            <a href="../dashboard/vente.php">
                <i class="fa fa-arrow-left"></i>

                <span>Quitter</span>
            </a>
        </header>

        <div class="contenue">
            <div class="partie1">
                <form action="" class="form form1" id="form1">
                    <h4>Selectionnez le produit</h4>

                    <div class="inputZone" required>
                        <label for="">Code Bar</label>
                        <div class="searchZone">
                            <input 
                                type="text" 
                                placeholder="Cliquez ici et scanner votre produit" 
                                id="codeBar"
                            >

                            <i class="fa fa-barcode searchBtn"></i>
                        </div>
                    </div>

                    <div class="inputZone searchProduitZone">
                        <div class="searchZone">
                            <input type="text" placeholder="Recherchez un produit" id="searchInput">

                            <i class="fa fa-search searchBtn"></i>
                        </div>

                        <select id="selectProduitZone1" required>
                            <?php
                            if ($boutique->chargerProduit()) {
                                foreach ($boutique->getTableauProduit() as $produit) {
                                    if ($produit['quantiteProduit'] < 1) {
                                        continue;
                                    }
                            ?>
                                    <option 
                                        data-idProduit="<?php echo ($produit['idProduit']) ?>" 
                                        data-prixDet="<?php echo ($produit['prixVenteDetail'])  ?>" 
                                        data-prixGro="<?php echo ($produit['prixVenteEngros'])  ?>" 
                                        data-stock="<?php echo ($produit['quantiteProduit'])  ?>" 
                                        data-codeBar="<?php echo ($produit['codeBar'])  ?>" 
                                        selected
                                    ><?php echo ($produit['nomProduit']) ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="inputZone" required>
                        <label for="">Quantite</label>
                        <input id="quantiteZone1" type="number" min="1" required>
                    </div>

                    <div class="inputZone">
                        <div>
                            <label>Prix Detaillant</label>
                            <input value="0" id="prixDetZone1" disabled>
                        </div>

                        <div>
                            <label>Prix Grossiste</label>
                            <input value="0" id="prixGroZone1" disabled>
                        </div>
                    </div>

                    <div class="inputZone">
                        <div>
                            <label>Prix personnel</label>
                            <input type="number" id="prixPersonnel" placeholder="Donnez votre prix. minimum = 5 fcfa" min="5" >
                        </div>
                    </div>

                    <div class="btnZone">
                        <button type="submit" class="btnValider">
                            Ajouter au panier
                            <i class="fa fa-shopping-bag"></i>
                        </button>
                    </div>
                </form>

                <div class="ListeProduitZone">
                    <h4>La liste des produits</h4>
                    <table>
                        <thead>
                            <th>Produit</th>
                            <th>P.U</th>
                            <th>Qt</th>
                            <th>Total</th>
                            <th>Action</th>
                        </thead>

                        <tbody id="tableauListeProduit">
                        </tbody>
                    </table>


                    <p>
                        <button class="btnAnnuler" id="btnViderTableau" style="width: 100%; color:white">
                            Vider
                            <i class="fa fa-refresh"></i>
                        </button>
                    </p>
                </div>
            </div>

            <form class="form partie2" id="formFinalVente">
                <div class="typeVenteZone">
                    <span for="">Type de vente</span>
                    <select id="typeVente" required>
                        <option value="det" selected>Vente detaillant</option>
                        <option value="gro">Vente Grossiste</option>
                    </select>
                </div>

                <div action="" class="form2">
                    <h4>Les informations du clients</h4>

                    <?php
                    $numUser = 0;
                    $query = $bdd->query("SELECT COUNT(idVente) AS nbrVente FROM vente");
                    if ($res = $query->fetch()) {
                        $numUser = $res['nbrVente'] + 1;
                    }
                    ?>

                    <div class="inputZone" required>
                        <label for="">Nom du client</label>

                        <input type="text" id="nomClient" value="Client #<?php echo ($numUser) ?>" placeholder="Donnez le nom du client" required>
                    </div>

                    <div class="inputZone" required>
                        <label for="">Num du client</label>

                        <input type="text" id="numClient" value="-- -- <?php echo ($numUser) ?> -- --" placeholder="Exemple: +223 66 03 53 00" required>
                    </div>
                </div>

                <div action="" class="form2">
                    <h4>Informations de paiment</h4>

                    <div class="inputZone" required>
                        <label for="">Somme recu</label>

                        <input type="number" id="sommeRecu" placeholder="Saisissez le montant recu" value="0" step="5" min="0" required>
                    </div>

                    <div class="inputZone" required>
                        <label for="">Faire reduction</label>

                        <input type="number" id="reduction" placeholder="Somme reduction" value="0" step="5" min="0" required>

                        <span class="reductionCheck" id="reductionBtn">
                            <i class="fa fa-check"></i>
                        </span>
                    </div>
                </div>

                <div class="infoVenteZone">
                    <h3 align="center">resume de la vente</h3>

                    <div class="infoVente">
                        <span class="titre">
                            Nombre de produit
                        </span>

                        <span id="nombreProduitSpan">
                            --
                        </span>
                    </div>

                    <div class="infoVente">
                        <span class="titre">
                            montant total hors remise
                        </span>

                        <span id="montantHorsRemise">
                            --
                        </span>
                    </div>

                    <div class="infoVente">
                        <span class="titre">
                            montant total avec remise
                        </span>

                        <span id="montantAvecRemise">
                            --
                        </span>
                    </div>

                    <div class="infoVente">
                        <span class="titre">
                            montant a remettre
                        </span>

                        <span id="montantARemettre">
                            --
                        </span>
                    </div>
                </div>

                <div class="btnZone" id="validerBtnZone">
                    <button type="submit" class="btnValider" id="validerVenteBtn">
                        <span>
                            Valider la vente
                            <i class="fa fa-shopping-cart"></i>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="../../style/js/Form/ajouter_vente.js"></script>
</body>

</html>