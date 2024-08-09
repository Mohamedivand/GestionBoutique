<?php
    ob_start();
    require("../../script/connexion_bd.php");
    require("../../class/User.php");
    require("../../class/Boutique.php");

    if(!isset($_COOKIE['idUser'], $_COOKIE['idBoutique'])){
        header("location:../dashboard/boutique.php");
    }
    $userTmp= new User($bdd, $_COOKIE['idUser']);
    if(!$userTmp->getExiste()){
        header("location:../../index.php");
    }

    if(!in_array($userTmp->getRole()->getRole(),array("admin", "proprietaire"))){
        header("location:../../index.php");  
    }

    $idBoutique= $_COOKIE['idBoutique'];
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
            <h6>Commandez des produits chez d'autres boutiques</h6>

            <a href="../dashboard/sousTraitance.php">
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
                                if($boutique->chargerProduit()){
                                    foreach($boutique->getTableauProduit() as $produit){
                            ?>
                                        <option
                                            data-idProduit="<?php echo($produit['idProduit']) ?>" 
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
                        <input id="quantiteZone1" type="number" min="1" value="1" required>
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
                            <th>Qt</th>
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

                <div class="form2">
                    <h4>Les informations du clients</h4>
                    
                    <div class="inputZone" required>
                        <label for="" >Nom de la boutique</label>

                        <input type="text" id="nomClient" placeholder="Nom de la boutique" required>
                    </div>

                    <div class="inputZone" required>
                        <label for="">Num de la boutique</label>

                        <input type="text" id="numClient" placeholder="Exemple: +223 66 03 53 00" required>
                    </div>
                    
                    <div class="inputZone" required>
                        <label for="">Email de la boutique</label>

                        <input type="email" id="emailClient" placeholder="Exemple: abc@gmail.com">
                    </div>
                    
                    <div class="inputZone" required>
                        <label for="">Adresse de boutique</label>

                        <input type="text" id="adresseClient" placeholder="Exemple: Bamako" value="Bamako" required>
                    </div>
                </div>

                <div class="infoVenteZone">
                    <div class="infoVente">
                        <span class="titre">
                            Nombre de produit
                        </span>

                        <span id="nombreProduitSpan">
                            --
                        </span>
                    </div>

                </div>  

                <div class="btnZone" id="validerBtnZone">
                    <button type="submit" class="btnValider" id="validerVenteBtn">
                        <span>
                            Valider
                            <i class="fa fa-file-pdf"></i>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="../../style/js/Form/ajouter_sousTraitence.js"></script>
</body>

</html>