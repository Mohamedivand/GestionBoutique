<?php
    ob_start();

    try{
        require("../script/connexion_bd.php");
        require("../script/apiInfo.php");

        if(!checkToken($API_TOKEN, $bdd)){
            header('HTTP/1.1 403 Vous netes pas authoriser');
            exit();
        }
        
        if(!isset($_POST['idCommande']) && !(isset($_POST['idBoutique']) || isset($_COOKIE['idBoutique']))){
            header("HTTP/1.1 403 manque d'info");
            exit();
        }
        $idBoutique=(isset($_POST['idBoutique'])) ? $_POST['idBoutique'] : $_COOKIE['idBoutique'];
        $idCommande=$_POST['idCommande'];

        require("../class/Commande.php");

        if(is_numeric($idCommande)){
            $commande= new Commande($bdd, $idCommande);

            if(!$commande->getExiste()){
                header("HTTP/1.1 404 commande introuvable");
                exit();
            }

            echo(json_encode($commande->getJson_array()));
            header("HTTP/1.1 200 ok");
            exit();
        }
        else{
            header("HTTP/1.1 403 impossible");
            exit();
        }
        
        
    }
    catch(Exception $e){
        header('HTTP/1.1 500 Erreur serveur');
        exit();
    }
    ob_end_flush();

?>