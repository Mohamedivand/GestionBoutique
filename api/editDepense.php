<?php
    ob_start();
    try{
        require_once("../script/connexion_bd.php");
        require_once("../script/apiInfo.php");
        require_once("../class/User.php");
        require_once("../class/Boutique.php");

        if(!checkToken($API_TOKEN, $bdd)){
            header('HTTP/1.1 403 Vous netes pas authoriser');
            exit();
        }
        
        if(!isset($_COOKIE['idUser'], $_COOKIE['idBoutique'])){
            header('HTTP/1.1 403 Vous netes pas connecter');
            exit();
        }
        $idUser=$_COOKIE['idUser'];
        $idBoutique=$_COOKIE['idBoutique'];

        $user= new User($bdd, $idUser);
        $boutique= new Boutique($bdd, $idBoutique);

        if(!$user->getExiste() || !$boutique->getExiste()){
            header("HTTP/1.1 404 utilisateur introuvable");
            exit();
        }

        if(!in_array($user->getRole()->getRole(), array("admin", "proprietaire"))){
            header('HTTP/1.1 403 Vous etes un simple fournisseur');
            exit();
        }

        if(!isset($_POST['detail'], $_POST['montant'], $_POST['provientBenefice'])){
            header("HTTP/1.1 403 manque d'informations");
            exit();
        }

        $detail= $_POST['detail'];
        $montant= $_POST['montant'];
        $provientBenefice= isset($_POST['provientBenefice']) ? $_POST['provientBenefice'] : 0;

        $query=$bdd->prepare('INSERT INTO `depense` (detail, montant, provientBenefice, id_boutique) VALUES(?,?,?,?)');
        if($query->execute(array($detail, $montant, $provientBenefice, $boutique->getId()))){
            header('HTTP/1.1 200 Ok');
        }
        else{
            header('HTTP/1.1 500 Echouer');
        }
    }
    catch(Exception $e){
        header('HTTP/1.1 500 Erreur serveur');
        exit();
    }
    ob_end_flush();

?>