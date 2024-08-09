<?php
    ob_start();
    try{
        require_once("../script/connexion_bd.php");
        require_once("../script/apiInfo.php");
        require_once("../class/User.php");
        require_once("../class/Boutique.php");
        require_once("../class/Vente.php");

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

        if(!isset($_POST['idVente'], $_POST['montant'])){
            header("HTTP/1.1 403 manque d'informations");
            exit();
        }

        $idVente= $_POST['idVente'];
        $montant= $_POST['montant'];

        $vente = new Vente($bdd, $idVente);

        if(!$vente->getExiste()){
            header('HTTP/1.1 404 Dette introuvable');
            exit();
        }

        if($vente->getIdBoutique() != $boutique->getId()){
            header('HTTP/1.1 404 Dette introuvable chez vous');
            exit();
        }

        if($vente->reduireDette($montant)){
            header('HTTP/1.1 200 Ok dette');
            exit();
        }
        else{
            header('HTTP/1.1 500 non dette');
            exit();
        }
    }
    catch(Exception $e){
        header('HTTP/1.1 500 Erreur serveur');
        exit();
    }

?>