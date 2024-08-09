<?php
    ob_start();
    try{
        // On verifie lutilisateur et la boutique
        require_once("../../script/connexion_bd.php");
        require_once("../../class/User.php");
        require_once("../../class/Boutique.php");
        require_once("../../class/Produit.php");
        require_once("../../class/Type.php");
        require_once("../../class/Categorie.php");
        require_once("../../class/Collection.php");
        require_once("../../class/Marque.php");

        if(!isset($_COOKIE['idUser'], $_COOKIE['idBoutique'], $_GET['idProduit'])){
            header('location:../dashboard/produit.php?popUpText=3');
            exit();
        }

        $idUser=$_COOKIE['idUser'];
        $idBoutique=$_COOKIE['idBoutique'];
        $idProduit=$_GET['idProduit'];

        $user= new User($bdd, $idUser);
        $boutique= new Boutique($bdd, $idBoutique);
        $produit= new Produit($bdd, $idProduit);

        if(!$user->getExiste() || !in_array($user->getRole()->getRole() , array("admin", "proprietaire"))){
            header('location:../dashboard/produit.php?popUpText=5');
            exit();
        }

        if(!$boutique->getExiste()){
            header('location:../dashboard/produit.php?popUpText=4');
            exit();
        }
        
        if(!$produit->getExiste()){
            header('location:../dashboard/produit.php?popUpText=10');
            exit();
        }

        if($produit->getIdBoutique() != $boutique->getId()){
            header('location:../dashboard/produit.php?popUpText=10');
            exit();
        }

    }
    catch(Exception $e){
        header('location:../dashboard/produit.php?popUpText=7');
    }
?>

<!DOCTYPE html>

<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Ajoutez des produits a votre site Djessy Parfuma (Parfumerie malienne)">
        <meta name="author" content="Djessy Parfuma">
        <meta name="image" content="../../ressources/images/logo.webp">

        <meta property="og:title" content="Ajoue de produit">
        <meta property="og:description" content="Ajoutez un produit">
        <meta property="og:image" content="../../ressources/images/logo.webp">

        <link rel="stylesheet" href="../../style/css/form/ajouterProduit.css">
        <link rel="stylesheet" href="../../style/include/pop_up/pop_up.css">
        <link rel="stylesheet" href="../../lib/css/fontawesome/css/all.css">

        <link rel="icon" type="image/x-icon" href="../../res/images/logo.webp">
        <link rel="apple-touch-icon" href="../../res/images/logo.webp">

        <script src="../../lib/js/jquery-3.6.1.min.js"></script>
        <title>Modification de produit</title>
    </head>

    <body>

        <div id="container">
            <section class="sec_form">
                <div class="title">
                    <img src="../../res/images/logo.svg" alt="Logo de Djessy Parfuma (Parfumerie malienne)" />
                    <h1 style="max-width:100%; overflow:hidden" title="Modification de <?php echo($produit->getNomProduit()) ?>">
                        Modification de <?php echo($produit->getNomProduit()) ?>
                    </h1>
                </div>

                <form action="traitement/modifier_produit.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="idProduit" value="<?php echo($produit->getId()) ?>">

                    <label for="">Nom du produit</label>
                    <input name="nom" type="text" value="<?php echo($produit->getNomProduit()) ?>" placeholder="Saisissez le nom du produit" class="inp_text" required>

                    <label for="">Prix d'achat</label>
                    <input name="prixAchat" type="number" value="<?php echo($produit->getPrixAchat()) ?>" placeholder="Saisissez le prix d'achat"  class="inp_text" min="0" required>
                    
                    <label for="">Prix de vente en engros</label>
                    <input name="prixVenteEngros" type="number" value="<?php echo($produit->getPrixVenteEngros()) ?>" placeholder="Saisissez le prix de vente en engros"  class="inp_text" min="0" required>
                    
                    <label for="">Prix de vente en detail</label>
                    <input name="prixVenteDetail" type="number" value="<?php echo($produit->getPrixVenteDetail()) ?>" placeholder="Saisissez le prix de vente en detail"  class="inp_text" min="0" required>
                    
                    <label for="">Quantite dans la boutique</label>
                    <input name="quantite" type="number" value="<?php echo($produit->getQuantite()) ?>" placeholder="Saisissez la quantite disponible"  class="inp_text" min="0" required>
                    
                    <label for="">Quantite dans l'entrepot</label>
                    <input name="quantiteEntrepot" type="number" value="<?php echo($produit->getQuantiteEntrepot()) ?>" placeholder="Saisissez la quantite disponible"  class="inp_text" min="0" required>
                    
                    <label for="">Description</label>
                    <input name="description" type="text" value="<?php echo($produit->getDescription()) ?>" placeholder="Saisissez la description du produit"  class="inp_text" required>

                    <label for="codeBar">Scannez le codeBar <i class="fa fa-barcode"></i></label>
                    <input 
                        name="codeBar" 
                        id="codeBar" 
                        type="text" 
                        value="<?php echo($produit->getCodeBar()) ?>" 
                        placeholder="La valeur du codeBar s'affichera ici"  
                        class="inp_text"
                    >

                    <label for="type">Type de produit</label>
                    <div class="selectdiv">
                        <select name="type" id="type">
                            <option value="aucun" selected>Aucun</option>

                            <?php
                                $query=$bdd->prepare('SELECT * FROM `type` WHERE id_boutique=? ORDER BY nomType');
                                $query->execute(array($boutique->getId()));
                                while($res=$query->fetch()){
                                    $res_tmp = new Type($bdd, null, $res);
                                    $selected_tmp = "";
                                    if($produit->getType()->getExiste()){
                                        if($produit->getType()->getId() == $res_tmp->getId()){
                                            $selected_tmp= "selected";
                                        }
                                    }
                            ?>
                                    <option value="<?php echo($res_tmp->getId()) ?>" <?php echo($selected_tmp) ?>>
                                        <?php echo($res_tmp->getNomType()) ?>
                                    </option>
                            <?php
                                }
                            ?>
                        </select>
                    </div>

                    <label for="categorie">Categories du produit</label>
                    <div class="selectdiv">
                        <select name="categorie" id="categorie">
                            <option value="aucun" selected>Aucun</option>

                            <?php
                                $query=$bdd->prepare('SELECT * FROM `categorie` WHERE id_boutique=? ORDER BY nomCategorie');
                                $query->execute(array($boutique->getId()));
                                while($res=$query->fetch()){
                                    $res_tmp = new Categorie($bdd, null, $res);
                                    $selected_tmp = "";
                                    if($produit->getCategorie()->getExiste()){
                                        if($produit->getCategorie()->getId() == $res_tmp->getId()){
                                            $selected_tmp= "selected";
                                        }
                                    }
                            ?>
                                    <option value="<?php echo($res_tmp->getId()) ?>" <?php echo($selected_tmp) ?>>
                                        <?php echo($res_tmp->getNomCategorie()) ?>
                                    </option>
                            <?php
                                }
                            ?>
                        </select>
                    </div>

                    <label for="collection">Collection du produit</label>
                    <div class="selectdiv">
                        <label>
                            <select name="collection" id="collection">
                                <option value="aucun" selected>Aucun</option>

                                <?php
                                    $query=$bdd->prepare('SELECT * FROM `collection` WHERE id_boutique=? ORDER BY nomCollection');
                                    $query->execute(array($boutique->getId()));
                                    while($res=$query->fetch()){
                                        $res_tmp = new Collection($bdd, null, $res);
                                        $selected_tmp = "";
                                        if($produit->getCollection()->getExiste()){
                                            if($produit->getCollection()->getId() == $res_tmp->getId()){
                                                $selected_tmp= "selected";
                                            }
                                        }
                                ?>
                                        <option value="<?php echo($res_tmp->getId()) ?>" <?php echo($selected_tmp) ?>>
                                            <?php echo($res_tmp->getNomCollection()) ?>
                                        </option>
                                <?php
                                    }
                                ?>
                            </select>
                        </label>
                    </div>
                    
                    <label for="marque">Marque du produit</label>
                    <div class="selectdiv">
                        <label>
                            <select name="marque" id="marque">
                                <option value="aucun" selected>Aucun</option>
                                
                                <?php
                                    $query=$bdd->prepare('SELECT * FROM `marque` WHERE id_boutique=? ORDER BY nomMarque');
                                    $query->execute(array($boutique->getId()));
                                    while($res=$query->fetch()){
                                        $res_tmp = new Marque($bdd, null, $res);
                                        $selected_tmp = "";
                                        if($produit->getMarque()->getExiste()){
                                            if($produit->getMarque()->getId() == $res_tmp->getId()){
                                                $selected_tmp= "selected";
                                            }
                                        }
                                ?>
                                        <option value="<?php echo($res_tmp->getId()) ?>" <?php echo($selected_tmp) ?>>
                                            <?php echo($res_tmp->getNomMarque()) ?>
                                        </option>
                                <?php
                                    }
                                ?>
                            </select>
                        </label>
                    </div>
                    
                    <label for="fournisseur">Fournisseur du produit</label>
                    <div class="selectdiv">
                        <label>
                            <select name="fournisseur" id="fournisseur">
                                <option value="aucun" selected>Aucun</option>

                                <?php
                                    $boutique->chargerFournisseur();

                                    foreach($boutique->getTableauFournisseur() AS $fournisseur_tmp){
                                ?>
                                        <option 
                                            value="<?php echo($fournisseur_tmp['idUser']) ?>" 
                                            <?php 
                                                if(!is_null($produit->getFournisseur())){
                                                    echo($fournisseur_tmp['idUser'] != $produit->getFournisseur()->getId()) ? "": "selected";
                                                }
                                            ?>
                                        >
                                            <?php echo($fournisseur_tmp['nomUser']." ".$fournisseur_tmp['prenomUser']) ?>
                                        </option>
                                <?php
                                    }
                                ?>
                            </select>
                        </label>
                    </div>
                    

                    <label for="myFile">Selectionner les photos du produit</label>

                    <label class="image-class" id="image-class" for="myFile">
                        <i class="fas fa-regular fa-camera"></i>
                    </label>
                    <input name="files[]" type="file" accept="image/png, image/gif, image/jpeg, image/webp, image/jpg, image/heivc" class="inp_image" id="myFile" onchange="handle()" multiple>

                    <div class="imageSliderZone">

                        <!-- on listera les imagess ici -->
                        <?php
                            if($produit->getImageProduit()){
                        ?>
                                <div class="productImagesZone">
                                    <img src="<?php echo("../../res/images/produit/".$produit->getImageProduit()) ?>" alt="">
                                </div>
                        <?php
                            }
                            if(!is_null($produit->getTableauImage())){

                                foreach($produit->getTableauImage() AS $image_tmp){
                        ?>
                                    <div class="productImagesZone">
                                        <img src="<?php echo($image_tmp['nomImageProduit']) ?>" alt="">
                                    </div>
                        <?php
                                }
                            }

                        ?>

                    </div>

                    <div class="options_form">
                        <button type="submit" name="modifier"><b>Ajouter</b></button>
                        <button type="reset" name="annuler" onclick="cancel()"><b>Annuler</b></button>  
                        <script src="../../style/js/cancel_btn.js"></script>
                    </div> 
                </form>

                <?php
                    include('../../include/pop_up.php');
                ?>
        
            </section>
        </div>

        <script src="../../style/js/Form/ajouterProduit.js"></script>        
        <script src="../../style/include/pop_up/pop_up.js"></script>
    </body>
</html>

<?php
    ob_end_flush();
?>
