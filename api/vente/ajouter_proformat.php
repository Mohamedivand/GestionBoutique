<?php
ob_start();

try {
    require_once("../../script/connexion_bd.php");
    require_once("../../script/apiInfo.php");
    require_once("../../class/User.php");
    require_once("../../class/Produit.php");
    require_once("../../class/Boutique.php");
    require_once("../../class/Role.php");
    require_once("../../class/Contact.php");
    require_once("../../class/Proformat.php");

    if (!checkToken($API_TOKEN, $bdd)) {
        header('HTTP/1.1 403 Vous netes pas authoriser');
        exit();
    }

    if (!isset(
        $_COOKIE['idUser'],
        $_COOKIE['idBoutique'],
        $_POST['listeProduit'],
        $_POST['typeVente'],
        $_POST['reduction'],
        $_POST['nom'],
        $_POST['tel']
    )) {
        header('HTTP/1.1 403 manque dinfo');
        exit();
    }

    $idUser = $_COOKIE['idUser'];
    $idBoutique = $_COOKIE['idBoutique'];
    $listeProduit = $_POST['listeProduit'];
    $reduction = $_POST['reduction'];
    $typeVente = $_POST['typeVente'];
    $nom = $_POST['nom'];
    $tel = $_POST['tel'];
    $email = (isset($_POST['email'])) ? $_POST['email'] : null;
    $adresse = (isset($_POST['adresse'])) ? $_POST['adresse'] : null;
    $whatsapp = (isset($_POST['whatsapp'])) ? $_POST['whatsapp'] : null;

    $query = $bdd->prepare('SELECT * FROM `role` WHERE nomRole=?');
    $query->execute(array("client"));
    if (!($res = $query->fetch())) {
        header('HTTP/1.1 404 role non');
        exit();
    }

    $roleClient = new Role($bdd, $res['idRole']);

    $user = new User($bdd, $idUser);

    if (!$user->getExiste()) {
        header("HTTP/1.1 404 utilisateur introuvable");
        exit();
    }

    $boutique = new Boutique($bdd, $idBoutique);

    if (!$boutique->getExiste()) {
        header("HTTP/1.1 404 boutique introuvable");
        exit();
    }

    if (!is_array($listeProduit)) {
        header("HTTP/1.1 404 Donnez des produits svp");
        exit();
    }

    if (!in_array($user->getRole()->getRole(), array("admin", "proprietaire"))) {
        header('HTTP/1.1 403 Vous navez pas le droit.');
        exit();
    }

    if (in_array($user->getRole()->getRole(), array("proprietaire")) && $boutique->getProprietaire()->getId() != $user->getId()) {
        header('HTTP/1.1 403 Vous navez pas le droit mr.');
        exit();
    }

    $produit = null;
    $sum_total_produit = 0;

    foreach ($listeProduit as $produit_tmp) {
        $leProduit = new Produit($bdd, $produit_tmp['idProduit']);

        if (
            isset($produit_tmp['prixPersonnel']) &&
            is_numeric($produit_tmp['prixPersonnel'])
        ) {
            $sum_total_produit += $produit_tmp['prixPersonnel'];
        } else {
            $sum_total_produit += (($typeVente == "det") ? $leProduit->getPrixVenteDetail() : $leProduit->getPrixVenteEngros()) * $produit_tmp['quantite'];
        }

        if (!$leProduit->getExiste()) {
            $produit = null;
            break;
        }

        if ($leProduit->getIdBoutique() != $boutique->getId()) {
            $produit = null;
            break;
        }

        if ($leProduit->getQuantite() < $produit_tmp['quantite']) {
            $produit = null;
            break;
        }

        $produit[] = array(
            "produit" => $leProduit,
            "prixPersonnel" => $produit_tmp['prixPersonnel'],
            "quantite" => $produit_tmp['quantite']
        );

    }

    if (is_null($produit)) {
        header("HTTP/1.1 404 Donnez des produits qui vous appartienne svp");
        exit();
    }


    $query = $bdd->prepare('INSERT INTO contact (tel, email, adresse, whatsapp) VALUES(?,?,?,?)');
    if (!$query->execute(
        array(
            $tel,
            $email,
            $adresse,
            $whatsapp
        )
    )) {
        header("HTTP/1.1 500 num non ajouter");
        exit();
    }

    $newContact = new Contact($bdd, $bdd->lastInsertId());

    $query = $bdd->prepare('INSERT INTO user (nomUser, prenomUser, id_contact, id_role) VALUES(?,?,?,?)');

    if (!$query->execute(
        array(
            $nom,
            $nom,
            $newContact->getId(),
            $roleClient->getId()
        )
    )) {
        $newContact->delete();

        header("HTTP/1.1 500 user non ajouter");
        exit();
    }

    $newClient = new User($bdd, $bdd->lastInsertId());

    $query = $bdd->prepare('INSERT INTO proformat (reduction, total, typeVente, id_boutique, id_user) VALUES(?,?,?,?,?)');
    $query->execute(array(
        $reduction,
        0,
        $typeVente,
        $boutique->getId(),
        $newClient->getId()
    ));

    $newVente = new Proformat($bdd, $bdd->lastInsertId());

    if ($newVente->ajouterProduit($listeProduit)) {
        echo ($newVente->getId());
        header("HTTP/1.1 200 ajouter");
        exit();
    } else {
        header("HTTP/1.1 500 Une erreur est servenue 1");
        exit();
    }
} catch (Exception $e) {
    header("HTTP/1.1 500 Une erreur est servenue");
    exit();
}

ob_end_flush();
