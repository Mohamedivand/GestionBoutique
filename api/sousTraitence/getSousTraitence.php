<?php
    ob_start();

    try{
        require("../../script/connexion_bd.php");
        require("../../script/apiInfo.php");
        require("../../class/sousTraitence.php");

        if(!checkToken($API_TOKEN, $bdd)){
            header('HTTP/1.1 403 Vous netes pas authoriser');
            exit();
        }

        header("Access-Control-Allow-Origin: *");
        
        if(!isset($_POST['idSousTraitence']) && !isset($_GET['idSousTraitence'])){
            header("HTTP/1.1 403 manque d'info");
            exit();
        }
        $idSousTraitence=isset($_POST['idSousTraitence']) ? $_POST['idSousTraitence'] : $_GET['idSousTraitence'];
        $sousTraitence= new SousTraitence($bdd, $idSousTraitence);

        if(!$sousTraitence->getExiste()){
            header("HTTP/1.1 404 marque introuvable");
            exit();
        }

        echo(json_encode($sousTraitence->getJson_array()));
        header("HTTP/1.1 200 ok");
        exit();
        
        
    }
    catch(Exception $e){
        header('HTTP/1.1 500 Erreur serveur');
        exit();
    }
    ob_end_flush();

?>