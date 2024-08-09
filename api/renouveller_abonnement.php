<?php
    ob_start();
    try{
        require("../script/connexion_bd.php");
        require("../script/apiInfo.php");
        require("../class/User.php");
        require("../class/Boutique.php");

        if(!checkToken($API_TOKEN, $bdd)){
            header('HTTP/1.1 403 Vous netes pas authoriser');
            exit();
        }
        
        if(!isset($_POST['loginAdmin'] , $_POST["mdpAdmin"])){
            header('HTTP/1.1 403 Vous netes pas connecter');
            exit();
        }

        $loginAdmin=$_POST['loginAdmin'];
        $mdpAdmin=$_POST['mdpAdmin'];

        $user= new User($bdd, false, $loginAdmin,$mdpAdmin);

        if(!$user->getExiste()){
            header("HTTP/1.1 404 utilisateur introuvable");
            exit();
        }

        if(!in_array($user->getRole()->getRole(), array("admin"))){
            header('HTTP/1.1 403 Vous netes pas admin');
            exit();
        }

        if(!isset($_POST['idBoutique']) && !isset($_POST['loginProprietaire']) && !isset($_POST['nomMois'])){
            header('HTTP/1.1 403 Manque dinfo');
            exit();
        }

        $idBoutique= $_POST['idBoutique'];
        $loginProprietaire= $_POST['loginProprietaire'];
        $debutAbonnement= $_POST['debutAbonnement'];
        $finAbonnement= $_POST['nomMois'];

        $user_tmp = new User($bdd , false, $loginProprietaire);

        if(!$user_tmp->getExiste()){
            header("HTTP/1.1 404 utilisateur existe pas");
            exit();
        }

        $boutique_tmp= new Boutique($bdd, $idBoutique);
        if(!$boutique_tmp->getExiste()){
            header("HTTP/1.1 404 boutique existe pas");
            exit();
        }

        if($boutique_tmp->getProprietaire()->getId() != $user_tmp->getId()){
            header("HTTP/1.1 404 proprietaire incorrecte");
            exit();
        }

        $currentDate = date('Y-m-d h:i:s');

        $newDate= date('Y-m-d h:i:s', strtotime(
            ($boutique_tmp->getFinAbonnement() > $currentDate) ? $boutique_tmp->getFinAbonnement()." + ".$finAbonnement." months" : $currentDate. " + ".$finAbonnement." months"
        ));

        if(
            $boutique_tmp->editInfo(
                $boutique_tmp->getNomBoutique(), 
                date ('Y-m-d H:i:s', $debutAbonnement), 
                $newDate,
                $boutique_tmp->getDate_ajoue_boutique(), 
                $boutique_tmp->getdateModificationBoutique(), 
                1, 
                $user_tmp->getId(), 
                $boutique_tmp->getContact()->getTel(),  
                $boutique_tmp->getContact()->getEmail(),  
                $boutique_tmp->getContact()->getAdresse(),  
                $boutique_tmp->getContact()->getWhatsapp()
            )
        ){
            header("HTTP/1.1 200 reussie");
            exit();
        }
        else{
            header("HTTP/1.1 500 echouer");
            exit();
        }


    }
    catch(Exception $e){
        header('HTTP/1.1 500 Erreur serveur');
    }

    ob_end_flush();

?>