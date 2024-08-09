<?php
    ob_start();
    try{
        require("../../script/connexion_bd.php");
        require("../../script/apiInfo.php");
        require("../../class/Boutique.php");
        require("../../class/CarteBancaire.php");
        
        if(!checkToken($API_TOKEN, $bdd)){
            header('HTTP/1.1 403 Vous netes pas authoriser');
            exit();
        }
        
        $token_tmp = $_POST['token'];
        
        if($token_tmp != $API_TOKEN){
            $boutique = new Boutique($bdd, null, $token_tmp);
        }
        else{
            if(!isset($_POST['idBoutique'])){
                header("HTTP/1.1 403 manque d'info");
                exit();
            }
            
            $idBoutique = $_POST['idBoutique'];
    
            if($idBoutique == 'djessy'){
                if(!isset($_COOKIE['idBoutique'])){
                    header("HTTP/1.1 403 manque d'info");
                    exit();
                }
                
                $idBoutique = $_COOKIE['idBoutique'];
            }
            
            $boutique = new Boutique($bdd , $idBoutique);
        }
        
        
        if(!$boutique->getExiste()){
            header('HTTP/1.1 404 boutique introuvable');
            exit();
        }
        header("Access-Control-Allow-Origin: *");

        if(!isset($_POST['idCarte'])){
            header('HTTP/1.1 403 aucune action');
            exit();
        }

        $idCarte = $_POST['idCarte'];

        $carte = new CarteBancaire($bdd, $idCarte);

        if(!$carte->getExiste() || $carte->getId_boutique() != $boutique->getId()){
            header("HTTP/1.1 404 carte introuvable");
            exit();
        }

        $carte->chargerTransactions();
        
        echo(json_encode($carte->getJson_array()));

        header("HTTP/1.1 200 carte boutique");
    }
    catch(Exception $e){
        header('HTTP/1.1 500 Erreur serveur');
        exit();
    }
    ob_end_flush();

?>