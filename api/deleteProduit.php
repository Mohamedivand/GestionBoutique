<?php
    ob_start();
    try{
        require('../script/connexion_bd.php');
        require("../script/apiInfo.php");
        require('../class/User.php');
        require('../class/Produit.php');
        require('../class/Boutique.php');

        if(!checkToken($API_TOKEN, $bdd)){
            header('HTTP/1.1 403 Token incorrecte');
            exit();
        }

        if(!isset($_COOKIE['idUser'], $_COOKIE['idBoutique'], $_POST['idProduit'])){
            header("HTTP/1.1 403 manque d'info");
            exit();
        }

        $idCurrentUser=$_COOKIE['idUser'];
        $idBoutique=$_COOKIE['idBoutique'];
        $idProduitPost=$_POST['idProduit']; 

        $currentUser= new User($bdd, $idCurrentUser);
        $produitPost= new Produit($bdd, $idProduitPost);
        $boutique= new Boutique($bdd, $idBoutique);

        if(!$currentUser->getExiste() || !$produitPost->getExiste() || !$boutique->getExiste()){
            header("HTTP/1.1 403 info introuvable");
            exit();
        }

        if(!in_array($currentUser->getRole()->getRole(), array("admin", "proprietaire"))){
            header('HTTP/1.1 403 cet utilisateur nexiste pas');
            exit();
        }

        if($boutique->deleteProduit($idProduitPost)){
            header('HTTP/1.1 200 supprimer');
            exit();
        }

        header('HTTP/1.1 500 suppression echouer');
 
    }
    catch(Exception $e){
        header('HTTP/1.1 500 une erreur est survenue');
    }

    ob_end_flush();
?>