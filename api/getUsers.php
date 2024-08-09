<?php
    ob_start();
    require("../script/connexion_bd.php");
    require("../script/apiInfo.php");
    if(!checkToken($API_TOKEN, $bdd)){
        header('HTTP/1.1 403 Vous netes pas authoriser');
        exit();
    }
    
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

    $user->getTableauBoutique();

    echo(json_encode($user->getJson_array()));
    ob_end_flush();
?>