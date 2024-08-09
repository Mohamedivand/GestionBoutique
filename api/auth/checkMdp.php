<?php
    ob_start();
    try{
        require("../../script/connexion_bd.php");
        require("../../script/apiInfo.php");
        if(!checkToken($API_TOKEN, $bdd)){
            header('HTTP/1.1 403 Vous netes pas authoriser');
            exit();
        }
        
        if(!isset($_COOKIE['idUser'])){
            header('HTTP/1.1 403 Vous netes pas connecter');
            exit();
        }
        
        if(!isset($_POST['mdp'])){
            header("HTTP/1.1 403 manque d'info");
            exit();
        }

        $mpd = $_POST['mdp'];
    
        $idUser=$_COOKIE['idUser'];
        require("../../class/User.php");
        $user= new User($bdd, $idUser);
        if(!$user->getExiste()){
            header("HTTP/1.1 404 utilisateur introuvable");
            exit();
        }
    
        if($user->getMdp() == $mpd){
            header('HTTP/1.1 200 ok mdp');
            exit();
        }
        else{
            header("HTTP/1.1 404 Utilisateur introuvable");
        }
    
    }
    catch(Exception $e){
        header("HTTP/1.1 500 Une erreur est survenue");
    }
    ob_end_flush();
?>