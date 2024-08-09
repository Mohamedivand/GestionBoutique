<?php
    ob_start();
    require_once("../script/connexion_bd.php");
    require_once("../class/Boutique.php");
    require_once("../class/Depense.php");

    if(!isset($_GET['idDepense'],$_COOKIE['idBoutique'])){
        header("HTTP/1.1 403 Manque d'info");
        exit();
    }
    $idDepense = $_GET['idDepense'];
    $depense = new Depense($bdd, $idDepense);
    $idBoutique = $_COOKIE['idBoutique'];
    $boutique = new Boutique($bdd, $idBoutique);
    if(!$depense->getExiste()){
        header("HTTP/1.1 404 depense introuvalbe");
        exit();
    }
    if(!$boutique->getExiste()){
        header("HTTP/1.1 403 boutique introuvable");
        exit();
    }
    if($depense->getIdBoutique() != $boutique->getId() ){
        header("HTTP/1.1 403 pas votre depense");
        exit();
    }
    if($depense->delete()){
        header("HTTP/1.1 200  supprimer");
        exit();
    }
    else{
        header("HTTP/1.1 500 Pas supprimer");
        exit();
    }
?>