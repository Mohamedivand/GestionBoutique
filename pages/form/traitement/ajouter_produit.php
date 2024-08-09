<?php
ob_start();
try {
    // On verifie lutilisateur et la boutique
    require_once("../../../script/connexion_bd.php");
    require_once("../../../class/User.php");
    require_once("../../../class/Boutique.php");

    if (!isset($_COOKIE['idUser'], $_COOKIE['idBoutique'])) {
        header('location:../ajouter_produit.php?popUpText=3');
        exit();
    }
    $idUser = $_COOKIE['idUser'];
    $idBoutique = $_COOKIE['idBoutique'];

    $user = new User($bdd, $idUser);
    $boutique = new Boutique($bdd, $idBoutique);
    if (!$user->getExiste() || !in_array($user->getRole()->getRole(), array("admin", "proprietaire"))) {
        header('location:../ajouter_produit.php?popUpText=5');
        exit();
    }
    if (!$boutique->getExiste()) {
        header('location:../ajouter_produit.php?popUpText=4');
        exit();
    }

    if (!isset($_POST['nom'], $_POST['prixAchat'], $_POST['prixVenteEngros'], $_POST['prixVenteDetail'], $_POST['quantite'], $_POST['description'])) {
        header("location:../ajouter_produit.php?popUpText=6");
        exit();
    }
    $nom = $_POST['nom'];
    $prixAchat = $_POST['prixAchat'];
    $prixVenteDetail = $_POST['prixVenteDetail'];
    $prixVenteEngros = $_POST['prixVenteEngros'];
    $quantite = $_POST['quantite'];
    $quantiteEntrepot = $_POST['quantiteEntrepot'];
    $description = $_POST['description'];
    $codeBar = (isset($_POST['codeBar'])) ? $_POST['codeBar'] : null;

    $type = ($_POST['type'] == "aucun") ? null : $_POST['type'];
    $categorie = ($_POST['categorie'] == "aucun") ? null : $_POST['categorie'];
    $collection = ($_POST['collection'] == "aucun") ? null : $_POST['collection'];
    $marque = ($_POST['marque'] == "aucun") ? null : $_POST['marque'];
    $fournisseur = ($_POST['fournisseur'] == "aucun") ? null : $_POST['fournisseur'];

    $destination = "../../../res/images/produit/";
    $allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'heivc', 'webp');

    $fileNames = array_filter($_FILES['files']['name']);

    if (!empty($fileNames)) {
        $produit_ajouter = false;

        foreach ($_FILES['files']['name'] as $key => $val) {
            $extension = pathinfo($_FILES["files"]["name"][$key], PATHINFO_EXTENSION);

            if (in_array($extension, $allowTypes)) {
                $filename = uniqid() . "-" . time();
                $basename = $filename . "." . $extension;
                $source = $_FILES["files"]["tmp_name"][$key];
                $path = $destination . $basename;

                if (move_uploaded_file($source, $path)) {
                    if (!$produit_ajouter) {
                        $query = $bdd->prepare('INSERT INTO produit (codeBar, imageProduit, nomProduit, prixAchat, prixVenteDetail, prixVenteEngros, quantiteProduit, quantiteEntrepot, descriptionProduit, id_categorie, id_type, id_marque, id_collection, id_user, id_boutique) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
                        if (!$query->execute(array(
                            $codeBar,
                            $basename,
                            $nom,
                            $prixAchat,
                            $prixVenteDetail,
                            $prixVenteEngros,
                            $quantite,
                            $quantiteEntrepot,
                            $description,
                            $categorie,
                            $type,
                            $marque,
                            $collection,
                            $fournisseur,
                            $idBoutique
                        ))) {
                            header('location: ../ajouter_produit.php?popUpText=9');
                            exit();
                        }
                        echo ("Ligne produit cree");
                        $idProduit_tmp = $bdd->lastInsertId();
                        $produit_ajouter = true;
                    } else {
                        $query = $bdd->prepare('INSERT INTO imageProduit (nomImageProduit, id_produit) VALUES(?, ?)');
                        $query->execute(array($basename, $idProduit_tmp));
                        echo ("ok");
                    }
                } else {
                    echo ("non");
                }
            }
        }

        header('location:../ajouter_produit.php?popUpText=8');
        exit();
    } else {
        $query = $bdd->prepare('INSERT INTO produit (codeBar, nomProduit, prixAchat, prixVenteDetail, prixVenteEngros, quantiteProduit, descriptionProduit, id_categorie, id_type, id_marque, id_collection, id_user, id_boutique) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)');
        if (!$query->execute(array(
            $codeBar,
            $nom,
            $prixAchat,
            $prixVenteDetail,
            $prixVenteEngros,
            $quantite,
            $description,
            $categorie,
            $type,
            $marque,
            $collection,
            $fournisseur,
            $idBoutique
        ))) {
            header('location:../ajouter_produit.php?popUpText=9');
            exit();
        }
        header('location:../ajouter_produit.php?popUpText=8');
        exit();
    }
} catch (Exception $e) {
    echo ($e->getMessage());
    header('location:../ajouter_produit.php?popUpText=9');
    exit();
}
