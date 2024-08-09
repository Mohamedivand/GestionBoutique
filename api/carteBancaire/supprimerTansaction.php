<?php
    ob_start();
    try{
        require_once("../../script/connexion_bd.php");
        require_once("../../script/apiInfo.php");
        require_once("../../class/User.php");
        require_once("../../class/Boutique.php");
        require_once("../../class/CarteBancaire.php");

        if(!checkToken($API_TOKEN, $bdd)){
            header('HTTP/1.1 403 Vous netes pas authoriser');
            exit();
        }
        
        if(!isset($_COOKIE['idUser'], $_COOKIE['idBoutique'])){
            header('HTTP/1.1 403 Vous netes pas connecter');
            exit();
        }
        $idUser=$_COOKIE['idUser'];
        $idBoutique=$_COOKIE['idBoutique'];

        $user= new User($bdd, $idUser);
        $boutique= new Boutique($bdd, $idBoutique);

        if(!$user->getExiste() || !$boutique->getExiste()){
            header("HTTP/1.1 404 utilisateur introuvable");
            exit();
        }

        if(!in_array($user->getRole()->getRole(), array("admin", "proprietaire"))){
            header('HTTP/1.1 403 Vous etes un simple fournisseur');
            exit();
        }

        if(!isset($_POST['idTransaction'], $_POST['idCarteBancaire'])){
            header('HTTP/1.1 403 manque dinfo');
            exit();
        }

        $carteBancaire = new CarteBancaire($bdd, $_POST['idCarteBancaire']);
        $idTransaction = $_POST['idTransaction'];

        if(!$carteBancaire->getExiste() || ($carteBancaire->getId_boutique() != $boutique->getId())){
            header('HTTP/1.1 404 Cette carte nest pas le votre');
            exit();
        }

        if($carteBancaire->supprimerTransaction($idTransaction)){
            header('HTTP/1.1 200 suppression ok');
            exit();
        }
        else{
            header('HTTP/1.1 500 suppression non');
            exit();
        }

    }
    catch(Exception $e){
        header('HTTP/1.1 500 Erreur serveur');
        exit();
    }
    ob_end_flush();
?>