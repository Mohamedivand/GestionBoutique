<?php
    ob_start();
    require("../script/connexion_bd.php");
    require("../script/apiInfo.php");
    if(!checkToken($API_TOKEN, $bdd)){
        header('HTTP/1.1 403 Vous netes pas authoriser');
        exit();
    }
    
    if(!isset($_POST['idCommande'])){
        header('HTTP/1.1 403 Aucune commande specifier');
        exit();
    }
    $idCommande = $_POST['idCommande'];
    require_once("../class/Commande.php");

    if(!isset($_COOKIE['idUser'])){
        header('HTTP/1.1 403 Vous netes pas connecter');
        exit();
    }
    $idUser=$_COOKIE['idUser'];

    require("../class/User.php");
    $user= new User($bdd, $idUser);
    if(!$user->getExiste()){
        header("HTTP/1.1 404 utilisateur introuvable");
        exit();
    }

    if(!in_array($user->getRole()->getRole(), array("admin", "proprietaire"))){
        header('HTTP/1.1 403 Vous navez pas le droit.');
        exit();
    }
    
    $commande = new Commande($bdd, $idCommande);

    if(!$commande->getExiste()){
        header('HTTP/1.1 404 Commande introuvable.');
        exit();
    }
    if(!isset($_POST['action'])){
        header('HTTP/1.1 404 action non mentionnee.');
        exit();
    }
    if(!is_numeric($_POST['action'])){
        header('HTTP/1.1 404 action inconnue.');
        exit();
    }
    $action = $_POST['action'];

    if($action == 1){
        if(!$commande->converteToVente()){
            header('HTTP/1.1 500 Action echouer.');
            exit();
        }
    }
    elseif($action == 2){
        if(!$commande->supprimer()){
            header('HTTP/1.1 500 Action echouer.');
            exit();
        }
    }

    header('HTTP/1.1 200 valider avec succes.');

    ob_end_flush();
?>