<?php
    ob_start();
    require_once("../script/connexion_bd.php");
    require_once("../class/Boutique.php");
    require_once("../class/Vente.php");

    if(!isset($_GET['idVente'],$_COOKIE['idBoutique'])){
        header("HTTP/1.1 403 Manque d'info");
        exit();
    }
    $idVente = $_GET['idVente'];
    $vente = new Vente($bdd, $idVente);
    $idBoutique = $_COOKIE['idBoutique'];
    $boutique = new Boutique($bdd, $idBoutique);
    if(!$vente->getExiste()){
        header("HTTP/1.1 404 vente introuvalbe");
        exit();
    }
    if(!$boutique->getExiste()){
        header("HTTP/1.1 403 boutique introuvable");
        exit();
    }
    if($vente->getIdBoutique() != $boutique->getId() ){
        header("HTTP/1.1 403 pas votre vente");
        exit();
    }
    if($vente->supprimer()){
        header("HTTP/1.1 200  supprimer");
        exit();
    }
    else{
        header("HTTP/1.1 500 Pas supprimer");
        exit();
    }
?>