<?php
    ob_start();
    
    try{
        require("../script/connexion_bd.php");
        require("../script/apiInfo.php");
        require_once('../class/User.php');
        require_once('../class/Boutique.php');
        
        if(!checkToken($API_TOKEN, $bdd)){
            header('HTTP/1.1 403 Vous netes pas authoriser');
            exit();
        }

        if(!isset($_COOKIE['idUser'])){
            header('HTTP/1.1 404 Access refuser');
            exit();
        }

        $idUser=$_COOKIE['idUser'];

        $user= new User($bdd, $idUser);

        if($user->getExiste() == false){
            header('HTTP/1.1 404 Access refuser cet utilisateur nexiste pas');
            exit();
        }
        
        if(!in_array($user->getRole()->getRole(), array('admin', 'proprietaire'))){
            header('HTTP/1.1 404 Access refuser. role incorrecte');
            exit();
        }

        if(!isset($_POST['idBoutique'])){
            header('HTPP/1.1 403 Manque dinfo');
            exit();
        }

        $idBoutique = $_POST['idBoutique'];

        $boutique = new Boutique($bdd, $idBoutique);

        if(in_array($user->getRole()->getRole(), array('proprietaire')) && $boutique->getProprietaire()->getId() != $user->getId()){
            header('HTTP/1.1 403 vous netes pas le proprietaire de la boutique');
            exit();
        }

        setcookie('idBoutique', $boutique->getId(), time()+3600*24*365, '/');

        header('HTPP/1.1 200 cree');
    }
    catch(Exception $e){
        header('HTPP/1.1 500 Erreur est survenue');
        exit();
    }

    ob_end_flush();
?>