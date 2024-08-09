<?php
    ob_start();
    require("../../script/connexion_bd.php");
    require("../../class/Boutique.php");

    if(!isset($_GET['idBoutique'])){
        header("location:../../error/404/index.php?message=1");
        // doit afficher boutique introuble
        exit();
    }

    $idBoutique= $_GET['idBoutique'];
    $boutique = new Boutique($bdd , $idBoutique);
    if(!$boutique->getExiste()){
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
            <h6>Commande chez <?php echo $boutique->getNomBoutique() ?></h6>

            <a href="../dashboard/commande.php">
                <span>
                    <i class="fa-solid fa-arrow-left"></i>
                </span>
                Retour

            </a>
        </header>

        <div class="contenue">
            <div class="partie1">
                <form action="" class="form form1" id="form1">
                    <h4>Selectionnez le produit</h4>

                    <div class="inputZone searchProduitZone">
                        <div class="searchZone">
                            <input type="text" placeholder="Recherchez un produit" id="searchInput">

                            <i class="fa fa-search searchBtn"></i>
                        </div>

                        <select id="selectProduitZone1" required>
                            <?php
                                if($boutique->chargerProduit()){
                                    foreach($boutique->getTableauProduit() as $produit){
                                        if($produit['quantiteProduit'] < 1){
                                            continue;
                                        }
                            ?>
                                        <option
                                            data-idProduit="<?php echo($produit['idProduit']) ?>" 
                                            data-prixDet="<?php echo($produit['prixVenteDetail'])  ?>" 
                                            data-prixGro="<?php echo($produit['prixVenteEngros'])  ?>" 
                                            data-stock="<?php echo($produit['quantiteProduit'])  ?>" 
                                            selected
                                        ><?php echo($produit['nomProduit']) ?></option>
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
                    <span for="">Quel type de client etes vous?</span>
                    <select id="typeVente" required>
                        <option value="det" selected>Detaillant</option>
                        <option value="gro">Grossiste</option>
                    </select>
                </div>

                <div class="form2">
                    <h4>Informations du clients</h4>

                    <div class="inputZone" required>
                        <label for="">Num du client</label>

                        <input type="text" id="numClient" placeholder="Exemple: +223 66 03 53 00" required>
                    </div>

                    <div class="inputZone" required>
                        <label for="">whatsapp du client</label>

                        <input type="text" id="whatsappClient" placeholder="Exemple: +223 66 03 53 00">
                    </div>
                    
                    <div class="inputZone" required>
                        <label for="">Email du client</label>

                        <input type="email" id="emailClient" placeholder="Exemple: abc@gmail.com">
                    </div>
                    
                    <div class="inputZone" required>
                        <label for="">Adresse du client</label>

                        <input type="text" id="adresseClient" placeholder="Exemple: Bamako" value="Bamako" required>
                    </div>
                </div>

                <div class="infoVenteZone">
                    <h3 align="center">resume sur le proformat</h3>

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
                            montant total de la commande
                        </span>

                        <span id="montantHorsRemise">
                            --
                        </span>
                    </div>

                </div>  

                <div class="btnZone" id="validerBtnZone">
                    <button type="submit" class="btnValider" id="validerVenteBtn">
                        <span>
                            Effectuer et Generer un bon de commande
                            <i class="fa fa-file-pdf"></i>
                        </span>
                    </button>
                    <!-- <button type="submit" class="btnValider" id="validerVenteBtn">
                        <span>
                            Effectuer commande
                            <i class="fa fa-file-pdf"></i>
                        </span>
                    </button> -->
                </div>
            </form>
        </div>
    </div>

    <script src="../../style/js/extern_public/ajouter_commande.js"></script>
</body>

</html>