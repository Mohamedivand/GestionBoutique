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

        if(!isset($_POST['montant'], $_POST['date'], $_POST['nomEmployer'], $_POST['numEmployer']) && !(isset($_POST['idCarte']) || isset($_POST['numeroCarte']))){
            header('HTTP/1.1 403 manque dinfo');
            exit();
        }

        $idCarte = (isset($_POST['idCarte'])) ? $_POST['idCarte'] : null;    
        $numeroCarte = (isset($_POST['numeroCarte'])) ? $_POST['numeroCarte'] : null;    
        $montant = $_POST['montant'];    
        $date = $_POST['date'];    
        $nomEmployer = $_POST['nomEmployer'];    
        $numEmployer = $_POST['numEmployer'];    

        $carte = new CarteBancaire($bdd, $idCarte, $numeroCarte);

        if(!$carte->getExiste() || $carte->getId_boutique() != $boutique->getId()){
            header('HTTP/1.1 404 carte introuvable');
            exit();
        }

        if($carte->depot($montant, $date, $nomEmployer, $numEmployer)){
            header('HTTP/1.1 200 depot ok');
            exit();
        }
        else{
            header('HTTP/1.1 500 depot non');
            exit();
        }
    }
    catch(Exception $e){
        header('HTTP/1.1 500 Erreur serveur');
        exit();
    }
    ob_end_flush();
?>