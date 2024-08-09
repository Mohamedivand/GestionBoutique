<?php
    ob_start();
    require_once("../script/connexion_bd.php");
    require_once("../class/Boutique.php");
    require_once("../class/Proformat.php");

    if(!isset($_GET['idProformat'],$_COOKIE['idBoutique'])){
        header("HTTP/1.1 403 Manque d'info");
        exit();
    }
    $idProformat = $_GET['idProformat'];
    $Proformat = new Proformat($bdd, $idProformat);
    $idBoutique = $_COOKIE['idBoutique'];
    $boutique = new Boutique($bdd, $idBoutique);
    if(!$Proformat->getExiste()){
        header("HTTP/1.1 404 Proformat introuvalbe");
        exit();
    }
    if(!$boutique->getExiste()){
        header("HTTP/1.1 403 boutique introuvable");
        exit();
    }
    if($Proformat->getId_boutique() != $boutique->getId() ){
        header("HTTP/1.1 403 pas votre Proformat");
        exit();
    }
    if($Proformat->supprimer()){
        header("HTTP/1.1 200  supprimer");
        exit();
    }
    else{
        header("HTTP/1.1 500 Pas supprimer");
        exit();
    }
?>