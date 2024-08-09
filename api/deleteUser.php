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

        if(!isset($_COOKIE['idUser'], $_COOKIE['idBoutique'], $_POST['idUser'])){
            header("HTTP/1.1 403 manque d'info");
            exit();
        }

        $idCurrentUser=$_COOKIE['idUser'];
        $idBoutique=$_COOKIE['idBoutique'];
        $idUserPost=$_POST['idUser']; 

        $currentUser= new User($bdd, $idCurrentUser);
        $userPost= new User($bdd, $idUserPost);
        $boutique= new Boutique($bdd, $idBoutique);

        if(!$currentUser->getExiste() || !$userPost->getExiste() || !$boutique->getExiste()){
            header("HTTP/1.1 403 info introuvable");
            exit();
        }

        if(!in_array($currentUser->getRole()->getRole(), array("admin", "proprietaire"))){
            header('HTTP/1.1 403 cet utilisateur nexiste pas');
            exit();
        }

        if($boutique->deleteUser($idUserPost)){
            header('HTTP/1.1 200 supprimer');
            exit();
        }

        header('HTTP/1.1 500 suppression echouer');
 
    }
    catch(Exception $e){
        header('HTTP/1.1 500 une erreur est survenue');
    }

    ob_end_flush();
?>