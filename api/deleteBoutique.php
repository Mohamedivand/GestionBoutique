<?php
    ob_start();
    try{
        require_once('../script/connexion_bd.php');
        require_once("../script/apiInfo.php");
        require_once('../class/User.php');
        require_once('../class/Boutique.php');

        if(!checkToken($API_TOKEN, $bdd)){
            header('HTTP/1.1 403 Token incorrecte');
            exit();
        }

        if(!isset($_COOKIE['idUser'], $_POST['idBoutique'], $_POST['loginAdmin'], $_POST['mdpAdmin'], $_POST['loginUser'])){
            header("HTTP/1.1 403 manque d'info");
            exit();
        }

        $idCurrentUser=$_COOKIE['idUser'];
        $idBoutique=$_POST['idBoutique']; 
        $loginAdmin=$_POST['loginAdmin']; 
        $mdpAdmin=$_POST['mdpAdmin']; 
        $loginUser=$_POST['loginUser']; 

        $currentUser= new User($bdd, $idCurrentUser);
        
        if(!$currentUser->getExiste()){
            header("HTTP/1.1 403 info introuvable");
            exit();
        }

        if(!in_array($currentUser->getRole()->getRole(), array("admin"))){
            header('HTTP/1.1 403 cet utilisateur nexiste pas');
            exit();
        }

        if($currentUser->getlogin() != $loginAdmin && $currentUser->getMdp() != $mdpAdmin){
            header('HTTP/1.1 403 utilisateur incorrecte');
            exit();
        }

        $boutique = new Boutique($bdd, $idBoutique);
        if(!$boutique->getExiste()){
            header('HTTP/1.1 403 Boutique introuvable');
            exit();
        }

        if($boutique->getProprietaire()->getlogin() != $loginUser){
            header('HTTP/1.1 403 Proprietaire incorrecte');
            exit();
        }

        if($boutique->deleteBoutique()){
            header('HTTP/1.1 200 Supprimer');

            if($boutique->getId() == $_COOKIE['idBoutique']){
                setcookie('idBoutiqe', 1, 1, '/');
                unset($_COOKIE['idBoutiqe']);
            }
            exit();
        }
        else{
            header('HTTP/1.1 500 Non supprimer');
            exit();
        }
 
    }
    catch(Exception $e){
        header('HTTP/1.1 500 une erreur est survenue');
    }

    ob_end_flush();
?>